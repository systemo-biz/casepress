<?php

	wp_register_style( 'jquery-ui-theme-bootstrap', $this->url . '/assets/css/jquery-ui-theme-bootstrap.css', false, $this->class_version, 'all' );
	wp_register_style( $this->slug . '-general', $this->url . '/assets/css/general.css', false, $this->class_version, 'all' );

	wp_register_script( $this->slug . '-general', $this->url . '/assets/js/general.js', array( 'jquery' ), $this->class_version, false );

	wp_enqueue_style( 'jquery-ui-theme-bootstrap' );
	wp_enqueue_style( 'chosen' );
	wp_enqueue_style( $this->slug . '-general' );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-position' );
	wp_enqueue_script( 'jquery-ui-widget' );
	wp_enqueue_script( 'jquery-ui-mouse' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-resizable' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'chosen' );
	wp_enqueue_script( $this->slug . '-general' );

	wp_localize_script( $this->slug . '-general', 'cases_instant_post', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'loading' => __( 'Loading', $this->textdomain ) . '&hellip;',
		'publish' => __( 'Publish', $this->textdomain ),
		'cancel' => __( 'Cancel', $this->textdomain ),
		'message' => __( 'New message', $this->textdomain ),
		'incoming' => __( 'New incoming', $this->textdomain ),
		'full' => admin_url( '/post-new.php?post_type=cases&csposter' )
	) );
?>