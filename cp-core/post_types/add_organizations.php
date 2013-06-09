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

/*
add_action('init', 'organizations_rewrite');
function organizations_rewrite() {
	global $wp_rewrite;
	$wp_rewrite->add_rewrite_tag('%organizations_id%', '([^/]+)', 'post_type=organizations&p=');
	$wp_rewrite->add_permastruct('organizations', '/organizations/%organizations_id%', false);
	$wp_rewrite->flush_rules();
}

add_filter('post_type_link', 'organizations_permalink', 1, 3);
function organizations_permalink($post_link, $leavename, $sample) {
	global $wp_rewrite;
	$post = &get_post($id);
	if (is_wp_error($post)) return $post;
	$newlink = $wp_rewrite->get_extra_permastruct('organizations');
	$newlink = str_replace('%organizations_id%', $post->ID, $newlink);
	$newlink = home_url(user_trailingslashit($newlink));
	if(get_post_type() == 'organizations'){return $newlink;} else {return $post_link;}
}
*/

?>
