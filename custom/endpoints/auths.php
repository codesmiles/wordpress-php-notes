<?php

/*
|--------------------------------------------------------------------------
| register endpoint
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () {
    register_rest_route('auth/v1', '/signup', [
        'methods' => 'POST',
        'callback' => 'custom_register_user',
        'permission_callback' => '__return_true',
    ]);
});

function custom_register_user($request) {
    $params = $request->get_params();
    
    // Retrieve user data from the request
    $user_data = [
        'user_login' => sanitize_user($params['username']),
        'user_email' => sanitize_email($params['email']),
        'first_name' => sanitize_text_field($params['first_name']),
        'last_name' => sanitize_text_field($params['last_name']),
        'user_pass' => sanitize_text_field($params['password']),
    ];
    
    
    // Validate user data
    if (empty($user_data['user_login']) || empty($user_data['user_email']) || empty($user_data['user_pass'])) {
        $response =[
            'error' => true,
            'message' => 'Username, email, and password are required fields.',
            'data' => ['status' => 400]
            ];
        return sendResponseMessage($response);
    }
    
    if (!is_email($user_data['user_email'])) {
        $response = [
            'error' => true,
            'message' => 'Invalid email address.',
            'data' => ['status' => 400]
            ];
            
        return sendResponseMessage($response);
    }
    
    if (strlen($user_data['user_pass']) < 8) {
        return sendResponseMessage([
            'error' => true,
            'message' => 'Password must be at least 8 characters long.',
            'data' => ['status' => 400]
            ]);

    }

    // Register the new user
    $user_id = wp_insert_user($user_data);

    if (is_wp_error($user_id)) {
      return format_error_response(new WP_Error('registration_failed', $user_id->get_error_message(), array('status' => 400)));
    }

    
    // save user profile picture
    $user = get_user_by('email', $user_data["user_email"]);
    if ($user) {
            $user_id = $user->ID;
            
            if (isset($_FILES['profile_picture'])) {
                $file = $_FILES['profile_picture'];
                
                // File type validation
                $allowed_mime_types = array('image/jpeg', 'image/png', 'image/gif');
                $file_type = wp_check_filetype($file['name'], null);
                if (!in_array($file_type['type'], $allowed_mime_types)) {
                    return format_error_response(new WP_Error('invalid_file_type', 'Invalid file type. Only JPEG, PNG, and GIF images are allowed.', array('status' => 400)));
                }
            
                $max_file_size = wp_max_upload_size();
                if ($file['size'] > $max_file_size) {
                    // File size exceeds limit
                    return format_error_response(new WP_Error('file_size_exceeded', 'File size exceeds the maximum allowed size.', array('status' => 400)));
                }
            
                // Move the uploaded file to the uploads directory
                    $upload_dir = wp_upload_dir();
            
                
                    $file_name = sanitize_file_name($file['name']);
                    $file_path = $upload_dir['path'] . '/' . $file_name;
                    if (move_uploaded_file($file['tmp_name'], $file_path)) { // File uploaded successfully
                        $profile_picture_url = $upload_dir['url'] . '/' . $file_name;
                        update_field('user_profile_picture', $profile_picture_url,'user_' . $user_id);
                
                        
                    
                } else {
                    // Error occurred during file upload
                    return format_error_response(new WP_Error('file_upload_error', 'An error occurred during file upload.', array('status' => 500)));
                }
            }
        } else {
            return format_error_response(new WP_Error('user_data_Error', 'User not found.', array('status' => 404)));
        }
    
    // update user-meta
    $user_meta = [
            'user_bio' => isset($params['bio']) ? $params['bio'] : '', 
            'followed_topics' => isset($params['followed_topics']) ? $params['followed_topics'] : '', 
            'saved_posts' => isset($params['saved_posts']) ? $params['saved_posts'] : '', 
            'profile_completed' => isset($params['profile_completed']) ? $params['profile_completed'] : false, 
            'is_verified' => isset($params['is_verified']) ? $params['is_verified'] : false, 
        ];
    
      foreach ($user_meta as $key => $value) {
    	update_field($key, $user_meta[$key], 'user_' . $user_id);
    }
    
    // Get JWT token from the plugin's endpoint
    $expiration_time = time() + (24 * 60 * 60 * 7);
    $token_request = wp_remote_post( home_url( '/wp-json/jwt-auth/v1/token' ), array(
        'body' => array(
            'username' => $user_data["user_login"],
            'password' => $user_data["user_pass"],
             'exp' => $expiration_time,
        ),
    ) );

    if ( is_wp_error( $token_request ) ) {
        return format_error_response( new WP_Error( 'token_generation_failed', 'Failed to generate token.', array( 'status' => 500 ) ) );
    }

    $response_code = wp_remote_retrieve_response_code( $token_request );

    if ( $response_code !== 200 ) {
        return format_error_response( new WP_Error( 'token_generation_failed', 'Failed to generate token.', array( 'status' => $response_code ) ) );
    }

    $response_body = wp_remote_retrieve_body( $token_request );
    $token_data = json_decode( $response_body );

    if(!isset( $token_data->token )){
        return format_error_response( new WP_Error( 'token_generation_failed', 'Failed to generate token.', array( 'status' => 404 ) ) );
    }

    //mail
    $verification_token = rand(100000, 999999);
    $expiration_time = current_time('timestamp') + (2 * HOUR_IN_SECONDS); // Set token expiration time (2 hours from now)
    update_user_meta($user_id, 'verification_token', $verification_token);
    update_user_meta($user_id, 'verification_token_expiration', $expiration_time);

    // $verification_link = site_url("/wp-json/auth/v1/verify-email?token=$verification_token");
    $email_subject = 'Please verify your email';
    $email_body = "Here is the token for verifying your account:" . $verification_token;
    $sent = wp_mail($user_data["user_email"], $email_subject, $email_body);
    

	if (!$sent) {
		return format_error_response(new WP_Error('email_not_sent', 'User Registration Successful, unable to send verify email link.', ['status' => 500]));
	}
    return sendResponseMessage([
        'error'   => false,
        'message' => 'User registered successfully',
        'data'    => ['token' => $token_data->token],
        ]);
}

/*
|--------------------------------------------------------------------------
| verify User Endpoint
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function() {
    register_rest_route('auth/v1', '/verify-account', array(
        'methods' => 'POST',
        'callback' => 'verify_email_callback',
        'permission_callback' => '__return_true',
    ));
});

function get_user_by_verification_token($token) {
    $user_query = new WP_User_Query(array(
        'meta_key' => 'verification_token',
        'meta_value' => $token,
    ));

    $users = $user_query->get_results();

    if (!empty($users)) {
        foreach ($users as $user) {
            $expiration_time = get_user_meta($user->ID, 'verification_token_expiration', true);
            if (!$expiration_time || $expiration_time >= current_time('timestamp')) {
                return $user->ID; // Return the first user found whose token is not expired
            }
        }
    }

    return false;
}

function verify_email_callback($request) {
    
    $request_body = $request->get_body(); // Get raw request body
    $json_data = json_decode($request_body, true); // Decode JSON data
    
    // Retrieve the token from the decoded JSON data
    $token = isset($json_data['token']) ? sanitize_text_field($json_data['token']) : '';
    $user_id = get_user_by_verification_token($token);

    if (!$user_id) {
        return format_error_response(new WP_Error('invalid_token', 'Invalid or expired verification token.', array('status' => 400)));
    }

    // Update is_verified field to true
    update_user_meta($user_id, 'is_verified', true);
    
    // Remove the verification token and expiration time
    delete_user_meta($user_id, 'verification_token');
    delete_user_meta($user_id, 'verification_token_expiration');

    return sendResponseMessage([
        'error'   => false,
        'message' => 'Email verification successful.'
        ]);
}

/*
|--------------------------------------------------------------------------
| login endpoint
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () {
    register_rest_route('auth/v1', '/login', array(
        'methods' => 'POST',
        'callback' => 'login_callback',
        'permission_callback' => '__return_true',
    ));
});


function login_callback( $request ) {
    $parameters = $request->get_params();

    $username = isset( $parameters['username'] ) ? sanitize_text_field( $parameters['username'] ) : '';
    $password = isset( $parameters['password'] ) ? $parameters['password'] : '';

    if ( empty( $username ) || empty( $password ) ) {
        return format_error_response( new WP_Error( 'missing_params', 'Missing parameters.', array( 'status' => 400 ) ) );
    }

    // Perform user authentication
    $user = wp_authenticate( $username, $password );

    if ( is_wp_error( $user ) ) {
        return format_error_response( new WP_Error( 'login_failed', 'Login failed.', array( 'status' => 401 ) ) );
    }

    // Get JWT token from the plugin's endpoint
    $token_request = wp_remote_post( home_url( '/wp-json/jwt-auth/v1/token' ), array(
        'body' => array(
            'username' => $username,
            'password' => $password,
        ),
    ) );

    if ( is_wp_error( $token_request ) ) {
        return format_error_response( new WP_Error( 'token_generation_failed', 'Failed to generate token.', array( 'status' => 500 ) ) );
    }

    $response_code = wp_remote_retrieve_response_code( $token_request );

    if ( $response_code !== 200 ) {
        return format_error_response( new WP_Error( 'token_generation_failed', 'Failed to generate token.', array( 'status' => $response_code ) ) );
    }

    $response_body = wp_remote_retrieve_body( $token_request );
    $token_data = json_decode( $response_body );

    if ( isset( $token_data->token ) ) {
        $response = [
            'error'   => false,
            'message' => 'Successful',
            'data'    => [
                'token' => $token_data->token,
                ]
        ];
        return sendResponseMessage($response);
    } else {
        return format_error_response( new WP_Error( 'token_generation_failed', 'Failed to generate token.', array( 'status' => 404 ) ) );
    }
}

/*
|--------------------------------------------------------------------------
| forget password endpoint
|--------------------------------------------------------------------------
*/

add_action('rest_api_init', function () {
	register_rest_route('auth/v1', '/forgot-password', [
		'methods' => 'POST',
		'callback' => 'custom_forgot_password_callback',
		'permission_callback' => '__return_true',
	]);
});

function custom_forgot_password_callback($request){
	$parameters = $request->get_params();

	$email = isset($parameters['email']) ? sanitize_email($parameters['email']) : '';

	if (empty($email) || !is_email($email)) {
		return format_error_response(new WP_Error('invalid_email', 'Invalid email address.', array('status' => 400)));
	}

	$user = get_user_by('email', $email);
	if (!$user) {
		return format_error_response(new WP_Error('user_not_found', 'User not found.', array('status' => 404)));
	}

	$token = wp_generate_password(32, false);

	update_user_meta($user->ID, 'custom_password_reset_token', $token);

	$reset_link = home_url('/wp-json/auth/v1/reset-password/?token=' . $token); // Adjust the URL as needed
	$subject = 'Reset your password';
	$message = "Click the following link to reset your password: $reset_link";
	$sent = wp_mail($email, $subject, $message);

	if ($sent) {
	    return sendResponseMessage([
            'error'   => false,
            'message' => 'Successful',
            'data'    => [
                'message' => 'Password reset link sent successfully.'
                ]
	        ]);
	} else {
		return new format_error_response(WP_Error('email_not_sent', 'Failed to send password reset link.', array('status' => 500)));
	}
}

/*
|--------------------------------------------------------------------------
| reset password endpoint
|--------------------------------------------------------------------------
*/

add_action('rest_api_init', function () {
    register_rest_route('auth/v1', '/reset-password', [
        'methods' => 'POST',
        'callback' => 'custom_reset_password_callback',
        'permission_callback' => '__return_true',
    ]);
});

function get_user_by_token( $token ) {
	global $wpdb;

    $user_id = $wpdb->get_var( $wpdb->prepare(
        "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'custom_password_reset_token' AND meta_value = %s",
        $token
    ) );

    return $user_id;
}

function custom_reset_password_callback( $request ) {
    $parameters = $request->get_params();

    $token = isset( $parameters['token'] ) ? sanitize_text_field( $parameters['token'] ) : '';
    $new_password = isset( $parameters['new_password'] ) ? $parameters['new_password'] : '';

    $user_id = get_user_by_token( $token );
    if ( ! $user_id ) {
        return format_error_response( new WP_Error( 'invalid_token', 'Invalid token.', array( 'status' => 400 ) ));
    }

    if ( empty( $new_password ) ) {
        return format_error_response(new WP_Error( 'empty_password', 'New password is empty.', array( 'status' => 400 ) ) );
    }

    wp_set_password( $new_password, $user_id );
    return sendResponseMessage([
            'error'   => false,
            'message' => 'Successful',
            'data'    => [
               'message' => 'Password reset successful.',
                ]
        ]);
}


function generate_verification_token()
{
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function custom_send_email($to, $subject, $message, $headers = '')
{
    // $subject = 'Your Verification Code';
    // $message = 'Your verification code is: ' . $verification_token;
    // $headers = 'From: Your Site Name <no-reply@yourdomain.com>' . "\r\n" .
    //     'Content-Type: text/html; charset=UTF-8';
    
    // // Use PHP's mail function to send the email
    $success = mail($to, $subject, $message, $headers);

    return $success;
}


function send_verification_email($user_email, $token)
{
    $subject = 'Your Verification Code';
    $message = 'Your verification code is: ' . $token;
    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($user_email, $subject, $message, $headers);
}

// Send the verification email
send_verification_email($user_data['user_email'], $verification_token);

// Optionally, store the token in user meta for later verification
update_user_meta($user_id, 'verification_token', $verification_token);



if (
    empty($params["user_login"])
    || empty($params['email'])
    || empty($params['password'])
    || empty($params['last_name'])
    || empty($params['first_name'])
    || !is_email($user_data['email'])
    || empty($params['agree_to_our_terms_and_condition'])
) {
    return sendResponse(["error" => true, "message" => $GLOBALS['validation_message'],]);
}


// validate incoming requests 
    if ( empty($params['email']) 
    || !is_email($params['email'])
    || empty($params["token"])
    || !preg_match('/^\d{6}$/', $params['email'])
    ){
        
    }

    sanitize_text_field($input_token)



add_action('init', 'register_verification_endpoint');

function register_verification_endpoint() {
    add_rewrite_rule('^verify-account/?', 'index.php?verify_account=1', 'top');
}

add_filter('query_vars', function($vars) {
    $vars[] = 'verify_account';
    return $vars;
});

add_action('template_redirect', 'handle_verification');

function handle_verification() {
    if (get_query_var('verify_account') == 1) {
        $email = isset($_GET['email']) ? sanitize_email($_GET['email']) : '';
        $token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';

        if ($email && $token) {
            // Retrieve the token from the database using the email
            $user = get_user_by('email', $email);
            if ($user) {
                $saved_token = get_user_meta($user->ID, 'verification_token_' . $email, true);

                // Compare the tokens
                if ($saved_token === $token) {
                    // Token is valid, verify the user
                    delete_user_meta($user->ID, 'verification_token_' . $email);  // Remove token
                    update_user_meta($user->ID, 'is_verified', 1);  // Mark user as verified

                    // Redirect to the login page
                    wp_redirect(wp_login_url());
                    exit;
                }
            }
        }

        // Invalid token or email
        wp_die('Invalid verification token or email.');
    }
}

function send_verification_email($user_id) {
    // Get user information
    $user = get_user_by('ID', $user_id);
    $email = $user->user_email;

    // Generate a unique token
    $token = bin2hex(random_bytes(16));

    // Save the token with the email in user meta
    update_user_meta($user_id, 'verification_token_' . $email, $token);

    // Create a verification link with the email and token
    $verification_link = add_query_arg(
        [
            'token' => $token,
            'email' => urlencode($email),
        ],
        site_url('/verify-account/')
    );

    // Prepare the email
    $subject = 'Verify Your Account';
    $message = 'Please click the following link to verify your account: ' . $verification_link;

    // Send the email
    wp_mail($email, $subject, $message);
}
