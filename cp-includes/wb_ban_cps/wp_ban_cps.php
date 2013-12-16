<?php
/*
Plugin Name: WP CPS BAN
Plugin URI: http://www.casepress.org/
Description: Ban users in wordpress
Version: 20130816
Author: CasePress Studio
Author URI: http://www.casepress.org
License: GPL
*/

function add_banned_role() {
    add_role(
		'banned'
		, 'Заблокированный'
		, array(
			'read'         => false,
			'edit_posts'   => false,
			'delete_posts' => false
		)
	);
}

add_action( 'cp_activate', 'add_banned_role' );

function cps_ban_authenticate_user($user){

	if (is_wp_error($user)){
		return $user;
	}
	
	foreach($user->roles as $role){
		if($role == 'banned')
			return new WP_Error('cps_user_banned', '<strong>ОШИБКА</strong>: Ваш аккаунт заблокирован.');
	}
	
	return $user;
}
add_filter( 'wp_authenticate_user', 'cps_ban_authenticate_user', 1 );
function cps_ban_get_blogs_of_user( $blogs, $user_id, $all ){
	//global $current_user;
	$cu_blogs = get_all_sites_of_cu(get_current_user_id());
	//print_r($blogs);
	$return = $blogs;
	
	foreach($blogs as $key => $blog){
		if(!in_array($blog->path,$cu_blogs))
		{
			unset($return[$key]);
		}
	}
	//echo '<pre>';
	//print_r($cu_blogs);
	//echo '</pre>';
	return $return;
}
add_filter( 'get_blogs_of_user', 'cps_ban_get_blogs_of_user' );

function cps_ban_check(){
	if ( !is_user_logged_in() ) return;
	
	global $current_user;
	
	if ( is_multisite() ) {
		if(empty($current_user->roles)){
		
			load_template( dirname( __FILE__ ) . '/wp_ban_cps_ms_alert.php' );
		}
		foreach($current_user->roles as $role){
			if($role == 'banned'){
				
				load_template( dirname( __FILE__ ) . '/wp_ban_cps_ms_alert.php' );
			}
		}
	}else{
		foreach($current_user->roles as $role){
			if($role == 'banned'){
				wp_clear_auth_cookie();
				do_action('wp_logout');
				wp_redirect(home_url());
				exit;
			}
		}
	}
}

add_action('init', 'cps_ban_check');

if(!function_exists('get_all_sites_of_cu')){
  /**
   * Retrieves all multisite blogs
   *
   * @return array Blog IDs as keys and blog names as values.
   */
  function get_all_sites_of_cu($user_id) {

    global $wpdb;
	
	$out = array();
   
    // Query all blogs from multi-site install
    $blogs = $wpdb->get_results("SELECT blog_id,path FROM ".$wpdb->base_prefix."blogs ORDER BY path");

	$returns = $blogs;
	
	foreach($blogs as $blog){
		switch_to_blog($blog->blog_id);
			
		$current_user = get_user_by( 'id', $user_id );
			
		foreach($current_user->roles as $key => $role){
			if($role == 'banned'){
				foreach($returns as $return){
					if($return->blog_id == $blog->blog_id){
						unset($returns[$key]);
					}
				}
			}
		}

		restore_current_blog();
	}
	foreach($returns as $return){
		$out[] = $return->path;
	}
    return $out;
  }
}
?>