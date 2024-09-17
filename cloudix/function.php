<?php

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
if (
    empty($params['email'])
    || !is_email($params['email'])
    || empty($params["token"])
    || !preg_match('/^\d{6}$/', $params['email'])
) {

}




add_action('init', 'register_verification_endpoint');

function register_verification_endpoint()
{
    add_rewrite_rule('^verify-account/?', 'index.php?verify_account=1', 'top');
}

add_filter('query_vars', function ($vars) {
    $vars[] = 'verify_account';
    return $vars;
});

add_action('template_redirect', 'handle_verification');

function handle_verification()
{
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

function send_verification_email($user_id)
{
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

        ],
        site_url('/verify-account/')
    );

    // Prepare the email
    $subject = 'Verify Your Account';
    $message = 'Please click the following link to verify your account: ' . $verification_link;

    // Send the email
    wp_mail($email, $subject, $message);
}


// send verification token email
function resend_verification_token($email, $subject, $message)
{

    $user = get_user_by('email', $email);
    $token = bin2hex(random_bytes(16));
    update_user_meta($user->ID, 'verification_token', $token);
    // $verification_link = add_query_arg(
    //     [
    //         'token' => $token,
    //         'email' => $email,
    //     ],
    //     site_url('/verify-account/')
    // );

    wp_mail($email, $subject, $message . " Your verification token is: " . $token);
}


/*
|--------------------------------------------------------------------------
| delete user controller
|--------------------------------------------------------------------------
*/
function handle_user_deletion_controller($email)
{
    // Get the user by email
    $user = get_user_by('email', $email);

    if (!$user) {
        return new WP_Error('no_user', 'No user found with that email', ['status' => 404]);
    }

    // Ensure the user exists and is not the current admin
    if ($user->ID == get_current_user_id()) {
        return new WP_Error('invalid_request', 'You cannot delete yourself.', ['status' => 400]);
    }

    // Delete the user
    $deleted = wp_delete_user($user->ID);

    if ($deleted) {
        return new WP_REST_Response('User deleted successfully', 200);
    } else {
        return new WP_Error('delete_failed', 'User deletion failed', ['status' => 500]);
    }
}


$fetch_meta_data = get_user_meta($user_id, 'verification_token', true);

global $wpdb;

$user_id = $wpdb->get_var($wpdb->prepare(
    "SELECT * FROM $wpdb->usermeta WHERE meta_key = 'verification_token' AND meta_value = %s",
    $payload["token"]
));


$auth_route_prefix = "v1/api/auth";
function forgot_password_controller($request)
{
    $parameters = $request->get_params();

    $email = isset($parameters['email']) ? sanitize_email($parameters['email']) : '';

    if (empty($email) || !is_email($email)) {
        return format_error_response(new WP_Error('invalid_email', 'Invalid email address.', array('status' => 400)));
    }

    $user = get_user_by('email', $email);
    if (!$user) {
        return sendResponse(["error" => true, "messsage" => $GLOBALS['failed_message']]);
    }

    $token = wp_generate_password(32, false);

    update_user_meta($user->ID, 'custom_password_reset_token', $token);

    $reset_link = home_url("/wp-json/{$GLOBALS["auth_route_prefix"]}/reset-password/?token=" . $token); // Adjust the URL as needed
    $subject = 'Reset your password';
    $message = "Click the following link to reset your password: $reset_link";
    $sent = wp_mail($email, $subject, $message);

    if (!$sent) {
        return sendResponse(["error" => true, "messsage" => $GLOBALS['failed_message']]);
    }
    return sendResponse(["error" => false, "message" => $GLOBALS['success_message']]);
}

function get_user_by_token($token)
{
    global $wpdb;

    $user_id = $wpdb->get_var($wpdb->prepare(
        "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'password_reset_token' AND meta_value = %s",
        $token
    ));

    return $user_id;
}

function reset_password_controller($request)
{
    $parameters = $request->get_params();

    $token = isset($parameters['token']) ? sanitize_text_field($parameters['token']) : '';
    $new_password = isset($parameters['new_password']) ? $parameters['new_password'] : '';

    $user_id = get_user_by_token($token);
    if (!$user_id) {
        return sendResponse(["error" => true, "messsage" => $GLOBALS['failed_message']]);
    }

    if (empty($new_password)) {
        return sendResponse(["error" => true, "messsage" => $GLOBALS['failed_message']]);
    }

    wp_set_password(wp_hash_password($new_password), $user_id);

    return sendResponse(["error" => false, "message" => $GLOBALS['success_message']]);
}

function login_controller($request)
{
    $parameters = $request->get_params();

    $username = isset($parameters['username']) ? sanitize_text_field($parameters['username']) : '';
    $password = isset($parameters['password']) ? $parameters['password'] : '';

    if (empty($username) || empty($password)) {
        return sendResponse(["error" => true, "message" => $GLOBALS['validation_message']]);
    }

    // Perform user authentication
    $user = wp_authenticate($username, $password);

    if (is_wp_error($user)) {
        return sendResponse(["error" => true, "messsage" => $GLOBALS['failed_message']]);
    }

    // Get JWT token from the plugin's endpoint
    $token_request = wp_remote_post(home_url('/wp-json/jwt-auth/v1/token'), array(
        'body' => array(
            'username' => $username,
            'password' => $password,
        ),
    ));

    if (is_wp_error($token_request)) {
        return sendResponse(["error" => true, "messsage" => $GLOBALS['failed_message']]);
    }

    $response_code = wp_remote_retrieve_response_code($token_request);

    if ($response_code !== 200) {
        return sendResponse(["error" => true, "messsage" => $GLOBALS['failed_message']]);
    }

    $response_body = wp_remote_retrieve_body($token_request);
    $token_data = json_decode($response_body);

    if (!isset($token_data->token)) {
        return sendResponse(["error" => true, "messsage" => $GLOBALS['failed_message']]);
    }

    $response = [
        'token' => $token_data->token,
    ];

    return sendResponse(["error" => false, "message" => $GLOBALS['success_message'], "data" => $response]);
}