<?php

	// Enqueue backend assets
	if ( is_admin() ) {
		wp_enqueue_style( 'jstree', $this->assets( 'css', 'jstree.css' ), false, $this->version, 'all' );
		wp_enqueue_style( 'select2', $this->assets( 'css', 'select2.css' ), false, $this->version, 'all' );
		wp_enqueue_style( 'casepress-chooser', $this->assets( 'css', 'chooser.css' ), false, $this->version, 'all' );
		wp_enqueue_style( $this->slug . '-backend', $this->assets( 'css', 'backend.css' ), array( 'casepress-chooser', 'jstree', 'select2' ), $this->version, 'all' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jstree', $this->assets( 'js', 'jstree.min.js' ), array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'select2', $this->assets( 'js', 'select2.js' ), array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'casepress-chooser', $this->assets( 'js', 'chooser.js' ), array( 'jquery', 'cases-flexo', 'jquery-ui-sortable', 'jstree', 'select2' ), $this->version, false );
		wp_enqueue_script( $this->slug . '-backend', $this->assets( 'js', 'backend.js' ), array( 'casepress-chooser' ), $this->version, false );
	}

	// Enqueue frontend assets
	else if ( get_post_type() === 'cases' && is_single() ) {
		wp_enqueue_style( 'jstree', $this->assets( 'css', 'jstree.css' ), false, $this->version, 'all' );
		wp_enqueue_style( 'select2', $this->assets( 'css', 'select2.css' ), false, $this->version, 'all' );
		wp_enqueue_style( 'casepress-chooser', $this->assets( 'css', 'chooser.css' ), false, $this->version, 'all' );
		wp_enqueue_style( $this->slug . '-frontend', $this->assets( 'css', 'frontend.css' ), array( 'casepress-chooser', 'jstree', 'select2' ), $this->version, 'all' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jstree', $this->assets( 'js', 'jstree.min.js' ), array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'select2', $this->assets( 'js', 'select2.js' ), array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'casepress-chooser', $this->assets( 'js', 'chooser.js' ), array( 'jquery', 'cases-flexo', 'jquery-ui-sortable', 'select2' ), $this->version, false );
		wp_enqueue_script( $this->slug . '-frontend', $this->assets( 'js', 'frontend.js' ), array( 'casepress-chooser' ), $this->version, false );
	}
?>