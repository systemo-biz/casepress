<?php

	/**
	 * Register post type to save data in these posts
	 */
	function ctmeta_register_post_type() {

		// Get plugin object
		global $ctmeta_plugin;

		// Args
		$args = array(
			'labels' => array( 'name' => __( 'CTmeta post type', $ctmeta_plugin->textdomain ) ),
			'public' => false,
			'supports' => array( 'title', 'custom-fields' )
		);

		// Register post type
		register_post_type( ctmeta_settings( 'post_type_name' ), $args );
	}

	add_action( 'init', 'ctmeta_register_post_type' );
?>