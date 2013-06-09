<?php
add_action('init', 'register_report_tax');
function register_report_tax() {
	$labels = array(
		'name' 					=> 'Категория отчетов',
		'singular_name' 		=> 'отчет',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Категорию отчетов',
		'edit_item' 			=> 'Редактировать Категорию отчетов',
		'new_item' 				=> 'Новый Категория отчетов',
		'view_item' 			=> 'Просмотр Категории отчетов',
		'search_items' 			=> 'Поиск Категории отчетов',
		'not_found' 			=> 'Категория отчетов не найденя',
		'not_found_in_trash' 	=> 'В Корзине категория не найдена',
	);
	
	$pages = array('report','chart_report');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Категории отчетов',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'report_cat', 'with_front' => false ),
	 );
	register_taxonomy('report_cat', $pages, $args);
}

?>