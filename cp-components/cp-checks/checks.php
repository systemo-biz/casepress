<?php

	 
	define('CHEKURL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
	define('CHECKPATH', WP_PLUGIN_DIR."/".dirname( plugin_basename( __FILE__ ) ) );
	// Frontend and backend metaboxes
	global $new_check_id;
	global $args;
	global $cur_check_id;
	global $check_current_number;
	
	

	function cheklist_meta_box() {
	
		// Prepare variables
		global $post;
		$post_id = $post->ID;
		
		// Show meta box only on cases pages
		if ( is_single() && get_post_type() == 'cases' ) {
			require_once('form.php');	
		
		}
	}
	add_action( 'roots_entry_content_after', 'cheklist_meta_box', -20);
		
	function chk_enqueuescripts()
	{
		if ( is_single() && get_post_type() == 'cases' ) {
			wp_register_style('myStyleSheets_check', CHEKURL. '/css/ch_style.css');
			wp_enqueue_style( 'myStyleSheets_check');
			wp_enqueue_script('jquery.ui.custom.min', CHEKURL.'/js/jquery.ui.custom.min.js');
			wp_enqueue_script('nestedSortable', CHEKURL.'/js/nestedSortable.js');
			wp_enqueue_script('chk', CHEKURL.'/js/chk.js', array('jquery'));
			wp_localize_script( 'chk', 'checkaj', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );    
			
		}
	}	
	add_action('wp_enqueue_scripts', 'chk_enqueuescripts',100);
	
	//Рекурсивная функция отрисовки списков задач
	function get_args_check($check_id,$count){
	
	$check_current_number = $count;
	$new_check_id = $check_id;
		$args = array(  
			'post_type'       => 'checklist',
			'numberposts'     => -1,
			'post_parent'     => $new_check_id,
			'orderby'         => 'menu_order',	
			'order' 		  => 'ASC',
			'post_status'     => 'publish'
		);  
	$childrens = get_posts($args); 
		if (count($childrens) != 0){
			$i = 0;
			echo "<ul id='".$new_check_id."'style='list-style: none;'>";
			foreach($childrens as $children){
				setup_postdata($children);
				$cur_check_id = $children->ID;
				echo "<li class='check_li_test' id='ch_li_".$new_check_id."_".$count."'  name='".$cur_check_id."' >";
				
				$cur_check_title =  get_the_title($cur_check_id);
				$cur_check_state = 	get_the_content($cur_check_id);
				if ($cur_check_state == '0'){
					$is_checked_string = 'unchecked';
					$is_checked_string2 = 'none';
				}						
				else {
					$is_checked_string = 'checked';
					$is_checked_string2 = 'line-through';
				}
				echo "<div id='ch_id".$new_check_id."_".$i."' class='div_check_container' name='".$new_check_id."'>
				<img src='".CHEKURL."/".$is_checked_string.".png' class='check_".$is_checked_string."_image'>
				<input value='".$cur_check_title."' style='display: none;' class='check_form' name='loaded'>
				<span class='check_form_sp' style='text-decoration: ".$is_checked_string2.";'>".$cur_check_title."</span>
				<img src='".CHEKURL."/accept.png' class='check_ok_image'>
				<img src='".CHEKURL."/delete.png' class='check_del_image' style='display:none;'></div>";
				$count++;
				$i++;
				get_args_check($cur_check_id,$count);
				echo "</li>";
			}
			echo "</ul>";
		}	
		else echo "<ul id='".$new_check_id."'></ul>";
	}
	
	
	function save_my_check_please_(){
		$check_post_value = '0';			
		global $current_user;  
		get_currentuserinfo();
		
		$check_post_title = $_POST['check_title'];
		$parent_id = $_POST['check_parent_id'];
		$menu_order = $_POST['check_added_order'];
		// Create post object
		$my_post = array(
			'post_title' => $check_post_title,
			'post_content' => $check_post_value,
			'post_status' => 'publish',
			'post_author' => $current_user->ID,
			'post_category' => array(8,39),
			'post_parent' => $parent_id,
			'post_type' => 'checklist',
			'menu_order' => $menu_order
			
		);
		
		// Insert the post into the database
		$new_post_id = wp_insert_post( $my_post );
		echo $new_post_id;
		update_post_meta($new_post_id,'check_parent',$parent_id);
		
		die();
	}
	add_action( 'wp_ajax_save_my_check_please', 'save_my_check_please_' );
	
	function remove_my_check_please_(){
		if ((isset($_POST['ch_remove_id']))&&(isset($_POST['ch_post_parent']))){
			$post_for_remove = $_POST['ch_remove_id'];
			$post_for_remove_parent = $_POST['ch_post_parent'];
			
			wp_delete_post($post_for_remove);
			delete_post_meta($post_for_remove);
			
			if ($post_for_remove !=''){
				$args = array(  
					'post_type'       => 'checklist',
					'numberposts'     => -1,
					'post_parent'     => $post_for_remove,
					'orderby'         => 'id',	
					'order' 		  => 'ASC',
					'post_status'     => 'publish'
				);  
				$childrens = get_posts($args);
				$update_post = array();
				foreach ($childrens as $children){
					setup_postdata($children);
					$update_post['ID'] = $children->ID;
					$update_post['post_parent'] = $post_for_remove_parent;
					wp_update_post( $update_post );	
				}
			}
		}
	}
	add_action( 'wp_ajax_remove_my_check_please', 'remove_my_check_please_' );
	
	function change_check_value(){
		$new_check_value = $_POST['ch_check_value'];
		$new_check_value_id = $_POST['ch_check_value_id'];
		$update_post = array();
		$update_post['ID'] = $new_check_value_id;
		$update_post['post_content'] = $new_check_value;
		wp_update_post( $update_post );	
	}
	add_action( 'wp_ajax_change_check_value', 'change_check_value' );
	
	function change_uncheck_value(){
		$new_uncheck_value = $_POST['ch_uncheck_value'];
		$new_uncheck_value_id = $_POST['ch_uncheck_value_id'];
		$unupdate_post = array();
		$unupdate_post['ID'] = $new_uncheck_value_id;
		$unupdate_post['post_content'] = $new_uncheck_value;
		wp_update_post( $unupdate_post );
	}
	add_action( 'wp_ajax_change_uncheck_value', 'change_uncheck_value' );
	
	function save_current_check(){
		$new_ch_current_check_title = $_POST['ch_current_check_title'];
		$new_ch_post_to_update_id = $_POST['ch_current_check_id'];
		$to_update_post = array();
		$to_update_post['ID'] = $new_ch_post_to_update_id;
		$to_update_post['post_title'] = $new_ch_current_check_title;
		wp_update_post( $to_update_post );
		echo "Complited".$new_ch_post_to_update_id." :".$new_ch_current_check_title;
	}
	add_action( 'wp_ajax_save_current_check', 'save_current_check' );
	
	function check_update_post_relative(){
		$to_update_post = array();
		$to_update_post['ID'] = $_POST['check_id'];
		$to_update_post['post_parent'] = $_POST['check_new_parent_id'];
		$to_update_post['menu_order'] = $_POST['chek_new_index_value'];
		$changed_post = get_post($to_update_post['ID']);
		wp_update_post( $to_update_post );
		update_post_meta($to_update_post['ID'],'check_parent',$to_update_post['post_parent']);
		//echo 'post_id: '.$to_update_post['ID'].' post_parent_id: '.$to_update_post['post_parent'].' menu_order: '.$to_update_post['menu_order'];
		/*if ($changed_post->menu_order < $to_update_post['menu_order'])
		{
			$args = array(  
				'post_type'       => 'checklist',
				'numberposts'     => -1,
				'post_parent'     => $to_update_post['post_parent'],
				'orderby'         => 'menu_order',
				'order' 		  => 'ASC',				
				'post_status'     => 'publish'
			);  
			$childrens = get_posts($args); 
		
		
			foreach ($childrens as $children){
				setup_postdata($children);
				if ($children->ID != $_POST['check_id'] && $children->menu_order <= $_POST['chek_new_index_value'])
				{			
						$current_item_order = $children->menu_order;
						$to_update_post['ID'] = $children->ID;
						$to_update_post['menu_order'] = $current_item_order-1;
						wp_update_post( $to_update_post );
				}
			}
		
		}
		else
		{
			$args = array(  
				'post_type'       => 'checklist',
				'numberposts'     => -1,
				'post_parent'     => $to_update_post['post_parent'],
				'orderby'         => 'menu_order',	
				'post_status'     => 'publish'
			);  
			$childrens = get_posts($args); 
			
			
			foreach ($childrens as $children){
				setup_postdata($children);
				if ($children->ID != $_POST['check_id'] && $children->menu_order >= $_POST['chek_new_index_value'])
				{			
						$current_item_order = $children->menu_order;
						$to_update_post['ID'] = $children->ID;
						$to_update_post['menu_order'] = $current_item_order+1;
						wp_update_post( $to_update_post );
							
				}
			}
		}	*/
	}
	add_action( 'wp_ajax_check_update_post_relative', 'check_update_post_relative');
	
	//function check_postupdate_relatives($previou)
	
	function check_update_post_data(){
		$to_update_post = array();
		$to_update_post['ID'] = $_POST['check_id'];
		$to_update_post['post_title'] = $_POST['check_new_title'];
		wp_update_post( $to_update_post );
	
	}
	add_action(	'wp_ajax_check_update_post_data', 'check_update_post_data');
	
?>
