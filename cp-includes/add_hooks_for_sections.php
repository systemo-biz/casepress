<?php

add_filter('the_content', 'add_hook_for_sections_cp');
function add_hook_for_sections_cp($content){
	ob_start();
	do_action('cp_entry_sections');
	$html = ob_get_contents();
	ob_get_clean();
	return $content . $html;
}