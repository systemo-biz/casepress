<?php

	global $pagenow;

	// Register styles
	wp_register_style( 'chosen', $this->url . '/assets/css/chosen.css', false, $this->class_version, 'all' );
/*	wp_register_style( 'jquery-ui-lightness-datepicker', 'http://jquery-ui.googlecode.com/svn/tags/1.8.20/themes/ui-lightness/jquery.ui.datepicker.css', false, $this->version, 'all' );*/
	wp_register_style( $this->slug . '-backend', $this->url . '/assets/css/backend.css', false, $this->class_version, 'all' );

	// Register scripts
	wp_register_script( 'chosen', $this->url . '/assets/js/chosen.jquery.min.js', array( 'jquery' ), $this->class_version, false );
	wp_register_script( $this->slug . '-backend', $this->url . '/assets/js/backend.js', array( 'jquery', 'chosen' ), $this->class_version, false );
        wp_register_script('autohide',$this->url . '/assets/js/autohide.js',array('jquery'),$this->class_version,false);

	// Enqueue backend assets
	if ( is_admin() && $pagenow == 'edit-tags.php' ) {
		wp_enqueue_style( 'chosen' );
		wp_enqueue_style( 'jquery-ui-lightness-datepicker' );
		wp_enqueue_style( $this->slug . '-backend' );                
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'chosen' );
		wp_enqueue_script( 'datepicker' );
		wp_enqueue_script( $this->slug . '-backend' );
	}
        if ($pagenow == 'post-new.php' && $_GET['post_type']=='cases'){
            wp_enqueue_script('autohide');
            wp_localize_script('autohide', 'autohide', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ));
        }
?>