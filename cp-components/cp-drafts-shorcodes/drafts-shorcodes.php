<?php

	function draft_sc_func($attr){
		global $current_user;
		global $wpdb;
		get_currentuserinfo();
		$person_id = get_person_by_user($current_user->ID);
		$sql = 'SELECT p.ID as "ID" ,p.post_title as "title",p.post_date as "date"
			FROM `wp_posts` as p		
			WHERE
			p.post_type = "cases" and 
			p.post_status = "draft" and
			p.post_author = '.$current_user->ID.'
			LIMIT 30';
		$params = array('fields'=>'ID:link,title:link,date','titles'=>'title: название,date:дата создания');
		datatable_generator($params,$sql);		
		return $return;
	} 	 
	add_shortcode('drafts', 'draft_sc_func'); 
?>