<?php

function add_cases_report_content($content){
    global $post;
    if(! is_singular( 'report' ) ) return $content;
    
    ob_start();
    eval($post->php_content);
    $php_content = ob_get_contents();
  ob_end_clean();
  return $content.$php_content;
} add_filter('the_content', 'add_cases_report_content');

function codemirror_enqueue_scripts() {
  global $post;
  if(isset($post) && $post->post_type != 'report') return $post->ID;
  wp_enqueue_script('codemirror', plugin_dir_url( __FILE__ ).'codemirror/codemirror.js');
  wp_enqueue_style('codemirror', plugin_dir_url( __FILE__ ).'codemirror/codemirror.css');
  wp_enqueue_script('codemirror_sql', plugin_dir_url( __FILE__ ).'codemirror/sql.js');
} add_action('admin_enqueue_scripts', 'codemirror_enqueue_scripts');

function metabox_report_callback(){
  global $post;
  wp_nonce_field(basename(__FILE__), 'metabox_nonce');
  ?>
  <textarea id="php_content" rows="20" cols="90" name="php_content"><?php echo $post->php_content ?></textarea>
  <script>
    jQuery(document).ready(function(){
      var editor = CodeMirror.fromTextArea(document.getElementById('php_content'), {
        mode: 'text/x-mariadb',
        indentWithTabs: false,
        smartIndent: true,
        lineNumbers: true,
        matchBrackets: true,
        autofocus: false
      });
    });
  </script>
  <?php
}
function add_metabox_report() {
  add_meta_box('metabox_report', 'PHP-код формирования отчета', 'metabox_report_callback', 'report', 'advanced');
} add_action('admin_init', 'add_metabox_report', 1);

function save_report($post_id){
  if(!isset($_POST['metabox_nonce']) || !wp_verify_nonce($_POST['metabox_nonce'], basename(__FILE__))) return $post_id;
  if(!current_user_can('edit_post', $post_id)) return $post_id;
  $r = get_post($post_id);
  if($r->post_type != 'report') return $post_id;
  update_post_meta($post_id, 'php_content', $_POST['php_content']);
  return $post_id;
} add_action('save_post', 'save_report');
