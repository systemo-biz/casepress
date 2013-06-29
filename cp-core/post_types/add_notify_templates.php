<?php

add_action('init', 'register_notify_template_pt');	
function register_notify_template_pt() {
    $labels = array(
            'name' 			=> 'Шаблоны уведомлений',
            'singular_name'		=> 'Шаблон',
            'add_new' 			=> 'Добавить',
            'add_new_item' 		=> 'Добавить шаблон',
            'edit_item' 		=> 'Редактировать шаблон',
            'new_item' 			=> 'Новый шаблон',
            'view_item' 		=> 'Просмотр шаблона',
            'search_items' 		=> 'Поиск шаблонов',
            'not_found' 		=> 'Шаблон не найден',
            'not_found_in_trash'=> 'В Корзине шаблон не найден',
            'parent_item_colon' => ''
    );	
    $supports = array(
            'title',
            'editor',
    );
    $args = array(
            'labels' 			=> $labels,
            'singular_label' 	=> 'Шаблоны уведомленй',
            'public' 			=> false,
            'show_ui' 			=> true,
            'publicly_queryable'=> true,
            'query_var'			=> true,
            'capability_type' 	=> 'post',	
            'has_archive' 		=> true,
            'hierarchical' 		=> true,
            'rewrite' 			=> false, //array('slug' => 'reposts', 'with_front' => false ),
            'supports' 			=> $supports,
            'menu_position' 	=> 5
     );
    register_post_type('notify_templates',$args);	
} 
?>