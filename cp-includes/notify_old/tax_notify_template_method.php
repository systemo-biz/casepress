<?php
add_action('init', 'register_notify_template_method');
function register_notify_template_method() {
	$labels = array(
		'name' 					=> 'Способы доставки',
		'singular_name' 		=> 'Способ',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить способ',
		'edit_item' 			=> 'Редактировать способ',
		'new_item' 				=> 'Новый способ',
		'view_item' 			=> 'Просмотр способов',
		'search_items' 			=> 'Поиск способов',
		'not_found' 			=> 'Способ не найден',
		'not_found_in_trash' 	=> 'В Корзине способы доставки не найдены',
	);
	
	$pages = array('notify_templates');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Способ доставки',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'notify_template_method', 'with_front' => false ),
	 );
		register_taxonomy('notify_template_method', $pages, $args);
}
?>
