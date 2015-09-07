<?php

/**
 * Добавляем опцию выбора главного сайдбара в уже существующую секцию и страницу опций.
 Если этот сайдбар выбран, то к нему будут подцепляться остальные сайдбары.
 */
function add_settings_select_main_sidebar_for_cp(){
    
    // тут первый параметр это страниа на которой будет вывод поля, а второй - ключ опции для хранения
    register_setting( 'cp_settings_commone_group', 'main_sidebar_cp' ); 
    
    
    add_settings_field( 
        $id =  'main_sidebar_cp', 
        $title = 'Сайдбар темы, через который будут выведены сайдбары плагина', 
        $callback = 'main_sidebar_cp_callback', 
        $page = 'casepress_settings_sections', 
        $section = 'cp_settings_others_section' 
    );

    
}
add_action('admin_init',  'add_settings_select_main_sidebar_for_cp');

// Функция для генерации HTML поля
function main_sidebar_cp_callback(){
    
    $setting = esc_attr( get_option( 'main_sidebar_cp' ) );
?>    
<select name="main_sidebar_cp">
    <option value="" <?php selected( $setting, '' ); ?>>Не выбран</option>
<?php foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { ?>
     <option value="<?php echo $sidebar['id'] ?>" <?php selected( $setting, $sidebar['id'] ); ?>>
              <?php echo $sidebar['name'] ?>
     </option>
<?php } ?>
</select>
<?php
    
}