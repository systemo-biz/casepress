<?php

	/**
	 * Remove admin bar and hide admin menu
	 */
	function csposter_iframe_request() {
		if ( isset( $_GET['csposter'] ) ) {
			define( 'IFRAME_REQUEST', true );
			return ' folded';
		}
	}

	add_action( 'admin_init', 'csposter_iframe_request' );

	add_filter( 'admin_body_class', 'csposter_iframe_request' );
?>