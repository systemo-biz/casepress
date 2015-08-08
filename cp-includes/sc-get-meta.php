<?php
/*
Plugin Name: CasePress. Get meta
Plugin URI: https://github.com/systemo-biz/
Description: Плагине генерит шорткоды для получения данных о сущностях в контексте
Version: 20150808
License: GPL
Author: Systemo
Author URI: http://systemo.biz
*/


function sc_get_meta_cp($atts) {

	extract( shortcode_atts( array(
			  'object' => 'post',
				'meta' => 'no',
			  'key' => 'id',
				'id' => 'current'
		 ), $atts ) );

 switch ($object) {
   case 'post':
	 	 //Вычисляем $post_id
		 switch ($id) {
			 case 'current':
			 	 $post = get_post();
				 $post_id = $post->ID;
				 break;
			 case 'current_person':
				 $user_id = get_current_user_id();
				 $post_id = get_person_by_user($user_id);
				 break;
			 default:
				 $post_id = $id;
				 $post = get_post($post_id);
				 break;
		 }

	    if($meta == 'yes') {
				$value = get_post_meta($post_id, $key, true);
			} else {
				$value = $post->$key;
			}

   	break;

   case 'user':

		 //Вычисляем $user_id
		 switch ($id) {
			 case 'current':
				 $user_id = get_current_user_id();
				 break;

			 default:
				 $user_id = $id;
				 break;
		 }

		 $user = get_user_by('id', $user_id);

		 if($meta == 'yes') {
				$value = get_user_meta($user_id, $key, true);
			} else {
				$value = $user->$key;
			}

     break;
	 }

  $html = $value;
	return $html;

}
add_shortcode('get_meta_cp', 'sc_get_meta_cp');
