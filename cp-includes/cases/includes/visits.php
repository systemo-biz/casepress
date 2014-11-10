<?php

/*

Мезанизм отслеживания посещений, и создания комментариев соответствующего типа.

*/


function add_visits_tab( $tabs ) {
    $tabs[] = array( 'slug' => 'visits_tab', 'name' => 'Просмотры' );
    return $tabs;
} add_filter( 'cases_tabs', 'add_visits_tab', 20 ); 

//проверяем был ли вход и если да, то записываем факт посещения
function check_view(){
    if (is_user_logged_in()&&(get_post_type()=='cases')) {
    $usr=get_current_user_id();
    $post_id = get_the_ID();
    $args = array(  
        'number' => '1',  
        'post_id' => $post_id,
    	'type' => 'visited',  
        'user_id' => $usr 
    );
    $comments = get_comments($args);  
    if (!empty($comments)){
    foreach($comments as $comment){
    	$commentarr = array();
    	$commentarr['comment_ID'] = $comment->comment_ID;
    	$commentarr['comment_content'] = current_time('mysql');
    	wp_update_comment( $commentarr );
    }
    }
    else{
      wp_insert_comment(array(
        'comment_post_ID' => $post_id,
        'user_id' => $usr,
        'comment_type' => 'visited',
        'comment_date' => current_time('mysql'),
    	'comment_content' => current_time('mysql')
      ));
    }
    }
} add_action('wp_footer','check_view');

function cases_tab_content_visits_tab(){

    //if !(is_singular('cases')) return;


    $tt='<table border=1 width=100%>';
    $tt.='<tr style="font-weight:bold; text-align:center;"> <td>Посетитель</td><td>Дата первого посещения</td><td>Дата последнего посещения</td></tr>';
    $post_id = get_the_ID();
    $args = array(  
        'number' => '300',  
        'post_id' => $post_id,
    	'type' => 'visited');  
    	//change on display name users
    $comments = get_comments($args);  
    if (!empty($comments)){
    foreach($comments as $comment){
    $user_info = get_userdata($comment->user_id);  
    //$post_id_usr = get_post($my_id = $user_info->id_person);  
    $tt.='<tr>';
    $tt.='<td>'.$user_info->display_name.'</td>';
    $tt.='<td>'.$comment->comment_date.'</td>';
    $tt.='<td>'.$comment->comment_content.'</td>';
    $tt.='</tr>';
    } 
    }
    $tt.='</table>';
    return $tt; 
} add_action('template_redirect', 'cases_tab_content_visits_tab');