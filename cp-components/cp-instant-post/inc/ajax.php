<?php

	function cip_get_form() {

		global $cip_plugin;

		// Check permissions
		if ( !current_user_can( 'publish_posts' ) )
			die( __( 'You do not have sufficient permissions to do this!', $cip_plugin->textdomin ) );

		// Choose form to return
		if ( $_GET['form_type'] === 'message' )
			cip_message_form();
		elseif ( $_GET['form_type'] === 'incoming' )
			cip_incoming_form();

		// Prevent unwanted output
		die();
	}

	add_action( 'wp_ajax_cip_get_form', 'cip_get_form' );

	function cip_process_form() {

		// Get plugin object
		global $cip_plugin;

		// Check permissions
		if ( !current_user_can( 'publish_posts' ) )
			die( __( 'You do not have sufficient permissions to do this!', $cip_plugin->textdomin ) );

		// Prepare variables
		$data = array( );
		$errors = array( );

		// Verification passed, parse request
		parse_str( $_POST['data'], $data );

		// Message
		if ( isset( $data['action'] ) && $data['action'] === 'message' ) {

			// Validation
			if ( empty( $data['title'] ) )
				$errors[] = __( 'Please specify subject', $cip_plugin->textdomain );
			if ( empty( $data['responsible'] ) )
				$errors[] = __( 'Please choose recipient', $cip_plugin->textdomain );

			// Validation failed, output errors
			if ( count( $errors ) > 0 ) {
				$output = 'errors|||' . implode( '<br />', $errors );
			}

			// Validation passed, output success message with links
			else {

				// Insert post and it's ID
				$_REQUEST['responsible'] = $data['responsible'];
				$_REQUEST['participant'] = $data['participant'];
				$_REQUEST['post_id'] = '100';
				$post_id = wp_insert_post( array(
					'post_type' => 'cases',
					'post_status' => 'publish',
					'post_title' => $data['title'],
					'post_content' => $data['content'],
					'tax_input' => array( 'functions' => array( $data['category'] ) )
					) );
				// Update meta
				foreach ( array( 'initiator', 'responsible', 'participant' ) as $role )
					if ( !empty( $data[$role] ) )
						if ( is_array( $data[$role] ) ) {
							update_post_meta( $post_id, $role, implode( ',', $data[$role] ) );

							foreach ( $data[$role] as $item ) {
								$res = get_user_by_person( $item );
								if ( $res != 0 )
									update_acl( $post_id, $res, '0', 'member' );
							}
						}
						else {
							update_post_meta( $post_id, $role, $data[$role] );
							//acl
							$res = get_user_by_person( $data[$role] );
							if ( $res != 0 )
								update_acl( $post_id, $res, '0', 'member' );
						}

				// Generate output
				$output = 'ok|||' . '<h4>' . __( 'Message successfully published!', $cip_plugin->textdomain ) . '</h4>';
				$output .= '<p>' . __( 'Now, you can do next things', $cip_plugin->textdomain ) . '</p>';
				$output .= '<ul>';
				$output .= '<li><a href="' . get_permalink( $post_id ) . '">' . __( 'View published message', $cip_plugin->textdomain ) . ' &rsaquo;</a></li>';
				$output .= '<li><a href="' . get_edit_post_link( $post_id ) . '">' . __( 'Edit published message', $cip_plugin->textdomain ) . ' &rsaquo;</a></li>';
				$output .= '<li><a href="#" id="cip-add-another-message">' . __( 'Send another message', $cip_plugin->textdomain ) . ' &rsaquo;</a></li>';
				$output .= '<li><a href="#" id="cip-dialog-close-message">' . __( 'Close this dialog', $cip_plugin->textdomain ) . ' &rsaquo;</a></li>';
				$output .= '</ul>';
			}
		}

		// This is new incoming form
		elseif ( isset( $data['action'] ) && $data['action'] == 'incoming' ) {

			// Validation
			if ( empty( $data['title'] ) )
				$errors[] = __( 'Please specify title', $cip_plugin->textdomain );
			if ( empty( $data['category'] ) )
				$errors[] = __( 'Please choose case category', $cip_plugin->textdomain );
			if ( empty( $data['initiator'] ) )
				$errors[] = __( 'Please choose initiator', $cip_plugin->textdomain );

			// Validation failed, output errors
			if ( count( $errors ) > 0 ) {
				$output = 'errors|||' . implode( '<br />', $errors );
			}

			// Validation passed, output success message with links
			else {
				// Insert post and it's ID
				$_REQUEST['responsible'] = $data['responsible'];
				$_REQUEST['participant'] = $data['participant'];
				$_REQUEST['post_id'] = '100';
				$post_id = wp_insert_post( array(
					'post_type' => 'cases',
					'post_status' => 'publish',
					'post_title' => $data['title'],
					'post_content' => $data['content'],
					'tax_input' => array( 'functions' => array( $data['category'] ) )
					) );

				// Update meta

				foreach ( array( 'initiator', 'responsible', 'participant' ) as $role )
					if ( !empty( $data[$role] ) )
						if ( is_array( $data[$role] ) ) {
							update_post_meta( $post_id, $role, implode( ',', $data[$role] ) );

							foreach ( $data[$role] as $item ) {
								$res = get_user_by_person( $item );
								if ( $res != 0 )
									update_acl( $post_id, $res, '0', 'member' );
							}
						}
						else {
							update_post_meta( $post_id, $role, $data[$role] );
							//acl
							$res = get_user_by_person( $data[$role] );
							if ( $res != 0 )
								update_acl( $post_id, $res, '0', 'member' );
						}

				$output = 'ok|||' . '<h4>' . __( 'Incoming case successfully published!', $cip_plugin->textdomain ) . '</h4>';
				$output .= '<p>' . __( 'Now, you can do next things', $cip_plugin->textdomain ) . '</p>';
				$output .= '<ul>';
				$output .= '<li><a href="' . get_permalink( $post_id ) . '">' . __( 'Go to published case', $cip_plugin->textdomain ) . ' &rsaquo;</a></li>';
				$output .= '<li><a href="' . get_edit_post_link( $post_id ) . '">' . __( 'Edit published case', $cip_plugin->textdomain ) . ' &rsaquo;</a></li>';
				$output .= '<li><a href="#" id="cip-add-another-incoming">' . __( 'Add another incoming case', $cip_plugin->textdomain ) . ' &rsaquo;</a></li>';
				$output .= '<li><a href="#" id="cip-dialog-close-incoming">' . __( 'Close this dialog', $cip_plugin->textdomain ) . ' &rsaquo;</a></li>';
				$output .= '</ul>';
			}
		}

		echo $output;

		// Prevent unwanted output
		die();
	}

	add_action( 'wp_ajax_cip_process_form', 'cip_process_form' );
?>