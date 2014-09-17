<?php

class CP_Include {
	function __construct(){
		add_action('wp_enqueue_scripts', array($this, 'register_ss'));
		add_action ( 'admin_enqueue_scripts', array($this, 'register_ss'));
	}
	
	function register_ss(){

		wp_enqueue_style('jquery');

		//select2 - register component files
		wp_register_script( 'select2', plugins_url( '/select2/select2.js',__FILE__ ), array(), '3.4.0', 'all');
		wp_register_style( 'select2', plugins_url( '/select2/select2.css',__FILE__ ), array(), '3.4.0', 'all' );

		wp_register_script( 'moment', plugins_url( '/momentjs/moment.min.js',__FILE__ ), array(), '2.8.3', 'all', true);
		wp_enqueue_script('moment');

		wp_register_script( 'moment_ru', plugins_url( '/momentjs/locale/ru.js',__FILE__ ), array('moment'), '2.8.3', 'all', true);
		wp_enqueue_script('moment_ru');

		wp_register_script( 'rome_standalone', plugins_url( '/rome_js/rome.standalone.min.js',__FILE__ ), array(), '1.1.5', 'all', true);
		wp_enqueue_script('rome_standalone');

		wp_register_style( 'rome', plugins_url( '/rome_js/rome.min.css',__FILE__ ), array(), '1.1.5', 'all' );
		wp_enqueue_style('rome');

		wp_register_script( 'selectize', plugins_url( '/selectize/js/standalone/selectize.min.js',__FILE__ ), array('jquery'), '0.11.0', 'all', false);
		wp_enqueue_script('selectize');

		wp_register_style( 'selectize_css', plugins_url( '/selectize/css/selectize.bootstrap3.css',__FILE__ ), array(), '0.11.0', 'all' );
		wp_enqueue_style('selectize_css');


	}
}

$The_CP_Include = new CP_Include();
?>