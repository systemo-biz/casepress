<?php

	/**
	 * Fill post title
	 */
	function ctmeta_fill_title() {

		// Get post ID
		$post_id = get_the_ID();

		// Check revision, post title and post type
		if ( !is_admin() && is_single() && get_post_type() == 'cases' && get_the_title( $post_id ) == '' ) {

			$functions = wp_get_post_terms( $post_id, 'functions' );
			$function = (!is_wp_error( $functions ) && is_numeric( $functions[0]->term_id ) ) ? $functions[0]->term_id : false;
			$organizations = wp_get_post_terms( $post_id, 'organizations' );
			$organization = (!is_wp_error( $organizations ) && is_numeric( $organizations[0]->term_id ) ) ? $organizations[0]->term_id : false;

			$short_tags = array(
				'tags' => array(
					'%post_id%',
					'%function%',
				),
				'values' => array(
					$post_id,
					( $function ) ? $functions[0]->name : '',
				)
			);

			$new_post_args = array(
				'ID' => $post_id,
				'post_title' => do_shortcode( str_replace( $short_tags['tags'], $short_tags['values'], ctmeta_get_title_template( $function ) ) ),
				'post_content' => do_shortcode( str_replace( $short_tags['tags'], $short_tags['values'], ctmeta_get_content_template( $function ) ) )
			);
			wp_update_post( $new_post_args );

			if ( !empty( $new_post_args['post_title'] ) ) {
				wp_redirect( get_permalink( $post_id ) );
				exit;
			}
		}
	}

	add_action( 'template_redirect', 'ctmeta_fill_title' );
?>