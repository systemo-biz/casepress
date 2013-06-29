<?php

	/**
	 * Meta shortcode
	 */
	function cases_meta_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			'post_id' => null,
			'key' => null,
			'processor' => null,
			'keep' => null
			), $atts );
		$post_id = ( is_null( $atts['post_id'] ) ) ? get_the_ID() : $atts['post_id'];
		if ( !$post_id || !$atts['key'] )
			return;
		$meta = get_post_meta( $post_id, $atts['key'], true );
		$result = ( isset( $atts['processor'] ) && function_exists( $atts['processor'] ) )
				? call_user_func( $atts['processor'], $meta ) : $meta;
		if ( !$meta && isset( $atts['keep'] ) ) {
			$sc_atts = array( );
			foreach ( $atts as $att_name => $att_value )
				if ( $att_value )
					$sc_atts[] = ' ' . $att_name . '="' . $att_value . '"';

			$result = '[meta' . implode( '', $sc_atts ) . ']';
		}
		if ( ($atts['key'] == 'find_employee_position') && ($meta == '') )
			$result = '';
		return $result;
	}

	add_shortcode( 'meta', 'cases_meta_shortcode' );

	/**
	 * Term shortcode
	 */
	function cases_term_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			'post_id' => null,
			'taxonomy' => null,
			'param' => 'term_id',
			'processor' => null,
			'keep' => null,
			'termname' => null
			), $atts );
		$post_id = ( is_null( $atts['post_id'] ) ) ? get_the_ID() : $atts['post_id'];
		if ( !$post_id || !$atts['taxonomy'] )
			return;
		$terms = wp_get_post_terms( $post_id, $atts['taxonomy'] );
		$term = (!is_wp_error( $terms ) && is_numeric( $terms[0]->term_id ) ) ? $terms[0]
				: false;
		if ( $term )
			$result = ( isset( $atts['processor'] ) && function_exists( $atts['processor'] ) )
					? call_user_func( $atts['processor'], $term->$atts['param'] ) : $term->$atts['param'];
		elseif ( isset( $atts['keep'] ) ) {
			$sc_atts = array( );
			foreach ( $atts as $att_name => $att_value )
				if ( $att_value )
					$sc_atts[] = ' ' . $att_name . '="' . $att_value . '"';

			$result = '[term' . implode( '', $sc_atts ) . ']';
		}
		return $result;
	}

	add_shortcode( 'term', 'cases_term_shortcode' );

	/**
	 * Post shortcode
	 */
	function cases_post_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			'post_id' => null,
			'param' => 'ID',
			'processor' => null,
			'keep' => null
			), $atts );
		$post_id = ( is_null( $atts['post_id'] ) ) ? get_the_ID() : $atts['post_id'];
		if ( !$post_id )
			return;
		global $post;
		$the_post = (!empty( $post ) ) ? $post : get_post( $atts['post_id'] );
		$the_post = (!empty( $the_post ) ) ? $the_post : false;
		if ( $the_post )
			$result = ( isset( $atts['processor'] ) && function_exists( $atts['processor'] ) )
					? call_user_func( $atts['processor'], $the_post->$atts['param'] ) : $the_post->$atts['param'];
		elseif ( isset( $atts['keep'] ) ) {
			$sc_atts = array( );
			foreach ( $atts as $att_name => $att_value )
				if ( $att_value )
					$sc_atts[] = ' ' . $att_name . '="' . $att_value . '"';

			$result = '[post' . implode( '', $sc_atts ) . ']';
		}
		return $result;
	}

	add_shortcode( 'post', 'cases_post_shortcode' );
?>