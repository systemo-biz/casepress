<?php

add_action('cp-activate','cases_report_rewrite_flush');
function cases_report_rewrite_flush(){
 // register_cases_report_posttype();
  flush_rewrite_rules();
}  

add_filter('the_content', 'add_cases_report_content');
function add_cases_report_content($content){
  global $post;
  if($post->post_type=='report'){
    echo wpautop($content);
    $params = shortcode_parse_atts(get_post_meta($post->ID, 'datatable', true));
    $sql = $post->post_excerpt;

    if(function_exists('datatable_generator')) {
        datatable_generator($params, $sql);
    }
    return;
  }
  return $content;
} 

?>