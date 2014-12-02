<?php

	/**
	 * Register multiple sidebars
	 */
	function cases_theme_register_sidebars() {

		// List of sidebars
		$sidebars = array(
			// Post type archives
			array( 'cases', 'Дела', 'Сайдбар для страницы с архивом типа записи Дела' ),
			array( 'report', 'Отчеты', 'Сайдбар для отчетов' ),
			array( 'objects', 'Объекты', 'Сайдбар для страницы с архивом типа записи Объекты' ),
			array( 'organizations', 'Организации', 'Сайдбар для страницы с архивом типа записи Организации' ),
			array( 'persons', 'Персоны', 'Сайдбар для страницы с архивом типа записи Персоны' ),
			// Front-page
			array( 'commone', 'Общий', 'Сайдбар для всех страниц' ),
            array( 'blog', 'Блог', 'Сайдбар для блога' ),
            array( 'other', 'Прочее', 'Сайдбар для прочих страниц' ),

		);

		// Register sidebars
		foreach ( $sidebars as $sidebar ) {

			register_sidebar( array(
				'id' => $sidebar[0],
				'name' => $sidebar[1],
				'description' => $sidebar[2],
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget' => '</div></section>',
				'before_title' => '<h3>',
				'after_title' => '</h3>'
			) );
		}
	}

	add_action( 'widgets_init', 'cases_theme_register_sidebars' );


//Тут прописана логика загрузки сайдбаров через хук sidebar_cp


//Добавляем хук в тему для вызова наших сайдбаров
function add_sidebar_hook_cp($index){
    
    //Если есть виджеты - возврат
    if(is_active_sidebar($index)) return;
    
    // Получаем опцию сайдбара темы, под который будем выводить сайдбары плагина
    $mysidebar = esc_attr( get_option( 'main_sidebar_cp' ) );
    
    //Если опция не задана - возврат
    if(empty($mysidebar)) return;
    
    // Если текущий сайдбар не тот что в опции - возврат
    if($index != $mysidebar) return;
    
    //if( current_user_can('administrator')) exit(var_dump($has_widget));
    do_action('sidebars_cp', $index);

} add_action( 'dynamic_sidebar_after', 'add_sidebar_hook_cp' );




//Выводим сайдбары по условию
function view_sidebars_cp(){
    
    dynamic_sidebar('commone');
    
	
    if (is_home() or is_tag() or is_category() or is_singular('post')) {
		$sidebar_id = 'blog';
	} elseif (is_post_type_archive('cases') or is_singular('cases') or is_tax( 'functions' )) {
		$sidebar_id = 'cases';
	} elseif (is_post_type_archive('persons') or is_singular('persons')) {
		$sidebar_id =  'persons';
    } elseif (is_post_type_archive('organizations') or is_singular('organizations')) {
		$sidebar_id = 'organizations';
	} elseif (is_post_type_archive('report') or is_singular('report') or is_tax( 'report_cat' )) {
		$sidebar_id = 'report' ;
	} else {
        $sidebar_id = 'other';
        
    }
    
    $sidebar_id = apply_filters('change_sidebar_cp', $sidebar_id);
    
    dynamic_sidebar( $sidebar_id );
    
} add_action('sidebars_cp', 'view_sidebars_cp');

//подменяем сайдбар для страниц объектов
function change_sidebar_for_objects($sidebar_id){

    if (is_post_type_archive('objects') or is_singular('objects') or is_tax( 'objects_category' )) 
        $sidebar_id = 'objects';

    return $sidebar_id;
    
} add_filter('change_sidebar_cp', 'change_sidebar_for_objects');
