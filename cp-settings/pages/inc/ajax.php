<?php


	add_action( 'wp_ajax_cp_settings_pages_save', 'cp_settings_pages_save' );	
	function cp_settings_pages_save()
	{
		update_option('cp_settings_cases_page',$_POST['cp_cases_page']);
		echo 'Настройки обновлены';
		die;
	}
	
	
?>