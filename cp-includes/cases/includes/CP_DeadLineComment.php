<?php
/*
Plugin Name: CasePress. Уведомление о нарушении срока
Plugin URI:
Description: Плагин добавляет авто-комментарий "Нарушение срока - <Срок>, ответственный: <Ответственный>" с типом "deadline_fail" к новым делам
Version: 1.0
Author: Evgenii Rezanov
Author URI:
*/
add_filter('cp_notice_chg_message', 'chg_mail', 10, 2);
function chg_mail($msg, $comment){
  $comment_type=$comment->comment_type;
  $comment_post_ID=$comment->comment_post_ID;
  $post_name=get_the_title($comment_post_ID);
  if ($comment_type=='deadline_fail'){
  //$msg['subject'] = 'Нарушение срока по задаче - '.$post_name.' ['.$comment_post_ID.']';
  $msg['subject'] = $post_name.' ['.$comment_post_ID.'] - нарушение срока по задаче';
  $msg['text'] = '<div>'.$comment->comment_content.'</div>';
  $msg['text'] .= '<hr>';
  $msg['text'] .= '<a href="'.get_permalink($comment->comment_post_ID).'">Перейти к задаче</a>';
return $msg;}
  else{
return $msg;}
}

// добавляем запланированный хук  
add_action('cp_activate', 'add_action_for_event_deadline');  
function add_action_for_event_deadline() {  
	wp_schedule_event( time(), 'hourly', 'cp_deadline_control'); 
}

add_action('cp_deactivate', 'del_action_for_event_deadline');  
function del_action_for_event_deadline() {  
 wp_clear_scheduled_hook('cp_deadline_control');	
}

// добавляем функцию к указанному хуку  
add_action('cp_deadline_control', 'DeadLineComment');

function DeadLineComment() { 
  // параметры выборки
  error_log("Запуск проверки срока");
  $today = current_time('mysql',0);
  $args = array(
	'post_type' => 'cases',
	'meta_query' => array(
		'relation' => 'AND',
		array(
			'key' => 'cp_date_deadline',
			'value' => $today,
			'compare' => '<'
			),
	)
);
  $posts = new WP_Query( $args );
  // если есть посты
  if($posts->have_posts()) {
	while($posts->have_posts()){ $posts->next_post();
		$post_id = $posts->post->ID;
		if ($post_id<>0){
		  $post_date_end = get_post_meta($post_id,'cp_date_end', true);
		  if ($post_date_end==''){
		    $comment = array (
              'post_id' => $post_id,   
              'type' => 'deadline_fail',  
            );		  
	        $comm = get_comments($comment);
	        if ($comm) {return 0;}
			else {
	            //error_log($post_id.'-нет коммента');
			  // создаем массив данных нового комментария
                $persID=get_post_meta($post_id,'responsible-cp-posts-sql',true);
			    $persFIO=get_post($persID);
			    $FIO=$persFIO->post_title;
			    $deadline=get_post_meta($post_id,'cp_date_deadline',true);
			    $deadlinecomment='Нарушение срока: '.$deadline.', по задаче №'.$post_id.', ответственный:'.$FIO;
			    $commentdata = array(
                  'comment_post_ID' => $post_id,
                  'comment_author' => 'CasePress',
                  'comment_author_email' => get_option('admin_email'),
                  'comment_content' => $deadlinecomment,
                  'comment_type' => 'deadline_fail',
                  'comment_parent' => 0,
                  'user_ID' => '',
	            );
               // добавляем данные в Базу Данных
               wp_new_comment($commentdata);
			   //error_log($post_id.'добавил коммент');
		    }
	      }
		  }
                        }
		}	
	}
?>