<?php



//получаем ИД персоны по почте
function get_person_id_by_email($email=null){
	if(!isset($email)) return 0;
	global $wpdb;
	$pid = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'email' AND meta_value = %s", $email));
	return (int)$pid;
}

//получаем ид юзера по ид персоны
function get_user_by_person($person_id){
	global $wpdb;
	$user_id=$wpdb->get_var("SELECT user_id FROM $wpdb->usermeta where meta_key='id_person' and meta_value='".$person_id."'");
	if (!isset($user_id)) $user_id=0;
	return $user_id;
}

//получаем ид персоны по ид юзера
function get_person_by_user($user_id){
	global $wpdb;
	$person_id=$wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta where meta_key='id_person' and user_id='".$user_id."'");
	if (!isset($person_id)) $person_id=0;
	return $person_id;
} 
	