<?php

	/**
	 * Process extra buttons actions
	 */
	function cmmngt_process_button_extra() {
		// Get plugin object
		global $cmmngt;
		// Check that request is sent
		if ( !isset( $_POST['cmmngt_action'] ) || $_POST['cmmngt_action'] == '' )
			return;
		// Prepare post ID
		$post_id = ( isset( $_POST['post_id'] ) ) ? ( int ) intval( $_POST['post_id'] ) : false;
		// Prepare actions
		$action = ( string ) trim( $_POST['cmmngt_action'] );
		$subaction = ( string ) trim( $_POST['cmmngt_sub_action'] );
		// Prepare current user data (for comments)
		$ip = $_SERVER['REMOTE_ADDR'];
		$user = wp_get_current_user();
		// Prepare permalink
		$permalink = get_permalink( $post_id );
		// Check that is not new post page and user logged in
		if ( !is_numeric( $post_id ) || !is_numeric( $user->ID ) )
			return;
		### additional: familiar, agreed ###
		if ( $action === 'additional' ) {
			## familiar ##
			if ( $subaction === 'familiar' ) {
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
			## agreed ##
			elseif ( $subaction === 'agreed' ) {
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
		}
		### additional: delegate ###
		elseif ( $action === 'delegate' ) {
			// Prepare current person
			$current = cmmngt_get_person_by_email( $user->data->user_email );
			// Prepare participants
			$participants = explode( ',', get_post_meta( $post_id, 'participant', true ) );
			// Add current person to list of participants
			if ( !in_array( $current, $participants ) ) {
				// Put new person in array
				array_unshift( $participants, $current );
				// Update post meta
				update_post_meta( $post_id, 'participant', implode( ',', $participants ) );
			}
			// Update responsible
			if ( is_numeric( $subaction ) )
				update_post_meta( $post_id, 'responsible', $subaction );
			// Redirect
			wp_redirect( $permalink );
			exit;
		}
	}

//	add_action( 'wp_loaded', 'cmmngt_process_button_extra' );
?>