<?php



function add_notice_for_added_members($meta_id, $object_id, $meta_key, $meta_value){

    if ($meta_key != 'members-cp-posts-sql') return;
    
    $comment_check = get_comments(array (
            'post_id' => $object_id,  
            'post_type' => 'cases', 
            'type' => 'event_case_member_added',
            'meta_key' => 'member_id',
           'meta_value' => $meta_value,
        ));
    
    if (! empty($comment_check)) return;
        
    $user_ID = get_current_user_id();

    $comment_id = wp_new_comment(array(
       'comment_post_ID' => $object_id,
       'comment_author' => 'CasePress',
       'comment_content' => 'Добавлен участник - ' . $meta_value,
       'comment_type' => 'event_case_member_added',
       'user_ID' => $user_ID
	   ));
    
    //Добавляем ИД участника в мету
    add_comment_meta( $comment_id, 'member_id', $meta_value, true );
    
}
add_action('added_comment_meta', 'add_notice_for_added_members', 20, 4);

//deleted_post_meta 

/*
Description: добавляет автокомментарий "Создана задача" с типом "event_case_added" к новым делам

*/
function add_comment_new_case_cp($post_ID, $post) {

    $user_ID = get_current_user_id();
    //$post_ID = get_the_ID();

    
    if ('cases' != get_post_type()) return;  


      $comment_check = get_comments(array (
            'post_id' => $post_ID,  
            'post_type' => 'cases', 
            'type' => 'event_case_added',
        ));
	 
      if (empty($comment_check)) {
	   // создаем массив данных нового комментария
       $commentdata = array(
       'comment_post_ID' => $post_ID,
       'comment_author' => 'CasePress',
       'comment_content' => 'Добавлено дело',
       'comment_type' => 'event_case_added',
       'user_ID' => $user_ID
	   );
       // добавляем данные в Базу Данных
       wp_new_comment($commentdata);
	}

} add_action('wp_insert_post','add_comment_new_case_cp', 100, 2);

