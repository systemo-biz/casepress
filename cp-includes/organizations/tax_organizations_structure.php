<?php

add_action('init', 'register_organization_structure_tax');
function register_organization_structure_tax() {
	$labels = array(
		'name' 					=> 'Структуры организаций',
		'singular_name' 		=> 'Структура организаций',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Структуру организаций',
		'edit_item' 			=> 'Редактировать Структуру организаций',
		'new_item' 				=> 'Новая Структура организаций',
		'view_item' 			=> 'Просмотр Структуры организаций',
		'search_items' 			=> 'Поиск Структуры организаций',
		'not_found' 			=> 'Структура организаций не найдена',
		'not_found_in_trash' 	=> 'В Корзине Структура организаций не найдена',
	);
	
	$pages = array('persons');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Структура организаций',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'organization_structure', 'with_front' => false ),
	 );
	register_taxonomy('organization_structure', $pages, $args);
}

?>
