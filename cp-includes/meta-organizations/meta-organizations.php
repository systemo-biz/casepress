<?php 
/*
Plugin Name: Meta Organization
Description: Description business processes and org unit
*/


// Register Custom Post Type
function model_process() {

	$labels = array(
		'name'                => _x( 'Processes', 'Post Type General Name', 'meta_organization' ),
		'singular_name'       => _x( 'Process', 'Post Type Singular Name', 'meta_organization' ),
		'menu_name'           => __( 'Processes', 'meta_organization' ),
		'parent_item_colon'   => __( 'Parent', 'meta_organization' ),
		'all_items'           => __( 'All', 'meta_organization' ),
		'view_item'           => __( 'View', 'meta_organization' ),
		'add_new_item'        => __( 'Add New', 'meta_organization' ),
		'add_new'             => __( 'New', 'meta_organization' ),
		'edit_item'           => __( 'Edit', 'meta_organization' ),
		'update_item'         => __( 'Update', 'meta_organization' ),
		'search_items'        => __( 'Search', 'meta_organization' ),
		'not_found'           => __( 'Not found', 'meta_organization' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'meta_organization' ),
	);
	$args = array(
		'label'               => __( 'Processes', 'meta_organization' ),
		'description'         => __( 'Desciption processes', 'meta_organization' ),
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

}

// Hook into the 'init' action
add_action( 'init', 'model_process', 0 );



function model_orgunits() {

	$labels = array(
		'name'                => _x( 'Org Items', 'Post Type General Name', 'meta_organization' ),
		'singular_name'       => _x( 'Org Item', 'Post Type Singular Name', 'meta_organization' ),
		'menu_name'           => __( 'Org Items', 'meta_organization' ),
		'parent_item_colon'   => __( 'Parent', 'meta_organization' ),
		'all_items'           => __( 'All', 'meta_organization' ),
		'view_item'           => __( 'View', 'meta_organization' ),
		'add_new_item'        => __( 'Add New', 'meta_organization' ),
		'add_new'             => __( 'New', 'meta_organization' ),
		'edit_item'           => __( 'Edit', 'meta_organization' ),
		'update_item'         => __( 'Update', 'meta_organization' ),
		'search_items'        => __( 'Search', 'meta_organization' ),
		'not_found'           => __( 'Not found', 'meta_organization' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'meta_organization' ),
	);
	$args = array(
		'label'               => __( 'Org Item', 'meta_organization' ),
		'description'         => __( 'Desciption Organization', 'meta_organization' ),
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
?>