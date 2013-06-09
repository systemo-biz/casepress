<?php

	wp_register_style( $this->slug . '-frontend', $this->assets( 'css', 'frontend.css' ), false, $this->version, 'all' );
	wp_register_script( $this->slug . '-frontend', $this->assets( 'js', 'frontend.js' ), array( 'jquery' ), $this->version, false );

	if ( !is_admin() ) {
		wp_enqueue_style( $this->slug . '-frontend' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( $this->slug . '-frontend' );
	}
?>