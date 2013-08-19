<?php

	/**
	 * Save meta
	 *
	 * @param int $post_id Saving post ID
	 */
	function lfc_save_meta( $post_id ) {
		// Prepare data
		$post = get_post( $post_id );
		// Check post type
		if ( $post->post_type !== 'life_cycle' )
			return $post_id;
		delete_post_meta($post_id, 'cp_posts_life_cycle_case_category');
		// Save fields for functions
		if ($_POST['case_category'] != '') {
			foreach (explode( ',', trim( $_POST['case_category'] ) ) as $term ){
				add_post_meta( $post_id, 'cp_posts_life_cycle_case_category', $term);
			}
		}
		
		
		// Save base life cicle
		//error_log('цикл по умолчанию: '.$_POST['cp_base_lc']);
		if ($_POST['cp_base_lc'] != '') {
			update_option( 'default_life_cycle_id', $_POST['cp_base_lc'] );
		}
		
		// Save fields for state and results
		foreach ( array('state', 'results' ) as $field ) {
			// Reset terms
			$terms = array( );
			// Field is set
			if ( isset( $_POST[$field] ) && $_POST[$field] !== '' ) {
				foreach ( ( array ) explode( ',', trim( $_POST[$field] ) ) as $term ) {
					// Value is not numeric, need to register
					if ( !is_numeric( $term ) ) {
						// Register new term
						$new_term = wp_insert_term( $term, $field );
						// Save new term ID
						$term = $new_term['term_id'];
					}
					$terms[] = ( string ) $term;
				}
				// Save meta
				update_post_meta( $post_id, 'cp_posts_life_cycle_' . $field, serialize( $terms ) );
			}
			// Functions is not set, delete meta
			else
				delete_post_meta( $post_id, 'cp_posts_life_cycle_' . $field );
		}
		// Return post ID
		return $post_id;
	}

	add_action( 'save_post', 'lfc_save_meta' );
?>