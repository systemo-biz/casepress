<?php

	// AJAX hooks
	add_action( 'wp_ajax_cmp_save_field', 'cmp_save_field' ); // Update participants by AJAX request
	add_action( 'wp_ajax_cmp_get_s2', 'cmp_get_s2' ); // Get select2 by AJAX request

	/**
	 * Update participants by AJAX request
	 */
	function cmp_save_field() {

		//echo( '<pre>' . print_r( $_REQUEST, true ) . '</pre><br />' );
		// Get plugin object
		global $cmp_plugin;
		// Prepare vars
		$output = array( );
		$id = ( is_numeric( $_POST['post_id'] ) ) ? intVal( $_POST['post_id'] ) : false;
		$data = ( isset( $_POST['data'] ) ) ? $_POST['data'] : false;
		$subjects = array(
			'persons' => array( ),
			'terms' => array( )
		);
		$role = htmlspecialchars( trim( $_POST['role'] ) );
		$mode = ( isset( $_POST['mode'] ) ) ? $_POST['mode'] : 'chooser';
		$last = false;
		// Create Members Class instance
		$members = new CasePress_Members( $id );
		// Check results
		if ( $id && $data ) {
			// Remove old results for non-multiple fields
			if ( $_POST['multiple'] !== true ) {
				// Remove subject
				$members->delete_subject( array(
					'subject_type' => 'person',
					'role' => $role
				) );
				$members->delete_subject( array(
					'subject_type' => 'term',
					'role' => $role
				) );
			}
			// Loop through results
			foreach ( $data as $result ) {
				// Is person
				if ( $result['type'] === 'person' )
					$value = $subjects['persons'][] = $result['id'];
				// Is term
				elseif ( $result['type'] === 'term' )
					$value = $subjects['terms'][] = $result['tax'] . ':' . $result['id'];
				// Is S2 mode
				elseif ( $mode === 's2' ) {
					if ( is_numeric( $result ) ) $value = $subjects['persons'][] = $result;
					else $value = $subjects['terms'][] = $result;
				}
				// Save last field type for non-multiple fields
				if ( !$_POST['multiple'] )
					$last = $result['type'];
				// Put link to output
				$output[] = cmp_get_link( $value );
			}
			// Save data (persons)
			if ( count( $subjects['persons'] ) ) {
				// Remove person subject for non-multiple fields
				if ( !$_POST['multiple'] && $last !== 'person' )
					$members->delete_subject( array(
						'subject_type' => 'person',
						'role' => $role
					) );
				// Update subject
				$members->update_subject( array(
					'subject' => $subjects['persons'],
					'subject_type' => 'person',
					'role' => $role
				) );
			}
			// Save data (terms)
			if ( count( $subjects['terms'] ) ) {
				// Remove person subject for non-multiple fields
				if ( !$_POST['multiple'] && $last !== 'term' )
					$members->delete_subject( array(
						'subject_type' => 'person',
						'role' => $role
					) );
				// Update subject
				$members->update_subject( array(
					'subject' => $subjects['terms'],
					'subject_type' => 'term',
					'role' => $role
				) );
			}
		}
		// Results not selected
		else {
			// Remove subject
			$members->delete_subject( array(
				'subject_type' => 'person',
				'role' => $role
			) );
			$members->delete_subject( array(
				'subject_type' => 'term',
				'role' => $role
			) );
			$output[] = '<strong>' . __( 'Not selected', $cmp_plugin->textdomain ) . '</strong>';
		}
		// Print output
		die( implode( ', ', $output ) );
	}

	/**
	 * Get select2 by AJAX request
	 */
	function cmp_get_s2() {
		die( json_encode( ( array ) cmp_get_persons() ) );
	}

?>