<?php

add_action( 'wp_enqueue_scripts', 'cp_load_acf_fs_ss' );
function cp_load_acf_fs_ss(){
	global $pagenow;
	
	$path_to_plugin = trailingslashit(plugin_dir_url(__FILE__) );

	if ( is_admin() && in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_script( 'cp-acf-functions-select-backend', $path_to_plugin . '/assets/js/backend.js', array( 'jquery' ), '20130819');
	}
}

?>