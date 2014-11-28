<?php

//Редирект с главной страницы на страницу персоны
//В будущем этот функционал мб стоит расширить

function redirect_from_main_page() {


	if(is_front_page()) {
		
		$user_id = get_current_user_id();
		
		$person_id = get_person_by_user($user_id);
		
		$person_url = get_permalink($person_id);

		exit( wp_redirect( $person_url ) );				
	}

} add_action('template_redirect', 'redirect_from_main_page');
