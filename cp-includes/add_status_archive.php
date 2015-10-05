<?php

function status_archive_cp(){
	register_post_status( 'archive', array(
		'label'                     => 'Архив',
		'label_count'               => _n_noop( 'Архив <span class="count">(%s)</span>', 'Архивы <span class="count">(%s)</span>' ),
		'public'                    => true,
		'show_in_admin_status_list' => true // если установить этот параметр равным false, то следующий параметр можно удалить
	) );
}
add_action( 'init', 'status_archive_cp' );

function append_post_status_list_cp(){
	global $post;
	$optionselected = '';
 	$statusname = '';
	if($post->post_status == 'archive'){ // если посту присвоен статус архива
		$optionselected = ' selected="selected"';
		$statusname = "$('#post-status-display').text('Архивировано');";
	}
	/*
	 * Код jQuery мы просто выводим в футере
	 */
	echo "<script>
	jQuery(function($){
		$('select#post_status').append('<option value=\"archive\"$optionselected>Архив</option>');
		$statusname
	});
	</script>";
	
}
add_action('admin_footer-post-new.php', 'append_post_status_list_cp'); // страница создания нового поста
add_action('admin_footer-post.php', 'append_post_status_list_cp');



function status_display_in_archive_admin_cp( $statuses ) {
	global $post;
	if( get_query_var( 'post_status' ) != 'archive' ){ // проверка, что мы не находимся на странице всех постов данного статуса
		if($post->post_status == 'archive'){ // если статус поста - Архив
			return array('Архив');
		}
	}
	return $statuses;
}
 
add_filter( 'display_post_states', 'status_display_in_archive_admin_cp' );


function add_status_cp() {
	echo "<script>
	jQuery(document).ready( function($) {
		$( 'select[name=\"_status\"]' ).append( '<option value=\"archive\">Архив</option>' );
	});
	</script>";
}
add_action('admin_footer-edit.php','add_status_cp');
