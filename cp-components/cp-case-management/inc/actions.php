<?php

	/**
	 * Process actions form
	 */
	function cmmngt_process() {
		// Get plugin object
		global $cmmngt;
		$options = $cmmngt->get_option();
		// Check that request is sent
		if ( !isset( $_POST['cmmngt_action'] ) || $_POST['cmmngt_action'] == '' )
			return;
		// Prepare post ID
		$post_id = ( isset( $_POST['post_id'] ) ) ? ( int ) intval( $_POST['post_id'] )
				: false;
		// Prepare actions
		$action = ( string ) trim( $_POST['cmmngt_action'] );
		$subaction = ( string ) trim( $_POST['cmmngt_sub_action'] );
		// Prepare current user data (for comments)
		$ip = $_SERVER['REMOTE_ADDR'];
		$user = wp_get_current_user();
		// Prepare permalink
		$permalink = get_permalink( $post_id );
		// Create Members Class instance
		$members = new CasePress_Members( $post_id );
		### accept ###
		if ( $action === 'accept' ) {
			// Set post term state, "Выполнение"
			wp_set_post_terms( $post_id, array( ( int ) $options['execution'] ), 'state' );
			// Remove results terms
			wp_set_post_terms( $post_id, null, 'results' );
			// Update date_start
			update_post_meta( $post_id, 'date_start', cmmngt_get_date() );
			// Remove date_end
			delete_post_meta( $post_id, 'date_end' );
			// Redirect
			wp_redirect( get_permalink( $post_id ) );
			exit;
		}
		### hold ###
		elseif ( $action === 'hold' ) {
			// Set post term state, "Регистрация"
			wp_set_post_terms( $post_id, array( ( int ) $options['registration'] ), 'state' );
			// Set post term results, "Отложено"
			wp_set_post_terms( $post_id, array( ( int ) $options['delayed'] ), 'results' );
			// Redirect
			wp_redirect( get_permalink( $post_id ) );
			exit;
		}
		### close ###
		elseif ( $action === 'close' ) {
			// Prepare result
			$result = ( is_numeric( $subaction ) ) ? ( int ) intval( $subaction ) : 0;
			// Set post term state, "Архив"
			wp_set_post_terms( $post_id, array( ( int ) $options['archive'] ), 'state' );
			// Set post term results
			wp_set_post_terms( $post_id, array( $result ), 'results' );
			// Update date_start
			update_post_meta( $post_id, 'date_end', cmmngt_get_date() );
			// Redirect
			wp_redirect( get_permalink( $post_id ) );
			exit;
		}
		### edit ###
		elseif ( $action === 'edit' ) {
			// Redirect
			wp_redirect( get_edit_post_link( $post_id, array( 'display' => '&' ) ) );
			exit;
		}
		### delegate ###
		elseif ( $action === 'delegate' ) {
			// Prepare current person
			$current = cmmngt_get_person_by_email( $user->data->user_email );
			// Prepare participants
			$participants = $members->get_members( 'person', 'to' );
			// Add current person to list of participants
			if ( !in_array( $current, $participants ) ) {
				// Put new person in array
				array_unshift( $participants, $current );
				// Save participants
				$members->update_subject( array(
					'subject' => (array) $participants,
					'subject_type' => 'person',
					'role' => 'to'
				) );
			}
			// Update responsible
			if ( is_numeric( $subaction ) ) {
				// Save responsible
				$members->update_subject( array(
					'subject' => array( $subaction ),
					'subject_type' => 'person',
					'role' => 'responsible'
				) );
			}
			// Redirect
			wp_redirect( $permalink );
			exit;
		}
		### familiar ###
		elseif ( $action === 'familiar' ) {
			// Insert comment
			$comment_id = wp_insert_comment( array(
				'comment_post_ID' => $post_id,
				'comment_author' => $user->data->display_name,
				'comment_author_email' => $user->data->user_email,
				'comment_author_url' => $user->data->user_url,
				'comment_content' => __( 'Familiar', $cmmngt->textdomain ),
				'user_id' => $user->ID,
				'comment_author_IP' => $ip,
				'comment_approved' => 1
				) );
			// Setup comment action type
			update_comment_meta( $comment_id, 'action_type', 'familiar' );
			// Redirect
			wp_redirect( $permalink );
			exit;
		}
		### agreed ###
		elseif ( $action === 'agreed' ) {
			// Insert comment
			$comment_id = wp_insert_comment( array(
				'comment_post_ID' => $post_id,
				'comment_author' => $user->data->display_name,
				'comment_author_email' => $user->data->user_email,
				'comment_author_url' => $user->data->user_url,
				'comment_content' => __( 'Agreed', $cmmngt->textdomain ),
				'user_id' => $user->ID,
				'comment_author_IP' => $ip,
				'comment_approved' => 1
				) );
			// Setup comment action type
			update_comment_meta( $comment_id, 'action_type', 'agreed' );
			// Redirect
			wp_redirect( $permalink );
			exit;
		}

		### duplicate ###
		elseif ( $action === 'additional' && $subaction === 'duplicate' ) {
			// Get current post
			$current_post = get_post( $post_id, ARRAY_A );
			// Get current post meta
			$current_post_meta = get_post_custom( $post_id );
			// Get current post taxonomies
			$current_post_taxonomies = get_object_taxonomies( 'cases' );
			$current_post_terms = array( );
			foreach ( $current_post_taxonomies as $current_post_taxonomy )
				$current_post_terms[$current_post_taxonomy] = wp_get_post_terms( $post_id, $current_post_taxonomy, array( 'fields' => 'names' ) );
			// Unset post_id to prevent update of existing post
			unset( $current_post['ID'] );
			// Insert new post and get its ID
			$new_post_id = wp_insert_post( $current_post );
			// New post inserted and we have it's ID
			if ( is_numeric( $new_post_id ) ) {
				// If current post has meta, insert these meta into new post
				if ( count( $current_post_meta ) > 0 )
					foreach ( $current_post_meta as $current_post_meta_key => $current_post_meta_value )
						update_post_meta( $new_post_id, $current_post_meta_key, $current_post_meta_value[0] );
				// Set taxonomies
				foreach ( $current_post_terms as $current_post_tax => $current_post_terms ) {
					if ( $current_post_tax == 'functions' ) {
						$functions_term = get_term_by( 'name', $current_post_terms[0], 'functions', 'ARRAY_A' );
						wp_set_post_terms( $new_post_id, $functions_term['term_id'], 'functions' );
					}
					else
						wp_set_post_terms( $new_post_id, $current_post_terms, $current_post_tax );
				}
				// Redirect
				wp_redirect( get_edit_post_link( $new_post_id, array( 'display' => '&' ) ) );
				exit;
			}
		}
		### delete ###
		elseif ( $action === 'additional' && $subaction === 'delete' ) {
			// Delete post
			wp_trash_post( $post_id );
			// Redirect
			wp_redirect( home_url() );
			exit;
		}
	}

	add_action( 'wp_loaded', 'cmmngt_process' );
?>