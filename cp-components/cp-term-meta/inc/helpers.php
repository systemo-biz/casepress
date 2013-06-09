<?php

	/**
	 * Get plugin internal settings
	 * @param string $key Settings key name
	 */
	function ctmeta_settings( $key ) {
		$settings = array(
			'post_type_name' => 'ctmeta_post',
			'slug_format' => 'ctmeta-%s1-%s2'
		);

		// If key specified return it's value
		// return all settings if not
		return ( $key ) ? $settings[$key] : $settings;
	}

	/**
	 * Update term meta value
	 *
	 * create post, if it's doesn't exists
	 *
	 * @param string $key Meta key name
	 * @param string $value Meta value
	 * @param int $taxonomy Taxonomy ID
	 * @param int $term Term ID
	 * @return bool True if meta value updated, and false if not
	 */
	function ctmeta_update_meta( $key, $value, $taxonomy, $term ) {

		// Post name (post slug)
		$slug = str_replace( array( '%s1', '%s2' ), array( $taxonomy, $term ), ctmeta_settings( 'slug_format' ) );

		// Get posts
		$posts = get_posts( array( 'name' => $slug, 'post_type' => ctmeta_settings( 'post_type_name' ) ) );

		// Check that post is exists
		$post_exists = ( count( $posts ) > 0 ) ? true : false;

		// If post already exists
		if ( $post_exists )
			$post_id = $posts[0]->ID;

		// Post doesn't exists
		else
			$post_id = wp_insert_post( array(
				'post_name' => $slug,
				'post_title' => $slug,
				'post_status' => 'publish',
				'post_type' => 'ctmeta_post'
				) );

		// Given correct post ID
		if ( is_numeric( $post_id ) ) {
			update_post_meta( $post_id, $key, $value );
			return true;
		}

		else
			return false;
	}

	/**
	 * Get term meta
	 *
	 * @param string $key Meta key name
	 * @param int $taxonomy Taxonomy ID
	 * @param int $term Term ID
	 * @return mixed Meta value if it's exists, and false if not
	 */
	function ctmeta_get_meta( $key, $taxonomy, $term ) {

		// Post name (post slug)
		$slug = str_replace( array( '%s1', '%s2' ), array( $taxonomy, $term ), ctmeta_settings( 'slug_format' ) );

		// Get posts
		$posts = get_posts( array( 'name' => $slug, 'post_type' => ctmeta_settings( 'post_type_name' ) ) );

		// Check that post is exists
		$post_exists = ( count( $posts ) > 0 ) ? true : false;

		// If post exists
		if ( $post_exists && is_numeric( $posts[0]->ID ) )
			return get_post_meta( $posts[0]->ID, $key, true );

		// Post doesn't exists
		else
			return false;
	}

	/**
	 * Get formatted term meta
	 *
	 * @param string $key Meta key name
	 * @param int $taxonomy Taxonomy ID
	 * @param int $term Term ID
	 * @return mixed Meta value if it's exists, and false if not
	 */
	function ctmeta_get_formatted_datatable_params( $key, $taxonomy, $term ) {

		// Post name (post slug)
		$slug = str_replace( array( '%s1', '%s2' ), array( $taxonomy, $term ), ctmeta_settings( 'slug_format' ) );

		// Get posts
		$posts = get_posts( array( 'name' => $slug, 'post_type' => ctmeta_settings( 'post_type_name' ) ) );

		// Check that post is exists
		$post_exists = ( count( $posts ) > 0 ) ? true : false;

		// If post exists
		if ( $post_exists && is_numeric( $posts[0]->ID ) )
			return shortcode_parse_atts( stripslashes( get_post_meta( $posts[0]->ID, $key, true ) ) );

		// Post doesn't exists
		else
			return false;
	}

	function ctmeta_get_title_template( $term ) {

		if ( $term ) {
			$template = ctmeta_get_meta( 'ctmeta_title_template', 'functions', $term );

			// Template for current term isn't set
			if ( empty( $template ) ) {
				for ( $i = 0; $i < 5; $i++ ) {

					// Get current term
					$term_obj = ( isset( $parent ) ) ? get_term_by( 'id', $parent, 'functions' ) : get_term_by( 'id', $term, 'functions' );

					// Check parent
					$parent = (!is_wp_error( $term_obj ) && is_numeric( $term_obj->parent ) ) ? $term_obj->parent : false;

					// Got parent
					if ( $parent ) {
						if ( ctmeta_get_meta( 'ctmeta_title_template', 'functions', $parent ) ) {
							$template = ctmeta_get_meta( 'ctmeta_title_template', 'functions', $parent );
							break;
						}
					}

					// No parents, stop the loop
					else {
						$template = '';
						break;
					}
					unset( $term_obj );
				}
			}
		}

		return $template;
	}
	
	function ctmeta_get_content_template( $term ) {

		if ( $term ) { 
			$template = ctmeta_get_meta( 'ctmeta_content_template', 'functions', $term );

			// Template for current term isn't set
			if ( empty( $template ) ) {
				for ( $i = 0; $i < 5; $i++ ) {

					// Get current term
					$term_obj = ( isset( $parent ) ) ? get_term_by( 'id', $parent, 'functions' ) : get_term_by( 'id', $term, 'functions' );

					// Check parent
					$parent = (!is_wp_error( $term_obj ) && is_numeric( $term_obj->parent ) ) ? $term_obj->parent : false;

					// Got parent
					if ( $parent ) {
						if ( ctmeta_get_meta( 'ctmeta_title_template', 'functions', $parent ) ) {
							$template = ctmeta_get_meta( 'ctmeta_title_template', 'functions', $parent );
							break;
						}
					}

					// No parents, stop the loop
					else {
						$template = '';
						break;
					}
					unset( $term_obj );
				}
			}
		}

		return $template;
	}
	
	function ctmeta_get_default_responsible( $term ) {

		if ( $term ) {
			$responsible = ctmeta_get_meta( 'ctmeta_default_responsible', 'functions', $term );

			// Template for current term isn't set
			if ( empty( $responsible ) ) {
				for ( $i = 0; $i < 5; $i++ ) {

					// Get current term
					$term_obj = ( isset( $parent ) ) ? get_term_by( 'id', $parent, 'functions' ) : get_term_by( 'id', $term, 'functions' );

					// Check parent
					$parent = (!is_wp_error( $term_obj ) && is_numeric( $term_obj->parent ) ) ? $term_obj->parent : false;

					// Got parent
					if ( $parent ) {
						if ( ctmeta_get_meta( 'ctmeta_default_responsible', 'functions', $parent ) ) {
							$responsible = ctmeta_get_meta( 'ctmeta_default_responsible', 'functions', $parent );
							break;
						}
					}

					// No parents, stop the loop
					else {
						$responsible = '';
						break;
					}
					unset( $term_obj );
				}
			}
		}

		return $responsible;
	}

	function ctmeta_is_working_day( $date ) {
		if ( is_string( $date ) )
			$date = strtotime( $date );

		// Saturday or Sunday
		if ( date( 'N', $date ) == 6 || date( 'N', $date ) == 7 )
			return false;

		return true;
	}

	function ctmeta_get_next_working_day( $date ) {
		if ( is_string( $date ) )
			$date = strtotime( $date );

		$date = strtotime( '+1 day', $date );
		while ( ! ctmeta_is_working_day( $date ) )
			$date = strtotime( '+1 day', $date );
	
		return $date;
	}

	/**
	 * Determine case end date.
	 *
	 * Четыре параметра:
	 * 1. Начало рабочего дня (9:00)
	 * 2. Конец рабочего дня (18:00)
	 * 3. Начало обеда (13:00)
	 * 4. Конец обеда (14:00)
	 *
	 * Вычисление даты:
	 * 1. Берём время начала.
	 * 2. Смотрим, до обеда оно или после.
	 * 3. Вычисляем, сколько осталось до конца рабочего дня (с учётом обеда).
	 * 4. Если срок меньше этого времени, то задача решена.
	 * 5. Если больше, то из срока отнимаем время, оставшееся до конца рабочего дня (величину, полученную на шаге 3).
	 * 6. Переходим на следующий рабочий день (возвращаемся к шагу 1).
	 *
	 * @return string Case end date.
	 */
	function ctmeta_get_case_end_date( $estimated_time, $date_start ) {
		global $post;

		$settings = array(
			'working_hours_start' => '09:00',
			'working_hours_end'   => '17:00',
			'lunch_break_start'   => '13:00',
			'lunch_break_end'     => '13:00',
		);

		$minute_in_seconds = 60;
		$hour_in_seconds = 60 * $minute_in_seconds;
		$day_in_seconds = 24 * $hour_in_seconds;
		$week_in_seconds = 7 * $day_in_seconds;

		if ( empty( $estimated_time ) || empty( $date_start ) )
			return false;

		if ( isset( $post->post_date ) )
			$date_start = $post->post_date;

		if ( is_string( $date_start ) )
			$date_start = strtotime( $date_start );

		// Если начало работы приходится на нерабочее время или выходной день, то сдвигаем его на начало ближайшего рабочего дня.
		// Если начало работы приходится на время обеда, то сдвигаем его на время конца обеда.
		$case_start_time = date( 'H:i', $date_start );
		if ( $case_start_time < $settings['working_hours_start'] && ctmeta_is_working_day( $date_start ) )
			$date_start = date( 'd.m.Y', $date_start ) . ' ' . $settings['working_hours_start'];
		elseif ( $case_start_time >= $settings['lunch_break_start'] && $case_start_time < $settings['lunch_break_end'] && ctmeta_is_working_day( $date_start ) )
			$date_start = date( 'd.m.Y', $date_start ) . ' ' . $settings['lunch_break_end'];
		elseif ( $case_start_time >= $settings['working_hours_end'] || ! ctmeta_is_working_day( $date_start ) )
			$date_start = date( 'd.m.Y', ctmeta_get_next_working_day( $date_start ) ) . ' ' . $settings['working_hours_start'];

		$time_of_lunch = $settings['lunch_break_end'] - $settings['lunch_break_start'];
		$case_end_date = $date_start;
		$finishing_today = false;

		while ( ! $finishing_today ) {
			if ( is_string( $case_end_date ) )
				$case_end_date = strtotime( $case_end_date );

			$start_before_lunch = ( date( 'H:i', $case_end_date ) < $settings['lunch_break_start'] );

			$working_hours_end = date( 'd.m.Y', $case_end_date ) . ' ' . $settings['working_hours_end'];
			$time_until_working_hours_end = intval( ( strtotime( $working_hours_end ) - $case_end_date ) / $hour_in_seconds );
			if ( $start_before_lunch )
				$time_until_working_hours_end -= $time_of_lunch;

			$finishing_today = ( $estimated_time <= $time_until_working_hours_end );
			if ( $finishing_today ) {
				$case_end_time = date( 'H:i', $case_end_date + $estimated_time * $hour_in_seconds );
				if ( $case_end_time <= $settings['lunch_break_start'] || ! $start_before_lunch )
					$case_end_date = date( 'd.m.Y H:i', $case_end_date + $estimated_time * $hour_in_seconds );
				else
					$case_end_date = date( 'd.m.Y H:i', $case_end_date + ( $estimated_time + $time_of_lunch ) * $hour_in_seconds );
			} else {
				$estimated_time -= $time_until_working_hours_end;
				$case_end_date = date( 'd.m.Y', ctmeta_get_next_working_day( $case_end_date ) ) . ' ' . $settings['working_hours_start'];
			}
		}

		return strtotime( $case_end_date );
	}

	function ctmeta_get_default_deadline( $term, $priority ) {

		if ( $term ) {
			$deadline = ctmeta_get_meta( 'ctmeta_default_deadline', 'functions', $term );
			$deadline = ( ctmeta_get_meta( 'ctmeta_deadline_priority_toggle', 'functions', $term ) == 'on' && ctmeta_get_meta( 'ctmeta_deadline_priority_' . $priority, 'functions', $term ) ) ? ctmeta_get_meta( 'ctmeta_deadline_priority_' . $priority, 'functions', $term ) : $deadline;

			// Deadline for current term isn't set
			if ( empty( $deadline ) ) {
				for ( $i = 0; $i < 5; $i++ ) {

					// Get current term
					$term_obj = ( isset( $parent ) ) ? get_term_by( 'id', $parent, 'functions' ) : get_term_by( 'id', $term, 'functions' );

					// Check parent
					$parent = (!is_wp_error( $term_obj ) && is_numeric( $term_obj->parent ) ) ? $term_obj->parent : false;

					// Got parent
					if ( $parent ) {
						if ( ctmeta_get_meta( 'ctmeta_default_deadline', 'functions', $parent ) ) {
							$deadline = ctmeta_get_meta( 'ctmeta_default_deadline', 'functions', $parent );
							$deadline = ( ctmeta_get_meta( 'ctmeta_deadline_priority_toggle', 'functions', $parent ) == 'on' && ctmeta_get_meta( 'ctmeta_deadline_priority_' . $priority, 'functions', $parent ) ) ? ctmeta_get_meta( 'ctmeta_deadline_priority_' . $priority, 'functions', $parent ) : $deadline;
							break;
						}
					}

					// No parents, stop the loop
					else {
						$deadline = false;
						break;
					}
					unset( $term_obj );
				}
			}
		}

		// Convert to date-time
		if ( $deadline && is_numeric( $deadline ) ) {
			// $deadline = date_i18n( _x( 'm/d/Y, G:i', 'timezone date format' ), current_time( 'timestamp' ) + ( $deadline * 60 * 60 ) );
			$deadline = date_i18n( _x( 'm/d/Y, G:i', 'timezone date format' ), ctmeta_get_case_end_date( $deadline, current_time( 'timestamp' ) ) );
		}

		return $deadline;
	}

	/**
	 * Retrieve or display list of persons as a dropdown (select list).
	 */
	function ctmeta_dropdown_persons( $args = '' ) {

		$defaults = array(
			'depth' => 0,
			'child_of' => 0,
			'selected' => 0,
			'echo' => 1,
			'name' => 'page_id',
			'id' => '',
			'show_option_none' => '',
			'show_option_no_change' => '',
			'option_none_value' => '',
			'placeholder' => '',
			'multiple' => false,
			'walker' => new ctmeta_Walker_PersonDropdown
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );

		$pages = get_pages( $r );
		$output = '';
		// Back-compat with old system where both id and name were based on $name argument
		if ( empty( $id ) )
			$id = $name;

		if ( $multiple )
			$multiple = ' multiple';

		if ( !empty( $pages ) ) {
			$output = '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="ctmeta-init-chosen"' . $multiple . ' data-placeholder="' . $placeholder . '">';
			$output .= '<option value=""></option>';
			$output .= walk_page_dropdown_tree( $pages, $depth, $r );
			$output .= '</select>';
		}

		$output = apply_filters( 'wp_dropdown_pages', $output );

		if ( $echo )
			echo $output;

		return $output;
	}

	/**
	 * Create HTML dropdown list of persons.
	 */
	class ctmeta_Walker_PersonDropdown extends Walker {

		var $tree_type = 'page';
		var $db_fields = array( 'parent' => 'post_parent', 'id' => 'ID' );

		/**
		 * @see Walker::start_el()
		 */
		function start_el( &$output, $page, $depth, $args ) {
			$pad = str_repeat( '&nbsp;', $depth * 3 );

			$selected = ( strpos( $args['selected'], ',' ) ) ? explode( ',', $args['selected'] ) : array( $args['selected'] );

			$output .= "\t<option class=\"level-$depth\" value=\"$page->ID\"";
			if ( in_array( $page->ID, $selected ) )
				$output .= ' selected="selected"';
			$output .= '>';
			$title = apply_filters( 'list_pages', $page->post_title, $page );
			$output .= $pad . esc_html( $title );
			$output .= "</option>\n";
		}

	}

?>