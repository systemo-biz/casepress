<?php

//Тут прописана логика загрузки сайдбаров через хук sidebar_cp
// Этот хук должен быть вставлен в шаблоне sidebar.php темы. Или в другом аналогичном месте

add_action('sidebar_cp', 'view_sidebars_cp');

function view_sidebars_cp(){

	if(is_post_type_archive('organizations') or is_singular('organizations')) {
		dynamic_sidebar( 'organizations' );
	} elseif (is_post_type_archive('cases') or is_singular('cases')) {
		dynamic_sidebar( 'cases' );
	} elseif (is_post_type_archive('report') or is_singular('report')) {
		dynamic_sidebar( 'report' );
	} elseif (is_post_type_archive('objects') or is_singular('objects')) {
		dynamic_sidebar( 'objects' );
	} elseif (is_post_type_archive('persons') or is_singular('persons')) {
		dynamic_sidebar( 'persons' );
	} else {
		dynamic_sidebar( apply_filters('change_default_sidebar_cp', 'commone') );
	}


}