<?php

function register_chart_report_posttype(){
  $labels = array(
    'name'=>'Chart отчеты',
    'singular_name'=>'Chart отчет',
    'add_new'=>'Добавить',
    'add_new_item'=>'Добавить Chart отчет',
    'edit_item'=>'Редактировать Chart отчет',
    'new_item'=>'Новый Chart отчет',
    'view_item'=>'Просмотр Chart отчета',
    'search_items'=>'Поиск Chart отчета',
    'not_found'=>'Chart отчет не найден',
    'parent_item_colon'=>''
  );

  register_post_type('chart_report', array(
    'label'=>$labels['singular_name'],
    'labels'=>$labels,
    'public'=>true,
    'hierarchical'=>true,
    'supports'=>array('title', 'editor', 'author', 'excerpt', 'custom-fields', 'page-attributes'),
    'taxonomies'=>array(),
    'query_var'=>true,
    'menu_position'=>10,
  ));
} add_action('init', 'register_chart_report_posttype');





?>
