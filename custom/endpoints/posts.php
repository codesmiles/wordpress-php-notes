<?php

$post = 'v1/posts';
/*
|--------------------------------------------------------------------------
| find post by topic id
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () use($post) {
    register_rest_route($post, '/posts-by-topic-id/(?P<topic_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_posts_by_topic_id',
         'permission_callback' => '__return_true',
    ));
});

function get_posts_by_topic_id($request) {
    $params = $request->get_params();
    $topic_id = $params['topic_id'];

    $args = array(
        'post_type' => 'app-posts',
        'meta_query' => array(
            array(
                'key' => 'topic_id',
                'value' => $topic_id,
                'compare' => '=',
            ),
        ),
    );

    $posts_query = new WP_Query($args);

    if ($posts_query->have_posts()) {
        $posts_data = array();
        while ($posts_query->have_posts()) {
            $posts_query->the_post();
            $post_id = get_the_ID();
            $post_data = array(
                'id' => $post_id,
                'date' => get_the_date('Y-m-d\TH:i:s', $post_id),
                'date_gmt' => get_post_time('Y-m-d\TH:i:s', true, $post_id),
                'guid' => array(
                    'rendered' => get_permalink($post_id),
                ),
                'modified' => get_the_modified_date('Y-m-d\TH:i:s', $post_id),
                'modified_gmt' => get_post_modified_time('Y-m-d\TH:i:s', true, $post_id),
                'slug' => get_post_field('post_name', $post_id),
                'status' => get_post_status($post_id),
                'type' => get_post_type($post_id),
                'link' => get_permalink($post_id),
                'template' => '',
                'acf' => get_fields($post_id),
            );
            $posts_data[] = $post_data;
        }
        return sendResponseMessage(["error" => false, "message" => "successful", "data" => $posts_data]);
    } else {
        return sendResponseMessage(["error" => false, "message" => "successful", "data" => []]);
    }
}
