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
		
		//sorting fileds on sections
		add_settings_section( 'cp_settings_pages_section', 'Настройки базовых страниц', array($this, 'cp_settings_pages_section_callback'), 'casepress_menu_settings' );
		add_settings_field( 'page_for_cases_list_field', 'Страница для списка дел', array($this, 'page_for_cases_list_field_callback'), 'casepress_menu_settings', 'cp_settings_pages_section' );		
		add_settings_field( 'page_for_organizations_list_field', 'Страница для списка организаций', array($this, 'page_for_organizations_list_field_callback'), 'casepress_menu_settings', 'cp_settings_pages_section' );		
		add_settings_field( 'page_for_persons_list_field', 'Страница для списка персон', array($this, 'page_for_persons_list_field_callback'), 'casepress_menu_settings', 'cp_settings_pages_section' );		
		add_settings_field( 'page_for_objects_list_field', 'Страница для списка объектов', array($this, 'page_for_objects_list_field_callback'), 'casepress_menu_settings', 'cp_settings_pages_section' );		
		add_settings_field( 'page_for_reports_list_field', 'Страница для списка отчетов', array($this, 'page_for_reports_list_field_callback'), 'casepress_menu_settings', 'cp_settings_pages_section' );		
	}
	
	function cp_settings_pages_section_callback(){
		echo "<p>Укажите ID страницы, на которой нужно будет выводить список:</p>";
	}

	
	function page_for_reports_list_field_callback(){
		$setting = esc_attr( get_option( 'page_for_reports_list' ) );
		?>
		<label for="page_for_reports_list">
			<?php wp_dropdown_pages( array( 'name' => $setting, 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting ) ) ; ?>
		</label>
		<?php
	}
	function page_for_persons_list_field_callback(){
		$setting = esc_attr( get_option( 'page_for_persons_list' ) );
		?>
		<label for="page_for_reports_list">
			<?php wp_dropdown_pages( array( 'name' => $setting, 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting ) ) ; ?>
		</label>
		<?php
	}
	function page_for_objects_list_field_callback(){
		$setting = esc_attr( get_option( 'page_for_objects_list' ) );
		?>
		<label for="page_for_reports_list">
			<?php wp_dropdown_pages( array( 'name' => $setting, 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting ) ) ; ?>
		</label>
		<?php
	}
	function page_for_organizations_list_field_callback(){
		$setting = esc_attr( get_option( 'page_for_organizations_list' ) );
		?>
		<label for="page_for_reports_list">
			<?php wp_dropdown_pages( array( 'name' => $setting, 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting ) ) ; ?>
		</label>
		<?php
	}

	function page_for_cases_list_field_callback(){
		$setting = esc_attr( get_option( 'page_for_cases_list' ) );
		?>
		<label for="page_for_reports_list">
			<?php wp_dropdown_pages( array( 'name' => $setting, 'echo' => 1, 'show_option_none' => 'Не выбрано', 'option_none_value' => '0', 'selected' => $setting ) ) ; ?>
		</label>
		<?php
	}

	function get_cp_commone_setting_form() {
		?>
		<div class="wrap">
			<h1>Настройки общие</h1>
			<form action="options.php" method="POST">
				<?php settings_fields( 'cp_settings_commone_group' ); ?>
				<?php do_settings_sections( 'casepress_menu_settings' ); ?>
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
