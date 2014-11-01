<?php

class CasesAPISingltone {
private static $_instance = null;
private function __construct() {

    add_action("added_term_relationship", array($this, "auto_set_date_end"), 111, 2);
    add_action("deleted_term_relationships", array($this, "auto_del_date_end"), 111, 2);

    add_action( 'added_post_meta', array($this, 'add_responsible_to_members'), 11, 4 );
    add_action( 'updated_post_meta', array($this, 'add_responsible_to_members'), 11, 4 );

    add_filter('the_excerpt', array($this, 'cp_do_not_show_excerpt'), 1, 2);
}


    

// Вместо цитаты выводим краткое содержимое. Потому что цитата используется под кеш поиска данных.
function cp_do_not_show_excerpt($excerpt, $post_id){
    $data = get_post($post_id);
  return strip_tags(substr($data->post_content,0,256));
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
	
    $terms = get_terms($taxonomy, 'hide_empty=0');//get_term_by( $field, $tt_id, $taxonomy );
	foreach ($terms as $term){
		if ($term->term_taxonomy_id == $tt_id){
			$this_term = get_term_by( 'id', $term->term_id, $taxonomy );
		}
	}

    //$current_date_end = get_post_meta($post_id, $key, true);

//error_log('sdfsd - ' . print_r($this_term, true) . 'sfsdfsfdsf = ' . $this_term->taxonomy);
    if (is_object($this_term) && $this_term->taxonomy == "results"){
		update_post_meta( $post_id, 'cp_date_end', $value);
		//$r = get_post_meta($post_id, 'cp_date_end');
		//error_log("ываыва ". print_r($r, true));
    }
}

//Удаляем дату закрытие, если удаляется результат
function auto_del_date_end($object_id, $tt_id) {
	
    //get list terms from tax 'results' and find term deleted by taxonomy id
    
    $terms = get_terms('results', 'hide_empty=0');
    foreach ($terms as $term){
		if ($term->term_taxonomy_id == $tt_id[0]) delete_post_meta($object_id, 'cp_date_end');
	}
    
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





