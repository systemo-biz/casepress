<?php

function create_events(){
		$labels = array(
			'name' => _x('Заметки', 'post type general name'),
			'singular_name' => _x('Заметку', 'post type singular name'),
			'add_new' => _x('Добавить', 'Event'),
			'add_new_item' => __('Добавить новую заметку'),
			'edit_item' => __('Редактировать заметку'),
			'new_item' => __('Новая заметка'),
			'view_item' => __('Просмотреть заметку'),
			'search_items' => __('Поиск заметок'),
			'not_found' =>  __('Заметки не найдены'),
			'not_found_in_trash' => __('Не найдено'),
			'parent_item_colon' => ''
		);
		$supports = array('title', 'editor', 'custom-fields', 'revisions', 'excerpt');
		register_post_type( 'checklist',
			array(
			  'labels' => $labels,
			  'public' => true,
			  'supports' => $supports
			)
		);
	}
	add_action( 'init', 'create_events' );

?>