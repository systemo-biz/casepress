<?php

/*
Создаем отчеты и связанные таксономии
*/
class ReportsModelSingltone {
private static $_instance = null;

private function __construct() {
    add_action('cp_activate', array($this, 'register_report_post_type_cp'));	
    add_action('init', array($this, 'register_report_post_type_cp'));
    
    add_action('cp_activate', array($this, 'register_report_tax'));	
    add_action('init', array($this, 'register_report_tax'));  
}


function register_report_post_type_cp(){
  $labels = array(
    'name'=>'Отчеты',
    'singular_name'=>'Отчет',
    'add_new'=>'Добавить',
    'add_new_item'=>'Добавить отчет',
    'edit_item'=>'Редактировать отчет',
    'new_item'=>'Новый отчет',
    'view_item'=>'Просмотр отчета',
    'search_items'=>'Поиск отчета',
    'not_found'=>'Отчет не найден',
    'parent_item_colon'=>''
  );

   $supports = array(
    'editor',
    'title'
     );

  //add custom-fields, if it is enable
  if (get_option( 'enable_custom_fields_for_cases' )) $supports[]="custom-fields";
  
  
  register_post_type('report', array(
    'label'=>$labels['singular_name'],
    'labels'=>$labels,
    'public'=>true,
    //'hierarchical'=>true,
	 'rewrite' => array(
      'slug'                => 'reports',
      'with_front'          => true,
      'pages'               => true,
      'feeds'               => false,
    ),
    'supports'=> $supports,
    'taxonomies'=>array(),
    'has_archive'         => true,
    'query_var'=>true,
    'menu_position'=>10,
  ));
}
    
function register_report_tax() {
	$labels = array(
		'name' 					=> 'Категория отчетов',
		'singular_name' 		=> 'отчет',
		'add_new' 				=> 'Добавить',
		'add_new_item' 			=> 'Добавить Категорию отчетов',
		'edit_item' 			=> 'Редактировать Категорию отчетов',
		'new_item' 				=> 'Новый Категория отчетов',
		'view_item' 			=> 'Просмотр Категории отчетов',
		'search_items' 			=> 'Поиск Категории отчетов',
		'not_found' 			=> 'Категория отчетов не найденя',
		'not_found_in_trash' 	=> 'В Корзине категория не найдена',
	);
	
	$pages = array('report','chart_report');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> 'Категории отчетов',
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> true,
		'show_in_nav_menus' => true,
		'rewrite' 			=> array('slug' => 'report_cat', 'with_front' => false ),
	 );
	register_taxonomy('report_cat', $pages, $args);
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

} $ReportsModel = ReportsModelSingltone::getInstance();

