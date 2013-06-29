<?php

	add_filter( 'cases_tabs', 'add_acl_tab', 30 ); 
	function add_acl_tab( $tabs ) 
	{
		$tabs[] = array( 'slug' => 'acl_tab', 'name' => 'Доступ' );
		return $tabs;
	}
	
	
	
	
	//add_shortcode('update_meta_data','update_meta_data');
	function update_meta_data(){
		global $wpdb;
		$ids=$wpdb->get_results("SELECT ID FROM wp_posts where post_type='cases'"); 
		foreach($ids as $ps)
		{
			if ($ps->ID > 1)
			{
			//	$responsible=get_post_meta($ps->ID,'responsible',true);
				$initiator=get_post_meta($ps->ID,'initiator',true);
			//	$participant=get_post_meta($ps->ID,'participant',true);
				
			/*	if (strlen($responsible)>0)
				{
					update_post_meta($ps->ID,'cp_posts_persons_responsible',$responsible);
				}*/
				if (strlen($initiator)>0){
					update_post_meta($ps->ID,'cp_posts_persons_from',$initiator);
				} //cp_posts_persons_to
				
			/*	if (strlen($participant)>0)
				{
					$count_elems=substr_count($participant,",");
					if ($count_elems>0)
					{
						$part_mas=explode( ',', $participant ); 
						for ($i=0;$i<count($part_mas);$i++)
						{
							if (strlen($part_mas[$i])>0)
								add_post_meta($ps->ID,'cp_posts_persons_to',$part_mas[$i]);
						}
					}	
					else 
					{ 
						add_post_meta($ps->ID,'cp_posts_persons_to',$participant);
					}
				}*/
				echo '<br/>'.$ps->ID;
			}
		}
		echo 'done;';
	}


	function cases_tab_content_acl_tab()
	{
		
		$tt='Deny';
		if (current_user_can( 'manage_options' ) )
		{
		
			$url_dash_scripts=WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) );
			wp_enqueue_script('cases_acl', $url_dash_scripts.'/js/cases_acl.js', array('jquery'));
			wp_localize_script( 'cases_acl', 'cases_acl', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			$tt='
			Доступно ТОЛЬКО админу. DANGER!!! зря кнопки не тыкать :)
			
			object_id <input id="object_id" value="111"/><br/>
			object_type <input id="object_type" value="post" /><br/>
			subject <input id="subject"value="333" /><br/>
			subject_type <input id="subject_type" value="user" /><br/>
			accesses <input id="accesses" /><br/>
			access_type <input id="access_type" /><br/>
			<a class="btn" id="have_acl_access">have_acl_access</a><br/>
			<a class="btn" id="remove_acl_access">remove_acl_access</a><br/>
			<a class="btn" id="get_acl_access_by_object">get_acl_access_by_object</a><br/>
			<a class="btn" id="append_acl_access">append_acl_access</a><br/>
			<a class="btn" id="have_subs_acl_acceess">have_subs_acl_acceess</a><br/>
					<a class="btn" id="remove_acl_access_by_role">remove_acl_access_by_role</a><br/>
			<div id="convert_acl"></div>
			';
		
		}
		return $tt; 
	}




	function test_acl_shortcode()
	{
		$url_dash_scripts=WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) );
		wp_enqueue_script('cases_acl', $url_dash_scripts.'/js/cases_acl.js', array('jquery'));
		wp_localize_script( 'cases_acl', 'cases_acl', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
	?>
		object_id <input id="object_id" value="111"/><br/>
		object_type <input id="object_type" value="post" /><br/>
		subject <input id="subject"value="333" /><br/>
		subject_type <input id="subject_type" value="user" /><br/>
		accesses <input id="accesses" /><br/>
		access_type <input id="access_type" /><br/>
		<a class="btn" id="have_acl_access">have_acl_access</a><br/>
		<a class="btn" id="remove_acl_access">remove_acl_access</a><br/>
		<a class="btn" id="get_acl_access_by_object">get_acl_access_by_object</a><br/>
		<a class="btn" id="append_acl_access">append_acl_access</a><br/>
		<a class="btn" id="have_subs_acl_acceess">have_subs_acl_acceess</a><br/>
		<a class="btn" id="convert_acl">convert_acl</a><br/>
		<a class="btn" id="remove_acl_access_by_role">remove_acl_access_by_role</a><br/>
		<div id="convert_acl"></div>
	
	
	<?
	}
	add_shortcode( 'test_acl_shortcode', 'test_acl_shortcode' );
	
		add_action('wp_ajax_remove_acl_access_by_role_ajax', 'remove_acl_access_by_role_ajax');
	function remove_acl_access_by_role_ajax(){
	
		$object_id = $_POST['object_id'];
		$object_type = $_POST['object_type'];
		$subject = $_POST['subject'];
		$subject_type = $_POST['subject_type'];
		$access_type = $_POST['access_type'];
		$res = remove_acl_access_by_role($object_id, $object_type, $subject_type, $access_type);
		if ($res) 
			{ echo $res; }
		else
			{ echo 'false'; }
			
		die;
	}
	
	
		add_action('wp_ajax_have_acl_access_ajax', 'have_acl_access_ajax');
	function have_acl_access_ajax($object_id, $object_type, $subject, $subject_type){
	
		$object_id = $_POST['object_id'];
		$object_type = $_POST['object_type'];
		$subject = $_POST['subject'];
		$subject_type = $_POST['subject_type'];
		$res = have_acl_access($object_id, $object_type, $subject, $subject_type);
		if ($res) 
			{ echo $res; }
		else
			{ echo 'false'; }
			
		die;
	}
	
		add_action('wp_ajax_remove_acl_access_ajax', 'remove_acl_access_ajax');
	function remove_acl_access_ajax($object_id, $object_type, $subject, $subject_type){
	
		$object_id = $_POST['object_id'];
		$object_type = $_POST['object_type'];
		$subject = $_POST['subject'];
		$subject_type = $_POST['subject_type'];
		$res = remove_acl_access($object_id, $object_type, $subject, $subject_type);
		if ($res) 
			{ echo 'true'; }
		else
			{ echo 'false'; }
			
		die;
	}
	
		add_action('wp_ajax_get_acl_access_by_object_ajax', 'get_acl_access_by_object_ajax');
	function get_acl_access_by_object_ajax($object_id, $object_type){
	
		$object_id = $_POST['object_id'];
		$object_type = $_POST['object_type'];
		$subject = $_POST['subject'];
		$subject_type = $_POST['subject_type'];
		$res = get_acl_access_by_object($object_id, $object_type);
		if ($res) 
			{ print_r( $res); }
		else
			{ echo 'false'; }
			
		die;
	}
	
		add_action('wp_ajax_append_acl_access_ajax', 'append_acl_access_ajax');
	function append_acl_access_ajax($object_id, $object_type, $subject, $subject_type, $access_type){
	
		$access_type = $_POST['access_type'];
		$object_id = $_POST['object_id'];
		$object_type = $_POST['object_type'];
		$subject = $_POST['subject'];
		$subject_type = $_POST['subject_type'];
		$res = append_acl_access($object_id, $object_type, $subject, $subject_type, $access_type);
	
		if ($res) 
			{ echo 'true'; }
		else
			{ echo 'false'; }
			

			
		die;
	}
	
		add_action('wp_ajax_have_subs_acl_acceess_ajax', 'have_subs_acl_acceess_ajax');
	function have_subs_acl_acceess_ajax($object_id, $object_type, $subject, $subject_type, $access_type){
	
		$access_type = $_POST['access_type'];
		$object_id = $_POST['object_id'];
		$object_type = $_POST['object_type'];
		$subject = $_POST['subject'];
		$subject_type = $_POST['subject_type'];
		$res = have_subs_acl_acceess($object_id, $object_type, $subject);
	
		if ($res) 
			{ echo $res; }
		else
			{ echo 'false'; }
			

			
		die;
	}
	
//	add_action('wp_ajax_convert_acl', 'convert_acl');
	function convert_acl(){
	
		global $wpdb;
		
		$old=$wpdb->get_results('SELECT * FROM wp_cases_acl_2 ', ARRAY_A);
		
		foreach ($old as $old_elem)
		{
			$object_id = $old_elem['post_id'];
			$object_type = 'post';
			
			$access = $old_elem['accesses'];
			$trans=array("{}"=>"");
			$access=strtr($access,$trans); 
			$trans=array("}{"=>"|");
			$access=strtr($access,$trans); 
			$access=substr($access,1,strlen($access)-2); 
			$count_elems=substr_count($access,"|")+1;
			$elems=explode("|",$access);
			for ($i=0;$i<$count_elems;$i++)
				$elems[$i]=explode(",",$elems[$i]);
				
			$accesses =array();
			
			foreach ($elems	as $cur)
			{
				$accesses[] =array (
					'type' => 'user',
					'subject' => $cur[0],
					'access_type' => 'member'
				);
			}
			
			print_r($old_elem);
			echo '<br/>';
			print_r($elems);
			echo '<br/>';
			print_r($accesses);
			echo '<br/><br/>';
			
			
			
			update_acl($object_id,$object_type,$accesses);
		}
		//print_r ($old);
		die;
	}
	
	add_action('wp_ajax_convert_acl', 'first_acl_create');
	function first_acl_create(){
		global $wpdb;
		$ids=$wpdb->get_results("SELECT ID FROM wp_posts where post_type='cases'"); 
		foreach($ids as $ps)
		{
			$object_id = $ps->ID;
			$object_type = 'post';
			$accesses = array();
			
			$responsible=get_post_meta($ps->ID,'responsible',true);
			$initiator=get_post_meta($ps->ID,'initiator',true);
			$participant=get_post_meta($ps->ID,'participant',true);
			
			if (strlen($responsible)>0)
			{
				$responsible=get_user_by_person($responsible);
				$accesses[] =array (
					'type' => 'user',
					'subject' => $responsible,
					'access_type' => 'responsible'
				);
			}
			if (strlen($initiator)>0){
				$initiator=get_user_by_person($initiator);
				$accesses[] =array (
					'type' => 'user',
					'subject' => $initiator,
					'access_type' => 'initiator'
				);
			} 
			
			if (strlen($participant)>0)
			{
				$count_elems=substr_count($participant,",");
				if ($count_elems>0)
				{
					$part_mas=explode( ',', $participant ); 
					for ($i=0;$i<count($part_mas);$i++)
					{
						$participant=get_user_by_person($part_mas[$i]);
						if ($participant!=0) 
							$accesses[] =array (
								'type' => 'user',
								'subject' => $participant,
								'access_type' => 'participant'
							);
					
					}
				}	
				else 
				{ 
					$participant=get_user_by_person($participant);
					$accesses[] =array (
						'type' => 'user',
						'subject' => $participant,
						'access_type' => 'participant'
					);
				}
			}
			
		update_acl($object_id,$object_type,$accesses);
		}
		//return $output;
	}
	



	function acl_activate_plugin() {
		global $wpdb;
		$acl_table = 'cases_acl';
		$acl_table = $wpdb->prefix . $acl_table;
		$substitution = 'cases_substitution';
		$substitution = $wpdb->prefix . $substitution;
		if ( ! $wpdb->get_var( "SHOW TABLES LIKE '".$acl_table."'" ) ) {
			$charset_collate = '';
			if ( ! empty( $wpdb->charset ) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty( $wpdb->collate ) )
				$charset_collate .= " COLLATE $wpdb->collate";
			$wpdb->query(
				"CREATE TABLE IF NOT EXISTS ".$acl_table." (
					id INT NOT NULL AUTO_INCREMENT,
					object_id INT NOT NULL ,
					object_type varchar(30) NOT NULL, 
					accesses TEXT NOT NULL, 
					PRIMARY KEY (id)
				) $charset_collate"
			);
		}
		
		if ( ! $wpdb->get_var( "SHOW TABLES LIKE '".$substitution."'" ) ) {
			$charset_collate = '';
			if ( ! empty( $wpdb->charset ) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty( $wpdb->collate ) )
				$charset_collate .= " COLLATE $wpdb->collate";
			$wpdb->query(
				"CREATE TABLE IF NOT EXISTS ".$substitution." (
				id int NOT NULL AUTO_INCREMENT,
				replaceable int NOT NULL,
				substitutional int NOT NULL,
				date_start date NOT NULL,
				date_end date NOT NULL,
				type int NOT NULL,
				state varchar(30) NOT NULL,
				PRIMARY KEY (id)
				) $charset_collate"
			);
		}
	}
	
	
	//register_activation_hook(__FILE__, 'acl_activate_plugin');
	add_action('cp_activate','acl_activate_plugin');
	
	
	function auto_update_acl($meta_id, $object_id, $meta_key, $meta_value)
	{
			
		$pst = get_post($object_id);
		
		
		if ($pst->post_type == 'cases')
		{
		
			
			if ($meta_key == 'responsible')
			{
				remove_acl_access_by_role($object_id, 'post', 'user', 'responsible');
				$responsible=get_user_by_person($meta_value);
				append_acl_access($object_id, 'post', $responsible, 'user', 'responsible');
			}
			
			if ($meta_key == 'initiator')
			{
				//append_acl_access($object_id, 'post', $meta_value, 'user', 'test');	
				
				remove_acl_access_by_role($object_id, 'post', 'user', 'initiator');
				$responsible=get_user_by_person($meta_value);
				append_acl_access($object_id, 'post', $responsible, 'user', 'initiator');
			
			}
			
			if ($meta_key == 'participant')
			{

				remove_acl_access_by_role($object_id, 'post', 'user', 'participant');
				
				$count_elems=substr_count($meta_value,",");
				if ($count_elems>0)
				{
					$part_mas=explode( ',', $meta_value ); 
					for ($i=0;$i<count($part_mas);$i++)
					{
						$participant=get_user_by_person($part_mas[$i]);
						if ($participant!=0) 
							append_acl_access($object_id, 'post', $participant, 'user', 'participant');
					
					}
				}	
				else 
				{ 
					$participant=get_user_by_person($meta_value);
					append_acl_access($object_id, 'post', $participant, 'user', 'participant');
				}
			
			
			}
		
		}
	
	}
	if (get_option( 'enable_acl' ) == '1') add_action( 'updated_postmeta', 'auto_update_acl', 36, 4 );
	if (get_option( 'enable_acl' ) == '1') add_action( 'deleted_postmeta', 'auto_update_acl', 36, 4 );
	
	add_action( 'added_post_meta', 'auto_update_acl', 36, 4 );

	
	
	/*
	*
	* update_acl($object_id,$object_type,$accesses)
	* Полностью обновляет запись а acl, заменяя предыдущее значение
	* Возвращает true, если запись обновлена и false если произошла какая-либо ошибка
	* создает запись, если она не существовала
	*/
	function update_acl($object_id,$object_type,$accesses){
		if (!$object_id) return false;
		if (!$object_type) return false;
		
		global $wpdb; 
		
		if ($accesses!='')
		$accesses = serialize ($accesses);
		
		$acl_table = 'cases_acl';
		$acl_table = $wpdb->prefix . $acl_table;
		
		$acl_ids=$wpdb->get_row('SELECT * FROM '.$acl_table.' WHERE object_id="'.$object_id.'" and object_type="'.$object_type.'" ', ARRAY_A);
		
		if (!empty($acl_ids))
		{
			$id_cur=$acl_ids['id'];
			$wpdb->update( $acl_table,
			array( 'accesses' => $accesses),array( 'id' => $id_cur ),	array( '%s' ), array( '%s' ));
		
		}
		else 
		{
			$wpdb->insert($acl_table, array( 'object_id' => $object_id, 'object_type' => $object_type, 'accesses' => $accesses ),array( '%s')); 
		}
		return true;
	}
	
	
	
	/*
	*
	* have_acl_access($object_id, $object_type, $subject, $subject_type)
	* Проверяет доступ к объекту у переданого субъекта
	* Возвращает тип доступа или false если доступа нет
	*/
	
	function have_acl_access($object_id, $object_type, $subject, $subject_type){
	
		if (!$object_id) return false;
		if (!$object_type) return false;
		if (!$subject) return false;
		if (!$subject_type) $subject_type='user';
		
		global $wpdb;
		$acl_table = 'cases_acl';
		$acl_table = $wpdb->prefix . $acl_table;
		
		$acl_ids=$wpdb->get_row('SELECT * FROM '.$acl_table.' WHERE object_id="'.$object_id.'" and object_type="'.$object_type.'" ', ARRAY_A);
		
		$access_type = false;
		
		if (!empty($acl_ids))
		{
			$current_access = $acl_ids['accesses'];
			$current_access = unserialize($current_access);
			
			// таксаномия или конкретный пользователь. т.е. проверяем имеет ли данный пользователь
			// запись о том, что он имеет доступ к объекту. ИЛИ в случае проверки Имеет ли данная таксаномия запись о том, что имеется доступ.
			foreach ($current_access as $current)
			{
				
				if (($current['type'] == $subject_type)&&($current['subject'] == $subject)) 
					$access_type = $current['access_type'];
					
			}
			
			
			// если не найдено "прямых" связей проверяется наличие доступа у субъекта user к объекту, если субъект состоит в таксаномии, которая указана явно
			if ($subject_type == 'user' && !$access_type)
			{
				$c_person = get_person_by_user($subject);
				foreach ($current_access as $current)
				{
					if ($current['type'] == 'tax')
					{
						$taxs = get_object_taxs($c_person);
						if (in_array($current['subject'],$taxs)) $access_type = $current['access_type'];
					}
						
				}
			}
		}
		
		return $access_type;


	}
	
	function have_subs_acl_acceess($object_id, $object_type, $subject)
	{
		if (!$object_id) return false;
		if (!$object_type) return false;
		if (!$subject) return false;
		$subject_type='user';

		global $wpdb;
		$acl_table = 'cases_acl';
		$acl_table = $wpdb->prefix . $acl_table;
		
		$acl_ids=$wpdb->get_row('SELECT * FROM '.$acl_table.' WHERE object_id="'.$object_id.'" and object_type="'.$object_type.'" ', ARRAY_A);
		
		$access_type = false;
		
		$subs=get_substitution_by_user($subject);
		
		if (!empty($acl_ids))
		{
			$current_access = $acl_ids['accesses'];
			$current_access = unserialize($current_access);

			foreach ($current_access as $current)
			{
				if ($current['type'] == $subject_type)
					if (in_array($current['subject'],$subs) || $current['subject'] == $subject)
						$access_type = $current['access_type'];
			}
			
		}
		
		return $access_type;
	
	}


	/*
	*
	* remove_acl_access($object_id, $object_type, $subject, $subject_type)
	* Проверяет доступ к объекту у переданого субъекта
	* Возвращает тип доступа или false если доступа нет
	*/

	function remove_acl_access($object_id, $object_type, $subject, $subject_type){
		if (!$object_id) return false;
		if (!$object_type) return false;
		if (!$subject) return false;
		if (!$subject_type) $subject_type='user';
		
		global $wpdb;
		$acl_table = 'cases_acl';
		$acl_table = $wpdb->prefix . $acl_table;
		
		$acl_ids=$wpdb->get_row('SELECT * FROM '.$acl_table.' WHERE object_id="'.$object_id.'" and object_type="'.$object_type.'" ', ARRAY_A);
		
		if (!empty($acl_ids))
		{
			$id_cur=$acl_ids['id'];
			$current_access = $acl_ids['accesses'];
			$current_access = unserialize($current_access);
			
			$remove = false;
			$removed = false;
			
			foreach ($current_access as $key => $current)
			{
				if (($current['type'] == $subject_type)&($current['subject'] == $subject)) 
				{
					unset($current_access[$key]);
					$remove = true;
					break;
				}
					
			}
			if ($remove)
			{
				$current_access =serialize($current_access);
				$wpdb->update( $acl_table, array( 'accesses' => $current_access),array( 'id' => $id_cur ),	array( '%s' ), array( '%s' ));	
				$removed = true;
			}
			
		}
		
		return $removed;
	}
	
	
	
	function remove_acl_access_by_role($object_id, $object_type, $subject_type, $role){
		if (!$object_id) return false;
		if (!$object_type) return false;
		if (!$subject_type) $subject_type='user';
		
		global $wpdb;
		$acl_table = 'cases_acl';
		$acl_table = $wpdb->prefix . $acl_table;
		
		$acl_ids=$wpdb->get_row('SELECT * FROM '.$acl_table.' WHERE object_id="'.$object_id.'" and object_type="'.$object_type.'" ', ARRAY_A);
		
		if (!empty($acl_ids))
		{
			$id_cur=$acl_ids['id'];
			$current_access = $acl_ids['accesses'];
			$current_access = unserialize($current_access);
			
			$remove = false;
			$removed = false;
			
			foreach ($current_access as $key => $current)
			{
				if (($current['type'] == $subject_type)&($current['access_type'] == $role)) 
				{
					unset($current_access[$key]);
					$remove = true;
					
				}
					
			}
			if ($remove)
			{
				$current_access =serialize($current_access);
				$wpdb->update( $acl_table, array( 'accesses' => $current_access),array( 'id' => $id_cur ),	array( '%s' ), array( '%s' ));	
				$removed = true;
			}
			
		}
		
		return $removed;
	}
	
	


	function get_acl_access_by_object($object_id, $object_type){
		if (!$object_id) return false;
		if (!$object_type) return false;
		
		global $wpdb;
		$acl_table = 'cases_acl';
		$acl_table = $wpdb->prefix . $acl_table;
		
		$acl_ids=$wpdb->get_row('SELECT * FROM '.$acl_table.' WHERE object_id="'.$object_id.'" and object_type="'.$object_type.'" ', ARRAY_A);
		
		$current_access = false;
		
		if (!empty($acl_ids))
		{
			$current_access = $acl_ids['accesses'];
			$current_access = unserialize($current_access);
			
		}
		
		return $current_access;
	}

	
	/*
	*
	* remove_acl_access($object_id, $object_type, $subject, $subject_type)
	* Проверяет доступ к объекту у переданого субъекта
	* Возвращает тип доступа или false если доступа нет
	* $object_id, $object_type, $subject Обязательные поля. 
	*/

	
function append_acl_access($object_id, $object_type, $subject, $subject_type, $access_type){
		if (!$object_id) return false;
		if (!$object_type) return false;
		if (!$subject) return false;
		if (!$subject_type) $subject_type='user';
		if (!$access_type) $access_type='member';
		global $wpdb;
		$acl_table = 'cases_acl';
		$acl_table = $wpdb->prefix . $acl_table;
		
		$acl_ids=$wpdb->get_row('SELECT * FROM '.$acl_table.' WHERE object_id="'.$object_id.'" and object_type="'.$object_type.'" ', ARRAY_A);
		
	
		$append = false;
		
		if (!empty($acl_ids))
		{
			$id_cur=$acl_ids['id'];
			$current_access = $acl_ids['accesses'];
			$access=array();
			$access = unserialize($current_access);
		//	return $id_cur;

		/*	foreach ($access as $key => &$current)
			{
				if (($current['type'] == $subject_type)&&($current['subject'] == $subject)) 
				{
					$current['access_type'] = $access_type;
					$append = true;
					break;
				}
					
			}
			if (!$append)
			{*/
				$access[] =array (
					'type' => $subject_type,
					'subject' => $subject,
					'access_type' => $access_type
				);
				$append = true;
		//	}
			
			$access =serialize($access);
			$wpdb->update( $acl_table, array( 'accesses' => $access),array( 'id' => $id_cur ),	array( '%s' ), array( '%s' ));

		}
		else 
		{
			$accesses[] = array (
				'type' => $subject_type,
				'subject' => $subject,
				'access_type' => $access_type
			);
			$accesses =serialize($accesses);
			$wpdb->insert($acl_table, array( 'object_id' => $object_id, 'object_type' => $object_type, 'accesses' => $accesses ),array( '%s')); 
			$append = true;
		}
		
		return $append;
	}
	
	
/*	

function first_acl_update(){
global $wpdb;
$ids=$wpdb->get_results("SELECT ID FROM wp_posts where post_type='cases' limit 15000"); 
foreach($ids as $ps){
$output.=$ps->ID;
$acl_table=$wpdb->get_var("SELECT id FROM wp_cases_acl where post_id='".$ps->ID."'");
if (!isset($acl_table))
{
$output.=$ps->ID;
//remove_acl_access_by_role($post->ID,'member');
$responsible=get_post_meta($ps->ID,'responsible',true);
$initiator=get_post_meta($ps->ID,'initiator',true);
$participant=get_post_meta($ps->ID,'participant',true);
if (strlen($responsible)>0)
{
$responsible=get_user_by_person($responsible);
if ($responsible!=0) update_acl($ps->ID,$responsible,'0','member');
}
if (strlen($initiator)>0){
$initiator=get_user_by_person($initiator);
if ($initiator!=0) update_acl($ps->ID,$initiator,'0','member');
} 
if (strlen($participant)>0){
$count_elems=substr_count($participant,",");
if ($count_elems>0)
{
$part_mas=explode( ',', $participant ); 
for ($i=0;$i<count($part_mas);$i++){
$participant=get_user_by_person($part_mas[$i]);
if ($participant!=0) update_acl($ps->ID,$participant,'0','member'); 
}
}	else { 
	$participant=get_user_by_person($participant);
	update_acl($ps->ID,$participant,'0','member'); 
	}
}
}
}
return $output;
}
	
	*/
	
function acl_posts_results( $posts ) {
	if ( current_user_can( 'manage_options' ) )
	return $posts;
	global $wpdb, $wp_query;
	$acl_table = 'cases_acl';
	$acl_table = $wpdb->prefix . $acl_table;
	$user = get_current_user_id();
	$i=0;
	foreach ($posts as $post){
		if ($post->post_type=='report'){
			$elem=$wpdb->get_var("SELECT id FROM wp_cases_acl where object_id='".$post->ID."'");
			if ($elem==null)
			{
				$acl_allowed_posts[$i]=$post; $i++;
			}
			else
			{
				
				if (have_subs_acl_acceess($post->ID,'post',$user)) { $acl_allowed_posts[$i]=$post; $i++;}
			}
		}
		else
		{
			if ($post->post_type!='cases') {$acl_allowed_posts[$i]=$post; $i++; } else
				if (have_subs_acl_acceess($post->ID,'post',$user)) { $acl_allowed_posts[$i]=$post; $i++;}
		}
	}
	return $acl_allowed_posts;
}
//add_filter('posts_results','acl_posts_results');



	if (get_option( 'enable_acl' ) == '1') add_filter( 'posts_where' , 'allow_ids' );
	function allow_ids($where='')
	{
		global $wpdb, $wp_query;
		
		
	//	update_post_meta(2696,'test_meta2', $where);
	if( current_user_can('manage_options') ){ return $where.= 'AND 2=2'; } 
		
	//	if(strpos($where, "wp_posts.post_type IN ('cases')") >0 )
	//	{
			
			$acl_table = 'cases_acl';
			$acl_table = $wpdb->prefix . $acl_table;
			
			$subs_table = 'cases_substitution';
			$subs_table = $wpdb->prefix . $subs_table;
			
			$user = get_current_user_id();
			$find = '%:"'.$user.'";%' ;
			$find = 'where accesses LIKE \''.$find.'\' ';
			
			$subs=$wpdb->get_results('SELECT replaceable FROM '.$subs_table.' where substitutional= '.$user.' and state= \'open\'');
			//update_post_meta(2696,'test_sql',$subs.' '.$user);
			if (count($subs)>0)
				foreach ($subs as $sub)
				{
					$usr = '%:"'.$sub->replaceable.'";%';  
					$find.=' or accesses LIKE \''.$usr.'\'';
				}
			
			$in = '';
			
			$allows=$wpdb->get_results('SELECT object_id FROM '.$acl_table.' '.$find.' ');
			//update_post_meta(2696,'test_sql','SELECT object_id FROM '.$acl_table.' '.$find.' ');
			foreach ($allows as $allow)
			{
	
				if ($in == '')
				{
					$in = $allow->object_id;
				}
				else
				{
					$in .= ', '.$allow->object_id;
				}
			}
		
			
			
			$where .= ' AND if(wp_posts.post_type = \'cases\',wp_posts.ID IN ( '.$in.' ),3=3)=1 ';
			return $where;
	/*	}
		else
		{
			return $where.= 'AND 2=2';
		}
	*/	
	}
	
	//add_shortcode('check_access_sql','allow_ids');
	
/*	function acl_restrict_queries( $wp_query ) {

		if ( 'cases' == $wp_query->get( 'post_type' ) )
		{
			
			$out = allow_ids();
		//	$array = array_map( 'trim', explode( ',', $out ) );
			//$time = time();
			$wp_query->set( 'post__in', $out );
			update_post_meta(2696,'test_meta2',$wp_query->query);

		}
	}
	add_action( 'pre_get_posts', 'acl_restrict_queries' );


	*/
	
	
	


	function acl_report_add_meta_box() {
	add_meta_box( 'report_acl_metabox','report_acl', 'acl_report_meta_box', 'report', 'normal', 'high' );
	}
	add_action( 'add_meta_boxes', 'acl_report_add_meta_box' );
	
	function acl_report_meta_box() {
		$all_users = get_users('orderby=display_name');  
		echo '<select name="report_acl[]" id="report_acl" class=camech-init-chosen multiple=multiple>';
		foreach ($all_users as $user) {
		if (have_acl_access($_GET['post'],$user->ID,'user') == 'yes') $select=true; else $select=false;
		echo '<option value="' . $user->ID . '"', $select == true ? ' selected="selected"' : '', '>' . $user->display_name . '</option>';
		}
		echo '</select>';
	}


	
	function update_acl_person( $post_id ){
		if (isset($_POST['report_acl']))
		{
		$acl_users=$_POST['report_acl'];     
        foreach ($acl_users as $item){
		update_acl($post_id,$item,'0','read');
		}
//		update_post_meta($post_id, 'acl_test', $_POST['report_acl']);
		}
	}
	add_action('post_updated', 'update_acl_person');
	
	//replaceable-замещаемый substitutional-замещающий
	function get_substitution_by_user($substitutional){
	if (!$substitutional) return false;
	global $wpdb;
	$sub_table = 'cases_substitution';
	$sub_table = $wpdb->prefix . $sub_table;	
	$subs_ids=$wpdb->get_results(
	"SELECT * FROM ".$sub_table." WHERE substitutional=".$substitutional." and state='open' limit 100");
	$count_sub_ids=0;
	$output=array();
	foreach ($subs_ids as $sub_id){
	if ($sub_id->date_start>0 and $sub_id->date_end>0){
		$date_start = date_create($sub_id->date_start);
		$date_end = date_create($sub_id->date_end);
		$date_start = date_format($date_start, 'Y-m-d');
		$date_end = date_format($date_end, 'Y-m-d');
		$now_date=date('Y-m-d');
		if ($now_date>=$date_start&& $now_date<=$date_end)
		array_push($output,$sub_id->replaceable);
	} else array_push($output,$sub_id->replaceable);
	}
	if (count($output)==0) array_push($output,0);
	return $output; 
	
}

function get_all_subs(){
	global $wpdb;
	$sub_table = 'cases_substitution';
	$sub_table = $wpdb->prefix . $sub_table;
	$sub_ids=$wpdb->get_results("SELECT * FROM ".$sub_table."");
	$i=0;
	$all_sub=array();
	foreach ($sub_ids as $sub_id){
	$all_sub[$i][0]=$sub_id->id;
	$all_sub[$i][1]=$sub_id->replaceable;
	$all_sub[$i][2]=$sub_id->substitutional;
	$all_sub[$i][3]=$sub_id->date_start;
	$all_sub[$i][4]=$sub_id->date_end;
	$all_sub[$i][5]=$sub_id->type;
	$all_sub[$i][6]=$sub_id->state;
	$i++;
	}
	return $all_sub;
}

	
function update_subs($replaceable,$substitutional,$date_start,$date_end,$type,$state){
	if (!$replaceable) return false;
	if (!$substitutional) return false;
	if (!$type) $type='base';
	if (!$state) $state='open';
	global $wpdb;
	$sub_table = 'cases_substitution';
	$sub_table = $wpdb->prefix . $sub_table;
	$sub_ids=$wpdb->get_results(
	"SELECT * FROM ".$sub_table." WHERE replaceable=".$replaceable." and substitutional=".$substitutional." LIMIT 5");
		$count_sub_ids=0;
		foreach ($sub_ids as $sub_id){
			$count_sub_ids++;
			$id_cur=$sub_id->id;
			if ($sub_id->date_start>0 and $sub_id->date_end>0){
			$date_start = date_create($sub_id->date_start);
			$date_end = date_create($sub_id->date_end);
			$date_start = date_format($date_start, 'Y-m-d');
			$date_end = date_format($date_end, 'Y-m-d');
			} else
			{
			$date_start='';
			$date_end='';
			}
			}
		if ($count_sub_ids>1) return false;
		if ($count_sub_ids==1) // update ИСПРАВИТЬ!!!! 
		{
		$wpdb->update( $sub_table,
		array( 'date_start' => $date_start, 'date_end' => $date_end, 'type' => $type, 'state' => $state),
		array( 'id' => $id_cur ), array( '%s','%s','%s','%s' ), array( '%d' ));

		} else //add new 
		{
	$wpdb->insert($sub_table,
	array( 'date_start' => $date_start, 'date_end' => $date_end, 'replaceable' => $replaceable, 'substitutional' => $substitutional, 'type' => $type, 'state' => 'open' ),array( '%s','%s','%d','%d','%s','%s')); 
	}
	return 'yes';
}	 

add_action('admin_menu', 'add_menu_sub');
	function add_menu_sub(){
    add_menu_page("Замещения", "Замещения", 'manage_options', "substitutions", "substitutions_menu_common", null, 31);
} 
 

 
 
function substitutions_menu_common(){
/*
define('CHEKURL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) ); 
function acl_ajax(){
wp_enqueue_script('admin_acl_load', CHEKURL.'/admin_acl_load.js', array('jquery'));
wp_localize_script('admin_acl_load', 'aclajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )); 
}
add_action('wp_enqueue_scripts', 'acl_ajax');


*/
define('ACLAJAXURL', WP_PLUGIN_URL."/cases-acl"); 
wp_enqueue_script('admin_acl_load', ACLAJAXURL.'/admin_acl_load.js', array('jquery'));
wp_localize_script('admin_acl_load', 'aclajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )); 

  ?>
  <h2><?php echo get_admin_page_title()?></h2>
  <?
  echo '<div id="substitution_table">';
  echo '</br>';
  $subs=get_all_subs();
  echo '<table>';
  echo '<tr><td>id</td><td>Замещаемый</td><td>Замещающий</td>
  <td>Дата начала</td><td>Дата окончания</td><td>Тип замещения</td><td>Статус</td><td></td></tr>';
  for ($i=0;$i<count($subs);$i++)
  {
  $us=get_userdata( $subs[$i][2]);
  $usr=get_userdata( $subs[$i][1]);
  if ($subs[$i][6]=='open') 
  $ocbutton='<td><input name="add" id="'.$subs[$i][0].'" class="close_subst" type=button value=Закрыть /></td>';
  else
  $ocbutton='<td><input name="add" id="'.$subs[$i][0].'" class="open_subst" type=button value=Открыть /></td>';
  echo '<tr><td>'.$subs[$i][0].'</td><td>'.$us->display_name.' ('.$us->user_email.')</td><td>'.$usr->display_name.' ('.$us->user_email.')</td>
  <td>'.$subs[$i][3].'</td><td>'.$subs[$i][4].'</td><td>'.$subs[$i][5].'</td><td>'.$subs[$i][6].'</td>
  <td>'.$ocbutton.'</td></tr>';	 
  }
  echo '</table>';
  echo '</div>';
  $now_date=date('Y-m-d');
  //echo '<input id="replaceable" type=text value="" />';
  	$all_users = get_users('orderby=display_name');  
	echo 'Замещаемый <select name="substitutional" id="substitutional" class=chzn-select>';
	foreach ($all_users as $user) {
	echo '<option value="' . $user->ID . '">' . $user->display_name .' ('.$user->user_email.') </option>';}
	echo '</select>';
	echo 'Замещающий <select name="replaceable" id="replaceable" class=chzn-select>';
	foreach ($all_users as $user) {
	echo '<option value="' . $user->ID . '">' . $user->display_name .' ('.$user->user_email.') </option>';}
	echo '</select></br>';
 // echo '<input id="substitutional" type=text value="" />';
  echo 'Дата начала замещения <input id="date_start" type=date value="" />';
  echo 'Дата окончания замещения <input id="date_end" type=date value="" />';
  echo '<input name="add" class="check_acl" type=button value=Добавить />';
 // echo '<div id="substitution_table"></div>';
  }
  
  
 add_action('wp_ajax_sub_acl_update', 'sub_acl_update');			
function sub_acl_update(){
update_subs($_POST['replaceable'],$_POST['substitutional'],$_POST['date_start'],$_POST['date_end'],'base','open');
  echo '</br>';
  $subs=get_all_subs();
  echo '<table>';
  echo '<tr><td>id</td><td>Замещаемый</td><td>Замещающий</td>
  <td>Дата начала</td><td>Дата окончания</td><td>Тип замещения</td><td>Статус</td></tr>';
  for ($i=0;$i<count($subs);$i++)
  {
  $us=get_userdata( $subs[$i][2]);
  $usr=get_userdata( $subs[$i][1]);
  if ($subs[$i][6]=='open') 
  $ocbutton='<td><input name="add" id="'.$subs[$i][0].'" class="close_subst" type=button value=Закрыть /></td>';
  else
  $ocbutton='<td><input name="add" id="'.$subs[$i][0].'" class="open_subst" type=button value=Открыть /></td>';
  echo '<tr><td>'.$subs[$i][0].'</td><td>'.$us->display_name. ' ('.$us->user_email.')</td><td>'.$usr->display_name.' ('.$us->user_email.')</td>
  <td>'.$subs[$i][3].'</td><td>'.$subs[$i][4].'</td><td>'.$subs[$i][5].'</td><td>'.$subs[$i][6].'</td>
  <td>'.$ocbutton.'</td></tr>';	
  }
  echo '</table>';
die;
}	

 add_action('wp_ajax_sub_acl_close', 'sub_acl_close');
function sub_acl_close(){
	global $wpdb;
	$sub_table = 'cases_substitution';
	$sub_table = $wpdb->prefix . $sub_table;
$wpdb->update( $sub_table,array( 'state' => 'close'),array( 'id' => $_POST['id_sub'] ), array('%s' ), array( '%d' ));
  echo '</br>';
  $subs=get_all_subs();
  echo '<table>';
  echo '<tr><td>id</td><td>Замещаемый</td><td>Замещающий</td>
  <td>Дата начала</td><td>Дата окончания</td><td>Тип замещения</td><td>Статус</td></tr>';
  for ($i=0;$i<count($subs);$i++)
  {
  $us=get_userdata( $subs[$i][2]);
  $usr=get_userdata( $subs[$i][1]);
  if ($subs[$i][6]=='open') 
  $ocbutton='<td><input name="add" id="'.$subs[$i][0].'" class="close_subst" type=button value=Закрыть /></td>';
  else
  $ocbutton='<td><input name="add" id="'.$subs[$i][0].'" class="open_subst" type=button value=Открыть /></td>';
  echo '<tr><td>'.$subs[$i][0].'</td><td>'.$us->display_name.' ('.$us->user_email.')</td><td>'.$usr->display_name.' ('.$us->user_email.')</td>
  <td>'.$subs[$i][3].'</td><td>'.$subs[$i][4].'</td><td>'.$subs[$i][5].'</td><td>'.$subs[$i][6].'</td>
  <td>'.$ocbutton.'</td></tr>';	
  }
  echo '</table>';
die;	
}


 add_action('wp_ajax_sub_acl_open', 'sub_acl_open');
function sub_acl_open(){
	global $wpdb;
	$sub_table = 'cases_substitution';
	$sub_table = $wpdb->prefix . $sub_table;
$wpdb->update( $sub_table,array( 'state' => 'open'),array( 'id' => $_POST['id_sub'] ), array('%s' ), array( '%d' ));
  echo '</br>';
  $subs=get_all_subs();
  echo '<table>';
  echo '<tr><td>id</td><td>Замещаемый</td><td>Замещающий</td>
  <td>Дата начала</td><td>Дата окончания</td><td>Тип замещения</td><td>Статус</td></tr>';
  for ($i=0;$i<count($subs);$i++)
  {
  $us=get_userdata( $subs[$i][2]);
  $usr=get_userdata( $subs[$i][1]);
  if ($subs[$i][6]=='open') 
  $ocbutton='<td><input name="add" id="'.$subs[$i][0].'" class="close_subst" type=button value=Закрыть /></td>';
  else
  $ocbutton='<td><input name="add" id="'.$subs[$i][0].'" class="open_subst" type=button value=Открыть /></td>';
  echo '<tr><td>'.$subs[$i][0].'</td><td>'.$us->display_name.' ('.$us->user_email.')</td><td>'.$usr->display_name.' ('.$us->user_email.')</td>
  <td>'.$subs[$i][3].'</td><td>'.$subs[$i][4].'</td><td>'.$subs[$i][5].'</td><td>'.$subs[$i][6].'</td>
  <td>'.$ocbutton.'</td></tr>';	
  }
  echo '</table>';
die;	
}


/*
function auto_update_acl($meta_id, $object_id, $meta_key, $meta_value){
	global $wpdb;
	
//	update_acl($post_id,$id_user,$id_group,$role)
	//update_acl($object_id,$meta_value,'0',$meta_key);

	
	//if ('cases' == $object_id->post_type)
	if (('initiator' == $meta_key) || ('responsible' == $meta_key) || ('participant' == $meta_key))
	{
	//	$test=update_acl($object_id,$meta_value,'0',$meta_key);
//	}

	if ('initiator' == $meta_key)
		if ($meta_value!=0){
			$initiator=get_user_by_person($meta_value);
			update_acl($object_id,$initiator,'0','member');
		}

	if ('responsible' == $meta_key)
		if ($meta_value!=0){
			$responsible=get_user_by_person($meta_value);
			update_acl($object_id,$responsible,'0','member');
		}
		
	if ('participant' == $meta_key)
		if ($meta_value!=0){
		$count_elems=substr_count($meta_value,",");
		if ($count_elems>0){
			$elems=explode(",",$meta_value);
			for ($i=0;$i<count($elems);$i++){
				$participant=get_user_by_person($elems[$i]);
				update_acl($object_id,$participant,'0','member');
			}
		}
		else{
			$participant=get_user_by_person($meta_value);
			update_acl($object_id,$participant,'0','member');
			} 
		}	
	}
}
add_action( 'updated_post_meta', 'auto_update_acl', 5, 4 );
add_action( 'added_post_meta', 'auto_update_acl', 5, 4 );
*/


add_action('wp_ajax_subst_update', 'subst_update');			
function subst_update(){
	global $wpdb;
	$sub_table = 'cases_substitution';
	$sub_table = $wpdb->prefix . $sub_table;
	$sub_ids=$wpdb->get_results(
	"SELECT * FROM ".$sub_table." WHERE id=".$_POST['sub_id']." limit 1");
	foreach ($sub_ids as $sub_id){
		$wpdb->update( $sub_table,
		array( 'replaceable' => $_POST['replaceable'], 'substitutional' => $_POST['substitutional'], 'date_start' => $_POST['date_start'], 'date_end' => $_POST['date_end'], 'type' => $_POST['type'], 'state' => $_POST['state']),
		array( 'id' => $_POST['sub_id'] ), array( '%d','%d','%s','%s','%s','%s' ), array( '%d' ));
	}
echo "Замещение обновлено  <a href=http://".$_SERVER['HTTP_HOST']."/wp-admin/admin.php?page=subs_all > Список замещений </a>";
 
die;
}	

add_action('wp_ajax_subst_create', 'subst_create');			
function subst_create(){
	global $wpdb;
	$sub_table = 'cases_substitution';
	$sub_table = $wpdb->prefix . $sub_table;
	$wpdb->insert($sub_table,
	array( 'date_start' => $_POST['date_start'], 'date_end' => $_POST['date_end'], 'replaceable' => $_POST['replaceable'], 'substitutional' => $_POST['substitutional'], 'type' => $_POST['type'], 'state' => $_POST['state'] ),array( '%s','%s','%d','%d','%s','%s')); 
echo "Замещение добалено  <a href=http://".$_SERVER['HTTP_HOST']."/wp-admin/admin.php?page=subs_all > Список замещений </a>";
 
die;
}






?>