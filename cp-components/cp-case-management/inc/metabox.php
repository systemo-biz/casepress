<?php

	/**
	 * Backend metabox
	 */
	function cmmngt_backend() {
		// Prepare variables
		global $cmmngt, $post, $pagenow;
		$options = $cmmngt->get_option();
		$is_new = is_admin() && $pagenow === 'post-new.php';
		$post_id = ( $is_new ) ? false : get_the_ID();
		$post_id = ( is_admin() && $pagenow === 'post.php' ) ? $_GET['post'] : $post_id;
		$post = ( is_numeric( $post_id ) ) ? get_post( $post_id ) : null;
		$is_draft = $is_new || ( isset( $post ) && $post->post_status === 'draft' );
		$buttons = array( );
		$hidden = '';
		$additional = array( );
		$life_cycle = ( $is_new ) ? lfc_get_life_cycle( '-2', 'term' ) : lfc_get_life_cycle( $post_id, 'post' );
		$parent_id = ( is_numeric( $post->post_parent ) && $post->post_parent != $post_id )
				? $post->post_parent : 0;
		$parent_id = (isset( $_GET['csposter_parent_id'] )) ? $_GET['csposter_parent_id']
				: $parent_id;
		// Functions
		$selected_functions = get_the_terms( $post->ID, 'functions' );
		$selected_function = !empty( $selected_functions['0']->term_id ) ? $selected_functions['0']->term_id
				: 80;
		$functions = wp_dropdown_categories( 'show_option_none=' . __( 'Not selected', $cmmngt->textdomain ) . '&hide_empty=0&name=functions&id=cmmngt-functions&class=cmmngt-init-select2&selected=' . intval( $selected_function ) . '&hierarchical=1&taxonomy=functions&echo=0' );
		$functions = str_replace( '&nbsp;', '', $functions );
		// Priority
		$priorities = cmmngt_get_priorities();
		$current_priority = ( get_post_meta( $post_id, 'prioritet', 'true' ) ) ? get_post_meta( $post_id, 'prioritet', 'true' )
				: 3;
		$priority_marker = ( get_post_meta( $post_id, 'prioritet', 'true' ) ) ? get_post_meta( $post_id, 'prioritet', 'true' )
				: '-1';
		// Date register
		$date_register = $post->post_date;
		// Date deadline
		$date_deadline = ( get_post_meta( $post_id, 'date_deadline', true ) ) ? get_post_meta( $post_id, 'date_deadline', true )
				: null;
		// Date start
		$date_start = ( get_post_meta( $post_id, 'date_start', true ) ) ? get_post_meta( $post_id, 'date_start', true )
				: null;
		// Date end
		$date_end = ( get_post_meta( $post_id, 'date_end', true ) ) ? get_post_meta( $post_id, 'date_end', true )
				: null;
		// State
		$selected_states = get_the_terms( $post_id, 'state' );
		$selected_state = (!empty( $selected_states['0']->term_id ) ) ? $selected_states['0']->term_id
				: false;
		$inc_states = (count( $life_cycle['states'] )) ? implode( ',', $life_cycle['states'] )
				: '-2';
		$states = wp_dropdown_categories( 'show_option_none=' . __( 'Not selected', $cmmngt->textdomain ) . '&hide_empty=0&name=state&id=cmmngt-state&class=cmmngt-init-select2&selected=' . intval( $selected_state ) . '&hierarchical=1&taxonomy=state&echo=0&include=' . $inc_states );
		$states = str_replace( '&nbsp;', '', $states );
		// Result
		$selected_results = get_the_terms( $post_id, 'results' );
		$selected_result = (!empty( $selected_results['0']->term_id ) ) ? $selected_results['0']->term_id
				: false;
		$inc_results = (count( $life_cycle['results'] )) ? implode( ',', $life_cycle['results'] )
				: '-2';
		$results = wp_dropdown_categories( 'show_option_none=' . __( 'Not selected', $cmmngt->textdomain ) . '&hide_empty=0&name=results&id=cmmngt-results&class=cmmngt-init-select2&selected=' . intval( $selected_result ) . '&hierarchical=1&taxonomy=results&echo=0&include=' . $inc_results );
		$results = str_replace( '&nbsp;', '', $results );

		// Publish/Update actions
		// Publish post
		if ( $is_new || $is_draft )
			$buttons[5] = cmmngt_button( 'save', __( 'Send', $cmmngt->textdomain ), array( 'check', 'white' ) );
		// Update post
		else
			$buttons[5] = cmmngt_button( 'save', __( 'Update', $cmmngt->textdomain ), array( 'check', 'white' ) );
		// Draft button
		if ( $is_draft )
			$buttons[10] = cmmngt_button( 'draft', __( 'Save draft', $cmmngt->textdomain ), array( 'pencil', 'black' ) );
		// This is not draft
		if ( !$is_draft ) {
			// Post is not accepted and not in archive
			if ( !has_term( $options['execution'], 'state', $post_id ) && !has_term( $options['completion'], 'state', $post_id ) && !has_term( $options['archive'], 'state', $post_id ) )
				$buttons[15] = cmmngt_button( 'accept', __( 'Accept', $cmmngt->textdomain ), array( 'ok', 'white' ) );
			// Post is in archive
			elseif ( has_term( $options['archive'], 'state', $post_id ) )
				$buttons[15] = cmmngt_button( 'accept', __( 'Restore', $cmmngt->textdomain ), array( 'ok', 'white' ) );
			// Post is not holded and not closed
			/* if ( !has_term( 48, 'results', $post_id ) && !has_term( 50, 'state', $post_id ) )
			  $buttons[20] = cmmngt_button( 'hold', __( 'Hold over', $cmmngt->textdomain ), array( 'time', 'white' ) ); */
		}

		// Close button, post is not in archive
		// Get terms
		$results_terms = get_terms( 'results', array(
			'hide_empty' => 0,
			'include' => ( array ) $life_cycle['results']
			) );
		$results_array = array( );
		// If terms recieved, create options array
		if ( count( $results_terms ) )
			foreach ( $results_terms as $result_term )
				$results_array[] = array( $result_term->term_id, $result_term->name );
		if ( !$is_draft && !has_term( $options['archive'], 'state', $post_id ) )
			$buttons[25] = cmmngt_dropdown( 'close', __( 'Close', $cmmngt->textdomain ), array( 'remove', 'black' ), $results_array );

		// Delegate / Familiar / Agreed
		$buttons[30] = cmmngt_button( 'delegate', __( 'Delegate', $cmmngt->textdomain ), array( 'th-large', 'black' ), '', false );
		$buttons[35] = cmmngt_button( 'familiar', __( 'Familiar', $cmmngt->textdomain ), array( 'info-sign', 'black' ) );
		$buttons[40] = cmmngt_button( 'agreed', __( 'Agreed', $cmmngt->textdomain ), array( 'ok-sign', 'black' ) );

		// Prepare actions
		if ( !$is_new && !$is_draft )
			$additional[] = array( 'duplicate', __( 'Duplicate', $cmmngt->textdomain ) );
		if ( !$is_new && current_user_can( 'delete_post', $post_id ) )
			$additional[] = array( 'delete', __( 'Delete', $cmmngt->textdomain ) );
		// Add dropdown menu
		$buttons[70] = cmmngt_dropdown( 'additional', __( 'Additional', $cmmngt->textdomain ), array( 'plus', 'black' ), $additional );

		// Add comments and ping status
		$hidden .= cmmngt_hidden( 'comment_status', 'open' );
		$hidden .= cmmngt_hidden( 'ping_status', 'open' );
		// Hidden post_status field
		if ( $is_draft )
			$hidden .= cmmngt_hidden( 'post_status', 'draft' );
		// Add post_id to request
		if ( is_numeric( $post_id ) )
			$hidden .= cmmngt_hidden( 'post_id', $post_id );
		// Add cmmngt action field
		$hidden .= cmmngt_hidden( 'cmmngt_action', '' );
		$hidden .= cmmngt_hidden( 'cmmngt_sub_action', '' );
		// Render template
		include_once 'views/backend.php';
	}

	/**
	 * Frontend metabox
	 */
	function cmmngt_frontend() {

		lfc_get_life_cycle( 26711, 'post' );

		// Prepare variables
		global $cmmngt, $post, $pagenow;
		$options = $cmmngt->get_option();
		$is_new = is_admin() && $pagenow === 'post-new.php';
		$post_id = ( $is_new ) ? false : get_the_ID();
		$post_id = ( is_admin() && $pagenow === 'post.php' ) ? $_GET['post'] : $post_id;
		$post = ( is_numeric( $post_id ) ) ? get_post( $post_id ) : null;
		$is_draft = $is_new || ( isset( $post ) && $post->post_status === 'draft' );
		$buttons = array( );
		$hidden = '';
		$life_cycle = ( $is_new ) ? lfc_get_life_cycle( '-2', 'term' ) : lfc_get_life_cycle( $post_id, 'post' );
		$additional = array( );
		$args_result_terms = array(
			'hide_empty' => false
		);
		$result_terms = get_terms( 'results', $args_result_terms );
		$parent_id = ( is_numeric( $post->post_parent ) && $post->post_parent != $post_id )
				? $post->post_parent : 0;
		$parent_id = (isset( $_GET['csposter_parent_id'] )) ? $_GET['csposter_parent_id']
				: $parent_id;
		// Show meta box only on cases pages
		if ( !is_single() || get_post_type() !== 'cases' )
			return;
		// Functions
		$selected_functions = array_shift( get_the_terms( $post_id, 'functions' ) );
		$selected_function = !empty( $selected_functions ) ? $selected_functions->term_id
				: 80;
		// Priority
		$priorities = cmmngt_get_priorities();
		$current_priority = (!get_post_meta( $post_id, 'prioritet', 'true' ) || get_post_meta( $post_id, 'prioritet', 'true' ) == '-1' )
				? '-1' : get_post_meta( $post_id, 'prioritet', true );
		$current_priority_display = $priorities[$current_priority];
		// States
		$selected_states = array_shift( get_the_terms( $post_id, 'state' ) );
		$selected_state = (!empty( $selected_states ) ) ? $selected_states->term_id : false;
		// Result
		$selected_results = array_shift( get_the_terms( $post_id, 'results' ) );
		$selected_result = (!empty( $selected_results ) ) ? $selected_results->term_id
				: false;
		// Date register
		$date_register = $post->post_date;
		// Date deadline
		$date_deadline = ( get_post_meta( $post_id, 'date_deadline', true ) ) ? get_post_meta( $post_id, 'date_deadline', true )
				: null;
		// Date start
		$date_start = ( get_post_meta( $post_id, 'date_start', true ) ) ? get_post_meta( $post_id, 'date_start', true )
				: null;
		// Date end
		$date_end = ( get_post_meta( $post_id, 'date_end', true ) ) ? get_post_meta( $post_id, 'date_end', true )
				: null;
		//	print_r($result_terms);
		// This is not draft
		if ( !$is_draft ) {
			// Post is not accepted and not in archive
			if ( !has_term( $options['execution'], 'state', $post_id ) && !has_term( $options['completion'], 'state', $post_id ) && !has_term( $options['archive'], 'state', $post_id ) )
				$buttons[15] = cmmngt_button( 'accept', __( 'Accept', $cmmngt->textdomain ), array( 'ok', 'white' ) );
			// Post is in archive
			elseif ( has_term( $options['archive'], 'state', $post_id ) )
				$buttons[15] = cmmngt_button( 'accept', __( 'Restore', $cmmngt->textdomain ), array( 'ok', 'white' ) );
			// Post is not holded and not closed
			/* if ( !has_term( 48, 'results', $post_id ) && !has_term( 50, 'state', $post_id ) )
			  $buttons[20] = cmmngt_button( 'hold', __( 'Hold over', $cmmngt->textdomain ), array( 'time', 'white' ) ); */
		}
		// Edit button in frontend
		$buttons[25] = cmmngt_button( 'edit', __( 'Edit', $cmmngt->textdomain ), array( 'edit', 'black' ) );

		// Close button, post is not in archive
		// Get terms
		$results_terms = get_terms( 'results', array(
			'hide_empty' => 0,
			'include' => ( array ) $life_cycle['results']
			) );
		$results_array = array( );
		// If terms recieved, create options array
		if ( count( $results_terms ) )
			foreach ( $results_terms as $result_term )
				$results_array[] = array( $result_term->term_id, $result_term->name );
		if ( !$is_draft && !has_term( $options['archive'], 'state', $post_id ) )
			$buttons[30] = cmmngt_dropdown( 'close', __( 'Close', $cmmngt->textdomain ), array( 'remove', 'black' ), $results_array );

		// Delegate / Familiar / Agreed
		$buttons[35] = cmmngt_button( 'delegate', __( 'Delegate', $cmmngt->textdomain ), array( 'th-large', 'black' ), '', false );
		$buttons[40] = cmmngt_button( 'familiar', __( 'Familiar', $cmmngt->textdomain ), array( 'info-sign', 'black' ) );
		$buttons[45] = cmmngt_button( 'agreed', __( 'Agreed', $cmmngt->textdomain ), array( 'ok-sign', 'black' ) );

		// Prepare actions
		if ( !$is_new && !$is_draft )
			$additional[] = array( 'duplicate', __( 'Duplicate', $cmmngt->textdomain ) );
		if ( !$is_new && current_user_can( 'delete_post', $post_id ) )
			$additional[] = array( 'delete', __( 'Delete', $cmmngt->textdomain ) );
		// Add dropdown menu
		$buttons[70] = cmmngt_dropdown( 'additional', __( 'Additional', $cmmngt->textdomain ), array( 'plus', 'black' ), $additional );

		// Add post_id to request
		if ( is_numeric( $post_id ) )
			$hidden .= cmmngt_hidden( 'post_id', $post_id );
		// Add cmmngt action field
		$hidden .= cmmngt_hidden( 'cmmngt_action', '' );
		$hidden .= cmmngt_hidden( 'cmmngt_sub_action', '' );
		// Render template
		include_once 'views/frontend.php';
	}

	/**
	 * Register backend metabox
	 */
	function cmmngt_add_meta_box() {
		// Get plugin object
		global $cmmngt;
		// Add meta box
		add_meta_box( 'z10-cmmngt-meta-box', __( 'Management', $cmmngt->textdomain ), 'cmmngt_backend', 'cases', 'advanced', 'core' );
	}

	add_action( 'add_meta_boxes', 'cmmngt_add_meta_box', 90 );
	add_action( 'roots_entry_content_after', 'cmmngt_frontend', 90 );
?>