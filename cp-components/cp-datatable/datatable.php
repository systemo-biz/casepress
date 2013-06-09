<?php

include('datatable_generator.php');

function add_datatable_scripts(){

	

} add_action('wp_enqueue_scripts', 'add_datatable_scripts');

function datatable_shortcode($params){
  datatable_generator($params);
} add_shortcode('datatable','datatable_shortcode');
