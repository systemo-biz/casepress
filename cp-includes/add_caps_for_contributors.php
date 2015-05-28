<?php
function add_caps_for_contributors() {
	$role = get_role('contributor');
	$role->add_cap('publish_posts');
	$role->add_cap('edit_others_posts');
}
add_action( 'admin_init', 'add_caps_for_contributors');