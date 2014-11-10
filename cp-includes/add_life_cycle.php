<?php

function register_life_cycle_posttype(){
  $labels = array(
    'name'=>'Жизненный цикл',
    'singular_name'=>'Жизненный цикл',
    'add_new'=>'Добавить',
    'add_new_item'=>'Добавить Жизненный цикл',
    'edit_item'=>'Редактировать Жизненный цикл',
    'new_item'=>'Новый Жизненный цикл',
    'view_item'=>'Просмотр Жизненного цикла',
    'search_items'=>'Поиск Жизненного цикла',
    'not_found'=>'Жизненный цикл не найден',
    'parent_item_colon'=>''
  );

  register_post_type('life_cycle', array(
    'label'=>$labels['singular_name'],
    'labels'=>$labels,
    'public'=>true,
	'show_ui' => (current_user_can('manage_options') ) ? true : false,
    'hierarchical'=>true,
    'supports'=>array('title', 'custom-fields'),
    'taxonomies'=>array(),
    'query_var'=>true,
    'menu_position'=>20,
  ));
} add_action('init', 'register_life_cycle_posttype');





?>
