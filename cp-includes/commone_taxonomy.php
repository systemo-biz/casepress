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


//Добавляем подразделения
function register_branche_tax() {

$n = array('Подразделение', 'Подразделения');//in next versions this variable need move to options WP

  $labels = array(
    'name' => $n[1],
    'singular_name' => $n[0],
    'add_new' => 'Добавить',
    'add_new_item' => 'Добавить '.$n[0],
    'edit_item' => 'Редактировать '.$n[1],
    'new_item' => 'Новое '.$n[0],
    'view_item' => 'Просмотр '.$n[1],
    'search_items' => 'Поиск '.$n[1],
    'not_found' => $n[0].' не найдено',
    'not_found_in_trash' => 'В Корзине '.$n[0].' не найдено',
    );

  $post_types = array('cases','persons');

  $args = array(
    'labels' => $labels,
    'singular_label' => $n[0],
    'public' => true,
    'show_ui' => true,
    'hierarchical' => true,
    'show_tagcloud' => true,
    'show_in_nav_menus' => true,
    'rewrite' => array('slug' => 't-branche', 'with_front' => false ),
  );

  register_taxonomy('t-branche', $post_types, $args);
}
add_action('init', 'register_branche_tax');
add_action('cp_activate', 'register_branche_tax');
