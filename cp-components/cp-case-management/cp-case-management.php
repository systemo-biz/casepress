<?php

	// Create component instanse
	$cmmngt = new CasePress_Component( __FILE__ );

/* 	$cmmngt->add_settings_page( array(
		'parent' => 'options-general.php',
		'page_title' => __( 'CasePress. Metabox Management Settings', $cmmngt->textdomain ),
		'menu_title' => __( 'Metabox Management', $cmmngt->textdomain )
	) ); */

	// Include plugin actions
	require_once 'inc/deactivate.php';
	require_once 'inc/helpers.php';
	require_once 'inc/metabox.php';
	require_once 'inc/ajax.php';
	require_once 'inc/save.php';
	require_once 'inc/actions.php';
	//require_once 'inc/navbar.php';
	require_once 'inc/remove.php';
	require_once 'inc/redirect.php';
	require_once 'inc/button-extra.php';
?>