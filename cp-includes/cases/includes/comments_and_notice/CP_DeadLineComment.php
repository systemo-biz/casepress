<?php
/*
Plugin Name: CasePress. Уведомление о нарушении срока
Plugin URI:
Description: Плагин добавляет авто-комментарий "Нарушение срока - <Срок>, ответственный: <Ответственный>" с типом "deadline_fail" к новым делам
Version: 1.0
Author: Evgenii Rezanov
Author URI:
*/



class Notice_Deadline_CP_Singltone {
private static $_instance = null;
private function __construct() {
    //add_filter('cp_notice_chg_message', array($this, 'chg_mail'), 10, 2);
    add_action('cp_activate', array($this, 'add_action_for_event_deadline'));  
    add_action('cp_deactivate', array($this, 'del_action_for_event_deadline'));  
    add_action('cp_deadline_control', array($this,'DeadLineComment'));

}
    

// Создаем комменты если срок нарушен
function DeadLineComment() { 
// параметры выборки

$now = current_time('mysql',0);
$results_array = get_terms('results', 'fields=ids');

$cases = get_posts(array(
    'post_type' => 'cases',
    'nopaging' => true,
    'tax_query' => array(
          array(
              'taxonomy' => 'results',
              'operator' => 'NOT IN',
              'terms'    => $results_array,
          ),
      ),
    'meta_query' => array(
        array(
            'key' => 'deadline_cp',
            'value' => $now,
            'compare' => '<',
            'type' => 'DATETIME',
            ),
        )
    ));

    //error_log("Комменты: " . print_r($cases, true));
    /*
    foreach($cases as $case){
        error_log('Post ID - ' . print_r($case->ID, true));
    }*/
    
    foreach($cases as $case){
        //setup_postdata($post);
        $post_id = $case->ID;
        //error_log('Post ID - ' . print_r($post_id, true));
        $deadline_cp = get_post_meta($post_id,'deadline_cp',true);

        //Проверяем наличие комментария о нарушении срока
        $comment_check = get_comments(array (
            'post_id' => $post_id,   
            'type' => 'e_cp_deadline_fail',
            'meta_key' => 'deadline_date',
            'meta_value' => $deadline_cp,
        ));
        
        //error_log('Коммент - ' . print_r($comment_check, true));
        //error_log('Срок проверки - ' . print_r($deadline_cp, true));

        // Если комментарии с нарушением этого срока уже есть, то переходим к следующиему посту 
        if(! empty($comment_check)) continue;
        

        
        
        $responsible_id =get_post_meta($post_id,'responsible-cp-posts-sql',true);

        // Проверяем наличие ответственного, заполняем переменную имени ответственного
        if(empty($responsible_id)) {
            $responsible_name = 'Отсутствует';
        } else {
            $responsible_name = get_the_title($responsible_id);
        }
        
        $comment_id = wp_insert_comment(array(
              'comment_post_ID' => $post_id,
              'comment_author' => 'CasePress',
              'comment_author_email' => get_option('admin_email'),
              'comment_content' => "Нарушен срок: " . $deadline_cp . ". " . "Ответственный: " . $responsible_name,
              'comment_type' => 'e_cp_deadline_fail',
              'comment_parent' => 0,
              'user_ID' => '',
              'comment_approved' => true,
            ));
        
        if($comment_id) {
            update_comment_meta( $comment_id, 'deadline_date', $deadline_cp );
            if($responsible_id > 0) update_comment_meta( $comment_id, 'responsible_id', $responsible_id ); 
        }
    }
    //wp_reset_postdata();

}

// добавляем запланированный хук  
function add_action_for_event_deadline() {  
	wp_schedule_event( time(), 'hourly', 'cp_deadline_control'); 
}

function del_action_for_event_deadline() {  
 wp_clear_scheduled_hook('cp_deadline_control');	
}
    
    

// Функция меняет текст письма который уходит уведомлением о нарушении срока
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
    
    
    
    
/**
 * Служебные функции одиночки
 */
protected function __clone() {
	// ограничивает клонирование объекта
}
static public function getInstance() {
	if(is_null(self::$_instance))
	{
	self::$_instance = new self();
	}
	return self::$_instance;
}    
    
} $Notice_Deadline_CP = Notice_Deadline_CP_Singltone::getInstance();