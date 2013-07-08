<?php

class Private_Site {
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init() {
		if ( ! is_user_logged_in() && ! $this->is_login() ) {
			wp_redirect( admin_url() );
			exit;
		}
	}

	public function is_login() {
    	return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
    }
}

$private_site = new Private_Site;

?>