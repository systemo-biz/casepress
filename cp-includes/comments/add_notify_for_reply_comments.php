<?php


/*

Нужно добавлять в список уведомления пользователя, если на его комментарий появился ответ

*/

function add_users_for_notice_if_reply_cp($comment_id, $comment){

    //Если у коммента нет родителя, то возврат
    if (empty($comment->comment_parent)) return;

    //Если у родительского коммента нет пользователя, то возврат
    $comment_parent = get_comment($comment->comment_parent);    
    if(empty($comment_parent->user_id)) return;
    
    
    /*
    //get post ID and $post
    $post_id = $comment->comment_post_ID;
    $r = get_post( $post_id );
    
    //it is cases?
    if ( $r->post_type != 'cases') return;
*/
            
    //add tag for plan email
    add_comment_meta($comment_id, 'email_notify', 0, true);
    add_comment_meta( $comment_id, 'notify_user', $comment_parent->user_id);
    
} add_action( 'wp_insert_comment', 'add_users_for_notice_if_reply_cp', 10, 2);