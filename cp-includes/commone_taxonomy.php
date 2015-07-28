<?php

/*
 * Add taxonomy for classification subjects. This taxonomy replace 2 taxonomy: persons category and org category
 */


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
add_action('init', 'register_subjects_category_tax');
add_action('cp_activate', 'register_subjects_category_tax');


function register_organization_structure_tax() {
	$labels = array(
		'name' 					=> 'Подразделения',
		'singular_name' 		=> 'Подразделение',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить',
		'edit_item' 			=> 'Изменить',
		'new_item' 				=> 'Новая',
		'view_item' 			=> 'Просмотр',
		'search_items' 			=> 'Поиск',
		'not_found' 			=> 'Подразделения не найдены',
		'not_found_in_trash' 	=> 'В корзине не найдены подразделения',
	);
	
	$post_types = array('persons', 'cases');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Подразделение',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'organization_structure', 'with_front' => false ),
	 );
	register_taxonomy('organization_structure', $post_types, $args);
}
add_action('init', 'register_organization_structure_tax');
add_action('cp_activate', 'register_organization_structure_tax');
