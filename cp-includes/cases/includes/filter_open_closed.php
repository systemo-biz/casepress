<?php


//Добавляем поиск по типу поста на страницы поиска

function add_open_field_to_context_search() {

	if (!(is_post_type_archive('cases') or is_tax( 'functions' ))) return;
	
	$open = '';
	if(isset($_REQUEST['open'])) $open = $_REQUEST['open'];
	?>


	<div id="case_open_field_wrapper_cp" class="form-group">
		<label for="case_open_field">Открытые</label>
		<select type="text" id="case_open_field" class="form-control" name="open">
			<option <?php selected( $open, "", true ); ?> value="">Все дела</option>
			<option <?php selected( $open, "yes", true ); ?> value="yes">Только открытые дела</option>
			<option <?php selected( $open, "no", true ); ?> value="no">Только закрытые дела</option>
		</seclet>
	</div>

<?php
} add_action('search_form_add_item', 'add_open_field_to_context_search');


// Фильтр который отбирает закрытые и открытые дела

function filter_cases_open($query) {

	if(empty($_REQUEST['open'])) return;

	//error_log('message 20141010');

	if(! $query->is_main_query()) return;

	$tax_query = $query->get('tax_query');

	$results_array = get_terms('results', 'fields=ids');

	$open = $_REQUEST['open'];

	if($open == 'yes') {
		$tax_query[] = 
	            array(
	                'taxonomy' => 'results',
	             	'operator' => 'NOT IN',
	                'terms'    => $results_array,
	            );	
	} elseif ($open == 'no') {
		$tax_query[] = 
            array(
                'taxonomy' => 'results',
             	'operator' => 'IN',
                'terms'    => $results_array,
            );	
	}

	$query->set('tax_query',$tax_query);

	return;

} 
add_action('pre_get_posts', 'filter_cases_open');