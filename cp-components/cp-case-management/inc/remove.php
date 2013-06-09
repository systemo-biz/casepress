<?php

	/**
	 * Remove unused metaboxes
	 */
	function cmmngt_remove_metaboxes() {
		remove_meta_box( 'authordiv', 'cases', 'normal' );
		remove_meta_box( 'marketing_channelsdiv', 'cases', 'side' );
		remove_meta_box( 'pageparentdiv', 'cases', 'side' );
		remove_meta_box( 'submitdiv', 'cases', 'side' );
		remove_meta_box( 'tagsdiv-labels', 'cases', 'side' );
		remove_meta_box( 'slugdiv', 'cases', 'normal' );
		remove_meta_box( 'slugdiv', 'cases', 'side' );
		remove_meta_box( 'commentstatusdiv', 'cases', 'normal' );
	}

	add_action( 'admin_menu', 'cmmngt_remove_metaboxes' );
?>