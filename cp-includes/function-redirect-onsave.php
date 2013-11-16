<?php

	/**
	 * Redirect on saving post
	 */
	function cmmngt_onsave_redirect( $post_id ) {
		// Prepare data
		$r = get_post( $post_id );
		$post_status = '';
		if (isset($_POST['post_status'])) {
            $post_status = $_POST['post_status'];
        }
		$affected_post_types = array( 'cases', 'objects', 'persons', 'organizations' );
		if ( $post_status == 'publish' && in_array( $r->post_type, $affected_post_types ) ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return $post_id;
			header( 'Location: ' . get_permalink( $post_id ) );
			exit;
		}
		return $post_id;
	}

    // add condition for this hook
    if (!(esc_attr( get_option( 'enable_custom_fields_for_cases' ) ) == "1"))
        add_action( 'wp_insert_post', 'cmmngt_onsave_redirect' );
?>
