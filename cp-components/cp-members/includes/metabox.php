<?php

	function cp_members_add_meta_box() {
		add_meta_box( 'cp_members','Участники', 'cp_cases_members', 'cases', 'normal', 'high' );
	}
	//add_action( 'add_meta_boxes', 'cp_members_add_meta_box' );
	
	function cp_cases_members() {
	
	global  $post;
		$post_id = $post->ID;
	
		if ( is_single() && get_post_type() == 'cases' ) {
	
	$url=WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) );
		wp_enqueue_script('member_metabox', $url.'/metabox.js', array('jquery'));
		wp_localize_script( 'member_metabox', 'member_metabox_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
		
		?>
		<div class="row-fluid">
		<div class="span12">
		object_id <input id="object_id" value="111"/><br/>
		object_type <input id="object_type" value="cases" /><br/>
		subject <input id="subject" value="333,555" /><br/>
		subject_type <input id="subject_type" value="person" /><br/>
		role <input id="role" value="to" /><br/>
		term_id <input id="term_id" /><br/>
		role_rus <input id="desc" /><br/>
		<a class="btn" id="update_subject_to_object">update_subject_to_object</a><br/>
		<a class="btn" id="get_subject_by_role">get_subject_by_role</a><br/>
		<a class="btn" id="get_tax_roles">get_tax_roles</a><br/>
		<a class="btn" id="remove_tax_role">remove_tax_role</a><br/>
		<a class="btn" id="update_tax_role">update_tax_role</a><br/>
		<a class="btn" id="update_common_role">update_common_role</a><br/>
		<a class="btn" id="get_common_roles">get_common_roles</a><br/>
		<div id="result"></div>
		
		
		</div>
		</div>
		<hr>
		
		<?

		}
	}
	
	
	//add_action( 'roots_entry_content_before', 'cp_cases_members', 5 );
	
	
	add_action('wp_ajax_get_common_roles_ajax', 'get_common_roles_ajax');
	function get_common_roles_ajax(){

		$res = get_common_roles($_POST['object_type']);
		print_r($res);
			
		die;
	}
	
	add_action('wp_ajax_update_tax_role_ajax', 'update_tax_role_ajax');
	function update_tax_role_ajax(){

		$res = update_tax_role($_POST['term_id'], $_POST['role'], $_POST['desc']);
		print_r($res);
			
		die;
	}
	
	add_action('wp_ajax_get_tax_roles_ajax', 'get_tax_roles_ajax');
	function get_tax_roles_ajax(){

		$res = get_tax_roles($_POST['term_id']);
		print_r($res);
			
		die;
	}
	
	//get_subject_by_role($object, $subject_type, $role)
	
	add_action('wp_ajax_get_subject_by_role_ajax', 'get_subject_by_role_ajax');
	function get_subject_by_role_ajax(){

	$members = new CasePress_Members;
	$members-> set_object($_POST['object_id']);
	$res = $members-> get_members($_POST['subject_type'], $_POST['role']);

		print_r($res);
			
		die;
	}
	
	
	add_action('wp_ajax_update_subject_to_object_ajax', 'update_subject_to_object_ajax');
	function update_subject_to_object_ajax(){
	$subjects = explode(",", $_POST['subject']);
	$input = array (
	'subject' => $subjects,
	'subject_type' => $_POST['subject_type'],
	'role' => $_POST['role']
	);
	
	$members = new CasePress_Members;
	$members-> set_object($_POST['object_id']);
	
	$res = $members->update_subject($input);
		if ($res) 
			{ echo $res; }
		else
			{ echo 'false'; }
		print_r($res);
		die;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	add_action('wp_ajax_edit_members_obj', 'edit_members_obj');
	function edit_members_obj()
	{
	/*post_id:post_id,
				role:role*/
		$role = $_POST['role'];
		$post_id = $_POST['post_id'];
		
		$persons = get_subject_by_role($post_id, 'person', $role);
		
		$args = array(  
			'numberposts'     => -1,  
			'post_type'       => 'persons',  
			'post_status'     => 'publish'  
		);  
		  
		$posts = get_posts($args); 
		echo '<select id="select2" name="select2" multiple style="min-width:200px;">';
			
			foreach ($posts as $post)
			{
				if (in_array($post->ID,$persons))
				{
					echo '<option selected value="'.$post->ID.'">'.$post->post_title.'</option>';
				}
				else
				{
					echo '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
				}
			}
		
		echo '</select>';
		echo '<a class="btn update_members" >update</a>';
	
	die;
	}
	
	
?>