<?php
/*
* @CasePress class for add ACF form to pages
* https://github.com/casepress/casepress/issues/3
*/

class CP_Add_ACF_Form_On_Pages {

	function __construct(){
		add_action('cp_post_after', array($this, 'add_acf_form_to_page_organizaton'));
		add_action('cp_loop_after', array($this, 'add_acf_form_to_page_persones'));
		add_action('get_header', array($this, 'load_acf_components'));
	}
	
	function load_acf_components(){
		global $post;
		if (is_single() && ($post->post_type == 'organizations' || 
							$post->post_type == 'persons' || 
							$post->post_type == 'cases')) acf_form_head();
	}
	function add_acf_form_to_page_organizaton(){
		global $post;
		if (is_single() && $post->post_type == 'organizations') {
			echo '<br/><h1>Дополнительные данные</h1>';
			if (function_exists('acf_form')){
				acf_form();
			}
		}
	}
	function add_acf_form_to_page_persones(){
		global $post;
		if (is_single() && $post->post_type == 'persones') {
			if (function_exists('acf_form')){
				acf_form();
			}
		}
	}
}

$The_CP_Add_ACF_Form_On_Pages = new CP_Add_ACF_Form_On_Pages;

?>