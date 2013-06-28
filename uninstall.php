<?php 
if(!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN'))
    exit();

	delete_option('just_like_posts');
	delete_option('just_like_comments');	
	delete_option('just_like_count_tags');
	delete_option('just_like_like_label');
	delete_option('just_like_unlike_label');
	delete_option('just_like_no_auth');
		
	$comments = get_comments(array(
		'type' => 'like',
		'count' => false
	));	

	foreach($comments as $comment){  
		$res = wp_delete_comment($comment->comment_ID, true);
	}  

?>