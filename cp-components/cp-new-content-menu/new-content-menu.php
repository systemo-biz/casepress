<?php


function cases_new_content_menu( $wp_admin_bar ) {
	$actions = array();

	$cpts = (array) get_post_types( array( 'show_in_admin_bar' => true ), 'objects' );

	if ( isset( $cpts['cases'] ) && current_user_can( $cpts['cases']->cap->edit_posts ) ) {
		$actions[ 'post-new.php?post_type=cases' ] = array( $cpts['cases']->labels->name_admin_bar, 'new-cases' );
		unset( $cpts['cases'] );
	}

	if ( isset( $cpts['post'] ) && current_user_can( $cpts['post']->cap->edit_posts ) ) {
		$actions[ 'post-new.php' ] = array( $cpts['post']->labels->name_admin_bar, 'new-post' );
		unset( $cpts['post'] );
	}

	if ( current_user_can( 'upload_files' ) )
		$actions[ 'media-new.php' ] = array( _x( 'Media', 'add new from admin bar' ), 'new-media' );

	if ( current_user_can( 'manage_links' ) )
		$actions[ 'link-add.php' ] = array( _x( 'Link', 'add new from admin bar' ), 'new-link' );

	if ( isset( $cpts['page'] ) && current_user_can( $cpts['page']->cap->edit_posts ) ) {
		$actions[ 'post-new.php?post_type=page' ] = array( $cpts['page']->labels->name_admin_bar, 'new-page' );
		unset( $cpts['page'] );
	}

	// Add any additional custom post types.
	foreach ( $cpts as $cpt ) {
		if ( ! current_user_can( $cpt->cap->edit_posts ) )
			continue;

		$key = 'post-new.php?post_type=' . $cpt->name;
		$actions[ $key ] = array( $cpt->labels->name_admin_bar, 'new-' . $cpt->name );
	}

	if ( current_user_can( 'create_users' ) || current_user_can( 'promote_users' ) )
		$actions[ 'user-new.php' ] = array( _x( 'User', 'add new from admin bar' ), 'new-user' );

	if ( ! $actions )
		return;

	$title = '<span class="ab-icon"></span><span class="ab-label">' . _x( 'New', 'admin bar menu group label' ) . '</span>';

	$wp_admin_bar->add_menu( array(
		'id'    => 'new-content',
		'title' => $title,
		'href'  => admin_url( 'post-new.php?post_type=cases' ),
		'meta'  => array(
			'title' => _x( 'Add New', 'admin bar menu group label' ),
		),
	) );

	foreach ( $actions as $link => $action ) {
		list( $title, $id ) = $action;

		$wp_admin_bar->add_menu( array(
			'parent'    => 'new-content',
			'id'        => $id,
			'title'     => $title,
			'href'      => admin_url( $link )
		) );
	}
}

function cases_new_content_menu_init() {
	remove_action( 'admin_bar_menu', 'wp_admin_bar_new_content_menu', 70 );
	add_action( 'admin_bar_menu', 'cases_new_content_menu', 70 );
}
add_action( 'init', 'cases_new_content_menu_init', 11 );
?>