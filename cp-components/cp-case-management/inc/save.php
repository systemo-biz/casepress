<?php

	/**
	 * Save editables
	 *
	 * @global mixed $cmmngt Plugin object
	 */
	function cmmngt_save_editable() {
		// Prepare data
		global $cmmngt;
		$post_id = $_REQUEST['post_id'];
		$field = $_REQUEST['name'];
		$val = $_REQUEST['value'];
		$response = array( 'success' => true );
		// Recieved correct value
		if ( is_numeric( $val ) ) {
			// Function (case category)
			if ( $field === 'function' ) {
				$val = ( $val == 0 ) ? 80 : $val;
				// Вот собственно костыль, если терм 123 и пользователь не может его трогать, то пользователь внемлет редирект.
				switch ( true ) {
					case ( ($val == 123) && (!has_term( 'kadrovic', 'persons_post', get_person_by_user( get_current_user_id() ) )) ): $response['result'] = 'redirect';
						break;
					// Ниже то, что было до добавления костыля.
					case ($val != 123): wp_set_object_terms( $post_id, array( ( int ) $val ), 'functions' );
						$response['result'] = cmmngt_term_permalink( $val, 'functions', false );
						break;
					case ( ($val == 123) && (has_term( 'kadrovic', 'persons_post', get_person_by_user( get_current_user_id() ) )) ): wp_set_object_terms( $post_id, array( ( int ) $val ), 'functions' );
						$response['result'] = cmmngt_term_permalink( $val, 'functions', false );
						break;
				}
			}
			// Priority
			elseif ( $field === 'priority' ) {
				$priorities = cmmngt_get_priorities();
				update_post_meta( $post_id, 'prioritet', $val );
				$response['result'] = $priorities[$val];
			}
			// State
			elseif ( $field === 'state' ) {
				if ( $val == '0' )
					wp_set_post_terms( $post_id, null, 'state' );
				else
					wp_set_object_terms( $post_id, array( ( int ) $val ), 'state' );
				$response['result'] = cmmngt_term_title( $val, 'state', false );
			}
			// Result
			elseif ( $field === 'result' ) {
				if ( $val == '0' )
					wp_set_post_terms( $post_id, null, 'results' );
				else
					wp_set_object_terms( $post_id, array( ( int ) $val ), 'results' );
				$response['result'] = cmmngt_term_title( $val, 'results', false );
			}
			// Parent ID
			elseif ( $field === 'parent_id' ) {
				$new_post = array(
					'ID' => $post_id,
					'post_parent' => $val != 0 ? $val : $post_id
				);
				wp_update_post( $new_post );
				$response['result'] = cmmngt_case_link( $post_id );
			}
		}
		// Deadline
		elseif ( $field === 'deadline' ) {
			update_post_meta( $post_id, 'date_deadline', trim( $val ) );
			$response['result'] = cmmngt_pretty_date( $val, false );
		}
		// Date register
		elseif ( $field === 'date_register' ) {
			$post_date = ( $val ) ? date( "Y-m-d H:i:s", strtotime( $val ) ) : current_time( 'mysql' );
			$new_post = array(
				'ID' => $post_id,
				'post_date' => $post_date,
				'post_date_gmt' => get_gmt_from_date( $post_date )
			);
			wp_update_post( $new_post );
			$response['result'] = cmmngt_pretty_date( $post_date, false );
		}
		// Date start
		elseif ( $field === 'date_start' ) {
			update_post_meta( $post_id, 'date_start', trim( $val ) );
			$response['result'] = cmmngt_pretty_date( $val, false );
		}
		// Date end
		elseif ( $field === 'date_end' ) {
			update_post_meta( $post_id, 'date_end', trim( $val ) );
			$response['result'] = cmmngt_pretty_date( $val, false );
		}
		// Recieved incorrect value
		else
			$response['result'] = __( 'Not selected', $cmmngt->textdomain );
		// Print result
		die( json_encode( $response ) );
	}

	add_action( 'wp_ajax_cmmngt_save_editable', 'cmmngt_save_editable' );

	/**
	 * Save custom fields from backend
	 */
	function cmmngt_backend_save_meta( $post_id ) {

		//check post type 'cases'
		if ( !is_singular('cases') )
			return $post_id;
		// authentication checks
		// make sure data came from our meta box
		if ( !wp_verify_nonce( $_POST['cmmngt_nonce'], cmmngt_get_nonce() ) )
			return $post_id;

		// check user permissions
		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		// authentication passed, save data
		(!empty( $_POST['functions'] ) && $_POST['functions'] != '-1' ) ? wp_set_post_terms( $post_id, array( intval( $_POST['functions'] ) ), 'functions' ) : wp_set_post_terms( $post_id, null, 'functions' );
		(!empty( $_POST['prioritet'] ) && $_POST['prioritet'] != '-1') ? update_post_meta( $post_id, 'prioritet', $_POST['prioritet'] ) : update_post_meta( $post_id, 'prioritet', '3' );
		!empty( $_POST['date_deadline'] ) ? update_post_meta( $post_id, 'date_deadline', $_POST['date_deadline'] ) : delete_post_meta( $post_id, 'date_deadline' );
		(!empty( $_POST['state'] ) && $_POST['state'] != '-1') ? wp_set_post_terms( $post_id, array( intval( $_POST['state'] ) ), 'state' ) : wp_set_post_terms( $post_id, null, 'state' );
		(!empty( $_POST['results'] ) && $_POST['results'] != '-1') ? wp_set_post_terms( $post_id, array( intval( $_POST['results'] ) ), 'results' ) : wp_set_post_terms( $post_id, null, 'results' );

		return $post_id;
	}

	add_action( 'save_post', 'cmmngt_backend_save_meta' );
?>