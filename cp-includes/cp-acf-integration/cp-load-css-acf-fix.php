<?php

add_action( 'wp_enqueue_scripts', 'cp_load_css_acf_fix_ss' );
add_action( 'admin_enqueue_scripts', 'cp_load_css_acf_fix_ss' );
function cp_load_css_acf_fix_ss(){
	$path_to_plugin = trailingslashit(plugin_dir_url(__FILE__) );
    
	wp_enqueue_style( 'acf_fix', $path_to_plugin.'assets/css/acf_fix.css', false, false, 'all' );
}


// deregister admin style http://www.advancedcustomfields.com/resources/tutorials/creating-a-front-end-form/
add_action( 'wp_print_styles', 'my_deregister_styles', 100 );
function my_deregister_styles() {
	wp_deregister_style( 'wp-admin' );
}

?>