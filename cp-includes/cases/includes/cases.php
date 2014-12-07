<?php



class CasesModelSingltone {
private static $_instance = null;

private function __construct() {


    add_action('cp_activate', array($this, 'register_cases_post_type'));	
    add_action('init', array($this, 'register_cases_post_type'));

    add_action('cp_activate', array($this, 'register_results_tax'));	
    add_action('init', array($this, 'register_results_tax'));
    
    add_action('cp_activate', array($this, 'register_functions_tax'));	
    add_action('init', array($this, 'register_functions_tax'));

    add_filter('post_type_link', array($this, 'cases_post_type_link'), 10, 2);

    add_filter( 'comments_open', array($this, 'enable_comment_for_case_cp'), 10, 2);
    add_action( 'admin_menu', array($this, 'remove_cases_metabox'));
    add_action( 'admin_menu' , array($this, 'remove_result_mb_cases') );  
}


function register_functions_tax() {

$n = array('Категория дел', 'Категории дел', 'Категорию дел');//in next versions this variable need move to options WP

  $labels = array(
    'name' => $n[1],
    'singular_name' => $n[0],
    'add_new' => 'Добавить',
    'add_new_item' => 'Добавить '.$n[2],
    'edit_item' => 'Редактировать '.$n[2],
    'new_item' => 'Новая '.$n[0],
    'view_item' => 'Просмотр '.$n[1],
    'search_items' => 'Поиск '.$n[1],
    'not_found' => $n[0].' не найдена',
    'not_found_in_trash' => 'В Корзине '.$n[0].' не найдена',
    );

  $pages = array('cases', 'wiki');

  $args = array(
    'labels' => $labels,
    'singular_label' => $n[0],
    'public' => true,
    'show_ui' => true,
    'hierarchical' => true,
    'show_tagcloud' => true,
    'show_in_nav_menus' => true,
    'rewrite' => array('slug' => 'functions', 'with_front' => false ),
  );

  register_taxonomy('functions', $pages, $args);
} 
    
    
function register_results_tax() {
	$labels = array(
		'name' 					=> 'Результаты',
		'singular_name' 		=> 'Результат',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Результат',
		'edit_item' 			=> 'Редактировать Результат',
		'new_item' 				=> 'Новый Результат',
		'view_item' 			=> 'Просмотр Результата',
		'search_items' 			=> 'Поиск Результата',
		'not_found' 			=> 'Результат не найден',
		'not_found_in_trash' 	=> 'В Корзине Результат не найден',
	);
	
	$post_types = array('cases', 'process');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Результат',
		'public' 			=> false,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'results', 'with_front' => false ),
	 );
	register_taxonomy('results', $post_types, $args);    
}


function remove_result_mb_cases() {  
    remove_meta_box( 'resultsdiv' , 'cases' , 'side' );  
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
  remove_meta_box('commentstatusdiv', 'cases', 'side');  
}
    
// Включаем комменты для дел всегда
function enable_comment_for_case_cp($open, $post_id){
    if('cases' == get_post_type($post_id))
        $open = __return_true();
    return $open;
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

