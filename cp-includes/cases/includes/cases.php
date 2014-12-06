<?php



class CasesModelSingltone {
private static $_instance = null;

private function __construct() {


    add_action('cp_activate', array($this, 'register_cases_post_type'));	
    add_action('init', array($this, 'register_cases_post_type'));
    
    add_action( 'admin_menu', array($this, 'remove_cases_metabox'));
    add_action('wp', array('enable_comment_for_case_cp'));

    add_filter('post_type_link', array($this, 'cases_post_type_link'), 10, 2);
}



function register_cases_post_type() {

        $n = array('Дело', 'Дела', 'Дел');//in next versions this variable need move to options WP

      $labels = array(
        'name' => $n[1],
        'singular_name' => $n[0],
        'add_new' 			=> 'Добавить',
        'add_new_item' 		=> 'Добавить '.$n[0],
        'edit_item' 		=> 'Редактировать '.$n[0],
        'new_item' 			=> 'Новое '.$n[0],
        'view_item' 		=> 'Просмотр '.$n[1],
        'search_items' 		=> 'Поиск '.$n[1],
        'not_found' 		=> $n[0].' не найдено',
        'not_found_in_trash'=> 'В корзине '.$n[0].' не найдено',
        'parent_item_colon' => ''
        );

      $supports = array(
        'editor',
        'comments',
        'title'
         );

      //add custom-fields, if it is enable
      if (get_option( 'enable_custom_fields_for_cases' )) $supports[]="custom-fields";
      if (get_option( 'enable_custom_fields_for_cases' )) $supports[]="excerpt";
      
      $args = array(
        'labels' => $labels,
        'singular_label' => $n[0],
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'cases', 'with_front' => false ),
        'supports' => $supports,
        'menu_position' => 5,
        //'taxonomies' => $taxonomies
      );

    register_post_type('cases',$args);
    add_rewrite_rule(
        'cases/([0-9]+)?$',
        'index.php?post_type=cases&p=$matches[1]',
        'top' );
} 

    
function cases_post_type_link( $link, $post = 0 ){
    if ( $post->post_type == 'cases' ){
        return home_url( 'cases/' . $post->ID );
    } else {
        return $link;
    }
}


function gf_cases_rewrite(){
  global $wp_rewrite;
  $wp_rewrite->add_rewrite_tag('%cases_id%', '([^/]+)', 'post_type=cases&p=');
  $wp_rewrite->add_permastruct('cases', '/cases/%cases_id%', false);
  
} 



function remove_cases_metabox() {
  remove_meta_box('functionsdiv', 'cases', 'side');
  remove_meta_box('tagsdiv-navigation', 'cases', 'side');
  remove_meta_box('tagsdiv-results', 'cases', 'side');
  remove_meta_box('tagsdiv-state', 'cases', 'side');  
  remove_meta_box('commentsdiv', 'cases', 'side');
  //remove_meta_box('commentstatusdiv', 'cases', 'side');  
}
    
// Если есть шорткод, то включаем пагинацию комментов и выключаем комментарии у самой страницы
function enable_comment_for_case_cp(){
    global $post;
    if('cases' == $post->post_type)
        add_filter( 'comments_open', '__return_true' );
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

} $CasesModel = CasesModelSingltone::getInstance();

