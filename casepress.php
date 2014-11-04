<?php
/*
  Plugin Name: CasePress
  Plugin URI: http://casepress.org
  Description: Adaptive Case Managment System based on WordPress. Alpha version
  Author: CasePress
  GitHub Plugin URI: https://github.com/casepress-studio/casepress/
  GitHub Branch: master
  Author URI: http://casepress.org
  Version: 20141104.2
*/


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

include_once 'cp-includes/casepress_commone_functions.php';


//ядро - надо будет постепенно перетащить в cp-includes по принципу ВИСИ
include_once 'cp-core/post_types/add_checklist.php';
include_once 'cp-core/post_types/add_notify_templates.php';
include_once 'cp-core/post_types/add_objects.php';
include_once 'cp-core/post_types/add_organizations.php';
include_once 'cp-core/post_types/add_persons.php';
include_once 'cp-core/post_types/add_life_cycle.php';


include_once 'cp-core/taxs/tax_navigation.php';
include_once 'cp-core/taxs/tax_objects_category.php';
include_once 'cp-core/taxs/tax_organizations_category.php';
include_once 'cp-core/taxs/tax_subject_category.php';
include_once 'cp-core/taxs/tax_organizations_structure.php';
include_once 'cp-core/taxs/tax_persons_category.php';
//include_once 'cp-core/taxs/tax_labels.php';
//include_once 'cp-core/taxs/tax_notify_template_method.php';
//include_once 'cp-core/taxs/tax_notify_template_action.php';


//компоненты - надо будет постепенно перетащить в cp-includes по принципу ВИСИ
include_once 'cp-components/casepress-component.class.php';
include_once 'cp-components/cp-log_actions/cp-log_actions.php';
//include_once 'cp-acl/acl.php';
include_once 'cp-components/cp-visits/visits.php';
include_once 'cp-components/cp-labels/labels.php';
include_once 'cp-components/cp-datatable/dt.php';
include_once 'cp-components/cp-members/members.php';
include_once 'cp-components/cp-flexo/cp-flexo.php';
include_once 'cp-components/cp-drafts-shorcodes/drafts-shorcodes.php';
include_once 'cp-components/cp-new-content-menu/new-content-menu.php';
include_once 'cp-components/cp-overdue-sc/overdue-sc.php';
include_once 'cp-components/cp-shortcodes/cp-shortcodes.php';
include_once 'cp-components/cp-life-cycle/cp-life-cycle.php';
include_once 'cp-components/cp-settings/commone_settings_page.php';
include_once 'cp-components/cp-settings/setting_page_security.php';
include_once 'cp-components/projects_and_requests.php';



include_once 'cp-includes/cases/cases.php';
include_once 'cp-includes/cases/class-cp-box-case-management/class-cp-box-case-management.php';

include_once 'cp-includes/function-redirect-onsave.php';
include_once 'cp-includes/dossier.php';

include_once 'cp-includes/persons/persons.php';	
include_once 'cp-includes/persons/user_person_link.php';
include_once 'cp-includes/persons/change_person_for_user.php';
include_once 'cp-includes/persons/persons_init.php';

include_once 'cp-includes/cp-acf-integration/cp-acf-integration.php';
include_once 'cp-includes/cp-reports/cp-reports.php';
include_once 'cp-includes/redirect.php';
include_once 'cp-includes/data-registration.php';
include_once 'cp-includes/acl_settings.php';

include_once 'cp-includes/search-ext/_load.php';

include_once 'cp-includes/meta-organizations/meta-organizations.php';
include_once 'cp-includes/notificare/load.php';
include_once 'cp-includes/toolbar_home_icon/admin_menu_icon.php';
include_once 'cp-includes/need-authentication/need-auth-int-casepress.php';
include_once 'cp-includes/wb_ban_cps/wp_ban_cps.php';
include_once 'cp-includes/add_hooks_for_sections.php';
include_once 'cp-includes/sidebars/sidebars.php';

include_once 'cp-includes/meta_public_var.php';
include_once 'cp-includes/lab_temp.php';
//require_once 'cp-includes/load.php'; - удалить файл по возможнсти
//include_once 'cp-core/core-functions.php'; -- удалить файл по возможности
//include_once 'cp-includes/redirect/redirect.php'; нужно будет удалить по возможности весь компонент и всю папку
//require_once 'cp-core/core-includes.php'; - удалить файл по возможности
//include_once 'cp-components/components-includes.php'; - удалить файл по возможности


//add 15 sec interval for wp cron
add_filter( 'cron_schedules', 'cron_add_15sec'); 

function cron_add_15sec($schedules){

    $schedules['15sec'] = array(  
        'interval' => 15,  
        'display' => __( 'Once in 15 sec' )
    );  
    return $schedules;
}
