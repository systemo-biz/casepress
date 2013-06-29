<?php

add_action('init', 'register_objects_category_tax');
function register_objects_category_tax() {
	$labels = array(
		'name' 					=> 'Категории объектов',
		'singular_name' 		=> 'Категория объектов',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Категорию объектов',
		'edit_item' 			=> 'Редактировать Категорию объектов',
		'new_item' 				=> 'Новая Категория объектов',
		'view_item' 			=> 'Просмотр Категории объектов',
		'search_items' 			=> 'Поиск Категории объектов',
		'not_found' 			=> 'Категория объектов не найдена',
		'not_found_in_trash' 	=> 'В Корзине Категория объектов не найдена',
	);
	
	$pages = array('objects');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Категории объектов',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'objects_category', 'with_front' => false ),
	 );
	register_taxonomy('objects_category', $pages, $args);
}

?>
