<?php

	/**
	 * Fill post title
	 */
	function ctmeta_appoint_responsible() {

		// Get post ID
		$post_id = get_the_ID();

		// Check revision, post title and post type
		if ( !is_admin() && is_single() && get_post_type() == 'cases' && !get_post_meta( $post_id, 'responsible', true ) ) {

			$functions = wp_get_post_terms( $post_id, 'functions' );
			$function = (!is_wp_error( $functions ) && is_numeric( $functions[0]->term_id ) ) ? $functions[0]->term_id : false;

			if ( ctmeta_get_default_responsible( $function ) ) {
				update_post_meta( $post_id, 'responsible', ctmeta_get_default_responsible( $function ) );
				update_post_meta( $post_id, 'cp_posts_persons_responsible', ctmeta_get_default_responsible( $function ) );
				
				$responsible=get_user_by_person(ctmeta_get_default_responsible( $function ));
			//	if ($responsible!=0) update_acl($post_id,$responsible,'0','member');
				wp_redirect( get_permalink( $post_id ) );
				exit;
			}
		}
	}

	add_action( 'template_redirect', 'ctmeta_appoint_responsible' );
?>