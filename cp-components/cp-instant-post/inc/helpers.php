<?php

	function cip_get_current_person() {
		global $current_user;
		get_currentuserinfo();
		$email = $current_user->data->user_email;
		$persons = get_posts( array(
			'post_type' => 'persons',
			'meta_key' => 'email',
			'meta_value' => $email,
			'numberposts' => 1
			) );

		return ( is_numeric( $persons[0]->ID ) ) ? $persons[0]->ID : false;
	}

	/**
	 * Add plugin links to admin bar
	 */
	function cip_add_menu_links() {
		global $wp_admin_bar, $cip_plugin;

		if ( is_admin() )
			$wp_admin_bar->add_menu(
				array(
					'id' => 'go-to-site',
					'title' => '<span class="ab-icon"></span>',
					'href' => home_url()
				)
			);
		else
			$wp_admin_bar->add_menu(
				array(
					'id' => 'go-to-dashboard',
					'title' => '<span class="ab-icon"></span>',
					'href' => admin_url()
				)
			);

		// Check capability and admin bar visibility
		if ( !current_user_can( 'publish_posts' ) || !is_admin_bar_showing() )
			return;

		$wp_admin_bar->add_menu(
			array(
				'id' => 'cip-message',
				'title' => '<span class="ab-icon"></span><span class="ab-label">' . __( 'Message', $cip_plugin->textdomain ) . '</span>',
				'href' => admin_url( '/post-new.php?post_type=cases&csposter&csposter_function=5' ),
				'meta' => array( 'class', 'cip-form-type-message' )
			)
		);
		$wp_admin_bar->add_menu(
			array(
				'id' => 'cip-incoming',
				'title' => '<span class="ab-icon"></span><span class="ab-label">' . __( 'Incoming', $cip_plugin->textdomain ) . '</span>',
				'href' => admin_url( '/post-new.php?post_type=cases&csposter&csposter_function=80' ),
				'meta' => array( 'class', 'cip-form-type-incoming' )
			)
		);
	}

	add_action( 'admin_bar_menu', 'cip_add_menu_links' );

	/**
	 * Remove links from admin bar
	 */
	function cip_remove_menu_links() {
		global $wp_admin_bar;

		$wp_admin_bar->remove_menu( 'wp-logo' );
		$wp_admin_bar->remove_menu( 'site-name' );
	}

	add_action( 'admin_bar_menu', 'cip_remove_menu_links', 99 );

	/**
	 * Retrieve or display list of persons as a dropdown (select list).
	 */
	function cip_dropdown_persons( $args = '' ) {

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
			'walker' => new cip_Walker_PersonDropdown
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

		//var_dump( $pages );

		if ( !empty( $pages ) ) {
			$output = '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="cip-init-chosen"' . $multiple . ' data-placeholder="' . $placeholder . '">';
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
	class cip_Walker_PersonDropdown extends Walker {

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