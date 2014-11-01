<?php
/*
Функция - кеширование данных в цитату, чтобы затем искать по ним. Например это может быть значение какой либо меты. К примеру ответственный - чтобы затем дела находились по этому ответственному.
*/

if (!class_exists('CP_Excerpt_Search_And_Cache')) {
	class CP_Excerpt_Search_And_Cache {

		function __construct()
		{
			//add_action( 'add_post_meta', array($this, 'update_cache'), 10, 3 );
		}



		function update_cache($post_id, $meta_key, $meta_value)
		{
			global $The_CP_Members;
			
			remove_action( 'save_post', array($The_CP_Members, 'save_data_post'), 9);
			wp_update_post( array(
				'ID'           => $post_id,
				'post_excerpt' => '123'
			) );
			add_action( 'save_post', array($The_CP_Members, 'save_data_post'), 9);
		}
	/*	function update_cache($meta_id, $post_id, $meta_key, $meta_value)
		{

			$post = get_post($post_id);
			$excerpt = $post->post_excerpt;
			if (is_serialized( $excerpt )){
				$cache = unserialize($excerpt);
				
			}else{
				$cache = array('responsible-cp-posts-sql' => '', 'member_from-cp-posts-sql' => '', 'cp_date_deadline' => '');
			}
			
			switch ($meta_key) {
				case 'responsible-cp-posts-sql':
					$responsible = get_the_title($meta_value);
					$cache['responsible-cp-posts-sql'] = 'responsible:'.$responsible;
					break;
				case 'member_from-cp-posts-sql':
					$from = get_the_title($meta_value);
					$cache['member_from-cp-posts-sql'] = 'from:'.$from;
					break;
				case 'cp_date_deadline':
					$cache['cp_date_deadline'] = 'deadline:'.$meta_value;
					break;
				default: return;
			}
			$cache = serialize($cache);
			
			$atts = array(
				  'ID'           => $post_id,
				  'post_excerpt' => $cache
			);
			remove_action( 'added_post_meta', array($this, 'update_cache'), 10, 4 );
			remove_action( 'updated_post_meta', array($this, 'update_cache'), 10, 4 );
			wp_update_post( $atts );
			add_action( 'added_post_meta', array($this, 'update_cache'), 10, 4 );
			add_action( 'updated_post_meta', array($this, 'update_cache'), 10, 4 );
			//return;
		}
		function delete_cache($meta_ids, $post_id, $meta_key, $meta_values)
		{
			$post = get_post($post_id);
			$excerpt = $post->post_excerpt;
			if (is_serialized( $excerpt )){
				$cache = unserialize($excerpt);
				
			}else{
				$cache = array('responsible-cp-posts-sql' => '', 'member_from-cp-posts-sql' => '', 'cp_date_deadline' => '');
			}
			
			$cache[$meta_key] = '';
			
			$cache = serialize($cache);
			
			$atts = array(
				  'ID'           => $post_id,
				  'post_excerpt' => $cache
			);
			remove_action( 'deleted_post_meta', array($this, 'delete_cache'), 10, 4 );
			wp_update_post( $atts );
			add_action( 'deleted_post_meta', array($this, 'delete_cache'), 10, 4 );
		}*/
	}
}

$cp_excerpt_search_and_cache = new CP_Excerpt_Search_And_Cache();

?>