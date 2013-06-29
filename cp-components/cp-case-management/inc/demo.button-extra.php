<?php

	/**
	 * Add buttons
	 *
	 * @global mixed $cmmngt Plugin object
	 * @param array $buttons Buttons set
	 * @return array $buttons Modified buttons set
	 */
	function cmmngt_button_extra( $buttons ) {
		// Get plugin object
		global $cmmngt;
		// Prepare actions
		$actions = array(
			array( 'familiar', __( 'Familiar', $cmmngt->textdomain ) ),
			array( 'agreed', __( 'Agreed', $cmmngt->textdomain ) ),
			array( 'delegate', __( 'Delegate', $cmmngt->textdomain ), false )
		);
		// Add dropdown menu
		$buttons[47] = cmmngt_dropdown( 'additional', __( 'Additional', $cmmngt->textdomain ), array( 'plus', 'black' ), $actions );
		// Return modified buttons set
		return $buttons;
	}

	add_filter( 'cmmngt_print_buttons', 'cmmngt_button_extra' );

	/**
	 * Add delegate box after buttons
	 */
	function cmmngt_button_extra_after( $html ) {
		// Get plugin object
		global $cmmngt;
		$html .= '<div id="cmmngt-delegate-select-box" class="well"><strong>' . __( 'Select person that will be set as responsible', $cmmngt->textdomain ) . '</strong>:<br /><span class="cmmngt-loading-persons"></span></div>';
		// Return modified markup
		return $html;
	}

	add_filter( 'cmmngt_print_buttons_after', 'cmmngt_button_extra_after' );

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

	add_action( 'wp_loaded', 'cmmngt_process_button_extra' );

	/**
	 * AJAX handler that returns select tag with list of persons
	 */
	function cmmngt_get_persons_select() {
		// Prepare data
		$persons = cmmngt_get_persons();
		$options = array( );
		// Fill select with options
		foreach ( $persons as $person )
			$options[] = ( $person['name'] ) ? '<option value="' . $person['id'] . '">' . $person['name'] . '</option>' : '';
		// Print select
		die( '<select name="delegate" id="cmmngt-delegate-select">' . implode( '', $options ) . '</select>' );
	}

	add_action( 'wp_ajax_cmmngt_get_persons_select', 'cmmngt_get_persons_select' );
?>