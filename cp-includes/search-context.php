<?php
/*
Plugin Name: Context Search
Description: Добавляет виджет поиска, который зависим от контекста текущего запроса
*/


add_shortcode('search_context_form', 'search_context_form');

function search_context_form($attr) {
	global $wp;
	ob_start();

	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

	?>
	<form role="search" method="get" class="search-context-form" action="<?php echo esc_url( $current_url ); ?>">
		<div>
			<label>
				<span class="screen-reader-text"><?php _ex( 'Search:', 'label', 'alienship' ); ?></span>
				<input type="search" class="search-field form-control" placeholder="Поиск" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
			</label>
		</div>
		<?php do_action('search_form_add_item'); ?>
		<div>
			<input type="submit" class="search-submit btn btn-default" value="Найти...">
		</div>
	</form>
	<?php

	$html = ob_get_contents();
	ob_get_clean();

	return $html;

}