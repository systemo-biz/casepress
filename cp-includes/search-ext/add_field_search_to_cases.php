<?php



//Добавляем поиск по типу поста на страницы поиска

function add_responsible_to_context_search() {

	if (!(is_post_type_archive('cases') or is_tax( 'functions' ))) return;
	if(isset($_REQUEST['meta_responsible-cp-posts-sql'])) $resp = $_REQUEST['meta_responsible-cp-posts-sql'];
	?>


	<div id="case_responsible_field_wrapper_cp" class="form-group">
		<label for="case_responsible_field"><span>Ответственный</span></label>
		<input type="text" id="case_responsible_field" class="form-control" placeholder="Ответственный" name="meta_responsible-cp-posts-sql" value="<?php echo $resp ?>" />
	</div>

<?php
} add_action('search_form_add_item', 'add_responsible_to_context_search');