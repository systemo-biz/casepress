<?php

	// Register and enqueue assets
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-widget' );
	wp_enqueue_script( 'jquery-ui-mouse' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-resizable' );
	wp_enqueue_script( 'detectmobilebrowser', $this->assets( 'js', 'detectmobilebrowser.js' ), array( 'jquery' ), $this->class_version, false );
	wp_enqueue_script( 'flexo', $this->assets( 'js', 'jquery.flexo.js' ), array( 'jquery', 'detectmobilebrowser', 'jquery-ui-draggable', 'jquery-ui-resizable' ), $this->class_version, false );
	wp_enqueue_script( 'cases-flexo', $this->assets( 'js', 'frontend.js' ), array( 'flexo' ), $this->class_version, false );

	wp_enqueue_style( 'flexo', $this->assets( 'css', 'jquery.flexo.css' ), false, $this->class_version, 'all' );
?>