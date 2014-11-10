<?php

add_action('init', 'register_objects_posttype');	
function register_objects_posttype() {
	$labels = array(
		'name' 				=> 'Объекты',
		'singular_name'		=> 'Объект',
		'add_new' 			=> 'Добавить',
		'add_new_item' 		=> 'Добавить Объект',
		'edit_item' 		=> 'Редактировать Объект',
		'new_item' 			=> 'Новый Объект',
		'view_item' 		=> 'Просмотр Объекта',
		'search_items' 		=> 'Поиск Объекта',
		'not_found' 		=> 'Объект не найден',
		'not_found_in_trash'=> 'В Корзине Объект не найден',
		'parent_item_colon' => ''
	);
	
	$taxonomies = array();
	
	$supports = array(
		'title',
		'editor',
		'author',
		'thumbnail',
//		'excerpt',
//		'custom-fields',
		'comments',
		'revisions',
//		'post-formats',
		'page-attributes'
	);

	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Объект',
		'public' 			=> true,
		'show_ui' 			=> true,
		'publicly_queryable'=> true,
		'query_var'			=> true,
		'capability_type' 	=> 'post',	
		'has_archive' 		=> true,
		'hierarchical' 		=> true,
		'rewrite' 			=> array('slug' => 'objects', 'with_front' => false ),
		'supports' 			=> $supports,
		'menu_position' 	=> 5,
		'taxonomies'		=> $taxonomies
	 );
	register_post_type('objects',$args);
}

?>