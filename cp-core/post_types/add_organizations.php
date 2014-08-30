<?php

add_action('init', 'register_organizations_posttype');	
function register_organizations_posttype() {
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
}



add_filter('post_type_link', 'org_post_type_link', 9, 3);
function org_post_type_link( $link, $post = 0 ){
    if ( $post->post_type == 'organizations' ){
        return home_url( 'organizations/' . $post->ID );
    } else {
        return $link;
    }
}
 
add_action( 'init', 'org_rewrites_init' );
function org_rewrites_init(){
    add_rewrite_rule(
        'organizations/([0-9]+)?$',
        'index.php?post_type=organizations&p=$matches[1]',
        'top' );
}