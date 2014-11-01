<?php

/*

Тут располагаются временные лабараторные функции, для проверки гипотез. Если функции приживутся, то их переносим в иерархию компонентов на свое место по методу ВИСИ (http://casepress.org/mece)

*/

//Удаляем цитату и заменяет ее на часть контента


// Вместо цитаты выводим краткое содержимое. Потому что цитата используется под кеш поиска данных.
function cp_do_not_show_excerpt($excerpt, $post_id){

    $data = get_post($post_id);
  	return strip_tags(mb_substr($data->post_content, 0, 256, 'UTF-8'));

} add_filter('the_excerpt', 'cp_do_not_show_excerpt', 5, 2);


//Добавляем секцию с мета данными для всех типов постов

function add_top_section_metadata_to_post($content) {

	$content =  '<div class="metadata_top_cp">' . do_action('add_metadata_to_post_cp') . '<div>' . $content;

	return $content;

}  add_filter('the_content', 'add_top_section_metadata_to_post');




//Добавляем тип поста на страницы поиска через хук the_content

function add_post_type_label_to_search_page($excerpt) {

	if(is_search()) {
		global $post;

		$post_type = get_post_type( $post );

		$obj = get_post_type_object( $post_type );

		echo  '<div><span class="label label-default">' . $obj->labels->singular_name . '</span><div>' . $excerpt;
	}


} add_action('add_metadata_to_post_cp', 'add_post_type_label_to_search_page');




//Добавляем поиск по типу поста на страницы поиска

function add_dropdown__post_type_to_context_search() {

if(! is_search()) return;

	if(empty($_REQUEST['post_type'])) {
		$post_type_ruquest ='';
	} else {
		$post_type_ruquest = $_REQUEST['post_type'];
	}

	$post_types = get_post_types(array(
		'public'   				=> true,
		//'publicly_queryable'	=> true,
		'has_archive'			=> true,
		'exclude_from_search'	=> false,
		), 'objects');
//var_dump($post_types);
?>

<div id="post_type_field_wrapper_cp" class="form-group">
	<label for="post_type_field_cp"><span>Тип</span></label>
	<select id="post_type_field_cp" class="form-control" placeholder="Выберите тип" name="post_type">
	<?php
	echo '<option value="" ' . selected( $post_type_ruquest, '', false ) . '>Все типы</option>'; 
	foreach($post_types as $post_type) {
	    echo '<option value="' . $post_type->name . '"' . selected( $post_type_ruquest, $post_type->name, false ) . '>' . $post_type->labels->name . '</option>';
	}
	?>
	</select>
</div>

<?php
} add_action('search_form_add_item', 'add_dropdown__post_type_to_context_search');