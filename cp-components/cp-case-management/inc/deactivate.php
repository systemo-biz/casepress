<?php

	function cmmngt_deactivate_prev() {
		global $cmmngt;
		$_camech = 'cases-metabox-characteristics/cases-metabox-characteristics.php';
		$_cmsubmit = 'cases-metabox-submit/cases-metabox-submit.php';
		// Check that plugins is active
/* 		if ( is_plugin_active( $_camech ) || is_plugin_active( $_cmsubmit ) ) {
			// Deactivate previous plugins
			deactivate_plugins( array( $_camech, $_cmsubmit ), true );
			// Show message
			wp_die( str_replace( '%s', admin_url( 'plugins.php' ), __( '<h4>Warning</h4><p>Plugins <strong>Cases. Metabox Characteristics</strong> and <strong>Cases. Metabox Submit</strong> is incompatible with <strong>Cases. Metabox Management</strong>. Deprecated plugins will be automatically deactivated.<br/><br/><a href="%s">Return to plugins dashboard</a></p>', $cmmngt->textdomain ) ) );
		} */
	}

	add_action( 'wp_loaded', 'cmmngt_deactivate_prev' );
?>