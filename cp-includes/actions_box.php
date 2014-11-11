<?php
	/**
	 * Functions Action Box
	 */
	function actions_box_shortcode(){
		if(is_tax('functions') || is_singular('cases') || is_post_type_archive('cases')){ 	
			$return = '<ul class="actions_box">';	
			if (is_post_type_archive('cases')){
			
				$return .= '<li><a href="'.admin_url( 'post-new.php?post_type=cases', 'http' ).'">Добавить задачу</a></li>';  
			}else{
				if (!is_singular('cases')){
					global $wp_query;
					$queried_object = get_queried_object();
					$term_id = $queried_object->term_id; 
				}else{
					global $post;
					$terms = get_the_terms( $post->id, 'functions' );
					if (is_array($terms)) $term = array_shift($terms);
					$term_id = $term->term_id;
				}
				if (is_numeric($term_id) && $term_id > 0){
					$return .= '<li><a href="'.admin_url( 'post-new.php?post_type=cases&case_category_id='. $term_id , 'http' ).'">Добавить задачу</a></li>';  
				}
			}
		}
		if (is_singular('cases')){
			$return .= '<li><a href="'.admin_url( 'post-new.php?post_type=cases&case_parent_id='. get_the_ID(), 'http' ).'">Добавить подзадачу</a></li>';
		}
		if(is_tax('functions') || is_singular('cases') || is_post_type_archive('cases')){ 
			$return .= '</ul>';
		}
		return $return;
	}
	add_shortcode('actions_box', 'actions_box_shortcode');
	