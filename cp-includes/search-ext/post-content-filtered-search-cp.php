<?php
/*
Функция - кеширование данных в цитату, чтобы затем искать по ним. Например это может быть значение какой либо меты. К примеру ответственный - чтобы затем дела находились по этому ответственному.
*/


//Механизм, который добавляет фильтр для записи поискового кеша
function post_content_filtered_search_array_cp($data, $postarr){

	$search_array = array(); //создаем массив
	$search_array = apply_filters('cp_search_array', $search_array, $data, $postarr); //включаем фильтр
//var_dump($search_array);
	if(empty($search_array)){
		return $data;
	} else {
		$search_array = array_unique($search_array); //уникализируем
		$search_str = implode(",", $search_array);
		$data['post_content_filtered'] = $search_str ; //пишем строку в поисковый кеш
	}

	return $data;

} add_filter('wp_insert_post_data', 'post_content_filtered_search_array_cp', 999, 2);






//Добавляем в поисковый кеш адрес эл почты организации
function search_array_cp_email_organization($search_array, $data, $postarr){

	//получаем адрес email для персоны и помещаем в переменную $meta;
	if($data['post_type'] != 'organizations') return $search_array;
	$post_id = $postarr['ID'];
	$search_array[] = get_post_meta($post_id, 'email', true); //добавляем в массив данное

	return $search_array;

} add_filter('cp_search_array', 'search_array_cp_email_organization', 10, 3);




//Добавляем в поисковый кеш телефон организации
function search_array_cp_tel_organization($search_array, $data, $postarr){

	//получаем адрес email для персоны и помещаем в переменную $meta;
	if($data['post_type'] != 'organizations') return $search_array;
	$post_id = $postarr['ID'];
	$search_array[] = get_post_meta($post_id, 'tel', true); //добавляем в массив данное

	return $search_array;

} add_filter('cp_search_array', 'search_array_cp_tel_organization', 10, 3);




//Добавляем в поисковый кеш адрес эл почты персоны
function search_array_cp_email_person($search_array, $data, $postarr){

	//получаем адрес email для персоны и помещаем в переменную $meta;
	if($data['post_type'] != 'persons') return $search_array;
	$post_id = $postarr['ID'];
	$search_array[] = get_post_meta($post_id, 'email', true); //добавляем в массив данное

	return $search_array;

} add_filter('cp_search_array', 'search_array_cp_email_person', 10, 3);



//Добавляем в поисковый кеш телефон персоны
function search_array_cp_tel_person($search_array, $data, $postarr){

	//получаем адрес email для персоны и помещаем в переменную $meta;
	if($data['post_type'] != 'persons') return $search_array;
	$post_id = $postarr['ID'];
	$search_array[] = get_post_meta($post_id, 'tel', true); //добавляем в массив данное

	return $search_array;

} add_filter('cp_search_array', 'search_array_cp_tel_person', 10, 3);



//Добавляем в поисковый кеш категорию дела
function search_array_cp_case_category($search_array, $data, $postarr){

	//получаем адрес email для персоны и помещаем в переменную $meta;
	if($data['post_type'] != 'cases') return $search_array;
	$post_id = $postarr['ID'];
	$term = wp_get_object_terms( $post_id, 'functions' );


	$search_array[] = $term[0]->name;

	return $search_array;

} add_filter('cp_search_array', 'search_array_cp_case_category', 10, 3);


//Добавляем в поисковый кеш наименование поля От
function search_array_cp_case_from($search_array, $data, $postarr){

	//получаем адрес email для персоны и помещаем в переменную $meta;
	if($data['post_type'] != 'cases') return $search_array;
	$post_id = $postarr['ID'];
	$from_id = get_post_meta($post_id, 'cp_from', true);

	if($from_id) {
		$from_title = get_the_title($from_id);
		$search_array[] = $from_title;
	}

	return $search_array;

} add_filter('cp_search_array', 'search_array_cp_case_from', 10, 3);



//Добавляем в поисковый кеш наименование поля Адреса
function search_array_cp_case_to($search_array, $data, $postarr){

	//получаем адрес email для персоны и помещаем в переменную $meta;
	if($data['post_type'] != 'cases') return $search_array;
	$post_id = $postarr['ID'];
	$to_id = get_post_meta($post_id, 'cp_to', true);

	if($to_id) {
		$title = get_the_title($to_id);
		$search_array[] = $title;
	}

	return $search_array;

} add_filter('cp_search_array', 'search_array_cp_case_to', 10, 3);



//Добавляем в поисковый кеш список Участников
function search_array_cp_case_members($search_array, $data, $postarr){

	if($data['post_type'] != 'cases') return $search_array;
	$post_id = $postarr['ID'];
	$metadata = get_post_meta($post_id, 'members-cp-posts-sql');

	if(is_array($metadata)) {
		foreach ($metadata as $value) {
			$title = get_the_title($value);
			$search_array[] = $title;
		}
	}


	return $search_array;

} add_filter('cp_search_array', 'search_array_cp_case_members', 10, 3);
