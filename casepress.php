<?php

/*
  Plugin Name: CasePress
  Plugin URI: http://casepress.org
  Description: Adaptive Case Managment System based on WordPress
  Author: CasePress
  Author URI: http://casepress.org
  Version: 0.5beta20130610
*/
	 
	include_once 'cp-includes/load.php';
	include_once 'cp-core/core-includes.php';
	include_once 'cp-components/components-includes.php';
	include_once 'cp-templates/template-include.php';
	include_once 'cp-includes/class-cp-box-case-managemen/class-cp-box-case-management.php';
	include_once 'cp-includes/function-redirect-onsave.php';

	register_activation_hook( __FILE__, 'cp_activation' );
	function cp_activation() {
		do_action( 'cp_activate', $func );
	}

	register_deactivation_hook( __FILE__, 'cp_deactivation' );
	function cp_deactivation() {
		do_action( 'cp_deactivate', $func );
	}
?>