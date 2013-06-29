<?php
function casepress_autohide_function(){
    if (isset($_POST['autohide_term_id'])){
        $term_id = $_POST['autohide_term_id'];
        $title = ctmeta_get_meta('ctmeta_title_template','functions',$term_id);
        $content = ctmeta_get_meta('ctmeta_content_template','functions',$term_id);
        $result = array('title'=>$title,'content'=>$content);
        echo json_encode($result);
    }
    die();
}
add_action('wp_ajax_autohide_function','casepress_autohide_function');
?>