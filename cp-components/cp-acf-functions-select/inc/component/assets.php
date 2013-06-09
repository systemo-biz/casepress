<?php

	global $pagenow;

	if ( is_admin() && in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {
		wp_register_script( $this->slug . '-backend', $this->url . '/assets/js/backend.js', array( 'jquery' ), $this->class_version, true );
		wp_enqueue_script( $this->slug . '-backend' );
	}
?>