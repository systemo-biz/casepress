<?php

class CasesAPISingltone {
private static $_instance = null;
private function __construct() {

    //Добавляем механизм автоустановки или удаления даты закрытия при указании результата
    add_action("added_term_relationship", array($this, "auto_set_date_end"), 111, 2);
    add_action("deleted_term_relationships", array($this, "auto_del_date_end"), 11, 2);

    //Механизм добавления ответственного в участники, если его еще там нет
    add_action( 'added_post_meta', array($this, 'add_responsible_to_members'), 111, 4 );
    add_action( 'updated_post_meta', array($this, 'add_responsible_to_members'), 111, 4 );
    
    //Включаем фильтр по нарушению срока
    add_action('pre_get_posts', array($this, 'filter_cases_deadline'));

}




function filter_cases_deadline($query) {

	if(empty($_REQUEST['deadline'])) return;

	if(! $query->is_main_query()) return;

    $meta_query = $query->get('meta_query');

    $deadline = $_REQUEST['deadline'];    
    if($deadline == 'yes') {
		$meta_query[] = array(
            'key'           =>'deadline_cp',
            'value'        =>date('Ymd H:i'),
            'compare'   =>  '>=',
            'type' => 'DATETIME',
        );
	}

	$query->set('meta_query',$meta_query);

	return;

} 
  

//Добавляем ответственного в участники.
function add_responsible_to_members($meta_id, $post_id, $meta_key, $meta_value){
    
    if ($meta_key != 'responsible-cp-posts-sql') return;
	if(empty($meta_value)) return;

	$meta_members = get_post_meta($post_id, 'members-cp-posts-sql');

	if(!in_array($meta_value, $meta_members)){
        add_post_meta($post_id, 'members-cp-posts-sql', $meta_value);
    }
}



//Автоматическое добавление и удаление даты закрытия дела при указании результатов
function auto_set_date_end($object_id, $tt_id){
    //post
    //error_log('dddeee - ' . $current_date_end);

    $post_id = $object_id;
    $value = current_time("mysql");//date("Y-m-d H:i:s");

    $taxonomy = "results";
	
    $this_term = get_term_by( 'id', $tt_id, $taxonomy );
    if(empty($this_term)) return;
    /*
    $terms = get_terms($taxonomy, 'hide_empty=0');//get_term_by( $field, $tt_id, $taxonomy );
	foreach ($terms as $term){
		if ($term->term_taxonomy_id == $tt_id){
			$this_term = get_term_by( 'id', $term->term_id, $taxonomy );
		}
	}
*/
    //$current_date_end = get_post_meta($post_id, $key, true);
    update_post_meta( $post_id, 'cp_date_end', $value);

}

//Удаляем дату закрытие, если удаляется результат
function auto_del_date_end($object_id, $tt_id) {
	$taxonomy = 'results';
    //get list terms from tax 'results' and find term deleted by taxonomy id
    
    $term_id = '';
    
    
    $terms = get_terms('results', 'hide_empty=0');
    
    //Проверяем есть ли какой либо результат у поста
    $terms_in_object = wp_get_object_terms( $object_id, $taxonomy, array('fields' =>'ids') );
    if(! empty($terms_in_object)) return;
    
    //Получаем term_id из term_taxonomy_id если такое есть    
    foreach ($terms as $term){
		if ($term->term_taxonomy_id == $tt_id[0]) $term_id = $term->term_id;
	}
    
    //exit(var_dump($terms_in_object));
    if($term_id > 0) delete_post_meta($object_id, 'cp_date_end');
}







protected function __clone() {
	// ограничивает клонирование объекта
}

static public function getInstance() {
	if(is_null(self::$_instance))
	{
	self::$_instance = new self();
	}
	return self::$_instance;
}

} $CasesAPI = CasesAPISingltone::getInstance();





