<?php

/*

Функции общие для всей системы

*/


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

