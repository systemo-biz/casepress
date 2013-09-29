<?php

add_action('init', 'register_results_tax');
function register_results_tax() {
	$labels = array(
		'name' 					=> 'Результаты',
		'singular_name' 		=> 'Результат',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Результат',
		'edit_item' 			=> 'Редактировать Результат',
		'new_item' 				=> 'Новый Результат',
		'view_item' 			=> 'Просмотр Результата',
		'search_items' 			=> 'Поиск Результата',
		'not_found' 			=> 'Результат не найден',
		'not_found_in_trash' 	=> 'В Корзине Результат не найден',
	);
	
	$post_types = array('cases', 'process');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Результат',
		'public' 			=> false,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'results', 'with_front' => false ),
	 );
	register_taxonomy('results', $post_types, $args);    
}


add_action( 'admin_menu' , 'remove_result_mb_cases' );  
function remove_result_mb_cases() {  
    remove_meta_box( 'resultsdiv' , 'cases' , 'side' );  
}  
