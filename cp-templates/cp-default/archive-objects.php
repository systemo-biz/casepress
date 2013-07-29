<?php
	/**
	 * Template name: Объекты
	 * Description: Шаблон архива
	 *
	 * Этот файл можно использовать как шаблон страницы или он будет
	 * автоматически использоваться на странице архива
	 */
	get_header();
	global $post;
?>
<?php do_action('cp_content_before'); ?>
<div id="content" class="clearfix row-fluid">
	<?php do_action('cp_sidebar_before'); ?>
	<aside id="sidebar" class="fluid-sidebar sidebar span3" role="complementary">
		<?php do_action('cp_sidebar_inside_before'); ?>
		<div class="well">
			<?php dynamic_sidebar( 'objects' ); ?>
		</div>
		<?php do_action('cp_sidebar_inside_after'); ?>
	</aside><!-- /#sidebar -->
	<?php do_action('cp_sidebar_after'); ?>
	<?php do_action('cp_main_before'); ?>
	<div id="main" class="span9 clearfix" role="main">
		<?php do_action('cp_loop_before'); ?>
		<?php /* Start loop */ ?>
		<?php if ( have_posts() ) : the_post(); ?>
				<?php do_action('cp_post_before'); ?>
				<?php do_action('cp_post_inside_before'); ?>

				<?php
				// page content
				if ( is_archive() ) {
					echo "<h1>". post_type_archive_title('',false) ."</h1>";
					}
				else {
					echo "<h1>". $post->post_title . "</h1>";
					}
				echo '<hr/>';
				
				// DataTable
				if ( function_exists( 'datatable_generator' ) )
					datatable_generator( array(
						'type' => 'objects',
						'fields' => 'ID:int, post_title:link',
						'titles' => 'post_title:Объект'
					) );
				?>
				<?php do_action('cp_post_inside_after'); ?>
				<?php do_action('cp_post_after'); ?>
			<?php endif; /* End loop */ ?>
		<?php do_action('cp_loop_after'); ?>
	</div><!-- /#main -->
	<?php do_action('cp_main_after'); ?>
</div><!-- /#content -->
<?php do_action('cp_content_after'); ?>
<?php get_footer(); ?>