<?php

include_once 'users/user_person_link.php';
//include_once 'admin/functions.php';
include_once 'frontend_fields/dosie.php';



function gf_cases_flush_rules(){
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
} add_action('init', 'gf_cases_flush_rules');

function gf_remove_cases_fields(){
  remove_meta_box('functionsdiv', 'cases', 'side');
  remove_meta_box('tagsdiv-navigation', 'cases', 'side');
  remove_meta_box('tagsdiv-results', 'cases', 'side');
  remove_meta_box('tagsdiv-state', 'cases', 'side');
} add_action('admin_menu', 'gf_remove_cases_fields');

function gf_cpt_activation(){
  flush_rewrite_rules(false);
} 
add_action('cp_activate', 'gf_cpt_activation');
//register_activation_hook(__FILE__, 'gf_cpt_activation');

function gf_cpt_deactivation(){
  flush_rewrite_rules(false);
} 
add_action('cp_deactivate', 'gf_cpt_deactivation');
//register_deactivation_hook(__FILE__, 'gf_cpt_deactivation');
