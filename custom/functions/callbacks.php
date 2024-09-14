<?php

/*
|--------------------------------------------------------------------------
| block and unblocked fields callback
|--------------------------------------------------------------------------
*/
function block_field_callback($current_user_id, $request_action, $field_id, $field_title ) {
   
    $blocked_fields = get_user_meta($current_user_id, $field_title, true);
    if (!$blocked_fields) {
        $blocked_fields = [];
    }
    
    if ($request_action === 'block') {
        $blocked_fields[] = $field_id;
        $unique_blocked_fields = array_unique($blocked_fields);
        update_user_meta($current_user_id, $field_title, $unique_blocked_fields);
        $message = 'successful.';
    } 

    
    elseif ($request_action === 'unblock') {
        $blocked_fields = array_diff($blocked_fields,[$field_id]);
        update_user_meta($current_user_id, $field_title, $blocked_fields);
        $message = 'unblocked successful';
    } 
    else {
        return format_error_response(new WP_Error('invalid_action', 'Invalid action specified.', ['status' => 400]));
    }

     return rest_ensure_response([
        'error' => false,
        'message' => $message,
        'data' => [$field_title => $unique_blocked_fields ?? $blocked_fields]
    ]);
}

/*
|--------------------------------------------------------------------------
| save metadata callback
|--------------------------------------------------------------------------
*/
function save_metadata_callback($current_user_id, $field_title, $field_id){
    
    $fields = get_user_meta($current_user_id, $field_title, true);
    if (!$fields) {
        $fields = [];
    }
    
     if(in_array($field_id, $fields)){
        $fields = array_diff($fields,[$field_id]);
         update_user_meta($current_user_id, $field_title, $fields);
         $message = 'successfully removed from ' . $field_title . " data";
     }else{
        $fields[] = $field_id;
        update_user_meta($current_user_id, $field_title, $fields);
        $message = 'successfully added to ' . $field_title . " data";
     }
        
    return rest_ensure_response([
        'error' => false,
        'message' => $message,
        'data' => [$field_title => $fields]
    ]);
        
}
