<?php

	/**
	 * Redirect on saving post
	 */
	function cmmngt_onsave_redirect( $post_id ) {
		// Prepare data
		global $post;
		$post_status = $_POST['post_status'];
		$affected_post_types = array( 'cases', 'objects', 'persons', 'organizations' );
		if ( $post_status == 'publish' && in_array( $post->post_type, $affected_post_types ) ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return $post_id;
			header( 'Location: ' . get_permalink( $post_id ) );
			exit;
		}
		return $post_id;
	}

	add_action( 'wp_insert_post', 'cmmngt_onsave_redirect' );
?>