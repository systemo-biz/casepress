<?php
/*
Plugin Name: CasePress. Шаблон вывода страницы на полный экран
License: Free
*/

class FullScreen_CP_Singltone {
private static $_instance = null;
private function __construct() {
    add_action( 'template_redirect', array($this, 'view_cp'), 0, 5);
    add_action('add_action_cp', array($this, 'add_url_to_action_box'));
}

//загружаем шаблон из папки, если обнаружен параметр view=cover
function view_cp() {

    if(empty($_REQUEST['view'])) return;

    if ( $_REQUEST['view'] == 'fullscreen' ) {
            include( plugin_dir_path(__FILE__) . 'tmpl.php' );
            exit();
    }
} 
    
 

function add_url_to_action_box(){
    $url = add_query_arg( array('view' => 'fullscreen'));
    ?>
    <li>
        <a href="<?php echo $url ?>">Развернуть</a>
    </li>
    <?php
} 
    
     
/**
 * Служебные функции одиночки
 */
protected function __clone() {
	// ограничивает клонирование объекта
}
static public function getInstance() {
	if(is_null(self::$_instance))
	{
	self::$_instance = new self();
	}
	return self::$_instance;
}    
    
} $FullScreen_CP = FullScreen_CP_Singltone::getInstance();
