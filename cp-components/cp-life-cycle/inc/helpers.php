<?php

	/**
	 * Get life cycle data by term or post ID
	 *
	 * @param int $id The post or term ID
	 * @param string $by The condition that determines the post or term ID is presented (term*|post)
	 * @return mixed $data Array with the life cycle data or false if errors
	 *
	 * @example $data
	 * Array(
	 * 		[default] => true,
	 * 		[terms] => Array( 111, 112, 113 ),
	 * 		[states] => Array( 222, 223, 224 ),
	 * 		[results] => Array( 333, 334, 335 )
	 * )
	 */
	function lfc_get_life_cycle( $id = false, $by = 'term' ) {
		// Prepare vars
		$data = array( );
		// Check the ID
		if ( !is_numeric( $id ) )
			return false;
		// Get term ID by post ID
		if ( $by === 'post' ) {
			// Get post terms
			$terms = wp_get_post_terms( $id, 'functions', array( 'fields' => 'ids' ) );
			// Terms is found, get first term ID
			if ( count( $terms ) && !is_wp_error( $terms ) )
				$id = $terms[0];
			// Terms not found
			else
				$id='0';//set no term for get default life cicle;
		}
		// Prepare args
		$args = array(
			'post_type' => 'life_cycle',
			'numberposts' => 1,
			'posts_per_page' => 1,
			'meta_key' => 'cp_posts_life_cycle_case_category',
			'meta_value' => $id
		);
		// Query posts
		$cycles = new WP_Query( $args );
		// Check posts count
		if ( $cycles->post_count == 0 ) {
			// Get default life cycle if posts not found
			unset( $args['meta_key'] );
			unset( $args['meta_value'] );
			unset( $args['meta_compare'] );
			$args['p'] = get_option( 'default_life_cycle_id' );
			$cycles = new WP_Query( $args );
		}
		// Prepare first founded cycle
		$cycle = $cycles->post;
		// Get cycle data
		$data['id'] = ( int ) $cycle->ID;
		$data['title'] = ( string ) $cycle->post_title;
		$data['default'] = ( $cycle->ID == get_option( 'default_life_cycle_id' ) ) ? true
				: false;
		$data['terms'] = ( array ) maybe_unserialize( get_post_meta( $cycle->ID, 'cp_posts_life_cycle_functions', true ) );
		$data['states'] = ( array ) maybe_unserialize( get_post_meta( $cycle->ID, 'cp_posts_life_cycle_state', true ) );
		$data['results'] = ( array ) maybe_unserialize( get_post_meta( $cycle->ID, 'cp_posts_life_cycle_results', true ) );
		// Return data
		return $data;
	}

	/**
	 * Dropdown with terms by specified taxonomy
	 *
	 * @global mixed $cplfc Component instance
	 * @param array $args Array with args
	 * @return string Echo or return select tag markup depend on echo arg
	 */
	function lfc_dropdown_terms( $args ) {
		// Get component instance
		global $cplfc;
		// Prepare vars
		$select = array( );
		// Prepare defaults
		$defaults = array(
			'prefix' => 'lfc-',
			'taxonomy' => false,
			'id' => 'functions',
			'class' => 'init-select2',
			'selected' => array( ),
			'multiple' => true,
			'echo' => true
		);
		// Parse args
		$args = wp_parse_args( $args, $defaults );
		// Prepare select data
		$multiple = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
		$class = ( $args['class'] ) ? ' class="' . $args['class'] . '"' : '';
		$taxonomy = ( $args['taxonomy'] ) ? $args['taxonomy'] : $args['id'];
		// Get terms
		$terms = get_terms( $taxonomy, array(
			'hide_empty' => false
			) );
		// Start of select
		$select[] = '<select name="' . $args['prefix'] . $args['id'] . '" id="' . $args['prefix'] . $args['id'] . '"' . $class . $multiple . '>';
		// Add default option
		$select[] = '<option value="0">' . __( 'Not selected', $cplfc->textdomain ) . '</option>';
		// Fill select
		foreach ( $terms as $term ) {
			// Is option selected?
			$selected = ( in_array( $term->term_id, $args['selected'] ) ) ? ' selected="selected"'
					: '';
			// Add an option
			$select[] = '<option value="' . $term->term_id . '"' . $selected . '>' . $term->name . '</option>';
		}
		// End of select
		$select[] = '</select>';
		// Return result
		if ( $args['echo'] )
			echo implode( "\n", $select );
		else
			return implode( "\n", $select );
	}

	function lfc_selection( $value, $tax ) {
		// Prepare vars
		$data = array( );
		// Check value and taxonomy
		if ( !is_array( $value ) || empty( $tax ) )
			return;
		// Check value term count
		if ( count( $value ) )
			foreach ( $value as $term )
				$data[] = $term . ':' . str_replace( array( ':', ';' ), '', lfc_term_title( $term, $tax ) );
		// Terms not found
		else
			return;
		// Print result
		echo implode( ';', $data );
	}

	/**
	 * Print term title
	 */
	function lfc_term_title( $term, $taxonomy ) {
		$term_obj = get_term_by( 'id', $term, $taxonomy );
		if ( !is_wp_error( $term_obj ) )
			$result = $term_obj->name;
		else
			$result = false;
		return $result;
	}

?>