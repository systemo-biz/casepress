<?php



/*
Создаем объекты и связанные таксономии
*/
class ObjectsModelSingltone {
private static $_instance = null;

private function __construct() {
    add_action('cp_activate', array($this, 'register_objects_post_type'));	
    add_action('init', array($this, 'register_objects_post_type'));
   
    add_action('cp_activate', array($this, 'register_objects_category_tax'));	
    add_action('init', array($this, 'register_objects_category_tax'));  

}


function register_objects_post_type() {
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

    
    
function register_objects_category_tax() {
	$labels = array(
		'name' 					=> 'Категории объектов',
		'singular_name' 		=> 'Категория объектов',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Категорию объектов',
		'edit_item' 			=> 'Редактировать Категорию объектов',
		'new_item' 				=> 'Новая Категория объектов',
		'view_item' 			=> 'Просмотр Категории объектов',
		'search_items' 			=> 'Поиск Категории объектов',
		'not_found' 			=> 'Категория объектов не найдена',
		'not_found_in_trash' 	=> 'В Корзине Категория объектов не найдена',
	);
	
	$pages = array('objects');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Категории объектов',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'objects_category', 'with_front' => false ),
	 );
	register_taxonomy('objects_category', $pages, $args);
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

} $ObjectsModel = ObjectsModelSingltone::getInstance();