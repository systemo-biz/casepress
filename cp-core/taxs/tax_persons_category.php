<?php

add_action('init', 'register_persons_category_tax');
function register_persons_category_tax() {
	$labels = array(
		'name' 					=> 'Категории персон',
		'singular_name' 		=> 'Категория персон',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Категорию персон',
		'edit_item' 			=> 'Редактировать Категорию персон',
		'new_item' 				=> 'Новая Категория персон',
		'view_item' 			=> 'Просмотр Категории персон',
		'search_items' 			=> 'Поиск Категории персон',
		'not_found' 			=> 'Категория персон не найдена',
		'not_found_in_trash' 	=> 'В Корзине Категория персон не найдена',
	);
	
	$pages = array('persons');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Категории персон',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> false,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'persons_category', 'with_front' => false ),
	 );
	register_taxonomy('persons_category', $pages, $args);
}

add_action('init', 'register_persons_post_tax');
function register_persons_post_tax() {
	$labels = array(
		'name' 					=> 'Должности',
		'singular_name' 		=> 'Должность',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить должность',
		'edit_item' 			=> 'Редактировать должность',
		'new_item' 				=> 'Новая должность',
		'view_item' 			=> 'Просмотр должности',
		'search_items' 			=> 'Поиск должностей',
		'not_found' 			=> 'Должность не найдена',
		'not_found_in_trash' 	=> 'В Корзине должность не найдена',
	);
	
	$pages = array('persons','org_unit');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Должность',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'persons_post', 'with_front' => false ),
	 );
	//if (current_user_can('manage_options')) 
	//{
		register_taxonomy('persons_post', $pages, $args);
	//}
}

?>
