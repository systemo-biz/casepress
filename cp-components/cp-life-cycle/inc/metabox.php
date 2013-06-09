<?php

	/**
	 * Add metabox
	 */
	function lfc_add_metabox() {
		// Get plugin object
		global $cplfc;
		// Register meta box
		add_meta_box( 'casepress-life-cycle', __( 'Life cycle', $cplfc->textdomain ), 'lfc_metabox', 'life_cycle', 'normal', 'high' );
	}

	add_action( 'add_meta_boxes', 'lfc_add_metabox', 90 );

	/**
	 * Metabox function
	 */
	function lfc_metabox( $post ) {
		// Get component instance
		global $cplfc;
		// Hide set-default link for default life cycle
		?>
		<p>
			<input type="checkbox" name="cp_base_lc" value="<?php echo $post->ID ?>" <?php checked($post->ID, get_option('default_life_cycle_id')); ?>/>
			<strong>Основной жизненный цикл</strong>
			<p><small>Поставьте отметку, если хотите чтобы этот жизенный цикл соответствовал всем делам по умолчанию</small></p>
		</p>
		<p>
			<strong>Категории дел</strong>
			<p><small>Укажите ID категорий дел через запятую, которые должны соответствовать данному жизненному циклу</small></p>
			<span>
				<input 
					type="text" 
					name="case_category" 
					value="<?php echo implode( ',', ( array ) maybe_unserialize( get_post_meta( $post->ID, 'cp_posts_life_cycle_case_category') ) ); ?>" 
					style="width: 100%;">
			</span>
		</p>
		<p>
			<strong>Статусы</strong>
			<p><small>Укажите ID статусов через запятую, которые должны соответствовать данному жизненному циклу</small></p>
			<span>
				<input 
					type="text" 
					name="state" 
					value="<?php echo implode( ',', ( array ) maybe_unserialize( get_post_meta( $post->ID, 'cp_posts_life_cycle_state', true ) ) ); ?>"
					style="width: 100%;">
			</span>
		</p>
		<p>
			<strong>Результаты</strong>
			<p><small>Укажите ID результатов через запятую, которые должны соответствовать данному жизненному циклу</small></p>
			<span>
				<input 
					type="text" 
					name="results" 
					value="<?php echo implode( ',', ( array ) maybe_unserialize( get_post_meta( $post->ID, 'cp_posts_life_cycle_results', true ) ) ); ?>"
					style="width: 100%;">
			</span>
		</p>
		<?php
	}
?>