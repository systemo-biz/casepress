<?php
	
	include_once 'inc/ajax.php';
	
	if (is_admin())
	{
	$url=WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) );
	wp_enqueue_script( 'cp_pages_settings', $url . '/js/pages.js', array( 'jquery' ) );
	wp_localize_script( 'cp_pages_settings', 'cp_pages_settings', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
	}
	add_action('admin_menu', 'add_pages_settings');
	
	function add_pages_settings()
	{
		add_submenu_page("wpcases_menu", "Настройки страниц", "Настройки страниц", 8, "cp_pages_settings", "cp_pages_settings");
	}
	
	function cp_pages_settings()
	{
	
		$args = array(  
			'numberposts'     => -1,  
			'post_type'       => 'page',  
 			'post_status'     => 'publish'  
		);  
		  
		$pages = get_posts($args);
		$cp_cases_page = get_option('cp_settings_cases_page');		

		?>
		<p></p><p></p>
		<table role="container" >
		
			<tr role="row" >
				<td role="td_name" width="250px">
					Страница Дела
				</td>
				<td role="td_option" width="300px">
					<select  name="cp_cases_page"  id="cp_cases_page">
					<option value="">--Не задано--</option>
					<? 
					foreach($pages as $page)
					{ 
						if ($page->ID == $cp_cases_page)
						{
							echo '<option selected value="'.$page->ID.'">'.$page->post_title.'</option>';
						}
						else
						{
							echo '<option value="'.$page->ID.'">'.$page->post_title.'</option>';
						}
					}  
					?>
					</select>
				</td>
			</tr>
			<p></p>

			
			
		</table>
		
		<input type="button" id="cp_save_page_settings" value="Обновить настройки" />
		
		<?
	}

?>