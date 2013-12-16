<?php
/*
 * Content functions
 *
add_filter( 'the_content', 'redirect_favorite_the_content_filter', 10 );

//thi function shows "favorite" link
function redirect_favorite_the_content_filter( $content ) {

	global $current_user;

	//add action url
	$action_url = site_url('/wp-content/plugins/redirect_plugin') . "/add_user_favorite.php";

	//print the content
	echo $content;

	//print favorite link
	echo "<a href='javaScript:void(0);' id='favorite_post'>Favorite this post</a> ";

	?>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#favorite_post").click(function(){
				$.ajax({
					url: '<?php echo $action_url ?>',
					type: 'POST',
					dataType: 'json',
					data: {
						post_id: <?php echo get_the_ID(); ?>,
						user_id: <?php echo $current_user->ID; ?>,
						favorite: true
					},
					success: function(data) {
						console.log(data);
						if(data.result == true){
							alert("Added to favorite.");
						}else{
							alert("Error.");
						}
					},
					error: function() {
						fail('error sending request');
					}
				});
			});
		});
	</script>
<?php
}
*/
//get and print all terms on settings page
function redirect_print_categories_admin() {

	//array of args for get_terms function
	$args = array(
		'child_of'    => 0,
		'hide_empty'  => false
	);

	//get terms
	$terms = get_terms('category', $args);

	if(get_option('redirect_category'))
	{
		$term_ = get_term(get_option('redirect_category'), 'category');
		$option = $term_->name;
		$value = $term_->term_id;
	}
	else
	{
		$option = 'None';
		$value = '';
	}

	//print this terms on select box
	?>
	<select name="redirect_category">
		<option selected="selected" value="<?php echo $value ?>"><?php echo $option; ?></option>
	<?php
	foreach($terms as $term):
		echo "<option value='" . $term->term_id . "' >" . $term->name . "</option>";
	endforeach;
	?>
	</select>
	<?php
}

//this function print all posts
function redirect_posts_list($post_type = 'post') {

	//array of args for get_terms function
	$args = array(
		'post_type'    => $post_type,
		'numberposts'  => -1,
		'orderby'      => 'post_date',
		'order'        => 'DESC',
		'post_status'  => 'publish'
	);

	//get posts
	$posts = get_posts($args);

	if(get_option('redirect_posts_list'))
	{
		$post_ = get_post(get_option('redirect_posts_list'));
		$option = $post_->post_title;
		$value = $post_->ID;
	}
	else
	{
		$option = 'None';
		$value = '';
	}

	//print this terms on select box
	?>
	<select name="redirect_posts_list" size="2" style="height: 10em;">
		<option selected="selected" value="<?php echo $value ?>"><?php echo $option; ?></option>
		<?php
		foreach($posts as $single_post):
			echo "<option value='" . $single_post->ID . "' >" . $single_post->post_title . "</option>";
		endforeach;
		?>
	</select>
<?php
}

//this function print all links
function redirect_links_list($post_type = 'redirect') {

	//array of args for get_terms function
	$args = array(
		'post_type'    => $post_type,
		'numberposts'  => -1,
		'orderby'      => 'post_date',
		'order'        => 'DESC',
		'post_status'  => 'publish'
	);

	//get links
	$links = get_posts($args);

	if(get_option('redirect_links_list'))
	{
		$link_ = get_post(get_option('redirect_links_list'));
		$option = $link_->post_title;
		$value = $link_->ID;
	}
	else
	{
		$option = 'None';
		$value = '';
	}

	//print this terms on select box
	?>
	<select name="redirect_links_list" size="2" style="height: 10em;">
		<option selected="selected" value="<?php echo $value ?>"><?php echo $option; ?></option>
		<?php
		foreach($links as $single_link):
			echo "<option value='" . $single_link->ID . "' >" . $single_link->post_title . "</option>";
		endforeach;
		?>
	</select>
<?php
}

//This function print type of link
function redirect_choose_type_of_link() {

	if(get_option('redirect_type'))
	{
		echo "Selected type: ". get_option('redirect_type');
	}
	?>
	<br />
	<select name="redirect_type">
		<option value="post">Post</option>
		<option value="link">Link</option>
		<option value="custom_url">Custom URL</option>
		<option value="category">Category</option>
		<option value="person">person</option>
	</select>
	<?php
}

function redirect_is_user_logged_in() {

	$option = '';

	if( get_option('redirect_type') == 'post' )
	{
		$option = get_permalink(get_option('redirect_posts_list'));
		redirect_is_this_page_uri($option);
	}

	if( get_option('redirect_type') == 'link' )
	{
		$option = get_permalink(get_option('redirect_links_list'));
		redirect_is_this_page_uri($option);
	}

	if( get_option('redirect_type') == 'custom_url' )
	{
		$option = get_option('redirect_link_user');
		redirect_is_this_page_uri($option);
	}

	if( get_option('redirect_type') == 'category' )
	{
		$term = get_term_by('id', get_option('redirect_category'), 'category');
		$option = get_term_link($term);
		redirect_is_this_page_uri($option);
	}

	if( get_option('redirect_type') == 'person' )
	{
		$current_person = get_person_by_user(wp_get_current_user()->ID);
		if ($current_person && ($personName = get_post($current_person)->post_name))
		{
			redirect_is_this_page_uri(home_url('/?persons='.$personName));
		}

	}

}


function redirect_is_this_page_uri($link) {
	global $wp;
	if( is_multisite() )
	{
		switch_to_blog(1);
		if(add_query_arg( '', '', home_url( $wp->request ) ) != $link)
			wp_redirect($link);
		restore_current_blog();
	}
	elseif((get_site_url().$_SERVER['REQUEST_URI']) != $link)
	{
		wp_redirect($link);
		die;//ставим тут die для того, чтобы корректно отрабатывать переход
	}
}

/*
 * Debugging functions
 */
function pre_dump($param) {
	echo "<!--";
	var_dump($param);
	echo "-->";
}

/**
 * Замена функции is_home
 */
function rp_is_home()
{
    if(isset($_SERVER['HTTP_HOST']))
        return get_home_url() === rtrim( 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], '/' );
}