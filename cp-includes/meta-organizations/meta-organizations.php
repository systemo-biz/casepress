<?php



/*
Создаем организации и связанные таксономии
*/
class MetaOrgModelSingltone {
private static $_instance = null;

private function __construct() {
    
    add_action('cp_activate', array($this, 'model_orgunits'));	
    add_action('init', array($this, 'model_orgunits'));

    add_action('cp_activate', array($this, 'model_process'));	
    add_action('init', array($this, 'model_process'));    

    add_action('cp_activate', array($this, 'model_process_category'));	
    add_action('init', array($this, 'model_process_category'));  

}


// Register Custom Post Type
function model_process() {

	$labels = array(
		'name'                => _x( 'Процессы', 'Post Type General Name', 'casepress' ),
		'singular_name'       => _x( 'Процесс', 'Post Type Singular Name', 'casepress' ),
		'menu_name'           => __( 'Процессы', 'casepress' ),
		'parent_item_colon'   => __( 'Parent', 'casepress' ),
		'all_items'           => __( 'All', 'casepress' ),
		'view_item'           => __( 'View', 'casepress' ),
		'add_new_item'        => __( 'Add New', 'casepress' ),
		'add_new'             => __( 'New', 'casepress' ),
		'edit_item'           => __( 'Edit', 'casepress' ),
		'update_item'         => __( 'Update', 'casepress' ),
		'search_items'        => __( 'Search', 'casepress' ),
		'not_found'           => __( 'Not found', 'casepress' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'casepress' ),
	);
	$args = array(
		'label'               => __( 'Процессы', 'casepress' ),
		'description'         => __( 'Описание процессов', 'casepress' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'comments', 'page-attributes', ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'process', $args );
    register_taxonomy_for_object_type( 'results', 'process' );
}




function model_orgunits() {

	$labels = array(
		'name'                => 'Оргструктура',
		'singular_name'       => "Единица структуры",
		'menu_name'           => "Оргструктура",
		'parent_item_colon'   => __( 'Parent', 'casepress' ),
		'all_items'           => __( 'All', 'casepress' ),
		'view_item'           => __( 'View', 'casepress' ),
		'add_new_item'        => "Добавить",
		'add_new'             => __( 'New', 'casepress' ),
		'edit_item'           => __( 'Edit', 'casepress' ),
		'update_item'         => __( 'Update', 'casepress' ),
		'search_items'        => __( 'Search', 'casepress' ),
		'not_found'           => __( 'Not found', 'casepress' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'casepress' ),
	);
	$args = array(
		'label'               => __( 'Org Item', 'casepress' ),
		'description'         => __( 'Desciption Organization', 'casepress' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'comments', 'page-attributes', ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'unit', $args );

}



// Register Custom Taxonomy
function model_process_category()  {

	$labels = array(
		'name'                       => _x( 'Category Process', 'Taxonomy General Name', 'casepress' ),
		'singular_name'              => _x( 'Category Process', 'Taxonomy Singular Name', 'casepress' ),
		'menu_name'                  => __( 'Category Process', 'casepress' ),
		'all_items'                  => __( 'All', 'casepress' ),
		'parent_item'                => __( 'Parent', 'casepress' ),
		'parent_item_colon'          => __( 'Parent:', 'casepress' ),
		'new_item_name'              => __( 'New', 'casepress' ),
		'add_new_item'               => __( 'Add New', 'casepress' ),
		'edit_item'                  => __( 'Edit', 'casepress' ),
		'update_item'                => __( 'Update', 'casepress' ),
		'separate_items_with_commas' => __( 'Separate with commas', 'casepress' ),
		'search_items'               => __( 'Search', 'casepress' ),
		'add_or_remove_items'        => __( 'Add or remove', 'casepress' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'casepress' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'process_category', 'process', $args );

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

} $MetaOrgModel = MetaOrgModelSingltone::getInstance();

