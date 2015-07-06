<?php
/*
Plugin Name: CasePress
Plugin URI: http://casepress.org
Description: Adaptive Case Managment System based on WordPress. Beta-version
Author: CasePress
Author URI: http://casepress.org
GitHub Plugin URI: https://github.com/systemo-biz/casepress
GitHub Branch: master
Version: 20150706-3
*/

/*
Все компоненты следует структурировать по методу ВИСИ http://casepress.org/mece/
*/




//Общие функции
include_once 'cp-includes/casepress_commone_functions.php';

//Страницы настроек
include_once 'cp-includes/settings/_load.php';

//Дела
include_once 'cp-includes/cases/_load.php';

//Персоны
require_once 'cp-includes/persons/_load.php'; 

//Организации
include_once 'cp-includes/organizations/_load.php'; 

//Прочее
include_once 'cp-includes/comments_action.php';

include_once 'cp-includes/taxonomy_subject_category.php';
include_once 'cp-includes/new-content-menu.php';
include_once 'cp-includes/redirect-onsave.php';
include_once 'cp-includes/acf_integrate/_load.php';
include_once 'cp-includes/acl/acl.php';
include_once 'cp-includes/todo-comments/todo-comments-integrate-cp.php';

include_once 'cp-includes/cp-log_actions/cp-log_actions.php';
include_once 'cp-includes/redirect_from_main_page.php';

include_once 'cp-includes/search-ext/_load.php';

include_once 'cp-includes/notice/_load.php';
//include_once 'cp-includes/notice-page/add_notification_list.php';

include_once 'cp-includes/toolbar_home_icon/admin_menu_icon.php';
include_once 'cp-includes/need-authentication/need-auth-int-casepress.php';
include_once 'cp-includes/wb_ban_cps/wp_ban_cps.php';
include_once 'cp-includes/add_wrapper_and_hooks_for_posts.php';
include_once 'cp-includes/sidebars/_load.php';

include_once 'cp-includes/search_by_meta.php';
include_once 'cp-includes/lab_temp.php';

include_once 'cp-includes/actions_box.php';
include_once 'cp-includes/at_js_integrate/at_js_integrate.php';
include_once 'cp-includes/add_status_archive.php';
include_once 'cp-includes/print_cover/_load.php';
include_once 'cp-includes/comments/_load.php';
include_once 'cp-includes/fullscreen/_load.php';
include_once 'cp-includes/add_caps_for_contributors.php';


//*********************************
//Под вопросом на удаление
//include_once 'cp-includes/add_checklist.php';
//include_once 'cp-includes/add_life_cycle.php';
//include_once 'cp-includes/notify_old/tax_notify_template_method.php';
//include_once 'cp-includes/notify_old/tax_notify_template_action.php';
//include_once 'cp-includes/notify_old/add_notify_templates.php';
//include_once 'cp-includes/tax_labels.php';
//include_once 'cp-includes/tax_navigation.php';



//*********************************
//На удаление
//include_once 'cp-core/core-functions.php'; -- удалить файл по возможности
//include_once 'cp-includes/redirect/redirect.php'; нужно будет удалить по возможности весь компонент и всю папку
//require_once 'cp-core/core-includes.php'; - удалить файл по возможности
//include_once 'cp-components/components-includes.php'; - удалить файл по возможности


register_activation_hook( __FILE__, 'cp_activation' );
function cp_activation() {
  
  //хук для компонентов, которым нужна активация
  do_action( 'cp_activate' );

  //сброс правил перезаписи, чтобы ссылки открывались как следует
  flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'cp_deactivation' );
function cp_deactivation() {
  do_action( 'cp_deactivate' );
  flush_rewrite_rules();
}
