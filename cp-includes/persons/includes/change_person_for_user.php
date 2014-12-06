<?php
/*
Plugin Name: pl-for-casepress3
Plugin URI: http://casepress.org
Description: Добавляет возможность выбора персоны для настройки профиля
Version: 1.0
Author: Dmitry
*/

add_action( 'show_user_profile', 'cp_case_field_id' );
add_action( 'edit_user_profile', 'cp_case_field_id' );
function cp_case_field_id ($user) {
	if ( !current_user_can( 'edit_user', $user->ID ) ) { return false; }
	
	echo "<div><label for=\"select_person\">Изменить персону<br>";
	$args = array(
			'id' => 'select_person',	
			'selected' => get_user_meta( $user->ID, 'id_person', true),
			'name' => 'person',
			'post_type' => 'persons'
			);

	wp_dropdown_pages( $args );
	echo "</label></div>";
}

add_action( 'personal_options_update', 'save_cp_case_field_id' );
add_action( 'edit_user_profile_update', 'save_cp_case_field_id' );
function save_cp_case_field_id( $user_id ) {
 
	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

	update_user_meta( $user_id, 'id_person', $_POST['person'] );
}

?>