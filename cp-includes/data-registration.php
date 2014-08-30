<?php

function date_registration($post) {
	echo "Дата регистрации:<br>";
	echo the_time ('d.m.Y');
}
add_action( 'add_field_for_case_aside_parameters', 'date_registration');

function cp_register_fields(){
  include_once('acf-repeater/repeater.php');
} add_action('acf/register_fields', 'cp_register_fields');