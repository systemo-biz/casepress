<?php
/*
* @CasePress class for add ACF form to pages
* https://github.com/casepress/casepress/issues/3
*/

class CP_Add_ACF_Form_On_Pages {

	function __construct(){
		add_action('cp_post_after', array($this, 'add_acf_form_to_page_organizaton'));
		add_action('get_header',array($this, 'load_acf_components'));
	}
	
	function load_acf_components(){
		global $post;
		if (is_single() && $post->post_type == 'organizations') acf_form_head();
	}
	function add_acf_form_to_page_organizaton(){
		global $post;
		if (is_single() && $post->post_type == 'organizations'){
			global $post;
			echo '<br/><h1>Дополнительные данные</h1>';
			if (is_single() && $post->post_type == 'organizations') {
				if (function_exists('acf_form')){
					acf_form();
				}
			}
		}
	}
}

$The_CP_Add_ACF_Form_On_Pages = new CP_Add_ACF_Form_On_Pages;

?>