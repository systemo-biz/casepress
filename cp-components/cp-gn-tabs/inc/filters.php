<?php

	/**
	 * Content filter to display custom tabs content
	 */
	function ctabs_content_filter( $content ) {

		// Check single case page
		if ( get_post_type() === 'cases' && is_single() && isset( $_GET['tab'] ) )
			$content = ( function_exists( 'cases_tab_content_' . $_GET['tab'] ) ) ? call_user_func( 'cases_tab_content_' . $_GET['tab'] ) : '';

		return $content;
	}

	add_filter( 'the_content', 'ctabs_content_filter' );
?>