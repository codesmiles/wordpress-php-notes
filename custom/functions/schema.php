<?php

/*
|--------------------------------------------------------------------------
| SCHEMA
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| ACF for users
|--------------------------------------------------------------------------
*/
// function my_custom_user_acf_fields(){
// 	acf_add_local_field_group(
// 		[
// 			'key' => 'group_user_profile',
//             'title' => 'User Profile',
// 			'fields' => [
//     			    array(
//                     'key' => 'field_user_profile_picture',
//                     'label' => 'Profile Picture',
//                     'name' => 'user_profile_picture',
//                     'type' => 'image',
//                     'instructions' => 'Upload the user\'s profile picture.',
//                     'required' => true, 
//                     'return_format' => 'url', 
//                       ),
// 				[
// 					'key' => 'field_followed_topics',
//                     'label' => 'Followed Topics',
//                     'name' => 'followed_topics',
//                     'type' => 'relationship',
//                     'post_type' => array('topic'),
//                     'return_format' => 'id',
//                     'multiple' => true,
// 				],
// 				[
// 					'key' => 'field_join_date',
// 					'label' => 'Join Date',
// 					'name' => 'join_date',
// 					'type' => 'date_time_picker',
// 					'display_format' => 'Y-m-d H:i:s',
// 					'return_format' => 'Y-m-d H:i:s',
// 				],

// 				[
// 					'key' => 'field_saved_posts',
// 					'label' => 'Saved Posts',
// 					'name' => 'saved_posts',
// 					'type' => 'relationship',
// 					'post_type' => 'post',
// 					'return_format' => 'id',
// 					'multiple' => true,
// 				],
// 				[
// 					'key' => 'field_profile_complete',
// 					'label' => 'Profile Complete',
// 					'name' => 'profile_complete',
// 					'type' => 'true_false',
// 					'ui' => 1,
// 				],
// 				[
// 					'key' => 'field_is_verified',
// 					'label' => 'Is Verified',
// 					'name' => 'is_verified',
// 					'type' => 'true_false',
// 					'ui' => 1,
// 				],
// 			],
// 			'location' => [
// 				[
// 					[
// 						'param' => 'user_form',
//                         'operator' => '==',
//                         'value' => 'all',
// 					],
// 				],
// 			],
// 		]
// 	);
// }
// add_action('acf/init', 'my_custom_user_acf_fields');

/*
|--------------------------------------------------------------------------
| ACF for Post
|--------------------------------------------------------------------------
*/
// function my_custom_post_acf_fields()
// {
//     acf_add_local_field_group(
//         [
//             'key' => 'group_post_details',
//             'title' => 'Post Details',
//             'fields' => [
//                 [
//                     'key' => 'field_creator',
//                     'label' => 'Creator',
//                     'name' => 'creator',
//                     'type' => 'user',
//                     'required' => true,
//                 ],
//                 [
//                     'key' => 'field_topic',
//                     'label' => 'Topic',
//                     'name' => 'topic',
//                     'type' => 'post_object',
//                     'post_type' => 'topics',
//                     'required' => true,
//                 ],
//                 [
//                     'key' => 'field_replies',
//                     'label' => 'Replies',
//                     'name' => 'replies',
//                     'type' => 'relationship',
//                     'post_type' => 'post-replies',
//                     'multiple' => true,
//                 ],
//                 [
//                     'key' => 'field_likes',
//                     'label' => 'Likes',
//                     'name' => 'likes',
//                     'type' => 'relationship',
//                     'post_type' => 'user',
//                     'multiple' => true,
//                 ],
//                 [
//                     'key' => 'field_likes_count',
//                     'label' => 'Likes Count',
//                     'name' => 'likes_count',
//                     'type' => 'number',
//                 ],
//                 [
//                     'key' => 'field_replies_count',
//                     'label' => 'Replies Count',
//                     'name' => 'replies_count',
//                     'type' => 'number',
//                 ],
//                 // [
//                 //     'key' => 'field_description',
//                 //     'label' => 'Description',
//                 //     'name' => 'description',
//                 //     'type' => 'textarea',
//                 // ],
//                 [
//                     'key' => 'field_file',
//                     'label' => 'File',
//                     'name' => 'file',
//                     'type' => 'text',
//                 ],
//                 [
//                     'key' => 'field_created_at',
//                     'label' => 'Created At',
//                     'name' => 'created_at',
//                     'type' => 'date_time_picker',
//                 ],
//                 [
//                     'key' => 'field_updated_at',
//                     'label' => 'Updated At',
//                     'name' => 'updated_at',
//                     'type' => 'date_time_picker',
//                 ],
//             ],
//             'location' => [
//                 [
//                     [
//                         'param' => 'post_type',
//                         'operator' => '==',
//                         'value' => 'post',
//                     ],
//                 ],
//             ],
//         ]
//     );
// }
// add_action('acf/init', 'my_custom_post_acf_fields');