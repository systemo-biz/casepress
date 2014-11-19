<?php
/*
Plugin Name: CasePress. Уведомления о событиях по делам
Description: Плагин добавляет уведомления о задачах
Version: 1.0
Author: Levkin Michael
*/
function notice_comment()
{
	$user_ID=get_current_user_id();
	$post_ID=get_the_ID();
	$post_type=get_post_type();
	
	$result=get_the_terms($post_ID,'results');
	
/*Выводим уведомление о возобновлении задачи*/	
	if(($post_ID<>0) and ($post_type=="cases") and ($result==0) and (get_post_meta($post_ID, 'change_task', true)==1))
	{
		update_post_meta($post_ID, 'change_task', 0);
		
		$commentdata_renew=array(
		"comment_post_ID"=>$post_ID,
		"comment_author"=>"CasePress",
		"comment_content"=>"Задача возобновлена\n".current_time('mysql'),
		"comment_type" =>"case_renew",
		"comment_parent"=>0,
		"user_ID" =>$user_ID);
		wp_new_comment($commentdata_renew);
	}		
/*Выводим уведомление о закрытии задачи*/
	if(($post_ID<>0) and ($post_type=="cases") and ($result>0) and (get_post_meta($post_ID, 'change_task', true)==0))
	{	
		update_post_meta($post_ID, 'change_task', 1);
	
		$commentdata_end=array(
			"comment_post_ID"=>$post_ID,
			"comment_author"=>"CasePress",
			"comment_content"=>"Задача закрыта\n".current_time('mysql'),
			"comment_type" =>"case_dead",
			"comment_parent"=>0,
			"user_ID" =>$user_ID); 
		wp_new_comment($commentdata_end);
	}		
/*Выводим уведомление о создании задачи*/
	if(($post_ID<>0) and ($post_type=="cases")) 
	{  
		$comment=array(
		'order'=>'DESC',  
		'post_id'=>$post_ID,  
		'post_type'=>'cases',  
		'type' =>'case_added',  
		'count'=>false,  
    );
	$args=get_comments($comment);
		if($args){return 0;}
		else
		{
			$commentdata = array(
			'comment_post_ID' => $post_ID,
			'comment_author' => 'CasePress',
			'comment_content' => "Задача создана\n".current_time('mysql'),
			'comment_type' => 'case_added',
			'comment_parent' => 0,
			'user_ID' => $user_ID);
		wp_new_comment($commentdata);
	}
	}
}
	add_action("save_post","notice_comment");
?>