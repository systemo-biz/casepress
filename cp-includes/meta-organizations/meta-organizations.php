<?php 
/*
Plugin Name: Meta Organization
Description: Description business processes and org unit
*/


// Register Custom Post Type
function model_process() {

	$labels = array(
		'name'                => _x( 'Processes', 'Post Type General Name', 'casepress' ),
		'singular_name'       => _x( 'Process', 'Post Type Singular Name', 'casepress' ),
		'menu_name'           => __( 'Processes', 'casepress' ),
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
		'label'               => __( 'Processes', 'casepress' ),
		'description'         => __( 'Desciption processes', 'casepress' ),
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

// Hook into the 'init' action
add_action( 'init', 'model_process', 0 );



function model_orgunits() {

	$labels = array(
		'name'                => _x( 'Org Items', 'Post Type General Name', 'casepress' ),
		'singular_name'       => _x( 'Org Item', 'Post Type Singular Name', 'casepress' ),
		'menu_name'           => __( 'Org Items', 'casepress' ),
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
	register_post_type( 'org_unit', $args );

}

// Hook into the 'init' action
add_action( 'init', 'model_orgunits', 0 );


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

// Hook into the 'init' action
add_action( 'init', 'model_process_category', 0 );

?>