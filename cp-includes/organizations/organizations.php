<?php



/*
Создаем организации и связанные таксономии
*/
class OrgModelSingltone {
private static $_instance = null;

private function __construct() {
    add_action('cp_activate', array($this, 'register_organizations_post_type'));	
    add_action('init', array($this, 'register_organizations_post_type'));
   
    add_filter('post_type_link', array($this, 'org_post_type_link'), 10, 2);

    add_action('cp_activate', array($this, 'register_organizations_category_tax'));	
    add_action('init', array($this, 'register_organizations_category_tax'));  

    add_action('cp_activate', array($this, 'register_organization_structure_tax'));	
    add_action('init', array($this, 'register_organization_structure_tax'));  

}

    
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
    
    
    
    
    
function register_organizations_category_tax() {
	$labels = array(
		'name' 					=> 'Категории организаций',
		'singular_name' 		=> 'Категория организаций',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Категорию организаций',
		'edit_item' 			=> 'Редактировать Категорию организаций',
		'new_item' 				=> 'Новая Категория организаций',
		'view_item' 			=> 'Просмотр Категории организаций',
		'search_items' 			=> 'Поиск Категории организаций',
		'not_found' 			=> 'Категория организаций не найдена',
		'not_found_in_trash' 	=> 'В Корзине Категория организаций не найдена',
	);
	
	$pages = array('organizations');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Категория организаций',
		'public' 			=> true,
		'show_ui' 			=> false,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => false,
		'rewrite' 			=> array('slug' => 'organizations_category', 'with_front' => false ),
	 );
	register_taxonomy('organizations_category', $pages, $args);
}
    
    
function register_organizations_post_type() {
	$labels = array(
		'name' 				=> 'Организации',
		'singular_name'		=> 'Организация',
		'add_new' 			=> 'Добавить',
		'add_new_item' 		=> 'Добавить Организацию',
		'edit_item' 		=> 'Редактировать Организацию',
		'new_item' 			=> 'Новая Организация',
		'view_item' 		=> 'Просмотр Организации',
		'search_items' 		=> 'Поиск Организации',
		'not_found' 		=> 'Организация не найдена',
		'not_found_in_trash'=> 'В Корзине Организация не найдена',
		'parent_item_colon' => ''
	);
	
	$taxonomies = array();
	
	$supports = array(
		'title',
		'editor',
//		'author',
//		'thumbnail',
//		'excerpt',
//		'custom-fields',
		'comments',
//		'revisions',
//		'post-formats',
//		'page-attributes'
	);

	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Организация',
		'public' 			=> true,
		'show_ui' 			=> true,
		'publicly_queryable'=> true,
		'query_var'			=> true,
		'capability_type' 	=> 'post',	
		'has_archive' 		=> true,
		'hierarchical' 		=> true,
		'rewrite' 			=> array('slug' => 'organizations', 'with_front' => false ),
		'supports' 			=> $supports,
		'menu_position' 	=> 5,
		'taxonomies'		=> $taxonomies
	 );
	register_post_type('organizations',$args);
    add_rewrite_rule(
        'organizations/([0-9]+)?$',
        'index.php?post_type=organizations&p=$matches[1]',
        'top' );
}



function org_post_type_link( $link, $post = 0 ){
    if ( $post->post_type == 'organizations' ){
        return home_url( 'organizations/' . $post->ID );
    } else {
        return $link;
    }
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

} $OrgModel = OrgModelSingltone::getInstance();

