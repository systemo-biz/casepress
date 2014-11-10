<?php

/*
Plugin Name: CP Public Var
Description: Механизм отбора мет через URL вида meta_{key}={value}
*/



//добавляем в урл параметр posts_per_page, который позволяет выбрать количество постов на странице
function change_posts_per_page_cp( $query ) {

	if(! $query->is_main_query() ) return;

	if(! empty($_REQUEST['posts_per_page'])):

		$posts_per_page = $_REQUEST['posts_per_page'];
		if(is_numeric($posts_per_page) and $posts_per_page>0) {
			$query->set( 'posts_per_page', $posts_per_page );
			return;
		}
		
	endif;

}

add_action( 'pre_get_posts', 'change_posts_per_page_cp' );




//добавляе возможность отбора постов через параметр урл case_members, который может содержать ИД персоны
// сейчас используется в досье Персноны, по возможности надо заменить на filter_posts_meta_cp (полный аналог, но более универсальный) и удалить всю данную функцию
function filter_case_member_cp( $query ) {
	
	if(! $query->is_main_query() ) return;
	if(empty($_REQUEST['case_members'])) return;

	$case_members = $_REQUEST['case_members'];

	if($case_members>0) {
	
		//Get original meta query
		$meta_query = $query->get('meta_query');

		//Add our meta query to the original meta queries
		$meta_query[] = array(
		                    'key'=>'members-cp-posts-sql',
		                    'value'=>$case_members,
		                    'compare'=>'in',
		                );

		$query->set('meta_query',$meta_query);

		return;
	}
		
	
}

add_action( 'pre_get_posts', 'filter_case_member_cp' );



//универсальный фильтр по метаполям
// Укахываем параметр meta_ и далее ключ. Если значения совпали, то произойдет отбор.
function filter_posts_meta_cp($query) {

	if(empty($_REQUEST)) return;

	if(! $query->is_main_query()) return;

	foreach ( array_keys($_REQUEST) as $key) {
	    if ( 'meta_' == substr( $key, 0, 5 ) ) $meta_keys[] = $key;
	}


	if(empty($meta_keys)) return;

	$meta_query = $query->get('meta_query');

	foreach ($meta_keys as $key_request) {
		$meta_key = substr( $key_request, 5);

		$meta_value = $_REQUEST[$key_request];
		
		$meta_query[] = array(
            'key' 		=>	$meta_key,
            'value'		=>	$meta_value,
            'compare'	=>	'in',
            );

	}

	$query->set('meta_query',$meta_query);

	return;	

} 
add_action('pre_get_posts', 'filter_posts_meta_cp');








