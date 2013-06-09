<?php

	// Enqueue backend assets
	if ( is_admin() && get_post_type() == 'life_cycle' ) {
		wp_enqueue_style( 'select2', $this->assets( 'css', 'select2.css' ), false, $this->class_version, 'all' );
		wp_enqueue_style( $this->slug . '-backend', $this->assets( 'css', 'backend.css' ), false, $this->class_version, 'all' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'select2', $this->assets( 'js', 'select2.js' ), array( 'jquery', 'jquery-ui-sortable' ), $this->class_version, false );
		wp_enqueue_script( $this->slug . '-backend', $this->assets( 'js', 'backend.js' ), array( 'select2' ), $this->class_version, false );
	}
?>