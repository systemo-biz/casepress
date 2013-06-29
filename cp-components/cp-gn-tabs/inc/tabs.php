<?php

	/**
	 * Main tabs function
	 *
	 * @global type $ctabs_plugin Plugin object
	 */
	function cases_tabs() {

		global $ctabs_plugin;

		// Check single case page
		if ( get_post_type() !== 'cases' || !is_single() )
			return;

		// Prepare variables
		$tabs_list = array( );

		// Add default tab
		$tabs = array( array(
				'slug' => null,
				'name' => __( 'Case', $ctabs_plugin->textdomain )
			) );

		// Add another tabs
		$tabs = ( array ) apply_filters( 'cases_tabs', $tabs );

		// Prepare tabs list
		if ( count( $tabs ) )
			foreach ( $tabs as $tab ) {

				// Prepare permalink
				$permalink = get_permalink();
				if ( isset( $tab['slug'] ) )
					$permalink = ( strpos( $permalink, '?' ) === false ) ? $permalink . '?tab=' . $tab['slug'] : $permalink . '&tab=' . $tab['slug'];

				// Check current tab
				if ( $_GET['tab'] == $tab['slug'] )
					$tabs_list[] = '<li class="active"><a href="' . $permalink . '" onClick="return false;">' . $tab['name'] . '</a></li>';
				else
					$tabs_list[] = '<li><a href="' . $permalink . '">' . $tab['name'] . '</a></li>';
			}

		// Print tabs
		echo '<ul class="nav nav-tabs">' . implode( '', $tabs_list ) . '</ul>';
	}

	add_action( 'roots_entry_content_before', 'cases_tabs', 80 );
?>