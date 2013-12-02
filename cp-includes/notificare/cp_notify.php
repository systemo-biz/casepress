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
        
        //add_action('cp_activate', array($this, 'activate'));
		
        //add_action('cp_deactivate', array($this, 'deactivate'));

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

        //$message = $post_id . '<пост, участник: ' . print_r($members, true);
        //error_log($message);
        
        //add user id to list for notification
        foreach ( $members as $member ) {
            $id_usr = get_user_by_person( $member);

			//Если участник текущий пользователь, то не нужно добавлять в список уведомлений
            if (get_current_user_id() == $id_usr) continue;

			//Если участник забанен, то не нужно добавлять в список уведомлений
			$userdata = new WP_User( $id_usr );
			$user_role = array_shift($userdata->roles);
			if ($user_role == 'banned') continue;
			
			//Если у участника есть пользователь
            if ($id_usr > 0) {
                add_comment_meta( $comment_id, 'notify_user', $id_usr);
                //error_log('comment: '. $comment_id . ', val: ' . $user);
            }
        }
    }
    
    function email_notifications_for_users() {
        error_log('********************** Запланированный хук ***************************');
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
                error_log('user: '.$nuser_id);
                //error_log('users note: '.print_r($users_notified, true));
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
        error_log("Отправлено: " . print_r($nuser->user_email, true));
		return $send;
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
            echo "<td class='noty_td'><a href='".get_permalink($comment->comment_post_ID)."'>#".$comment->comment_post_ID.": ".get_the_title($comment->comment_post_ID)."</a></td>";                
            echo "<td class='noty_td'>".$comment->comment_content."</td>";
            echo "<td class='noty_td'>".$comment->comment_date."</td>";
            echo "<tr/>";
        }
    }
    echo "</table>";
}

add_shortcode('notifies', 'list_notifies');