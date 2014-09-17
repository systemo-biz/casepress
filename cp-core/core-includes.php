<?php

include_once 'core-functions.php';

include_once 'post_types/add_cases.php';
include_once 'post_types/add_checklist.php';
include_once 'post_types/add_notify_templates.php';
include_once 'post_types/add_objects.php';
include_once 'post_types/add_organizations.php';
include_once 'post_types/add_persons.php';
include_once 'post_types/add_life_cycle.php';

include_once 'taxs/tax_results.php';
include_once 'taxs/tax_functions.php';
include_once 'taxs/tax_navigation.php';
include_once 'taxs/tax_objects_category.php';
include_once 'taxs/tax_organizations_category.php';
include_once 'taxs/tax_subject_category.php';
include_once 'taxs/tax_organizations_structure.php';
include_once 'taxs/tax_persons_category.php';

include_once 'taxs/tax_labels.php';

include_once 'taxs/tax_notify_template_method.php';
include_once 'taxs/tax_notify_template_action.php';

/*
* load basic scripts and styles for CasePress
*/
function cp_load_core_js(){
	wp_enqueue_script( 'jquery' ); 
	wp_localize_script( 'jquery', 'cp_core', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );  
} add_action( 'wp_enqueue_scripts', 'cp_load_core_js' );
?>