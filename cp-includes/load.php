<?php

class CP_Include {
	function __construct(){
		add_action('wp_enqueue_scripts', array($this, 'register_ss'));
		add_action ( 'admin_enqueue_scripts', array($this, 'register_ss'));
	}
	
	function register_ss(){

		//select2 - register component files
		wp_register_script( 'select2', plugins_url( '/select2/select2.js',__FILE__ ), array(), '3.4.0', 'all');
		wp_register_style( 'select2', plugins_url( '/select2/select2.css',__FILE__ ), array(), '3.4.0', 'all' );
	}
}

$The_CP_Include = new CP_Include();
?>