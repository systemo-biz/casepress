<?php
include_once 'add_report.php';
include_once 'tax_report_cat.php';

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


add_action('admin_enqueue_scripts', 'codemirror_enqueue_scripts');

function codemirror_enqueue_scripts() {
    global $post;
	if (isset($post) && ! $post->post_type == 'report') return $post->ID;
    
    wp_enqueue_script('codemirror', plugin_dir_url( __FILE__ ).'codemirror/codemirror.js');
	wp_enqueue_style('codemirror', plugin_dir_url( __FILE__ ).'codemirror/codemirror.css');
	wp_enqueue_script('cm_sql', plugin_dir_url( __FILE__ ).'codemirror/sql.js');
}

add_action('admin_init', 'add_metabox_report', 1);
function add_metabox_report() {
    add_meta_box( 'metabox_report', 'Параметры', 'metabox_report_callback', 'report', 'advanced' );

}

function metabox_report_callback() {
    wp_nonce_field( basename( __FILE__ ), 'metabox_nonce' );
    global $post;
    ?>
        <div>
            <div>
            <label for="cp_sql"><strong>Запрос</strong></label><br />
            <textarea id="cp_sql" rows="7" cols="90" name="cp_sql"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
            <hr>
            </div>
            </div>
            <label for="cp_parameters"><strong>Параметры отчета</strong></label><br />
            <textarea id="cp_parameters" rows="7" cols="90" name="datatable_parameters"><?php echo get_post_meta($post->ID, 'datatable', true) ?></textarea>
            <hr>
            </div>
        </div>
        <script>
            jQuery(document).ready(function() {
                    
                var editor = CodeMirror.fromTextArea(document.getElementById("cp_sql"), {
                    mode: 'text/x-mariadb',
                    indentWithTabs: false,
                    smartIndent: true,
                    lineNumbers: false,
                    matchBrackets : true,
                    autofocus: false                    
                });
				
                var editor2 = CodeMirror.fromTextArea(document.getElementById("cp_parameters"), {
                    smartIndent: true,
                    lineNumbers: false,
                    matchBrackets : true,
                    autofocus: false                    
                });
            });

        </script>
    <?php
    
}
/*
 * Этап 3. Сохранение
 */
function save_report ( $post_id ) {
	// проверяем, пришёл ли запрос со страницы с метабоксом
	if ( !isset( $_POST['metabox_nonce'] )
	|| !wp_verify_nonce( $_POST['metabox_nonce'], basename( __FILE__ ) ) )
        return $post_id;

    
    
	// проверяем, права пользователя, может ли он редактировать записи
	if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;
    
    
	// теперь также проверим тип записи	
	$post = get_post($post_id);
	if ($post->post_type == 'report') { // укажите собственный
        
   		remove_action('save_post', 'save_report');
		// update the post, which calls save_post again
		wp_update_post(array('ID' => $post_id, 'post_excerpt' => $_POST['cp_sql']));
		// re-hook this function
		add_action('save_post', 'save_report');
        
        update_post_meta($post_id, 'datatable', $_POST['datatable_parameters']);
	}
	return $post_id;
}
 
add_action('save_post', 'save_report');
?>