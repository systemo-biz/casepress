<?php

	/** Plugin options */
	$options = array(
		array(
			'type' => 'about'
		),
		array(
			'name' => __( 'Fillers', $this->textdomain ),
			'type' => 'opentab'
		),
		array(
			'name' => __( 'Test option', $this->textdomain ),
			'desc' => __( 'Test option description', $this->textdomain ),
			'std' => array(
				array(
					'title',
					"// Fill title\njQuery('#title').val('%value%');\n\n// RU: Заполнение заголовка записи"
				),
				array(
					'parent_id',
					"// Deselect all options\njQuery('select#parent_id option').attr('selected',false);\n\n// Select queried post_parent\njQuery('select#parent_id').find('option[value=\"%value%\"]').attr('selected',true);\n\n// RU: Выбор родительской записи"
				),
				array(
					'category',
					"// Change category by query\njQuery('input:checkbox[name=\"post_category[]\"][value=\"%value%\"]').attr('checked',true);\n\n// RU: выбор категории"
				),
				array(
					'tags',
					"// Post tags\njQuery('input#new-tag-post_tag').val('%value%').submit();\n\n// Click add button with 1sec delay\nwindow.setTimeout(function() {\n\tjQuery('input:button.button.tagadd').trigger('click');\n},1000);\n\n// RU: метки записи"
				),
			),
			'id' => 'fillers',
			'type' => 'fillers'
		),
		array(
			'type' => 'closetab'
		),
	);
?>