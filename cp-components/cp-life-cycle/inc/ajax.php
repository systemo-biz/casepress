<?php

	/**
	 * Handle for AJAX requests to get S2 options
	 */
	function lfc_get_s2() {
		// Prepare data
		$data = array( );
		$field = trim( $_POST['field'] );
		// Query terms
		$terms = get_terms( $field );
		// Terms are found, fill the data array
		if ( count( $terms ) && !is_wp_error( $terms ) )
			foreach ( $terms as $term )
				$data[] = array(
					'id' => $term->term_id,
					'text' => $term->name
				);
		// Terms not found or presented invalid taxonomy
		else
			$data = array(
				array(
					'id' => 'error',
					'text' => 'error'
				)
			);
		// Print json-encoded data
		die( json_encode( $data ) );
	}

	add_action( 'wp_ajax_lfc_get_s2', 'lfc_get_s2' );

	/**
	 * AJAX handler to setting up default life cycle
	 */
	function lfc_set_default() {
		global $cplfc;
		// Check post ID
		if ( !is_numeric( $_POST['post_id'] ) )
			die( __( 'Error', $cplfc->textdomain ) );
		// Save default life cycle
		update_option( 'default_life_cycle_id', $_POST['post_id'] );
		// Return link text
		die( __( 'This life cycle is now default', $cplfc->textdomain ) );
	}

	add_action( 'wp_ajax_lfc_set_default', 'lfc_set_default' );
?>