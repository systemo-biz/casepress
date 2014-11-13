<?php

/*

Функции интеграции для плагина упоминаний через @ https://github.com/casepress-studio/at-js-4-wp-cp

*/

//Добавляем упомянутых в список уведомлений
function add_mention_users_to_notify_list($meta_id, $object_id, $meta_key, $meta_value ) {
	if ($meta_key != 'users_mention_cp') return;

	$notify_user = get_comment_meta($object_id, 'notify_user');

	if(!in_array($meta_value, $notify_user)) {
		add_comment_meta($object_id, 'notify_user', $meta_value);
    	add_comment_meta($object_id, 'email_notify', 0, true);

	}
	return;
}

add_action('added_comment_meta', 'add_mention_users_to_notify_list', 20, 4);
add_action('updated_comment_meta', 'add_mention_users_to_notify_list', 20, 4);


//Добавляем упомянутых в список доступа
function add_mention_users_to_acl_mentions_list($meta_id, $object_id, $meta_key, $meta_value ) {
	if ($meta_key != 'users_mention_cp') return;

	$comment = get_comment($object_id);
	
	$post_data = get_post($comment->comment_post_ID);

	$acl_list_form_at_js = get_post_meta($post_data->ID, 'acl_list_from_at_js');

	if(!in_array($meta_value, $acl_list_form_at_js)) add_post_meta($post_data->ID, 'acl_list_from_at_js', $meta_value);

	return;
}

add_action('added_comment_meta', 'add_mention_users_to_acl_mentions_list', 20, 4);
add_action('updated_comment_meta', 'add_mention_users_to_acl_mentions_list', 20, 4);

//Добавляем пользователя в ACL при добавлении в список acl_list_form_at_js
function add_user_to_acl_from_at_js_cp($meta_id, $object_id, $meta_key, $meta_value){
	if($meta_key != 'acl_list_from_at_js') return;

    $acl_users = get_post_meta($post_data->ID, 'acl_users');
    
    if(!in_array($meta_value, $acl_users)) add_post_meta($object_id, 'acl_users', $meta_value);

	return;
}
add_action('added_post_meta', 'add_user_to_acl_from_at_js_cp', 20, 4);
add_action('updated_post_meta', 'add_user_to_acl_from_at_js_cp', 20, 4);
