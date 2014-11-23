<?php
/**
 * Добавляем опцию выбора главного сайдбара в уже существующую секцию и страницу опций.
 Если этот сайдбар выбран, то к нему будут подцепляться остальные сайдбары.
 */

class Option_Select_Page_Notice_CP_Singltone {
private static $_instance = null;
private function __construct() {
    add_action('admin_init', array($this, 'add_option_select_page_notice'));
}
    

    
function add_option_select_page_notice(){
    
    // тут первый параметр это страниа на которой будет вывод поля, а второй - ключ опции для хранения
    register_setting( 'cp_settings_commone_group', 'page_notice_cp' ); 
    
    
    add_settings_field( 
        $id =  'page_notice_cp', 
        $title = 'Страница для вывода уведомлений', 
        $callback = array($this, 'add_option_select_page_notice_callback'), 
        $page = 'casepress_settings_sections', 
        $section = 'cp_settings_others_section' 
    );
    
}

// Функция для генерации HTML поля
function add_option_select_page_notice_callback(){
    
    $setting = esc_attr( get_option( 'page_notice_cp' ) );  
    
    wp_dropdown_pages( array( 
        'name' => 'page_notice_cp', 
        'echo' => 1, 
        'show_option_none' => 'Не выбрано', 
        'option_none_value' => '0', 
        'selected' => $setting 
    ) ) ;

    
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
    
} $Option_Select_Page_Notice_CP = Option_Select_Page_Notice_CP_Singltone::getInstance();