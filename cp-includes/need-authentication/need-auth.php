<?php
/*
Plugin Name: Need Authentication
Plugin URI: http://casepress.org
Description: Restrict access for site without authentication.
Version: 1.0
*/

add_action('init','CheckAllow');
function CheckAllow() {

	$current_path = empty( $_SERVER["REQUEST_URI"] ) ? home_url() : $_SERVER["REQUEST_URI"];
	
	if (! is_user_logged_in() && ! is_login() ){
		wp_redirect( wp_login_url($current_path) );
		exit;
	}
}

function is_login() {
    return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
}