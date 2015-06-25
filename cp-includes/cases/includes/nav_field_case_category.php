<?php
     
/**
 * Added field Case category to search section
 * 
 */

function add_case_category_dropdown() {
	if (!(is_post_type_archive('cases') or is_tax( 'functions' ))) return;
    
     $value = '';
    if(isset($_REQUEST['functions'])) {
        $value = $_REQUEST['functions'];
        $value = get_term_by( 'slug', $value, 'functions' );
        $value = $value->term_id;
    }
     
     $args = array(
        "show_option_all"    => "Все дела",
        "show_option_none"   => "",
            'orderby'            => 'ID',
            'order'              => 'ASC',
            'show_last_update'   => 0,
            'show_count'         => 0,
            'hide_empty'         => 0,
            'child_of'           => 0,
            //'exclude'            => '',
            'echo'               => 1,
            'selected'           => $value,
            'hierarchical'       => 1,
            'name'               => 'functions',
            'id'                 => 'case_category_input',
            'class'              => 'form-control',
            //'depth'              => 0,
            //'tab_index'          => 0,
            'taxonomy'           => 'functions',
            'hide_if_empty'      => false,
            'value_field'        => 'slug', // значение value e option
        ); 

	?>
	<div id="field_wrapper_case_category_cp" class="form-group">
		<label for="case_responsible_field">Категория кейса</label>
        <?php  wp_dropdown_categories( $args ); ?>
	</div>
    <?php
} add_action('search_form_add_item', 'add_case_category_dropdown');