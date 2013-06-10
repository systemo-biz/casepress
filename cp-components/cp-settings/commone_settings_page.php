<?php
class cpCommoneSettingsPage{

	function __construct(){
		add_action('admin_menu', array($this, 'add_setting_page_in_menu'));
		add_action('admin_init', array($this, 'init_cp_settings_commone'));
		add_action('wp_enqueue_scripts', array($this, 'load_ss'));
	}
	

	function add_setting_page_in_menu(){
		add_menu_page('Общие - CasePress', 'CasePress', 'manage_options', 'casepress_menu_settings', array($this, 'get_cp_commone_setting_form'),'',100);
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
		
		$this->add_section_for_pages();
		$this->add_section_for_terms();		
	}

	function add_section_for_pages(){
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
		<p>Укажите ID страницы, на которой нужно будет выводить список:</p>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$("select").select2();
			});
		</script> 
		<style type="text/css">
			.select2-container {
				width:50%;
				}
		</style>
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


	
	function add_section_for_terms(){
		add_settings_section( 'cp_settings_term_section', 'Настройки параметров классификации', array($this, 'cp_settings_term_section_callback'), 'casepress_settings_sections' );
		add_settings_field( 'term_our_organizations_field', 'Термин для определения наших организаций', array($this, 'term_our_organizations_field_callback'), 'casepress_settings_sections', 'cp_settings_term_section' );	
		add_settings_field( 'term_employees_field', 'Термин для определения сотрудников в списке персон', array($this, 'term_employees_field_callback'), 'casepress_settings_sections', 'cp_settings_term_section' );	
	}
	
	function cp_settings_term_section_callback(){
		echo '<p>Укажите ID основных терминов:</p>';
	}
	function term_our_organizations_field_callback(){
		$setting = esc_attr( get_option( 'term_our_organizations' ) );
		wp_dropdown_categories( array( 'name' => 'term_our_organizations', 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting, 'hierarchical' => 1, 'taxonomy' => 'organizations_category' )) ; 
	}
	function term_employees_field_callback(){
		$setting = esc_attr( get_option( 'term_employees' ) );
		wp_dropdown_categories( array( 'name' => 'term_employees', 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting, 'hierarchical' => 1, 'taxonomy' => 'persons_category' )) ; 
		echo "<p><small>Добавить новый термин можно в разделе: <a href='#'>Категории персон</a></small></p>";
	}
	
	function get_cp_commone_setting_form() {
		?>
		<div class="wrap">
			<h1>Настройки общие</h1>
			<form action="options.php" method="POST">
				<?php settings_fields( 'cp_settings_commone_group' ); ?>
				<?php do_settings_sections( 'casepress_settings_sections' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
	
	function load_ss(){
		wp_enqueue_script( 'select2' );
		wp_enqueue_style( 'select2' );
	}
}


$cpanel = new cpCommoneSettingsPage();
