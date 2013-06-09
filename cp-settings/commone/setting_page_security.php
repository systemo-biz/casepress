<?php
class cpSecuritySettingsPage{

	function __construct(){
		add_action('admin_menu', array($this, 'add_setting_page'));
		add_action('admin_init', array($this, 'init_cp_settings_access'));
	}
	
	function init_cp_settings_access(){
		add_settings_section( 'cp_settings_access_section', 'Настройки доступа', array($this, 'cp_settings_access_section_callback'), 'cp_commone_access' );
		register_setting( 'cp_settings_access_group', 'enable_acl' );
		add_settings_field( 'enable_acl_field', 'Включить ограничение доступа по таблице ACL', array($this, 'enable_acl_field_callback'), 'cp_commone_access', 'cp_settings_access_section' );		
	}
	
	function cp_settings_access_section_callback(){
		echo 'Укажите параметры настроек доступа:';
	}
	
	function enable_acl_field_callback(){
	    $setting = esc_attr( get_option( 'enable_acl' ) );
		?>
		<input type='checkbox' name='enable_acl' value='1' <?php checked('1', get_option('enable_acl')); ?> />
		<?php
	}

	function add_setting_page(){
		add_submenu_page('casepress_menu_settings', 'Доступ', 'Доступ', 'manage_options', 'cp_commone_access', array($this, "get_cp_access_setting_form"));
	}
	
	function get_cp_access_setting_form() {
		?>
		<div class="wrap">
			<h1>Настройки контроля доступа</h1>
			<form action="options.php" method="POST">
				<?php settings_fields( 'cp_settings_access_group' ); ?>
				<?php do_settings_sections( 'cp_commone_access' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}


$cpanel = new cpSecuritySettingsPage();