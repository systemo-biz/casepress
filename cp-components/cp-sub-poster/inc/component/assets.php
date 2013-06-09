<?php

	global $pagenow;

	// Register styles
	wp_register_style( $this->slug . '-frontend', $this->url . '/assets/css/frontend.css', false, $this->version, 'all' );
	wp_register_style( $this->slug . '-backend', $this->url . '/assets/css/backend.css', false, $this->version, 'all' );
	wp_register_style( $this->slug . '-codemirror', $this->url . '/assets/css/codemirror.css', false, $this->version, 'all' );

	// Register scripts
	wp_register_script( $this->slug . '-frontend', $this->url . '/assets/js/frontend.js', array( 'jquery' ), $this->version, false );

	wp_register_script( $this->slug . '-form', $this->url . '/assets/js/form.js', array( 'jquery' ), $this->version, false );
	wp_register_script( $this->slug . '-codemirror', $this->url . '/assets/js/codemirror.js', array( 'jquery' ), $this->version, false );
	wp_register_script( $this->slug . '-backend', $this->url . '/assets/js/backend.js', array( 'jquery' ), $this->version, false );

	// Enqueue admin assets
	if ( is_admin() && $pagenow == $this->settings_menu && $_GET['page'] == $this->slug ) {
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_style( $this->slug . '-codemirror' );
		wp_enqueue_style( $this->slug . '-backend' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( $this->slug . '-form' );
		wp_enqueue_script( $this->slug . '-codemirror' );
		wp_enqueue_script( $this->slug . '-backend' );
	}

	// post-new.php
	elseif ( is_admin() && isset( $_GET['csposter'] ) ) {
		wp_enqueue_script( 'jquery' );
	}

	// Enqueue front-end assets
	elseif ( !is_admin() ) {
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_style( $this->slug . '-frontend' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( $this->slug . '-frontend' );
	}
?>