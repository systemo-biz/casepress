<?php

add_action('init', 'register_reports_category_tax');
function register_reports_category_tax() {
	$labels = array(
		'name' 					=> 'Категории отчетов',
		'singular_name' 		=> 'Категории отчетов',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Категорию отчетов',
		'edit_item' 			=> 'Редактировать Категорию отчетов',
		'new_item' 				=> 'Новая Категория отчетов',
		'view_item' 			=> 'Просмотр Категории отчетов',
		'search_items' 			=> 'Поиск Категории отчетов',
		'not_found' 			=> 'Категория отчетов не найдена',
		'not_found_in_trash' 	=> 'В Корзине Категория отчетов не найдена',
	);
	
	$pages = array('reports');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Категория отчетов',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> false,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'reports_category', 'with_front' => false ),
	 );
	register_taxonomy('reports_category', $pages, $args);
}

?>
