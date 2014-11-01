<?php
/*
Plugin Name: CasePress. Уведомления о создании задачи
Plugin URI:
Description: Плагин добавляет автокомментарий "Создана задача" с типом "case_added" к новым делам
Version: 1.0
Author: Evgenii Rezanov
Author URI:
*/
function NoticeComment($post_ID, $post) {



  $user_ID = get_current_user_id();
  //$post_ID = get_the_ID();

$members = get_post_meta( $post_ID, 'members-cp-posts-sql');
error_log('mem: '.print_r($members,true));

  $post_type = get_post_type();
  if (($post_ID<>0) and ($post_type=='cases')) {  
    $comment = array (
       'author_email' => '',  
       'ID' => '',  
       'karma' => '',  
       'number' => '',  
       'offset' => '',  
       'orderby' => '',  
       'order' => 'DESC',  
       'parent' => '',  
       'post_id' => $post_ID,  
       'post_author' => '',  
       'post_name' => '',  
       'post_parent' => '',  
       'post_status' => '',  
       'post_type' => 'cases',  
       'status' => '',  
       'type' => 'case_added',  
       'user_id' => '',  
       'search' => '',  
       'count' => false,  
       'meta_key' => '',  
       'meta_value' => '',  
       'meta_query' => '',
    );
	 $args = get_comments($comment);
	 if ($args) {return 0;}
	 else {
	   // создаем массив данных нового комментария
       $commentdata = array(
       'comment_post_ID' => $post_ID,
       'comment_author' => 'CasePress',
       'comment_author_email' => '',
       'comment_author_url' => 'http://',
       'comment_content' => 'Создана задача',
       'comment_type' => 'case_added',
       'user_ID' => $user_ID
	   );
       // добавляем данные в Базу Данных
       wp_new_comment($commentdata);
	}
  } 

}
  add_action('wp_insert_post','NoticeComment', 100, 2);
?>