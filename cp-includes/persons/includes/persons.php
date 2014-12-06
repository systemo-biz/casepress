<?php


class PersonsModelSingltone {
private static $_instance = null;

private function __construct() {

    add_action('cp_activate', array($this, 'register_persons_post_type'));	
    add_action('init', array($this, 'register_persons_post_type'));	

    add_action('cp_activate', array($this, 'register_persons_post_tax'));	
    add_action('init', array($this, 'register_persons_post_tax'));

    add_action('cp_activate', array($this, 'register_persons_category_tax'));	
    add_action('init', array($this, 'register_persons_category_tax'));

}

    
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
		'show_ui' 			=> false,
		'hierarchical' 		=> false,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => false,
		'rewrite' 			=> array('slug' => 'persons_category', 'with_front' => false ),
	 );
	register_taxonomy('persons_category', $pages, $args);
}

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
    
    
    
    
function register_persons_post_type() {
	$labels = array(
		'name' 				=> 'Персоны',
		'singular_name'		=> 'Персона',
		'add_new' 			=> 'Добавить',
		'add_new_item' 		=> 'Добавить Персону',
		'edit_item' 		=> 'Редактировать Персону',
		'new_item' 			=> 'Новая Персона',
		'view_item' 		=> 'Просмотр Персону',
		'search_items' 		=> 'Поиск Персоны',
		'not_found' 		=> 'Персона не найдена',
		'not_found_in_trash'=> 'В Корзине Персона не найдена',
		'parent_item_colon' => ''
	);
	
	$taxonomies = array();
	
	$supports = array(
		'editor',
		'comments',
		'thumbnail',
		'title'
		);
	
	if (get_option( 'enable_custom_fields_for_cases' )) $supports[]="custom-fields";
			
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Персона',
		'public' 			=> true,
		'show_ui' 			=> true,
		'publicly_queryable'=> true,
		'query_var'			=> true,
		'capability_type' 	=> 'post',	
		'has_archive' 		=> true,
		'hierarchical' 		=> true,
		'rewrite' 			=> array('slug' => 'persons', 'with_front' => false ),
		'supports' 			=> $supports,
		'menu_position' 	=> 5,
		'taxonomies'		=> $taxonomies
	 );
	register_post_type('persons',$args);
}
    
protected function __clone() {
	// ограничивает клонирование объекта
}

static public function getInstance() {
	if(is_null(self::$_instance))
	{
	self::$_instance = new self();
	}
	return self::$_instance;
}

} $PersonsModel = PersonsModelSingltone::getInstance();

