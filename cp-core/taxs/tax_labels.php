<?php

	function register_taxonomy_labels() {
		$labels = array(
			'name' => 'Ярлыки',
			'singular_name' => 'Ярлык',
			'search_items' => 'Поиск ярлыков',
			'popular_items' => 'Популярные ярлыки',
			'all_items' => 'Все ярлыки',
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => 'Изменить ярлык',
			'update_item' => 'Обновить ярлык',
			'add_new_item' => 'Добавить новый ярлык',
			'new_item_name' => 'Название нового ярлыка',
			'separate_items_with_commas' => 'Ярлыки разделяются запятыми',
			'add_or_remove_items' => 'Добавить или удалить ярлыки',
			'choose_from_most_used' => 'Выбрать из часто используемых ярлыков',
			'menu_name' => 'Ярлыки',
		); 

		register_taxonomy( 'labels', 'cases', array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => false,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'label'
			),
		) );
	}

	add_action('init', 'register_taxonomy_labels');


?>