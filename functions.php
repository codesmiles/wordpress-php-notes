<?php
/**
 * Twenty Twenty-Four functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Twenty Twenty-Four
 * @since Twenty Twenty-Four 1.0
 */

/**
 * Register block styles.
 */

if ( ! function_exists( 'twentytwentyfour_block_styles' ) ) :
	/**
	 * Register custom block styles
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function twentytwentyfour_block_styles() {

		register_block_style(
			'core/details',
			array(
				'name'         => 'arrow-icon-details',
				'label'        => __( 'Arrow icon', 'twentytwentyfour' ),
				/*
				 * Styles for the custom Arrow icon style of the Details block
				 */
				'inline_style' => '
				.is-style-arrow-icon-details {
					padding-top: var(--wp--preset--spacing--10);
					padding-bottom: var(--wp--preset--spacing--10);
				}

				.is-style-arrow-icon-details summary {
					list-style-type: "\2193\00a0\00a0\00a0";
				}

				.is-style-arrow-icon-details[open]>summary {
					list-style-type: "\2192\00a0\00a0\00a0";
				}',
			)
		);
		register_block_style(
			'core/post-terms',
			array(
				'name'         => 'pill',
				'label'        => __( 'Pill', 'twentytwentyfour' ),
				/*
				 * Styles variation for post terms
				 * https://github.com/WordPress/gutenberg/issues/24956
				 */
				'inline_style' => '
				.is-style-pill a,
				.is-style-pill span:not([class], [data-rich-text-placeholder]) {
					display: inline-block;
					background-color: var(--wp--preset--color--base-2);
					padding: 0.375rem 0.875rem;
					border-radius: var(--wp--preset--spacing--20);
				}

				.is-style-pill a:hover {
					background-color: var(--wp--preset--color--contrast-3);
				}',
			)
		);
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfour' ),
				/*
				 * Styles for the custom checkmark list block style
				 * https://github.com/WordPress/gutenberg/issues/51480
				 */
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
		register_block_style(
			'core/navigation-link',
			array(
				'name'         => 'arrow-link',
				'label'        => __( 'With arrow', 'twentytwentyfour' ),
				/*
				 * Styles for the custom arrow nav link block style
				 */
				'inline_style' => '
				.is-style-arrow-link .wp-block-navigation-item__label:after {
					content: "\2197";
					padding-inline-start: 0.25rem;
					vertical-align: middle;
					text-decoration: none;
					display: inline-block;
				}',
			)
		);
		register_block_style(
			'core/heading',
			array(
				'name'         => 'asterisk',
				'label'        => __( 'With asterisk', 'twentytwentyfour' ),
				'inline_style' => "
				.is-style-asterisk:before {
					content: '';
					width: 1.5rem;
					height: 3rem;
					background: var(--wp--preset--color--contrast-2, currentColor);
					clip-path: path('M11.93.684v8.039l5.633-5.633 1.216 1.23-5.66 5.66h8.04v1.737H13.2l5.701 5.701-1.23 1.23-5.742-5.742V21h-1.737v-8.094l-5.77 5.77-1.23-1.217 5.743-5.742H.842V9.98h8.162l-5.701-5.7 1.23-1.231 5.66 5.66V.684h1.737Z');
					display: block;
				}

				/* Hide the asterisk if the heading has no content, to avoid using empty headings to display the asterisk only, which is an A11Y issue */
				.is-style-asterisk:empty:before {
					content: none;
				}

				.is-style-asterisk:-moz-only-whitespace:before {
					content: none;
				}

				.is-style-asterisk.has-text-align-center:before {
					margin: 0 auto;
				}

				.is-style-asterisk.has-text-align-right:before {
					margin-left: auto;
				}

				.rtl .is-style-asterisk.has-text-align-left:before {
					margin-right: auto;
				}",
			)
		);
	}
endif;

add_action( 'init', 'twentytwentyfour_block_styles' );

/**
 * Enqueue block stylesheets.
 */

if ( ! function_exists( 'twentytwentyfour_block_stylesheets' ) ) :
	/**
	 * Enqueue custom block stylesheets
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function twentytwentyfour_block_stylesheets() {
		/**
		 * The wp_enqueue_block_style() function allows us to enqueue a stylesheet
		 * for a specific block. These will only get loaded when the block is rendered
		 * (both in the editor and on the front end), improving performance
		 * and reducing the amount of data requested by visitors.
		 *
		 * See https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/ for more info.
		 */
		wp_enqueue_block_style(
			'core/button',
			array(
				'handle' => 'twentytwentyfour-button-style-outline',
				'src'    => get_parent_theme_file_uri( 'assets/css/button-outline.css' ),
				'ver'    => wp_get_theme( get_template() )->get( 'Version' ),
				'path'   => get_parent_theme_file_path( 'assets/css/button-outline.css' ),
			)
		);
	}
endif;

add_action( 'init', 'twentytwentyfour_block_stylesheets' );

/**
 * Register pattern categories.
 */

if ( ! function_exists( 'twentytwentyfour_pattern_categories' ) ) :
	/**
	 * Register pattern categories
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function twentytwentyfour_pattern_categories() {

		register_block_pattern_category(
			'page',
			array(
				'label'       => _x( 'Pages', 'Block pattern category', 'twentytwentyfour' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfour' ),
			)
		);
	}
endif;

add_action( 'init', 'twentytwentyfour_pattern_categories' );

// require_once(dirname( __DIR__ ) . '/functions/callbacks.php');
// require_once( dirname( __FILE__ ) . '/custom/endpoints/topics.php' );
require_once( dirname( __FILE__ ) . '/custom/endpoints/auths.php' );
require_once( dirname( __FILE__ ) . '/custom/endpoints/users.php' );
require_once( dirname( __FILE__ ) . '/custom/endpoints/posts.php' );
require_once( dirname( __FILE__ ) . '/custom/functions/responses.php' );
require_once( dirname( __FILE__ ) . '/custom/functions/callbacks.php' );




/*
|--------------------------------------------------------------------------
| AUTHORIZED USER
|--------------------------------------------------------------------------
*/
$auth_user = 'v1/auth/users';


/*
|--------------------------------------------------------------------------
| profile
|--------------------------------------------------------------------------
*/

add_action( 'rest_api_init', function () use ($auth_user) {
    register_rest_route( $auth_user, '/profile', array(
        'methods'  => 'GET',
        'callback' => 'view_profile_callback',
        'permission_callback' => function () {
            return is_user_logged_in();
        }
    ));
});


function view_profile_callback( $request ) {
    $user = wp_get_current_user();
    $user_id = get_current_user_id();
    
     // Retrieve user meta data
    $user_meta_arr = [
        'user_bio',
        'saved_posts',
        'is_verified',
        'blocked_users',
        "blocked_posts",
        "blocked_topics",
        'followed_topics',
        'profile_completed',
        "user_profile_picture",
        ];
    $meta_arr = [];
    foreach($user_meta_arr as $meta){
        $response = get_field($meta, 'user_' . $user_id);
        $meta_arr[$meta] = $response;
    }
    
    return sendResponseMessage([
            "error" => false,
            'message' => 'successful',
             "data" => [
                 "user" => $user,
                 "user_meta" => $meta_arr
                 ]
        ]);
}


/*
|--------------------------------------------------------------------------
| change password while user is authenticated
|--------------------------------------------------------------------------
*/

add_action( 'rest_api_init', function () use ($auth_user) {
    register_rest_route( $auth_user, '/change-password', array(
        'methods'  => 'POST',
        'callback' => 'custom_change_password_callback',
        'permission_callback' => function () {
            return is_user_logged_in();
        }
    ));
});


function custom_change_password_callback( $request ) {
    $current_user = wp_get_current_user();
    
    $new_password = $request->get_param( 'new_password' );
    
       if (empty( $new_password ) ) {
        return format_error_response( new WP_Error( 'missing_params', 'Missing parameters.', array( 'status' => 400 ) ) );
    }
  
    $result = wp_update_user( array(
        'ID'        => $current_user->ID,
        'user_pass' => $new_password,
    ));

    if ( is_wp_error( $result ) ) {
        return format_error_response(new WP_Error( 'password_update_failed', $result->get_error_message(), array( 'status' => 400 ) ));
    }
    
    return sendResponseMessage([
            "error" => false,
            'message' => 'Password updated successfully' 
        ]);
}

/*
|--------------------------------------------------------------------------
| Delete auth user's account
|--------------------------------------------------------------------------
*/
add_action( 'rest_api_init', function () use ($auth_user) {
    register_rest_route( $auth_user, '/delete-account', array(
        'methods'  => 'POST',
        'callback' => 'custom_delete_account_callback',
        'permission_callback' => function () {
            return is_user_logged_in(); 
        }
    ));
});

function custom_delete_account_callback( $request ) {
    require_once ABSPATH . 'wp-admin/includes/user.php';
    
      $current_user = wp_get_current_user();
    
        $user_meta_keys = get_user_meta( $current_user->ID, '', false );
        
        foreach ( $user_meta_keys as $meta_key => $meta_values ) {
            if ($meta_key === "user_profile_picture") {
                $upload_dir = wp_upload_dir();
                $upload_path = str_replace($upload_dir['url'], $upload_dir['path'], $meta_values);
        
                if (file_exists($upload_path)) {
                    unlink($upload_path); // Delete the file
                }
                delete_user_meta($user_id, 'user_profile_picture');
            }
            delete_user_meta( $user_id, $meta_key );
        }

    $result = wp_delete_user( $current_user->ID );
   
     if ( is_wp_error( $result ) ) {
        return format_error_response(new WP_Error( 'account_deletion_failed', $result->get_error_message(), ['status' => 400 ] ));
    }
    
    return sendResponseMessage([
            "error" => false,
            'message' => 'Account deleted successfully' 
        ]);
}

/*
|--------------------------------------------------------------------------
| like and unlike a post
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () use ($auth_user) {
	register_rest_route($auth_user . '/post', '/like-unlike-post/(?P<id>\d+)', array (
		'methods' => 'POST',
		'callback' => 'like_post_callback',
		'permission_callback' => 'is_user_logged_in', 
	));
});


function like_post_callback($request){
    $post_id = $request->get_param('id');
    $current_user_id = get_current_user_id();

    // Check if the user has already liked the post
    $liked = get_post_meta($post_id, '_liked_by_' . $current_user_id, true);
    $action = '';

    if ($liked) {
        delete_post_meta($post_id, '_liked_by_' . $current_user_id);
        $action = 'unliked';
        $like_increment = -1;
    } else {
        update_post_meta($post_id, '_liked_by_' . $current_user_id, true);
        $action = 'liked';
        $like_increment = 1;
    }

    // Update likes_count field in ACF post schema
    $current_likes = get_field('likes_count', $post_id);
    if ($current_likes === '') {
        $current_likes = 0;
    }
    $updated_likes = $current_likes + $like_increment;
    update_field('likes_count', $updated_likes, $post_id);

    // Return success response
    return sendResponseMessage([
        "error" => false,
        "message" => $action,
        "data" => [
            'action' => $action,
            'likes_count' => $updated_likes,
        ]
    ]);
}

/*
|--------------------------------------------------------------------------
| Follow and unfollow a topic
|--------------------------------------------------------------------------
*/

add_action( 'rest_api_init', function () use ($auth_user) {
    register_rest_route( $auth_user . '/topics', '/follow-unfollow-topic/(?P<id>\d+)', [
        'methods' => 'POST',
        'callback' => 'follow_unfollow_topic_callback',
        'permission_callback' => 'is_user_logged_in', 
    ]);
} );

function follow_unfollow_topic_callback($request) {
    $topic_id = $request->get_param('id');
    $action = $request->get_param('action'); // 'follow' or 'unfollow'
    $current_user_id = get_current_user_id();

    // Update followed_count field in ACF topic schema
    $current_followed_count = get_field('followed_count', $topic_id);
    if ($current_followed_count === '') {
        $current_followed_count = 0;
    }

    // Update followed_topics field in ACF user schema
    $followed_topics = get_field('followed_topics', 'user_' . $current_user_id);
    if (!is_array($followed_topics)) {
        $followed_topics = [];
    }
    
    if ($action === 'follow') {
        // If user is not already following the topic, add it to followed_topics
        $followed_topics_index = array_map(function($value){
                return $value->ID;
            }, $followed_topics);
            
        if (!in_array($topic_id, $followed_topics_index)) {
            $followed_topics[] = $topic_id;
            $followed = update_field('followed_topics', $followed_topics, 'user_' . $current_user_id);
            
            $updated_followed_count = $current_followed_count + 1;
            update_field('followed_count', $updated_followed_count, $topic_id);
        }
    } elseif ($action === 'unfollow') {
        // If user is following the topic, remove it from followed_topics
        
        $index_arr = array_map(function($value){
            return $value->ID;
        }, $followed_topics);
        
        $index = array_search($topic_id, $index_arr);
        
        if ($index !== false) {
            unset($followed_topics[$index]);
            update_field('followed_topics', $followed_topics, 'user_' . $current_user_id);
            // Decrement followed_count for the topic
            $updated_followed_count = $current_followed_count - 1;
            update_field('followed_count', $updated_followed_count, $topic_id);
        }
    } else {
        // Invalid action, return error response
        return sendResponseMessage([
            "error" => true,
            "message" => "Invalid action.",
        ]);
    }

    // Return success response
    return sendResponseMessage([
        "error" => false,
        "message" => "Topic " . $action . "ed successfully.",
        "data" => [
            'followed_count' =>  $updated_followed_count ?? $current_followed_count,
        ]
    ]);
}


/*
|--------------------------------------------------------------------------
| view followed topics
|--------------------------------------------------------------------------
*/

add_action('rest_api_init', function () use ($auth_user) {
    register_rest_route($auth_user . '/topics', '/view-favourite-topics', array(
        'methods' => 'GET',
        'callback' => 'view_followed_topics_callback',
        'permission_callback' => 'is_user_logged_in', 
    ));
});

function view_followed_topics_callback($request) {
    $current_user_id = get_current_user_id();

    $followed_topics = get_field('followed_topics', 'user_' . $current_user_id);
    
    return rest_ensure_response(array(
        'error' => false,
        'message' => 'Followed topics retrieved successfully.',
        'data' => $followed_topics,
    ));
}


/*
|--------------------------------------------------------------------------
| Block and unblock users
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () use ($auth_user) {
    register_rest_route($auth_user, '/block-user/(?P<user_id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'block_user_callback',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        },
    ));
});

function block_user_callback($request) {
    $user_id = $request->get_param('user_id');
    $action = $request->get_param('action'); // 'block' or 'unblock'
    $current_user_id = get_current_user_id();
    
    

    // Check if both users exist
    if (!get_userdata($user_id) || !get_userdata($current_user_id)) {
        return format_error_response(new WP_Error('invalid_user', 'Invalid user IDs provided.', array('status' => 400)));
    }

 return block_field_callback($current_user_id, $action, $user_id, 'blocked_users');
}

/*
|--------------------------------------------------------------------------
| Check If User is Blocked Or Not
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () use ($auth_user) {
    register_rest_route($auth_user, '/check-blocked-user', array(
        'methods' => 'POST',
        'callback' => 'check_block_user_callback',
        'permission_callback' => '__return_true',
    ));
});

function is_user_blocked($other_user_id, $current_user_id = null) {
    if (!$current_user_id) {
        $current_user_id = get_current_user_id();
    }
    
    $blocked_users = get_user_meta($current_user_id, 'blocked_users', true);
    return is_array($blocked_users) && in_array($other_user_id, $blocked_users);
}

function check_block_user_callback($request){
    
    $user_id_arr = [
            $request->get_param('other_user_id'),
            $request->get_param('current_user_id')
        ];
        
    foreach($user_id_arr as $id){
    $user_data = get_userdata( $id );
        if(!$user_data){
            return format_error_response(new WP_Error('invalid_user', 'Invalid user IDs provided.', array('status' => 400)));
        }
    }

    if (is_user_blocked($user_id_arr[0], $user_id_arr[1])) {
    return sendResponseMessage(["error" => true,"message" =>"user is blocked", "data" => ["status" => 400]]);
    }
    
    return sendResponseMessage(["error" => false,"message" =>"user is not blocked", "data" => ["status" => 200]]);
}


/*
|--------------------------------------------------------------------------
| Block and unblock topic
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () use ($auth_user) {
    register_rest_route($auth_user . '/topics', '/block-topic/(?P<topic_id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'block_topic_callback',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        },
    ));
});


function block_topic_callback($request) {
    $topic_id = $request->get_param('topic_id');
    $action = $request->get_param('action'); // 'block' or 'unblock'
    $current_user_id = get_current_user_id();
    
    // Check if topic exist
    $url = "https://openforumapp.lighthq.com.ng/wp-json/wp/v2/topics/" . $topic_id;
    $response = wp_remote_get( $url );
    if ($response["response"]["code"] === 404) {
        return format_error_response(new WP_Error('invalid_topic', 'Invalid topic ID provided.', array('status' => 400)));
    }
    
    return block_field_callback($current_user_id, $action, $topic_id, 'blocked_topics');
}


/*
|--------------------------------------------------------------------------
| Block and unblock post
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () use ($auth_user) {
    register_rest_route($auth_user . '/app-posts', '/block-post/(?P<post_id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'block_post_callback',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        },
    ));
});

function block_post_callback ($request){
      $post_id = $request->get_param('post_id');
    $action = $request->get_param('action'); // 'block' or 'unblock'
    $current_user_id = get_current_user_id();
    
    // Check if topic exist
    $url = "https://openforumapp.lighthq.com.ng/wp-json/wp/v2/app-posts/" . $post_id;
    $response = wp_remote_get( $url );
    if ($response["response"]["code"] === 404) {
        return format_error_response(new WP_Error('invalid_post', 'Invalid post ID provided.', array('status' => 400)));
    }
    
    return block_field_callback($current_user_id, $action, $post_id, 'blocked_posts');
}

/*
|--------------------------------------------------------------------------
| save user post 
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () use ($auth_user) {
    register_rest_route($auth_user . '/app-posts', '/save-post/(?P<post_id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'save_post_callback',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        },
    ));
});
function save_post_callback ($request){
    $post_id = $request->get_param('post_id');
    $current_user_id = get_current_user_id();
    
    // Check if post exist
    $url = "https://openforumapp.lighthq.com.ng/wp-json/wp/v2/app-posts/" . $post_id;
    $response = wp_remote_get( $url );
    if ($response["response"]["code"] === 404) {
        return format_error_response(new WP_Error('invalid_post', 'Invalid post ID provided.', array('status' => 400)));
    }
    
    return save_metadata_callback($current_user_id, 'saved_posts', $post_id);
}

/*
|--------------------------------------------------------------------------
| edit user profile
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () use ($auth_user) {
    register_rest_route($auth_user , '/edit-user', array(
        'methods' => 'POST',
        'callback' => 'edit_user_data_callback',
        'permission_callback' => function() {
            return is_user_logged_in();
        },
    ));
});


function edit_user_data_callback($request) {
   
    $user = wp_get_current_user();

    // Get request parameters
    $params = $request->get_params();
    $user_data = $params['user_data']; // User data to update
    $meta_data = $params['meta_data']; // User metadata to update
 
    // if((!is_array($user_data) || !is_array($meta_data)) || (!count($user_data) || !count($meta_data))){
    //     return format_error_response(new WP_Error('No data passed', 'Data not found', array('status' => 400)));
    // }

    $message = [];
    if(is_array($user_data) && isset($user_data) && count($user_data)){
        $message["user_data"] = "it works for user_data";
        
        // // Update user data
        // foreach ($user_data as $key => $value) {
        //     $user->data->$key = $value;
        // }
        // wp_update_user($user->data);
    }
    if(is_array($meta_data) && isset($meta_data) && count($meta_data)){
       $message["meta_data"] = "it works for user meta";
        // // Update user metadata
        // foreach ($meta_data as $key => $value) {
        //     update_user_meta($user->ID, $key, $value);
        // }
    }
    
    return $message;

    // Return success response
    return new WP_REST_Response(array('message' => 'User data and metadata updated successfully'), 200);
}


