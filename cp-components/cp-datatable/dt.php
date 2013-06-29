<?php


$default_atts = array(
  "posttype"=>"cases",
  "tax_slug"=>"functions",
  "tax_id"=>"0",
  "title"=>"Дела",
  "status"=>"open",
  "fields"=>"id,post_title,initiator,responsible,date_deadline,date_end,state,objects,functions,prioritet,post_date",
  "fields_name"=>"ID,Заголовок,Инициатор,Ответственный,Срок,Дата завершения,Статус,Объект,Функция,Приоритет,Дата публикации",
  "id_object"=>"",
  "group"=>"",
  "format"=>"full",
);

include_once 'datatable.php';
include_once 'wp_view_jqgrid.php';
include_once 'wp_data_jqgrid.php';

function add_wp_loop_jqgrid_scripts(){
  wp_enqueue_script('jqgrid',plugins_url('js/jquery.jqGrid.min.js',__FILE__),array('jquery'));
  wp_enqueue_script('jqgridloc',plugins_url('js/i18n/grid.locale-ru.js',__FILE__),array('jquery'));
  wp_enqueue_style('jqg_css', plugin_dir_url(__FILE__).'css/ui.jqgrid.css');
  wp_enqueue_style('jq_ui_css', plugin_dir_url(__FILE__).'css/flick/jquery-ui-1.8.14.custom.css');
} add_action('wp_enqueue_scripts', 'add_wp_loop_jqgrid_scripts');



/*
jqGrid loop function.
Attributes: posttype, tax_slug (slug of taxonomy, example: functions), tax_id (term_taxonomy_id - '0' for all terms), title (table title), status ('open' or 'all'), fields, fields_name, format, id (table css id).
Attributes for fields: id, post_title, initiator, responsible, date_deadline, date_end, state, objects, functions, prioritet, post_date.
Example:
$params = array("posstype"=>"cases","tax_slug"=>"functions","tax_id"=>"0","fields"=>"id,post_title,functions");
wp_loop_jqgrid($params);
*/
function wp_loop_jqgrid($params){
  global $default_atts, $posttype, $tax_slug, $tax_id, $title, $status, $fields, $fields_name, $id_object, $group;
  extract(shortcode_atts($default_atts, $params));
  wp_view_jqgrid($params);
}

/*
jqGrid shortcode tag.
Attributes are same as in wp_loop_jqgrid function.
Example: [wp_loop_jqgrid posttype="cases" tax_slug="functions" tax_ids="0" title="Cases" fields="id,post_title,functions"]
*/
function wp_shortcode_loop_jqgrid($params){
  global $default_atts, $posttype, $tax_slug, $tax_id, $title, $status, $fields, $fields_name;
  extract(shortcode_atts($default_atts,$params));
  wp_view_jqgrid($params);
} add_shortcode('wp_loop_jqgrid','wp_shortcode_loop_jqgrid');


/*
jqGrid shortcode for releases management.
Example: [jqgrid_releases]
*/
function jqgrid_releases_shortcode($params){
  include('wp_releases_jqgrid.php');
} add_shortcode('jqgrid_releases','jqgrid_releases_shortcode');

