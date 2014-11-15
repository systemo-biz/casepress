<?php
/*
Plugin Name: CasePress. Обложка
Description: Обложка дела
License: Free
*/

/*
*   Шорткод контроля дела
*/

//загружаем шаблон из папки, если обнаружен параметр view=cover
function view_cover_cp() {

    if(empty($_REQUEST['view'])) return;

    if ( $_REQUEST['view'] == 'cover' ) {
            include( plugin_dir_path(__FILE__) . 'cover.php' );
            exit();
    }
} add_action( 'template_redirect', 'view_cover_cp', 0, 5);
    
 

function add_view_cover_to_action_box(){

    if (is_singular('cases')){ 
        $url = add_query_arg( array('view' => 'cover'));
        ?>
        <li>
            <a href="<?php echo $url ?>">Распечатать обложку</a>
        </li>
        <?php
    }
} add_action('add_action_cp', 'add_view_cover_to_action_box');