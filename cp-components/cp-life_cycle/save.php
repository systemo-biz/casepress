<?


	function update_life_cycle_meta( $post_id )
	{
		$post = get_post($post_id);
		if ($post->post_type== "life_cycle" )
		{ 
					
		
			if (isset($_POST['cp_posts_life_cycle_default']))
			{
				$args = array(  
					'numberposts'     => -1,  
					'meta_key'        => 'cp_posts_life_cycle_default',  
					'meta_value'      => 'yes',  
					'post_type'       => 'life_cycle',  
					'post_status'     => 'publish'  
				);  
				  
				$posts = get_posts($args); 
				foreach ($posts as $post)
				{
					delete_post_meta($post->ID,'cp_posts_life_cycle_default');
				}
			
				update_post_meta($post_id,'cp_posts_life_cycle_default','yes');
			
			}

			//СОСТОЯНИЯ
			if (isset($_POST['cp_posts_life_cycle_state']))
			if (strlen($_POST['cp_posts_life_cycle_state'])>0)
			{
				//заполнил массив новых данных
				$elems = explode(',',$_POST['cp_posts_life_cycle_state']);
				$res_array = array();
				foreach ($elems as $elem)
				{
					if (is_numeric($elem))
					{
						$res_array[]=$elem;
					}
					else
					{
						$term = wp_insert_term( $elem, 'state' );
						$res_array[]=$term['term_id'];
					}
				}
				
				//объединение старых и новых данный. актуальные данные
				$state_cur = get_post_meta($post_id,'cp_posts_life_cycle_state');
				$delete = array_diff($state_cur, $res_array);
				foreach ($delete as $elem)
				{
					delete_post_meta($post_id,'cp_posts_life_cycle_state',$elem);
				} 
				
				$add = array_diff($res_array, $state_cur);
				foreach ($add as $elem)
				{
					add_post_meta($post_id,'cp_posts_life_cycle_state',$elem);
				}
				
				//создание метаполя позиций
				$output = implode(",", $res_array);
				update_post_meta($post_id,'cp_posts_life_cycle_state_positions',$output);
			}
			
			//РЕЗУЛЬТАТЫ
			if (isset($_POST['cp_posts_life_cycle_results']))
			if (strlen($_POST['cp_posts_life_cycle_state'])>0)
			{
				//заполнил массив новых данных
				$elems = explode(',',$_POST['cp_posts_life_cycle_results']);
				$res_array = array();
				foreach ($elems as $elem)
				{
					if (is_numeric($elem))
					{
						$res_array[]=$elem;
					}
					else
					{
						$term = wp_insert_term( $elem, 'results' );
						$res_array[]=$term['term_id'];
					}
				}
				
				//объединение старых и новых данный. актуальные данные
				$res_cur = get_post_meta($post_id,'cp_posts_life_cycle_results');
				$delete = array_diff($res_cur, $res_array);
				foreach ($delete as $elem)
				{
					delete_post_meta($post_id,'cp_posts_life_cycle_results',$elem);
				} 
				
				$add = array_diff($res_array, $res_cur);
				foreach ($add as $elem)
				{
					add_post_meta($post_id,'cp_posts_life_cycle_results',$elem);
				}
				
				//создание метаполя позиций
				$output = implode(",", $res_array);
				update_post_meta($post_id,'cp_posts_life_cycle_results_positions',$output);
			}
			
			
			//ФУНКЦИИ
			if (isset($_POST['cp_posts_life_cycle_functions']))
			if (strlen($_POST['cp_posts_life_cycle_state'])>0)
			{
				//заполнил массив новых данных
				$elems = explode(',',$_POST['cp_posts_life_cycle_functions']);
				$res_array = array();
				foreach ($elems as $elem)
				{
						$res_array[]=$elem;
				}
				
				//объединение старых и новых данный. актуальные данные
				$func_cur = get_post_meta($post_id,'cp_posts_life_cycle_functions');
				$delete = array_diff($func_cur, $res_array);
				foreach ($delete as $elem)
				{
					delete_post_meta($post_id,'cp_posts_life_cycle_functions',$elem);
				} 
				
				$add = array_diff($res_array, $func_cur);
				foreach ($add as $elem)
				{
					add_post_meta($post_id,'cp_posts_life_cycle_functions',$elem);
				}
			}
			
			
	
		}
	}
	add_action( 'save_post', 'update_life_cycle_meta' );
	
	
?>