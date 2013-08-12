<?php

/*
 * Add taxonomy for classification subjects. This taxonomy replace 2 taxonomy: porsons category and org category
 */

add_action('init', 'register_subjects_category_tax');
function register_subjects_category_tax() {
	$labels = array(
		'name' 					=> 'Категории субъектов',
		'singular_name' 		=> 'Категория субъекта',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Категорию субъекта',
		'edit_item' 			=> 'Редактировать Категорию субъекта',
		'new_item' 				=> 'Новая Категория субъектов',
		'view_item' 			=> 'Просмотр Категории субъектов',
		'search_items' 			=> 'Поиск Категории субъекта',
		'not_found' 			=> 'Категория не найдена',
		'not_found_in_trash' 	=> 'В корзине не найдена',
	);
		
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Категория субъектов',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'subjects_category', 'with_front' => false ),
	 );
	register_taxonomy('subjects_category', array('organizations', 'persons'), $args);
}