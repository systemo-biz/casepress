<?php
/*
  Plugin Name: CasePress
  Plugin URI: http://casepress.org
  Description: Adaptive Case Managment System based on WordPress. Alpha version
  Author: CasePress
  GitHub Plugin URI: https://github.com/sistemo/casepress/
  GitHub Branch: master
  Author URI: http://casepress.org
  Version: 20141125
*/

/* 

Все компоненты следует структурировать по методу ВИСИ http://casepress.org/mece/

*/


//Надо отказываться от DT и выпиливать его к чертям. 
include_once 'cp-components/cp-datatable/dt.php';

//Общие функции
include_once 'cp-includes/casepress_commone_functions.php';

//Страницы настроек
include_once 'cp-includes/settings/_load.php';

//Дела
include_once 'cp-includes/cases/_load.php';
include_once 'cp-includes/function-redirect-onsave.php';

//Персоны
include_once 'cp-includes/persons/_load.php'; 

//Организации
include_once 'cp-includes/organizations/_load.php'; 

//Объекты
include_once 'cp-includes/objects/_load.php'; 

//Прочее
include_once 'cp-includes/notice-page/add_notification_list.php';

include_once 'cp-includes/acf_integrate/_load.php';
include_once 'cp-includes/acl_integrate/acl_int.php';
include_once 'cp-includes/cp-reports/cp-reports.php';

include_once 'cp-includes/cp-log_actions/cp-log_actions.php';
include_once 'cp-includes/redirect.php';
include_once 'cp-includes/data-registration.php';

include_once 'cp-includes/search-ext/_load.php';

include_once 'cp-includes/meta-organizations/meta-organizations.php';
include_once 'cp-includes/notificare/_load.php';
include_once 'cp-includes/toolbar_home_icon/admin_menu_icon.php';
include_once 'cp-includes/need-authentication/need-auth-int-casepress.php';
include_once 'cp-includes/wb_ban_cps/wp_ban_cps.php';
include_once 'cp-includes/add_wrapper_and_hooks_for_posts.php';
include_once 'cp-includes/sidebars/_load.php';

include_once 'cp-includes/meta_public_var.php';
include_once 'cp-includes/lab_temp.php';

include_once 'cp-includes/actions_box.php';
include_once 'cp-includes/at_js_integrate/at_js_integrate.php';
include_once 'cp-includes/add_status_archive.php';
include_once 'cp-includes/print_cover/_load.php';
include_once 'cp-includes/comments/_load.php';


//*********************************
//Под вопросом на удаление
//include_once 'cp-includes/add_checklist.php';
//include_once 'cp-includes/add_life_cycle.php';
//include_once 'cp-includes/notify_old/tax_notify_template_method.php';
//include_once 'cp-includes/notify_old/tax_notify_template_action.php';
//include_once 'cp-includes/notify_old/add_notify_templates.php';
//include_once 'cp-includes/tax_labels.php';
//include_once 'cp-includes/tax_navigation.php';
//include_once 'cp-components/cp-flexo/cp-flexo.php';
//include_once 'cp-components/cp-drafts-shorcodes/drafts-shorcodes.php';
//include_once 'cp-components/cp-new-content-menu/new-content-menu.php';
//include_once 'cp-components/cp-overdue-sc/overdue-sc.php';
//include_once 'cp-components/cp-shortcodes/cp-shortcodes.php';
//include_once 'cp-components/cp-life-cycle/cp-life-cycle.php';
//include_once 'cp-components/cp-members/members.php';
//include_once 'cp-components/casepress-component.class.php';
//include_once 'cp-components/cp-acl/acl.php';
//include_once 'cp-components/cp-labels/labels.php';


//*********************************
//На удаление
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



register_activation_hook( __FILE__, 'cp_activation' );
function cp_activation() {
  
  //хук для компонентов, которым нужна активация
  do_action( 'cp_activate' );

  //сброс правил перезаписи, чтобы ссылки открывались как следует
  flush_rewrite_rules(false);
}

register_deactivation_hook( __FILE__, 'cp_deactivation' );
function cp_deactivation() {
  do_action( 'cp_deactivate' );
  flush_rewrite_rules(false);
}
