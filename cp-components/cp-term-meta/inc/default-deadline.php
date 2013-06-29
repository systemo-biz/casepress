<?php

	/**
	 * Fill post title
	 */
	function ctmeta_set_deadline() {

		// Get post ID
		$post_id = get_the_ID();

		// Check revision, post title and post type
		if ( !is_admin() && is_single() && get_post_type() == 'cases' && !get_post_meta( $post_id, 'date_deadline', true ) ) {

			$functions = wp_get_post_terms( $post_id, 'functions' );
			$function = (!is_wp_error( $functions ) && is_numeric( $functions[0]->term_id ) ) ? $functions[0]->term_id : false;

			$priority = get_post_meta( $post_id, 'prioritet', true );
			$deadline = ctmeta_get_default_deadline( $function, $priority );

			if ( $deadline ) {
				update_post_meta( $post_id, 'date_deadline', $deadline );
				wp_redirect( get_permalink( $post_id ) );
				exit;
			}
		}
	}

	add_action( 'template_redirect', 'ctmeta_set_deadline' );
?>