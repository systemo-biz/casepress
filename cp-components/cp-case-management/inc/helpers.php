<?php

	/**
	 * Get unique nonce name
	 */
	function cmmngt_get_nonce() {
		return __FILE__;
	}

	/**
	 * Print submit button
	 *
	 * @param string $type Button type
	 * @param string $text Button text
	 * @param mixed $icon Icon. $icon[0] - icon type, $icon[1] - icon color
	 * @param string $class Additional button CSS class
	 * @param string $preventsubmit Prevent form submitting
	 */
	function cmmngt_button( $type, $text, $icon = array( ), $class = '', $submit = true ) {
		$i = ( count( $icon ) ) ? '<i class="icon-' . $icon[0] . ' icon-' . $icon[1] . '"></i> '
				: '';
		$class = ($class) ? ' ' . $class : '';
		$submit = ( $submit ) ? 'true' : 'false';
		return '<button type="submit" name="' . $type . '" class="cmmngt-' . $type . $class . $ps . ' btn btn-small cmmngt-action" data-action="' . $type . '" data-submit="' . $submit . '">' . $i . $text . '</button>';
	}

	/**
	 * Print button with sub-actions
	 *
	 * @param string $type Button type
	 * @param string $text Button text
	 * @param mixed $icon Icon. $icon[0] - icon type, $icon[1] - icon color
	 * @param array $subs Sub-menu actions - array( array( type, text, submit ), ... )
	 * @param string $class Additional CSS class for button
	 */
	function cmmngt_dropdown( $type, $text, $icon = array( ), $subs = array( ), $class = '' ) {
		$i = ( count( $icon ) ) ? '<i class="icon-' . $icon[0] . ' icon-' . $icon[1] . '"></i> '
				: '';
		$class = ($class) ? ' ' . $class : '';
		$actions = array( );
		if ( count( $subs ) )
			foreach ( $subs as $sub ) {
				$submit = ( $sub[2] === false ) ? 'false' : 'true';
				$actions[] = '<li><a href="#" class="cmmngt-sub-action" data-action="' . $sub[0] . '" data-submit="' . $submit . '">' . $sub[1] . '</a></li>';
			}
		return '<div class="btn-group dropup"><button class="cmmngt-action cmmngt-' . $type . $class . ' btn btn-small dropdown-toggle" data-toggle="dropdown" data-action="' . $type . '">' . $i . $text . ' <span class="caret"></span></button><ul class="dropdown-menu cmmngt-sub-actions pull-right cmmngt-' . $type . '-menu">' . implode( '', $actions ) . '</ul></div>';
	}

	/**
	 * Print hidden input
	 *
	 * @param string $name Input name
	 * @param string $value Input value
	 */
	function cmmngt_hidden( $name, $value ) {
		return '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
	}

	/**
	 * Helper function to get term data
	 */
	function cmmngt_get_term_data( $option, $term_id, $taxonomy ) {
		$term = get_term_by( 'id', $term_id, $taxonomy, 'ARRAY_A' );
		return( $term[$option] );
	}

	/**
	 * Get current date
	 */
	function cmmngt_get_date() {
		return date_i18n( _x( 'Y-m-d G:i', 'timezone date format' ) );
	}

	/**
	 * Print term permalink
	 */
	function cmmngt_term_permalink( $term, $taxonomy, $echo = true ) {
		global $cmmngt;
		$term_obj = get_term_by( 'id', $term, $taxonomy );
		if ( !is_wp_error( $term_obj ) && !is_wp_error( get_term_link( $term_obj ) ) )
			$result = '<a href="' . get_term_link( $term_obj ) . '">' . $term_obj->name . '</a>';
		else
			$result = __( 'Not selected', $cmmngt->textdomain );
		if ( $echo )
			echo $result;
		else
			return $result;
	}

	/**
	 * Print term title
	 */
	function cmmngt_term_title( $term, $taxonomy, $echo = true ) {
		global $cmmngt;
		$term_obj = get_term_by( 'id', $term, $taxonomy );
		if ( !is_wp_error( $term_obj ) && !is_wp_error( get_term_link( $term_obj ) ) )
			$result = $term_obj->name;
		else
			$result = __( 'Not selected', $cmmngt->textdomain );
		if ( $echo )
			echo $result;
		else
			return $result;
	}

	/**
	 * Convert any date to readable format
	 */
	function cmmngt_pretty_date( $date, $echo = true ) {
		global $cmmngt;
		if ( !empty( $date ) ) {
			$time = strtotime( $date );
			$month = date( 'n', $time );
			switch ( $month ) {
				case 1: $month = 'января';
					break;
				case 2: $month = 'февраля';
					break;
				case 3: $month = 'марта';
					break;
				case 4: $month = 'апреля';
					break;
				case 5: $month = 'мая';
					break;
				case 6: $month = 'июня';
					break;
				case 7: $month = 'июля';
					break;
				case 8: $month = 'августа';
					break;
				case 9: $month = 'сентября';
					break;
				case 10: $month = 'октября';
					break;
				case 11: $month = 'ноября';
					break;
				case 12: $month = 'декабря';
					break;
			}
			$result = date( 'j ' . $month . ' Y, G:i', $time );
		}
		else {
			$result = __( 'Not selected', $cmmngt->textdomain );
		}
		if ( $echo )
			echo $result;
		else
			return $result;
	}

	/**
	 * Get plugin affected pages
	 */
	function cmmngt_affected_pages() {
		// Pages affected by plugin
		$pages = array(
			'post-new.php',
			'post.php',
			'edit.php'
		);
		return $pages;
	}

	/**
	 * Get person/persons link
	 */
	function cmmngt_get_person_link( $person ) {
		// Prepare variables
		$result = '';
		// Multiple persons
		if ( strpos( trim( $person, ',' ), ',' ) !== false ) {
			foreach ( explode( ',', $person ) as $pers )
				$result[] = '<a href="' . get_permalink( $pers ) . '">' . get_the_title( $pers ) . '</a>';
			$result = implode( ', ', $result );
		}

		// Single person
		elseif ( !empty( $person ) )
			$result .= '<a href="' . get_permalink( $person ) . '">' . get_the_title( $person ) . '</a>';
		return $result;
	}

	/**
	 * Retrieve list of cases
	 */
	function cmmngt_get_cases( $args = '' ) {
		$pts = get_post_types( array( 'public' => true ), 'objects' );
		$query = array(
			'post_type' => 'cases',
			'suppress_filters' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'post_status' => 'publish',
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1,
		);
		$args['pagenum'] = isset( $args['pagenum'] ) ? absint( $args['pagenum'] ) : 1;
		if ( isset( $args['s'] ) )
			$query['s'] = $args['s'];
		$query['offset'] = $args['pagenum'] > 1 ? $query['posts_per_page'] * ( $args['pagenum'] - 1 )
				: 0;
		// Do main query.
		add_filter( 'posts_search', 'cmmngt_search_by_title', 500, 2 );
		$get_posts = new WP_Query;
		$posts = $get_posts->query( $query );
		// Check if any posts were found.
		if ( !$get_posts->post_count )
			return false;
		// Build results.
		$results = array( );
		foreach ( $posts as $post ) {
			if ( 'post' == $post->post_type )
				$info = mysql2date( __( 'Y/m/d' ), $post->post_date );
			else
				$info = $pts[$post->post_type]->labels->singular_name;
			$results[] = array(
				'id' => $post->ID,
				'text' => trim( esc_html( strip_tags( get_the_title( $post ) ) ) )
//				'permalink' => get_permalink( $post->ID ),
//				'info' => $info,
			);
		}
		remove_filter( 'posts_search', 'cmmngt_search_by_title' );
		return $results;
	}

	/**
	 * Retrieve list of persons
	 */
	function cmmngt_get_persons( $args = '' ) {
		$pts = get_post_types( array( 'public' => true ), 'objects' );
		$query = array(
			'post_type' => 'persons',
			'suppress_filters' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'post_status' => 'publish',
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1,
		);
		$args['pagenum'] = isset( $args['pagenum'] ) ? absint( $args['pagenum'] ) : 1;
		if ( isset( $args['s'] ) )
			$query['s'] = $args['s'];
		$query['offset'] = $args['pagenum'] > 1 ? $query['posts_per_page'] * ( $args['pagenum'] - 1 )
				: 0;
		// Do main query.
		add_filter( 'posts_search', 'cmmngt_search_by_title', 500, 2 );
		$get_posts = new WP_Query;
		$posts = $get_posts->query( $query );
		// Check if any posts were found.
		if ( !$get_posts->post_count )
			return false;
		// Build results.
		$results = array( );
		foreach ( $posts as $post ) {
			if ( 'post' == $post->post_type )
				$info = mysql2date( __( 'Y/m/d' ), $post->post_date );
			else
				$info = $pts[$post->post_type]->labels->singular_name;
			$results[] = array(
				'id' => $post->ID,
				'name' => trim( esc_html( strip_tags( get_the_title( $post ) ) ) )
			);
		}
		remove_filter( 'posts_search', 'cmmngt_search_by_title' );
		return $results;
	}

	function cmmngt_search_by_title( $search, &$wp_query ) {
		global $wpdb;
		if ( empty( $search ) )
			return $search; // skip processing - no search term in query
		$q = $wp_query->query_vars;
		$n = !empty( $q['exact'] ) ? '' : '%';
		$search =
			$searchand = '';
		foreach ( ( array ) $q['search_terms'] as $term ) {
			$term = esc_sql( like_escape( $term ) );
			$search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}' OR $wpdb->posts.ID = '{$term}')";
			$searchand = ' AND ';
		}
		if ( !empty( $search ) ) {
			$search = " AND ({$search}) ";
			if ( !is_user_logged_in() )
				$search .= " AND ($wpdb->posts.post_password = '') ";
		}
		return $search;
	}

	function cmmngt_get_priorities() {
		global $cmmngt;
		return array(
			'-1' => __( 'Not selected', $cmmngt->textdomain ),
			'1' => __( 'Critical', $cmmngt->textdomain ),
			'2' => __( 'High', $cmmngt->textdomain ),
			'3' => __( 'Normal', $cmmngt->textdomain ),
			'4' => __( 'Low', $cmmngt->textdomain ),
			'5' => __( 'Planned', $cmmngt->textdomain )
		);
	}

	/**
	 * Get case title by ID
	 */
	function cmmngt_ajax_get_title() {
		$post_id = $_REQUEST['post_id'];
		die( json_encode( array( 'text' => get_the_title( $post_id ) ) ) );
	}

	add_action( 'wp_ajax_cmmngt_ajax_get_title', 'cmmngt_ajax_get_title' );

	function cmmngt_print_buttons( $buttons ) {
		// Filter buttons
		$buttons = apply_filters( 'cmmngt_print_buttons', $buttons );
		ksort( $buttons );
		// Loop through buttons
		foreach ( $buttons as $btn_i => $btn ) {
			echo '<!-- start: CMMNGT: button order - ' . $btn_i . ' -->';
			echo $btn;
			echo '<!-- end: CMMNGT: button order - ' . $btn_i . ' -->' . "\n";
		}
	}

	function cmmngt_print_buttons_after() {
		// Filter buttons
		$html = apply_filters( 'cmmngt_print_buttons_after', '' );
		// Print markup
		echo $html;
	}

	function cmmngt_get_person_by_email( $email ) {
		$person = get_posts( array(
			'numberposts' => 1,
			'post_type' => 'persons',
			'meta_key' => 'email',
			'meta_value' => $email
			) );
		return $person[0]->ID;
	}

	function cmmngt_empty_field_marker( $value, $field ) {
		// Get plugin object
		global $cmmngt;
		// Prepare marker markup
		$marker = ' data-empty="true"';
		// Prepare hide flag
		$hide = false;
		// Check value
		if ( empty( $value ) || $value == '-1' || $value == '0' || $value == '' || $value == __( 'Not selected', $cmmngt->textdomain ) )
			$hide = true;
		// Apply filters
		$hide = apply_filters( 'cmmngt_empty_field_marker', $hide, $field );
		// Print result if field must be hidden
		if ( $hide )
			echo $marker;
	}

	function cmmngt_case_link( $post_id ) {
		global $cmmngt;
		return $post_id ? '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>'
				: __( 'Not selected', $cmmngt->textdomain );
	}

?>