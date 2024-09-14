<?php
$user = 'v1/users';

/*
|--------------------------------------------------------------------------
| find user
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () use ($user) {
    register_rest_route($user, '/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_single_user',
        'permission_callback' => '__return_true',
    ]);
});

function get_single_user($request){
     $user_id = $request['id'];

    $user = get_user_by('ID', $user_id);

    if (!$user) {
        return format_error_response(new WP_Error('user_not_found', 'User not found.', array('status' => 404)));
    }
    // Retrieve user meta data
    $user_meta_arr = [
        'user_bio',
        'saved_posts',
        'is_verified',
         'blocked_users',
        'followed_topics',
        'profile_completed',
        ];
    $payload = [];
    foreach($user_meta_arr as $meta){
        $response = get_field($meta, 'user_' . $user_id);
        $payload[$meta] = $response;
    }

    // // Prepare user data
    // $user_data = get_userdata($user_id);
    // $user_meta = get_user_meta($user_id);
    $user_data = [
        'id' => $user->ID,
        'email' => $user->user_email,
        'username' => $user->user_login,
        "display_name" => $user->display_name,
        "user_registered" => $user->user_registered,
        "profile_picture" => $user->user_profile_picture,
        ...$payload
    ];
    
 

return sendResponseMessage([
    "error" => false,
    "message" =>"successful",
    "data" => $user_data,
        
    ]);
}
