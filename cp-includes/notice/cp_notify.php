<?php
/*
Plugin Name: Cp-notify2
Plugin URI: http://casepress.org
Description: Add notify system into casepress
Version: 2.0
Author: CasePress Studio
Author URI: http://casepress.org
License: MIT
*/




class cp_notification_for_basic_comments {

    function __construct() {
        
        add_shortcode('notifies', array($this, 'list_notifies'));
        
        
        //add plan function
        add_action('cp_email_notification', array($this, 'email_notifications_for_users'));
        
        add_action('cp_activate', array($this, 'activate'));
		
        add_action('cp_deactivate', array($this, 'deactivate'));

    }
    
    

    
    function email_notifications_for_users() {
        //error_log('********************** Запланированный хук ***************************');
        $comments = get_comments( array(
                                'status' => 'approve', 
                                'meta_key'=>'email_notify',
                                'meta_value'=>'0'
                                ));
        foreach($comments as $comment){
            //error_log($comment->comment_author . '<br />' . $comment->comment_content);
            $comment_id = $comment->comment_ID;
            $users = get_comment_meta( $comment_id, 'notify_user');
            $users_notified = get_comment_meta( $comment_id, 'notified_user' );
            foreach ($users as $nuser_id) {


                //Если участник забанен, то не нужно добавлять в список уведомлений
                $userdata = new WP_User( $nuser_id );
                $user_role = array_shift($userdata->roles);
                if ($user_role == 'banned') continue;

                //тут не плохо было бы проверить отправлено данному пользователю уже уведомление или нет
                if(in_array($nuser_id, $users_notified)) continue;
                
                //если автор комментария ест в участниках, то ему уведомление на почту не отправлять, но отмечать как уведомленный
                if($comment->user_id == $nuser_id){
                    add_comment_meta( $comment_id, 'notified_user', $nuser_id);
                    continue;
                }
                
                //тут функция отправки, которая возвращает результат отправки
                if ($this->send_email($nuser_id, $comment)){
                    // если все хорошо то записываем пользователя в список отправленных уведомлений 
                    add_comment_meta( $comment_id, 'notified_user', $nuser_id);
                }
                
            }
            $users_notified = get_comment_meta( $comment_id, 'notified_user' );
            
            //if both lists equal - add tag about all ok
            if ($users == $users_notified) update_comment_meta ($comment_id, 'email_notify', '1', '0');
        }
    }
    

    function send_email($nuser_id, $comment) {
        
        $nuser = get_userdata($nuser_id);
        $r = get_post($comment->comment_post_ID);
        
        $cauthor = get_userdata( $comment->user_id);
        
		//error_log("comment->user_id = " . print_r($comment->user_id, true) . ", а автор: " . print_r($cauthor, true));
		
        $msg = array();
        $msg['subject'] = $r->post_title.' ['.$r->ID.']';
        $msg['text'] = '<p>Пользователь <a href="' .$cauthor->user_url. '">'.$cauthor->display_name.'</a> добавил(а) комментарий:</p><hr>';
        $msg['text'] .= '<div>'.$comment->comment_content.'</div>';
        $msg['text'] .= '<hr>';
        $msg['text'] .= '<a href="'.get_permalink($comment->comment_post_ID).'#comment-'.$comment->comment_ID.'">Перейти</a> | ';
        $msg['text'] .= '<a href="'.get_permalink($comment->comment_post_ID).'#respond">Ответить</a>';
        
		//Фильтр сообщения
        $msg = apply_filters('cp_notice_chg_message', $msg, $comment);
        
        add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
		$send = wp_mail($nuser->user_email, $msg['subject'], $msg['text']);
        //error_log("Отправлено: " . print_r($nuser->user_email, true));
		return $send;
    }

function list_notifies($atts){
$user_id = get_current_user_id();
$args = array(  
    'meta_key' => 'notify_user',
    'meta_value' => $user_id
);      
    $comments = get_comments($args);
    echo '<table class="table"><tr><th class="noty_th">Событие:</th><th>Содержание:</th><th>Дата:</th></tr>';
    
    if ($comments){
        foreach ($comments as $comment){                
            echo "<tr>";
            echo "<td class='noty_td'><a href='".get_permalink($comment->comment_post_ID)."'>#".$comment->comment_post_ID.": ".get_the_title($comment->comment_post_ID)."</a></td>";                
            echo "<td class='noty_td'>".$comment->comment_content."</td>";
            echo "<td class='noty_td'>".$comment->comment_date."</td>";
            echo "<tr/>";
        }
    }
    echo "</table>";
}

function activate(){
   wp_schedule_event( time(), '15sec', 'cp_email_notification'); 
}
/*Activation and deactivation plugin*/
function deactivate(){
    wp_clear_scheduled_hook('cp_email_notification');
}
}

$GLOBALS['cp_notification'] = new cp_notification_for_basic_comments();