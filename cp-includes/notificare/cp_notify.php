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
        //add users to list for notifi
        add_action( 'wp_insert_comment', array($this, 'add_users_list_to_comment_for_notice'), 110, 2);
        
        //add plan function
        add_action('cp_email_notification', array($this, 'email_notifications_for_users'));
        
        add_action('cp_activate', array($this, 'activate'));
		
        add_action('cp_deactivate', array($this, 'deactivate'));

    }
    
    
    function add_users_list_to_comment_for_notice($comment_id, $comment){
        
        if ( $comment->comment_type == 'visited') return;
        
        //get post ID and $post
        $post_id = $comment->comment_post_ID;
        $r = get_post( $post_id );
        
        //it is cases?
        if ( $r->post_type != 'cases') return;
        
        //add tag for plan email
        add_comment_meta($comment_id, 'email_notify', 0);
        
        //get members for cases
        $members = get_post_meta( $post_id, 'members-cp-posts-sql');

        $message = $post_id . '<пост, участник: ' . print_r($members, true);
        
        error_log($message);
        
        //add user id to list for notification
        foreach ( $members as $member ) {
            $user = get_user_by_person( $member);
            if (get_current_user_id() == $user) continue;
            if ($user > 0) {
                add_comment_meta( $comment_id, 'notify_user', $user);
                //error_log('comment: '. $comment_id . ', val: ' . $user);
            }
        }
    }
    
    function email_notifications_for_users() {
        //error_log('Запланированный хук');
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
            foreach ($users as $user_id) {
                //error_log('user: '.$user_id);
                //error_log('users note: '.print_r($users_notified, true));
                //тут не плохо было бы проверить отправлено данному пользователю уже уведомление или нет
                if(in_array($user_id, $users_notified)) continue;
                
                //если автор комментария ест в участниках, то ему уведомление на почту не отправлять, но отмечать как уведомленный
                if($comment->user_id == $user_id){
                    add_comment_meta( $comment_id, 'notified_user', $user_id);
                    continue;
                }
                
                //тут функция отправки, которая возвращает результат отправки
                if ($this->send_email($user_id, $comment)){
                    // если все хорошо то записываем пользователя в список отправленных уведомлений 
                    add_comment_meta( $comment_id, 'notified_user', $user_id);
                }
                
            }
            $users_notified = get_comment_meta( $comment_id, 'notified_user' );
            
            //if both lists equal - add tag about all ok
            if ($users == $users_notified) update_comment_meta ($comment_id, 'email_notify', '1', '0');
        }
    }
    

    function send_email($user_id, $comment) {
        
        $user = get_userdata($user_id);
        $r = get_post($comment->comment_post_ID);
        
        $author = get_userdata( $comment->user_id);
        
        $msg = array();
        $msg['subject'] = $r->post_title.' ['.$r->ID.']';
        $msg['text'] = '<p>Пользователь <a href="' .$author->user_url. '">'.$author->display_name.'</a> добавил(а) комментарий:</p><hr>';
        $msg['text'] .= '<div>'.$comment->comment_content.'</div>';
        $msg['text'] .= '<hr>';
        $msg['text'] .= '<a href="'.get_permalink($comment->comment_post_ID).'#comment-'.$comment->comment_ID.'">Перейти</a> | ';
        $msg['text'] .= '<a href="'.get_permalink($comment->comment_post_ID).'#respond">Ответить</a>';
        
        
        
        //
        //$subject = apply_filters('cp_notice_chg_subject', $subject, $comment);
        $msg = apply_filters('cp_notice_chg_message', $msg, $comment);
        
        add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
        return wp_mail($user->user_email, $msg['subject'], $msg['text']);
    }

    

}

$cp_notification = new cp_notification_for_basic_comments();


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
            //$event = $wpdb->get_results("SELECT * FROM `" . $this->events_table . "` WHERE `event_id` = ".$result->event_id);
            echo "<td class='noty_td'><a href='".get_permalink($comment->comment_post_ID)."'>#".$comment->comment_post_ID.": ".get_the_title($comment->comment_post_ID)."</a></td>";                
            echo "<td class='noty_td'>".$comment->comment_content."</td>";
            echo "<td class='noty_td'>".$comment->comment_date."</td>";
            echo "<tr/>";
        }
    }
    echo "</table>";
}

add_shortcode('notifies', 'list_notifies');