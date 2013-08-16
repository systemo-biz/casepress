<?php
define('WP_USE_THEMES', false);
include($_SERVER['DOCUMENT_ROOT'] . '/kb/wp-config.php');

if(isset($_POST['favorite']) && isset($_POST['user_id']) && isset($_POST['post_id'])):

	//get parameters
	$user_id = $_POST['user_id'];
	$post_id = $_POST['post_id'];
	$favorite = $_POST['favorite'];

	$result = true;
	$data = get_user_meta($user_id, 'favorite_link', true);

	if(!isset( $data ))
	{
		die("Hello");
		if(!add_user_meta($user_id, 'favorite_link', get_permalink($post_id))){
			$result = false;
		}
	}
	else
	{
		if(get_permalink($post_id) == $data)
		{
			$result = true;
		}
		else
		{
			if( !update_user_meta($user_id, 'favorite_link', get_permalink($post_id)) ){
				$result = false;
			}
		}

	}

	$array = array(
		'result' => $result
	);

	echo json_encode($array);
	exit;
endif;