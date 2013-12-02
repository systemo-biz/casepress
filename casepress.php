<?php

/*
  Plugin Name: CasePress
  Plugin URI: http://casepress.org
  Description: Adaptive Case Managment System based on WordPress
  Author: CasePress
  Author URI: http://casepress.org
  Version: b20131203-01
*/

include_once 'cp-includes/load.php';
include_once 'cp-core/core-includes.php';
include_once 'cp-core/sidebars.php';
include_once 'cp-components/components-includes.php';
include_once 'cp-templates/template-include.php';
include_once 'cp-includes/class-cp-box-case-management/class-cp-box-case-management.php';
include_once 'cp-includes/function-redirect-onsave.php';
include_once 'cp-includes/dossier.php';
include_once 'cp-includes/persons/persons.php';	
include_once 'cp-includes/persons/user_person_link.php';
include_once 'cp-includes/persons/change_person_for_user.php';
include_once 'cp-includes/cp-acf-integration/cp-acf-integration.php';
include_once 'cp-includes/cp-reports/cp-reports.php';
include_once 'cp-includes/redirect/redirect.php';
include_once 'cp-includes/data-registration.php';
include_once 'cp-includes/acl_settings.php';
include_once 'cp-includes/search-in-id/search-in-id.php';
include_once 'cp-includes/meta-organizations/meta-organizations.php';
include_once 'cp-includes/notificare/cp_notify.php';
include_once 'cp-includes/toolbar_home_icon/admin_menu_icon.php';
include_once 'cp-includes/NeedAuthentication/need-auth-int-casepress.php';

register_activation_hook( __FILE__, 'cp_activation' );
function cp_activation() {
	do_action( 'cp_activate' );
	flush_rewrite_rules(false);
}

register_deactivation_hook( __FILE__, 'cp_deactivation' );
function cp_deactivation() {
	do_action( 'cp_deactivate' );
	flush_rewrite_rules(false);
}


/*
Add function for chek update from GitHub
*/
require_once 'cp-includes/github-updater/plugin-updates/plugin-update-checker.php';
$ExampleUpdateChecker = new PluginUpdateChecker(
	'https://raw.github.com/casepress-studio/casepress/master/info.json',
	__FILE__
);


if ( is_admin() ) {
    register_activation_hook(__FILE__, 'activate' );
    register_deactivation_hook(__FILE__, 'deactivate' );
}
    
/*Activation and deactivation plugin*/
function deactivate(){
    wp_clear_scheduled_hook('cp_email_notification');
}

function activate(){
    wp_schedule_event( time(), 'seconds15', 'cp_email_notification'); 
}

//add 15 sec interval for wp cron
add_filter( 'cron_schedules', 'cron_add_15sec'); 

function cron_add_15sec($schedules){

    $schedules['seconds15'] = array(  
        'interval' => 15,  
        'display' => __( 'Once in 15 sec' )
    );  
    return $schedules;
}