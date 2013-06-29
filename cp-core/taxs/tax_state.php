<?php

add_action('init', 'register_state_tax');
function register_state_tax() {
	$labels = array(
		'name' 					=> 'Состояния',
		'singular_name' 		=> 'Состояние',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Состояние',
		'edit_item' 			=> 'Редактировать Состояние',
		'new_item' 				=> 'Новое Состояние',
		'view_item' 			=> 'Просмотр Состояния',
		'search_items' 			=> 'Поиск Состояния',
		'not_found' 			=> 'Состояние не найдено',
		'not_found_in_trash' 	=> 'В Корзине Состояние не найдено',
	);
	
	$pages = array('cases');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Состояние',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> false,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'state', 'with_front' => false ),
	 );
	register_taxonomy('state', $pages, $args);
}
								
?>
