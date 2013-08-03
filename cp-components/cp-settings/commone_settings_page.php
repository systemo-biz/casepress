<?php
class cpCommoneSettingsPage {

function __construct(){
    add_action('admin_menu', array($this, 'add_setting_page_in_menu'));
    add_action('admin_init', array($this, 'init_cp_settings_commone'));
    }



function add_setting_page_in_menu(){
    add_menu_page('Общие - CasePress', 'CasePress', 'manage_options', 'casepress_menu_settings', array($this, 'get_cp_commone_setting_form'),'',100);
    }

function get_cp_commone_setting_form() {
    ?>
    <div class="wrap">
        <style type="text/css">
            select {
                min-width: 50%;
            }
        </style>
        <h1>Настройки общие</h1>
        <form action="options.php" method="POST">
            <?php settings_fields( 'cp_settings_commone_group' ); ?>
            <?php do_settings_sections( 'casepress_settings_sections' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
    }

function init_cp_settings_commone(){
    //register settings
    register_setting( 'cp_settings_commone_group', 'page_for_cases_list' );
    register_setting( 'cp_settings_commone_group', 'page_for_organizations_list' );
    register_setting( 'cp_settings_commone_group', 'page_for_persons_list' );
    register_setting( 'cp_settings_commone_group', 'page_for_objects_list' );
    register_setting( 'cp_settings_commone_group', 'page_for_reports_list' );
    register_setting( 'cp_settings_commone_group', 'term_our_organizations' );
    register_setting( 'cp_settings_commone_group', 'term_employees' );
    register_setting( 'cp_settings_commone_group', 'term_for_projects' );
    register_setting( 'cp_settings_commone_group', 'term_for_messages' );
    register_setting( 'cp_settings_commone_group', 'enable_custom_fields_for_cases' );
    register_setting( 'cp_settings_commone_group', 'disable_redirection_when_saving_posts' );

    //sort settings of sections
    $The_cpAdd_Section_For_Pages = new cpAdd_Section_For_Pages;
    $The_cpAdd_Section_For_Terms = new cpAdd_Section_For_Terms;
    $The_cpAdd_Section_Others = new cpAdd_Section_Others;

    }

}

$cpanel = new cpCommoneSettingsPage();

class cpAdd_Section_For_Pages {

    function __construct() {

		//sorting fileds on sections
		add_settings_section( 'cp_settings_pages_section', 'Настройки базовых страниц', array($this, 'cp_settings_pages_section_callback'), 'casepress_settings_sections' );
		add_settings_field( 'page_for_cases_list_field', 'Страница для списка дел', array($this, 'page_for_cases_list_field_callback'), 'casepress_settings_sections', 'cp_settings_pages_section' );		
		add_settings_field( 'page_for_organizations_list_field', 'Страница для списка организаций', array($this, 'page_for_organizations_list_field_callback'), 'casepress_settings_sections', 'cp_settings_pages_section' );		
		add_settings_field( 'page_for_persons_list_field', 'Страница для списка персон', array($this, 'page_for_persons_list_field_callback'), 'casepress_settings_sections', 'cp_settings_pages_section' );		
		add_settings_field( 'page_for_objects_list_field', 'Страница для списка объектов', array($this, 'page_for_objects_list_field_callback'), 'casepress_settings_sections', 'cp_settings_pages_section' );		
		add_settings_field( 'page_for_reports_list_field', 'Страница для списка отчетов', array($this, 'page_for_reports_list_field_callback'), 'casepress_settings_sections', 'cp_settings_pages_section' );	
	}
	
	function cp_settings_pages_section_callback(){
		//указываем инструкцию и меняем Select на Select2 
		?>
		<p>Укажите ID страницы, на которой нужно будет выводить список.</p>
		<p>Можно добавить новые страницы в <a href="<?php echo admin_url( 'edit.php?post_type=page' ); ?>">соответствующем разделе</a></p>
		<?php
	}

	
	function page_for_reports_list_field_callback(){
		$setting = esc_attr( get_option( 'page_for_reports_list' ) );
		wp_dropdown_pages( array( 'name' => 'page_for_reports_list', 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting ) ) ;
	}
	function page_for_persons_list_field_callback(){
		$setting = esc_attr( get_option( 'page_for_persons_list' ) );
		wp_dropdown_pages( array( 'name' => 'page_for_persons_list', 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting ) ) ;
	}
	function page_for_objects_list_field_callback(){
		$setting = esc_attr( get_option( 'page_for_objects_list' ) );
		wp_dropdown_pages( array( 'name' => 'page_for_objects_list', 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting ) ) ;
	}
	function page_for_organizations_list_field_callback(){
		$setting = esc_attr( get_option( 'page_for_organizations_list' ) );
		wp_dropdown_pages( array( 'name' => 'page_for_organizations_list', 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting ) ) ; 
	}
	function page_for_cases_list_field_callback(){
		$setting = esc_attr( get_option( 'page_for_cases_list' ) );
		wp_dropdown_pages( array( 'name' => 'page_for_cases_list', 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting ) ) ; 
	}

}

class cpAdd_Section_For_Terms {

    function __construct() {
		add_settings_section( 'cp_settings_term_section', 'Настройки параметров классификации', array($this, 'cp_settings_term_section_callback'), 'casepress_settings_sections' );
		add_settings_field( 'term_our_organizations_field', 'Термин для определения наших организаций', array($this, 'term_our_organizations_field_callback'), 'casepress_settings_sections', 'cp_settings_term_section' );	
		add_settings_field( 'term_employees_field', 'Термин для определения сотрудников в списке персон', array($this, 'term_employees_field_callback'), 'casepress_settings_sections', 'cp_settings_term_section' );	
		add_settings_field( 'term_for_projects_field', 'Термин для определения проектов', array($this, 'term_for_projects_field_callback'), 'casepress_settings_sections', 'cp_settings_term_section' );	
		add_settings_field( 'term_for_messages_field', 'Термин для определения сообщений', array($this, 'term_for_messages_field_callback'), 'casepress_settings_sections', 'cp_settings_term_section' );	
	}
	
	function cp_settings_term_section_callback(){
		echo '<p>Укажите ID основных терминов:</p>';
	}
	
	function term_our_organizations_field_callback(){
		$setting = esc_attr( get_option( 'term_our_organizations' ) );
		wp_dropdown_categories( array( 'name' => 'term_our_organizations', 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting, 'hierarchical' => 1, 'taxonomy' => 'organizations_category' )) ; 
		echo "<p><small>Добавить новый термин можно в разделе: <a href=".admin_url('edit-tags.php?taxonomy=organizations_category&post_type=organizations').">Категории организаций</a></small></p>";
	}
	
	function term_employees_field_callback(){
		$setting = esc_attr( get_option( 'term_employees' ) );
		wp_dropdown_categories( array( 'name' => 'term_employees', 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting, 'hierarchical' => 1, 'taxonomy' => 'persons_category' )) ; 
		echo "<p><small>Добавить новый термин можно в разделе: <a href=".admin_url('edit-tags.php?taxonomy=persons_category&post_type=persons').">Категории персон</a></small></p>";
	}
	
	function term_for_projects_field_callback(){
		$setting = esc_attr( get_option( 'term_for_projects' ) );
		wp_dropdown_categories( array(
			'name' => 'term_for_projects',
			'echo' => 1,
			'show_count' => 1, 
			'show_option_none' => 'Не выбрано',
			'selected' => $setting,
			'hide_empty' => 0,
			'show_last_update' => 1,
			'hierarchical' => 1,
			'taxonomy' => 'functions' )) ; 

		echo "<p><small>Добавить новый термин можно в разделе: <a href=".admin_url('edit-tags.php?taxonomy=functions&post_type=cases').">Категории дел</a></small></p>";
	}
	
	function term_for_messages_field_callback(){
		$setting = esc_attr( get_option( 'term_for_messages' ) );
		wp_dropdown_categories( array(
			'name' => 'term_for_messages',
			'echo' => 1,
			'show_count' => 1, 
			'show_option_none' => 'Не выбрано',
			'hide_empty' => 0,
			'selected' => $setting,
			'hierarchical' => 1,
			'taxonomy' => 'functions' )) ; 
		echo "<p><small>Добавить новый термин можно в разделе: <a href=".admin_url('edit-tags.php?taxonomy=functions&post_type=cases').">Категории дел</a></small></p>";
	}
	

}

class cpAdd_Section_Others {

    function __construct() {
		add_settings_section( 'cp_settings_others_section', 'Настройки прочих параметров', "", 'casepress_settings_sections' );
		add_settings_field( 'enable_custom_fields_for_cases_field', 'Включить метабокс произвольных полей для дел', array($this, 'enable_custom_fields_for_cases_field_callback'), 'casepress_settings_sections', 'cp_settings_others_section' );	        
		add_settings_field( 'disable_redirection_when_saving_posts', 'Выключить переадресацию при сохранении постов', array($this, 'disable_redirection_when_saving_posts_field_callback'), 'casepress_settings_sections', 'cp_settings_others_section' );	        
        add_settings_field( 'link_to_taxonomy_results', 'Список результатов', array($this, 'link_to_taxonomy_results'), 'casepress_settings_sections', 'cp_settings_others_section' );
    }
    
    
    
    function link_to_taxonomy_results(){

        echo "<a href=\"" .admin_url('edit-tags.php?taxonomy=results&post_type=cases') . "\">Изменить список результатов</a>";

	}
    
    function disable_redirection_when_saving_posts_field_callback(){
        $setting = esc_attr( get_option( 'disable_redirection_when_saving_posts' ) );
	    
        ?>
		<input type='checkbox' name='disable_redirection_when_saving_posts' value='1' <?php checked('1', $setting); ?> />
		<p>
            <small>
                Если включить эту опцию, то при сохранении постов, автопереадресация в режим просмотра отключится.
            </small>
        </p>
        <?php
	}
    
    function enable_custom_fields_for_cases_field_callback(){
        $setting = esc_attr( get_option( 'enable_custom_fields_for_cases' ) );
	    
        ?>
		<input type='checkbox' name='enable_custom_fields_for_cases' value='1' <?php checked('1', $setting); ?> />
		<p>
            <small>
                Если включить эту опцию, то на странице дел в консоли появится метабокс с метаполями. Как правило это нужно для отладки или поиска ошибок.
            </small>
        </p>
        <?php
	}
}