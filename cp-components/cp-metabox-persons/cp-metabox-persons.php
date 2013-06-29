<?php

	/*
	  Plugin Name: CasePress. Metabox Persons
	  Plugin URI: http://casepress.org/
	  Version: 1.1.1
	  Author: Vladimir Anokhin
	  Author URI: http://gndev.info/
	  Description: Adds persons management metabox
	  Text Domain: casepress-metabox-persons
	  Domain Path: /languages
	  License: GPL3
	 */

	// Include CasePress Chooser class
	require_once 'classes/casepress-chooser.class.php';
	// Create plugin instance
	$cmp_plugin = new CasePress_Component( __FILE__ );
	// Create Chooser instance
	$cmp_chooser = new CasePress_Chooser( array(
			'auto_load' => false,
			'trigger' => false,
			'post_type' => 'persons',
			'textdomain' => $cmp_plugin->textdomain,
			'childs' => true,
			'actions' => '<span class="btn button cmp-cancel">' . __( 'Cancel', $cmp_plugin->textdomain ) . '</span> <span class="btn btn-primary button button-primary cmp-ok">' . __( 'Ok', $cmp_plugin->textdomain ) . '</span>'
			) );
	// Include helpers
	require_once 'inc/helpers.php';
	// Include metabox
	require_once 'inc/metabox.php';
	// AJAX actions
	require_once 'inc/ajax.php';
	// Make plugin meta translatable
	__( 'CasePress. Metabox Persons', $cmp_plugin->textdomain );
	__( 'Vladimir Anokhin', $cmp_plugin->textdomain );
	__( 'Adds persons management metabox', $cmp_plugin->textdomain );
?>