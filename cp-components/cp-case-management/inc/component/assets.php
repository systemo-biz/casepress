<?php

	global $pagenow;

	// Enqueue frontend assets
	if ( !is_admin() ) {

		// Stylesheets
		wp_enqueue_style( 'jquery-ui', $this->assets( 'css', 'jquery-ui.css' ), false, '1.9.2', 'all' );
		wp_enqueue_style( 'select2', $this->assets( 'css', 'select2.css' ), false, $this->class_version, 'all' );
		wp_enqueue_style( 'editable', $this->assets( 'css', 'editable.css' ), false, $this->class_version, 'all' );
		wp_enqueue_style( 'cmmngt-frontend', $this->assets( 'css', 'frontend.css' ), array( 'roots_bootstrap_style' ), $this->class_version, 'all' );

		// Scripts
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-datepicker-locale', $this->assets( 'js', 'datepicker-locale.js' ), array( 'jquery-ui-datepicker' ), $this->class_version, false );
		wp_enqueue_script( 'moment', $this->assets( 'js', 'moment.js' ), array( 'jquery' ), $this->class_version, false );
		wp_enqueue_script( 'select2', $this->assets( 'js', 'select2.js' ), array( 'jquery' ), $this->class_version, false );
		wp_enqueue_script( 'editable', $this->assets( 'js', 'editable.js' ), array( 'jquery', 'select2', 'jquery-ui-datepicker-locale', 'moment', 'jquery-ui-button' ), $this->class_version, false );
		wp_enqueue_script( 'cmmngt-frontend', $this->assets( 'js', 'frontend.js' ), array( 'jquery', 'editable' ), $this->class_version, false );

		// Localization
		wp_localize_script( 'cmmngt-frontend', 'cmmngt', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			's2' => array(
				'nomatches' => __( 'No matches %s', $this->textdomain ),
				'searching' => __( 'Searching...', $this->textdomain ),
				'tooshort' => __( 'Keep typing (%n)...', $this->textdomain )
			)
		) );
	}

	// Enqueue backend assets
	elseif ( is_admin() && in_array( $pagenow, cmmngt_affected_pages() ) ) {

		// Stylesheets
		wp_enqueue_style( 'jquery-ui', $this->assets( 'css', 'jquery-ui.css' ), false, '1.9.2', 'all' );
		wp_enqueue_style( 'select2', $this->assets( 'css', 'select2.css' ), false, $this->class_version, 'all' );
		wp_enqueue_style( 'cmmngt-bootstrap', $this->assets( 'css', 'bootstrap.min.css' ), false, $this->class_version, 'all' );
		wp_enqueue_style( 'cmmngt-backend', $this->assets( 'css', 'backend.css' ), false, $this->class_version, 'all' );

		// Scripts
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-datepicker-locale', $this->assets( 'js', 'datepicker-locale.js' ), array( 'jquery-ui-datepicker' ), $this->class_version, false );
		wp_enqueue_script( 'select2', $this->assets( 'js', 'select2.js' ), array( 'jquery' ), $this->class_version, false );
		wp_enqueue_script( 'cmmngt-bootstrap', $this->assets( 'js', 'bootstrap.min.js' ), array( 'jquery' ), $this->class_version, false );
		wp_enqueue_script( 'cmmngt-backend', $this->assets( 'js', 'backend.js' ), array( 'jquery', 'select2', 'jquery-ui-datepicker-locale' ), $this->class_version, false );

		// Localization
		wp_localize_script( 'cmmngt-backend', 'cmmngt', array(
			's2' => array(
				'nomatches' => __( 'No matches %s', $this->textdomain ),
				'searching' => __( 'Searching...', $this->textdomain ),
				'tooshort' => __( 'Keep typing (%n)...', $this->textdomain )
			)
		) );
	}
	// Navbar styles
	if ( is_admin() && is_admin_bar_showing() ) {
		wp_enqueue_style( 'navbar', $this->assets( 'css', 'navbar.css' ), false, $this->class_version, 'all' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'navbar', $this->assets( 'js', 'navbar.js' ), array( 'jquery' ), $this->class_version, false );
	}

	/**
	 * Deregster datepicker provided by ACF
	 */
	function cmmngt_deregister_assets() {
		// ACF
		wp_deregister_script( 'acf-datepicker' );
		wp_deregister_style( 'acf-datepicker' );
	}

	add_action( 'wp_print_scripts', 'cmmngt_deregister_assets', 100 );
?>