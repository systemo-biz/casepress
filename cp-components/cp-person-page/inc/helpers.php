<?php

	// Add post thumnails support to Persons post type
	add_post_type_support( 'persons', array( 'thumbnail' ) );
	// Add image size for persons
	add_image_size( 'ckpp-person-image', 200, 9999, true );

	/**
	 * Filter to hide person page content
	 */
	function ckpp_hide_person_content( $content ) {
		if ( get_post_type() == 'persons' )
			$content = '';
		return $content;
	}

	add_filter( 'the_content', 'ckpp_hide_person_content' );
?>