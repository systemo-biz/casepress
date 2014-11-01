<?php

/*

Функции общие для всей системы

*/


// Вместо цитаты выводим краткое содержимое. Потому что цитата используется под кеш поиска данных.
function cp_do_not_show_excerpt($excerpt, $post_id){

    $data = get_post($post_id);
  	return strip_tags(mb_substr($data->post_content, 0, 256, 'UTF-8'));

} add_filter('the_excerpt', 'cp_do_not_show_excerpt', 5, 2);


//Добавляем секцию с мета данными для всех типов постов
function add_top_section_metadata_to_post($content) {

	$content =  '<div class="metadata_top_cp">' . do_action('add_metadata_to_post_cp') . '<div>' . $content;

	return $content;

}  add_filter('the_content', 'add_top_section_metadata_to_post');




//Добавляем тип поста на страницы поиска через хук the_content

function add_post_type_label_to_search_page($excerpt) {

	if(is_search()) {
		global $post;

		$post_type = get_post_type( $post );

		$obj = get_post_type_object( $post_type );

		echo  '<div><span class="label label-default">' . $obj->labels->singular_name . '</span><div>' . $excerpt;
	}


} add_action('add_metadata_to_post_cp', 'add_post_type_label_to_search_page');



//Включение скриптов и стилей для системы
class CP_Include {
	function __construct(){
		add_action('wp_enqueue_scripts', array($this, 'register_ss'));
		add_action ( 'admin_enqueue_scripts', array($this, 'register_ss'));
	}
	
	function register_ss(){

	
		wp_enqueue_style('jquery');

		wp_localize_script( 'jquery', 'cp_core', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );  

		//select2 - register component files
		wp_register_script( 'select2', plugins_url( '/select2/select2.js',__FILE__ ), array(), '3.4.0', 'all');
		wp_enqueue_script('select2');
		wp_register_style( 'select2', plugins_url( '/select2/select2.css',__FILE__ ), array(), '3.4.0', 'all' );
		wp_enqueue_style('select2');

		wp_register_script( 'moment', plugins_url( '/momentjs/moment.min.js',__FILE__ ), array(), '2.8.3', 'all', true);
		wp_enqueue_script('moment');

		wp_register_script( 'moment_ru', plugins_url( '/momentjs/locale/ru.js',__FILE__ ), array('moment'), '2.8.3', 'all', true);
		wp_enqueue_script('moment_ru');

		/*
		Rome - date time picker
		*/

		wp_register_script( 'rome_standalone', plugins_url( '/rome_js/rome.standalone.min.js',__FILE__ ), array(), '1.1.5', 'all', true);
		wp_enqueue_script('rome_standalone');

		wp_register_style( 'rome', plugins_url( '/rome_js/rome.min.css',__FILE__ ), array(), '1.1.5', 'all' );
		wp_enqueue_style('rome');

/*

 Selectize - cancel and replaced Select2

		wp_register_script( 'selectize', plugins_url( '/selectize/js/standalone/selectize.min.js',__FILE__ ), array('jquery'), '0.11.0', 'all', false);
		wp_enqueue_script('selectize');

		wp_register_style( 'selectize_css', plugins_url( '/selectize/css/selectize.bootstrap3.css',__FILE__ ), array(), '0.11.0', 'all' );
		wp_enqueue_style('selectize_css');
*/

		/*
		popModal https://github.com/vadimsva/popModal/ - for tooltip
		*/
		wp_register_script( 'popModal', plugins_url( '/popModal/popModal.min.js',__FILE__ ), array('jquery'), '28.04.14', 'all', false);
		wp_enqueue_script('popModal');

		wp_register_style( 'popModal', plugins_url( '/popModal/popModal.min.css',__FILE__ ), array(), '28.04.14', 'all' );
		wp_enqueue_style('popModal');

	}
}

$The_CP_Include = new CP_Include();




//получаем ИД персоны по почте
function get_person_id_by_email($email=null){
	if(!isset($email)) return 0;
	global $wpdb;
	$pid = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'email' AND meta_value = %s", $email));
	return (int)$pid;
}

//получаем ид юзера по ид персоны
function get_user_by_person($person_id){
	global $wpdb;
	$user_id=$wpdb->get_var("SELECT user_id FROM $wpdb->usermeta where meta_key='id_person' and meta_value='".$person_id."'");
	if (!isset($user_id)) $user_id=0;
	return $user_id;
}

//получаем ид персоны по ид юзера
function get_person_by_user($user_id){
	global $wpdb;
	$person_id=$wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta where meta_key='id_person' and user_id='".$user_id."'");
	if (!isset($person_id)) $person_id=0;
	return $person_id;
} 
	
//не понятно
function get_object_taxs($object_id)
{
	global $wpdb;
	$term_r = 'term_relationships';
	$term_r = $wpdb->prefix . $term_r;
	$term_tax=$wpdb->get_results('SELECT * FROM '.$term_r.' WHERE object_id="'.$object_id.'" ', ARRAY_A);
	
	$exist_terms = array();
	if (!empty($term_tax))
		foreach ($term_tax as $tt)
			$exist_terms[] = $tt['term_taxonomy_id'];
				
	return $exist_terms;
}
	
	
	function get_tax_tree($parent, $lvl,$tax) 
	{ 
	
		$args = array(  
			'number'        => 0   
			,'hide_empty'   => false  
			,'hierarchical' => true  
			,'child_of'     => ''  
			,'parent'       => $parent  
		);  
  
		$mass = get_terms($tax, $args); 
		if (count($mass)>0)
		{
			if ($lvl==0){
				echo '<ul>'; 
			}
			else {
				echo "<ul>";
			}

			foreach ($mass as $term)
			{
				echo '<li term_id="'.$term->term_id.'">';
				echo '<a>'.$term->name.'</a>';
				$lvl++;
				get_tax_tree($term->term_id, $lvl,$tax); 
				$lvl--;
				
				echo "</li>";
			}
			echo "</ul>";
		}

	}
	
	
		/**
	 * Convert any date to readable format
	 */
	function cases_pretty_date( $date ) {

		if ( !empty( $date ) ) {
			$time = strtotime( $date );
			$result = date( 'j.m.Y', $time );
		}

		return $result;
	}

	/**
	 * Convert any date to readable format
	 */
	function cases_pretty_datetime( $datetime ) {

		if ( !empty( $datetime ) ) {
			$time = strtotime( $datetime );
			$result = date( 'j.m.Y, G:i', $time );
		}

		return $result;
	}

