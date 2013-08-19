<?php

function date_registration($post) {
	echo "Дата регистрации:<br>";
	echo the_time ('d.m.Y');
}
add_action( 'add_field_for_case_aside_parameters', 'date_registration');
?>