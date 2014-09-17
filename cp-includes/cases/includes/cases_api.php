<?php

add_action('wp_ajax_get_person_cp', 'get_person_cp');

function get_person_cp(){

	$data = get_posts(array(
	    //'s' => $_REQUEST['q'],
	    //'paged' => $_REQUEST['page'],
	    //'posts_per_page' => $_REQUEST['page_limit'],
	    'post_type' => 'persons'
	    ));

	$elements = array();


	foreach ($data as $item) :

		$organization = '';

		$elements[] = array(
	        'id' => $item->ID,
	        'title' => $item->post_title,
	        'organization' => $organization
	        );
	endforeach;

	

	$data_echo = array(
	    "total" => 1,//(int)$query->found_posts, 
	    'items' => $elements);
	//$data[] = $query;
	

	//$data_echo = array()$data;
	echo json_encode($data_echo);
	//var_dump($elements);
	//echo "string";
	exit;
}