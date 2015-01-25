<?php
/*
Plugin Name: Секция с комментариями на контроле
*/
function cases_display_todo_comments() {
	if(! is_singular('cases')) return;
	if (! shortcode_exists( 'cp_todo_comments' ) ) return;
	global $post;
	$args = array(
		'post_id' => $post->ID,
		'meta_query' => array(
			array(
				'key' => 'cp_control',
				'value' => 'yes',
			),
		),
		'meta_key' => 'cp_control_order',
		'orderby' => 'meta_value_num',
		'order' => 'ASC',
	);
	$comments_query = new WP_Comment_Query;
	$comments       = $comments_query->query( $args );
	if (empty ($comments)) return; 
	?>
	<section id="case_todo_comments_wrapper" class="cases-box">
		<header class="cases-box-header">
			<h1>Комментарии на контроле</h1>
			<hr>
		</header>
		<article class="cases-box-content">
			<?php echo do_shortcode('[cp_todo_comments]'); ?>
		</article>
	</section>
<?php

} add_action( 'cp_entry_sections', 'cases_display_todo_comments', 30 );