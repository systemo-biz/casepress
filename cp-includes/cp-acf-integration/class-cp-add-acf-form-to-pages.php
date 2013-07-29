<?php
/*
* @CasePress class for add ACF form to pages
* 
*/

class CP_Add_ACF_Form_On_Pages {

	function __construct(){
		add_action('cp_entry_content_after', array($this, 'add_acf_form_to_page_organizaton'));
		add_action('cp_entry_content_after', array($this, 'add_acf_form_to_page_person'), 40);
		add_action('cp_entry_content_after', array($this, 'add_acf_form_to_page_case'));
		add_action('cp_entry_content_after', array($this, 'add_acf_form_to_page_object'), 40);
        
        
		add_action('get_header', array($this, 'load_acf_components'));
	}
	
	function load_acf_components(){
		global $post;
		if (is_single() && ($post->post_type == 'organizations' || 
							$post->post_type == 'objects' || 
							$post->post_type == 'persons' || 
							$post->post_type == 'cases')) acf_form_head();
	}

	function add_acf_form_to_page_case(){
		global $post;
		if (is_singular("cases")) {
			if (function_exists('acf_form')){
                ?>
                <div class="cases-box">
                    <div class="cases-box-header">
						<h3>
							<a href="#" class="cases-box-toggle">Дополнительные данные</a>
							<a href="#add_data" name="add_data" class="cases-box-anchor">#</a>
						</h3>
                    </div>
                    <div class="cases-box-content" id="cases_add_data">
                        <?php acf_form(); ?>
                    </div>
                </div>
                <?php
			}
		}
	}
    


    function add_acf_form_to_page_organizaton(){
		global $post;
		if (is_singular('organizations')) {
			echo '<br/><h1>Дополнительные данные</h1>';
			if (function_exists('acf_form')){
                acf_form();
			}
		}
	}
    
	function add_acf_form_to_page_person(){
		global $post;
		if (is_singular('persons')) {
			if (function_exists('acf_form')){
    			echo '<br/><h1>Данные</h1>';
				acf_form();
			}
		}
	}
    
	function add_acf_form_to_page_object(){
		global $post;
		if (is_singular('objects')) {
			if (function_exists('acf_form')){
    			echo '<h1>Данные</h1>';
				acf_form();
			}
		}
	}
}

$The_CP_Add_ACF_Form_On_Pages = new CP_Add_ACF_Form_On_Pages;

?>