<?php
add_action('init', 'register_notify_template_action');
function register_notify_template_action() {
	$labels = array(
		'name' 					=> 'Действие',
		'singular_name' 		=> 'Действие',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить действие',
		'edit_item' 			=> 'Редактировать действие',
		'new_item' 				=> 'Новое действие',
		'view_item' 			=> 'Просмотр действия',
		'search_items' 			=> 'Поиск действия',
		'not_found' 			=> 'Действие не найдено',
		'not_found_in_trash' 	=> 'В Корзине действие не найдено',
	);
	
	$pages = array('notify_templates');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Действие',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'notify_template_action', 'with_front' => false ),
	 );
		register_taxonomy('notify_template_action', $pages, $args);
}

?>
