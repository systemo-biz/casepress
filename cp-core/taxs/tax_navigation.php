<?php

add_action('init', 'register_navigation_tax');
function register_navigation_tax() {
	$labels = array(
		'name' 					=> 'Навигации',
		'singular_name' 		=> 'Навигация',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Навигацию',
		'edit_item' 			=> 'Редактировать Навигацию',
		'new_item' 				=> 'Новая Навигация',
		'view_item' 			=> 'Просмотр Навигации',
		'search_items' 			=> 'Поиск Навигации',
		'not_found' 			=> 'Навигация не найдена',
		'not_found_in_trash' 	=> 'В Корзине Навигация не найдена',
	);
	
	$pages = array('cases');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Навигация',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> false,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'navigation', 'with_front' => false ),
	 );
	register_taxonomy('navigation', $pages, $args);
}

?>
