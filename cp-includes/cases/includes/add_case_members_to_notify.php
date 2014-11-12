<?php

function add_users_list_to_comment_for_notice($comment_id, $comment){
    //get post ID and $post
    $post_id = $comment->comment_post_ID;
    $r = get_post( $post_id );
    
    //it is cases?
    if ( $r->post_type != 'cases') return;

            
    if ( $comment->comment_type == 'visited') return;
    

    
    //add tag for plan email
    add_comment_meta($comment_id, 'email_notify', 0, true);
    
    //get members for cases
    $members = get_post_meta( $post_id, 'members-cp-posts-sql');

    //$message = $post_id . '<пост, участник: ' . print_r($members, true);
    //error_log($message);
    
    //add user id to list for notification
    foreach ( $members as $member ) {
        $id_usr = get_user_by_person( $member);

		//Если участник текущий пользователь, то не нужно добавлять в список уведомлений
        if (get_current_user_id() == $id_usr) continue;

		
		//Если у участника есть пользователь
        if ($id_usr > 0) {
            add_comment_meta( $comment_id, 'notify_user', $id_usr);
            //error_log('comment: '. $comment_id . ', val: ' . $user);
        }
    }
}
//add users to list for notifi
add_action( 'wp_insert_comment', 'add_users_list_to_comment_for_notice', 110, 2);
