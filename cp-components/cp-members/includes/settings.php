<?php

	add_action( 'admin_menu',  'cp_members_settings', 1000);

	function cp_members_settings()
	{
		//add_options_page('cp_members_settings', ' members_settings', 'manage_options','settings.php','cp_members_settings_options_page');
		add_submenu_page('casepress_menu_settings', 'Участники', 'Участники', 'manage_options', 'members_settings', 'cp_members_settings_options_page');
	}



	function cp_members_settings_options_page()
	{
		global $wpdb;
		
		$url=WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) );
		wp_enqueue_script('member_settings', $url.'/settings.js', array('jquery'));
		wp_localize_script( 'member_settings', 'member_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			
		$post_types = $wpdb->get_results("select p.post_type from $wpdb->posts p 
										  group by p.post_type");  
			
		?><table width="100%" cellspacing="2" cellpadding="5" class="form-table">
        <tbody><tr valign="baseline">
			<th scope="row">
			<?
			foreach ($post_types as $type) {  
				echo '<a class="cp_common_options_settings" style="cursor:pointer;" id="'.$type->post_type.'">'.$type->post_type.'</a><br/>';
			 
			}
			?>
			</th>
			<td id="options_content">
			<?
			echo 'options';
			
			?>
			</td>
		</tr>
		</tbody>
		</table>
	
	<?

	}
	
	add_action('wp_ajax_cp_members_load_options', 'cp_members_load_options');
	function cp_members_load_options()
	{
		$type = $_POST['post_type'];
		$roles = get_common_roles($type);
		echo '<h3>'.$type.'</h3>';
	//	print_r ($roles);
		foreach ($roles as $k => $role)
		{
		echo '<div style="width:100%; position: relative; float: left;">';
			echo '<input type="hidden" id="role_type_edit" value="'.$type.'" />';
			echo '<input style="position: relative; float: left;" type="text" id="role_name_edit" value="'.$k.'" />';
			echo '<textarea style="position: relative; float: left;" id="role_desc_edit">'.$role.'</textarea>';
			echo '<input style="position: relative; float: left;" alt="'.$k.'" type="button" id="role_delete" value="del" />';
		echo '</div>';
		echo '</br>';
		}
		
		echo '<div style="width:100%; position: relative; float: left;">'; 
		echo '<h3>Добавить роль</h3>';
		?>
		<input style="position: relative; float: left;" type="text" id="role_name" value="" />
		<textarea style="position: relative; float: left;" id="role_desc"></textarea>
		<input style="position: relative; float: left;" alt=<? echo $type; ?> type="button" id="role_add" value="Добавить" />
		<?
		echo '</div>';
		die;
	}
	
	add_action('wp_ajax_cp_members_add_option', 'cp_members_add_option');
	function cp_members_add_option()
	{
		$object_type = $_POST['post_type'];
		$role = $_POST['role_name'];
		$desc = $_POST['role_desc'];
		update_common_role($object_type, $role, $desc);
		
		//cp_members_load_options();
		die;
	}
	
?>