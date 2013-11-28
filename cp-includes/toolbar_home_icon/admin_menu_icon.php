<?php
/*
Plugin Name: admin_menu_icon
Description: Меняет иконку админ панели.
Version: 1.1
*/


add_action( 'admin_print_styles', 'restyle' );   
add_action( 'wp_print_styles',  'restyle' );
function restyle(){
    wp_register_style( 'admin-menu-icon-style', plugins_url('css/custom-style.css', __FILE__));
    wp_enqueue_style( 'admin-menu-icon-style' );
}

add_action( 'admin_bar_menu', 'remove_wp_admin_bar_wp_menu', 0 );	
function remove_wp_admin_bar_wp_menu() {
	remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );
}

add_action('admin_bar_menu', 'wp_admin_bar_wp_menu_home',0);  
function wp_admin_bar_wp_menu_home( $wp_admin_bar ) {
    
		global $wp_admin_bar;

		$wp_admin_bar->add_menu( array(
			'id'    => 'wp-logo',
			'title' => '<span class="ab-icon"></span>',
			'href'  => get_site_url(),
			'meta'  => array(
				'title' => 'На главную',
			),
		) );
}