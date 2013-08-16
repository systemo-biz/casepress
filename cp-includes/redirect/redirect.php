<?php
/*
Plugin Name: Redirect plugin
Description: Плагин для переадресации пользователя на заданные или вычисляемые страницы
Version: 1.1
Author: fruitware
*/

require('functions.php');
require('mb_class/my-meta-box-class.php');

//wp_enqueue_script('jquery');

/*
 * Config of metabox
 */
$config = array(
	'id' => 'redirect_links_meta_box',          // meta box id, unique per meta box
	'title' => 'Redirect info',          // meta box title
	'pages' => array('redirect'),      // post types, accept custom post types as well, default is array('post'); optional
	'context' => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
	'priority' => 'high',            // order of meta box: high (default), low; optional
	'fields' => array(),            // list of meta fields (can be added by field arrays)
	'local_images' => false,          // Use local or hosted images (meta box images for add/remove)
	'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
);

/*
 * Register new post_type for new links
 */
add_action( 'init', 'create_redirect_post_type' );
function create_redirect_post_type() {

	//register post type
	register_post_type( 'redirect',
		array(
			'labels' => array(
				'name' => __( 'Custom Links' ),
				'singular_name' => __( 'Custom Link' ),
				'add_new' => 'Add New Link',
				'add_new_item' => 'Add New Link',
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 80,
			'supports' => array( 'title' )
		)
	);
}

//metabox init
$metabox =  new AT_Meta_Box($config);
$metabox->addText('redirect_url_meta',array('name'=> 'URL'));



// create custom plugin settings menu
add_action('admin_menu', 'redirect_plugin_create_menu');

function redirect_plugin_create_menu() {

	//create new top-level menu
	add_options_page('Redirect Plugin Settings', 'Redirect Settings', 'administrator', __FILE__, 'redirect_plugin_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_redirect_settings' );
}


function register_redirect_settings() {
	//register our settings
	register_setting( 'redirect-settings-group', 'redirect_link_guest' );
	register_setting( 'redirect-settings-group', 'redirect_link_user' );
	register_setting( 'redirect-settings-group', 'redirect_posts_list' );
	register_setting( 'redirect-settings-group', 'redirect_category' );
	register_setting( 'redirect-settings-group', 'redirect_links_list' );
	register_setting( 'redirect-settings-group', 'redirect_type' );
}

function redirect_plugin_settings_page() {
	?>
<div class="wrap">
	<h2>Redirect plugin Settings</h2>

	<form method="post" action="options.php">
		<?php settings_fields( 'redirect-settings-group' ); ?>
		<?php do_settings_fields( 'redirect-settings-group', 'default' ); ?>
		<table class="form-table">

			<tr valign="top">
				<th scope="row">Choose the category</th>
				<td><?php redirect_print_categories_admin(); ?></td>
			</tr>

			<tr valign="top">
				<th scope="row">Choose the post</th>
				<td><?php redirect_posts_list(); ?></td>
			</tr>

			<tr valign="top">
				<th scope="row">Choose the link</th>
				<td><?php redirect_links_list(); ?></td>
			</tr>

			<tr valign="top">
				<th scope="row">Redirect link for user</th>
				<td><input type="text" name="redirect_link_user" value="<?php echo get_option('redirect_link_user'); ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">Redirect link for guest</th>
				<td><input type="text" name="redirect_link_guest" value="<?php echo get_option('redirect_link_guest'); ?>" /></td>
			</tr>
		</table>

		<h2>Redirect type</h2>
		<div>
			<?php redirect_choose_type_of_link(); ?>
		</div>
		<?php submit_button(); ?>

	</form>

</div>
<?php }


add_action( 'show_user_profile', 'redirect_block_show_user_meta' );
add_action( 'edit_user_profile', 'redirect_block_show_user_meta' );

function redirect_block_show_user_meta( $user ) {
	?>
	<h3>User Favorite link</h3>
	<table class="form-table">
	<tr>
		<th><label for="favorite_link_field">Redirect Link</label></th>
		<td><input type="text" value="<?php echo get_user_meta($user->ID, 'favorite_link', true); ?>" name="favorite_link" id="favorite_link_field" /><br>
		<span class="description">Link used to redirect user when he is opening main page</span></td>
	</tr>
	</table>
<?php
}

add_action( 'personal_options_update', 'redirect_link_save_user_meta' );
add_action( 'edit_user_profile_update', 'redirect_link_save_user_meta' );

function redirect_link_save_user_meta( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_user_meta( $user_id, 'favorite_link', $_POST['favorite_link'] );
}

/*
 * This function redirects user to link of Register Plugin settings, or to custom user link
 */
function plugin_template_redirect()
{
	if (rp_is_home())
	{
		if (is_user_logged_in())
		{
			if ($user_favorite_link = get_user_meta(get_current_user_id(), 'favorite_link', true))
			{
				redirect_is_this_page_uri($user_favorite_link);
			}
			else
			{
				redirect_is_user_logged_in();
			}
		}
		else
		{
			header("Location: ".get_option('redirect_link_guest'));
		}
	}
}
add_action('plugins_loaded', 'plugin_template_redirect', 1);
 ?>