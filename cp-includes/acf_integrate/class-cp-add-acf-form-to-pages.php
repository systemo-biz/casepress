<?php
/*
* @CasePress class for add ACF form to pages
* 
*/

class CP_Add_ACF_Form_On_Pages {

	function __construct(){
		add_filter('the_content', array($this, 'add_acf_form_to_post'));
		add_action('wp_print_styles', array($this, 'acf_deregister_styles'), 100 );
		add_action('get_header', array($this, 'load_acf_components'));
	}

	function add_acf_form_to_post($content){
		if (is_singular(array("cases", "persons", "organizations", "objects"))) {
			if (function_exists('acf_form')){
		        ob_start();
		        ?>
		        <section id="data_acf_post" class="cases-box">
		          <div class="cases-box-header">
		            <h1>Данные</h1>
		            <hr />
		          </div>
		          <div class="cases-box-content" id="cases_add_data">
		            <?php acf_form(); ?>
		          </div>
		        </section>
		        <?php
		        $html = ob_get_contents();
		        ob_get_clean();
		        return $content . $html;
			}
		}
		return $content;
	}

    function acf_deregister_styles() {
        wp_deregister_style( 'wp-admin' );
    }

	function load_acf_components(){
	    global $post;
	    if(is_single() && in_array($post->post_type, array('organizations', 'objects', 'persons', 'cases'))){
	      acf_form_head();
	      $path_to_plugin = trailingslashit(plugin_dir_url(__FILE__) );
	      wp_enqueue_style( 'acf_fix', $path_to_plugin.'assets/css/acf_fix.css', false, false, 'all' );
	    }
	}
}

$The_CP_Add_ACF_Form_On_Pages = new CP_Add_ACF_Form_On_Pages;
