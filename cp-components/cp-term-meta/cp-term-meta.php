<?php

	// Create component instanse
	$ctmeta_plugin = new CasePress_Component( __FILE__ );

	/**
	 * Add link to plugin actions "Saved data"
	 */
	function ctmeta_plugin_action_links( $links ) {
		global $ctmeta_plugin;
		$links[] = '<a href="' . admin_url( 'edit.php?post_type=ctmeta_post' ) . '">' . __( 'Saved data', $ctmeta_plugin->textdomain ) . '</a>';
		return $links;
	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ctmeta_plugin_action_links', -10 );
	// Helpers (save/get meta etc.)
	require_once 'inc/helpers.php';
	// Register cutom post type
	require_once 'inc/post-type.php';
	// Add meta fields to add/edit term forms
	require_once 'inc/meta-fields.php';
	// Save meta data
	require_once 'inc/save.php';
	// Fill title by default template
	require_once 'inc/title-template.php';
	// Apply default responsible by term meta
	require_once 'inc/default-responsible.php';
	// Apply default deadline by term meta
	require_once 'inc/default-deadline.php';
	//Auto hide titles and content editboxes 
	require_once 'inc/autohide.php';
?>