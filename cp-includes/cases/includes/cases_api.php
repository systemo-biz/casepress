<?php




/*
Механизм сохранения данных контроля кейсов
*/
function save_case_data_control() {
	if(! isset($_REQUEST['update_case_control'])) return; 

	global $post;

	if(isset($_REQUEST['case_category'])) {
		$case_category = $_REQUEST['case_category'];
		error_log('$case_category = ' . $case_category);
	}

}
add_action('init', 'save_case_data_control');






/*
Механизм получения данных о персонах для поля ответственного в виджете контроля кейсов
*/

function get_person_cp(){

	if(isset($_REQUEST['s'])) $s = $_REQUEST['s'];


	$data = get_posts(array(
	    's' => $s,
	    //'paged' => $_REQUEST['page'],
	    //'posts_per_page' => $_REQUEST['page_limit'],
	    'post_type' => 'persons'
	    ));

	$elements = array();


	foreach ($data as $item) :

		$element = array(
	        'id' => $item->ID,
	        'name' => $item->post_title,
        );

		$elements[] = $element;
	endforeach;

	

	$data_echo = array(
	    'items' => $elements
	    );

	wp_send_json($data_echo);
} 

add_action('wp_ajax_get_person_cp', 'get_person_cp');


