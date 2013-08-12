<?php

add_action('init', 'register_organizations_category_tax');
function register_organizations_category_tax() {
	$labels = array(
		'name' 					=> 'Категории организаций',
		'singular_name' 		=> 'Категория организаций',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Категорию организаций',
		'edit_item' 			=> 'Редактировать Категорию организаций',
		'new_item' 				=> 'Новая Категория организаций',
		'view_item' 			=> 'Просмотр Категории организаций',
		'search_items' 			=> 'Поиск Категории организаций',
		'not_found' 			=> 'Категория организаций не найдена',
		'not_found_in_trash' 	=> 'В Корзине Категория организаций не найдена',
	);
	
	$pages = array('organizations');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Категория организаций',
		'public' 			=> true,
		'show_ui' 			=> false,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => false,
		'rewrite' 			=> array('slug' => 'organizations_category', 'with_front' => false ),
	 );
	register_taxonomy('organizations_category', $pages, $args);
}

?>
