<?php
	get_header();
?>
<?php roots_content_before(); ?>
<?php do_action('cp_content_before'); ?>
<div id="content" class="<?php echo CONTAINER_CLASSES; ?>">
	<?php roots_sidebar_before(); ?>
	<?php do_action('cp_sidebar_before'); ?>
	<aside id="sidebar" class="<?php echo SIDEBAR_CLASSES; ?>" role="complementary">
		<?php roots_sidebar_inside_before(); ?>
		<?php do_action('cp_sidebar_inside_before'); ?>
		<div class="well">
			<?php dynamic_sidebar( 'persons' ); ?>
		</div>
		<?php roots_sidebar_inside_after(); ?>
		<?php do_action('cp_sidebar_inside_after'); ?>
	</aside><!-- /#sidebar -->
	<?php roots_sidebar_after(); ?>
	<?php do_action('cp_sidebar_after'); ?>
	<?php roots_main_before(); ?>
	<?php do_action('cp_main_before'); ?>
	<div id="main" class="<?php echo MAIN_CLASSES; ?>" role="main">
	<article <?php post_class() ?> id="post-<?php the_ID(); ?>">	
		<header>
			<a href="<?php the_permalink(); ?>">#<?php the_ID(); ?></a>
			<h1><?php the_title();	?></h1>
			<hr/>
		</header>

		<div class="entry-content">
		<?php do_action('cp_entry_content_before'); ?>
			<div class="entry-content-inner">
			<?php
				the_post();
				the_content();
			?>
			</div>
		<?php do_action('cp_entry_content_after'); ?>
		<hr/>
		</div>

		<footer>
			<?php do_action('cp_entry_footer_before'); ?>
			<?php do_action('cp_entry_footer_after'); ?>
		</footer>
	</article>
	</div><!-- /#main -->
	<?php roots_main_after(); ?>
	<?php do_action('cp_main_after'); ?>
</div><!-- /#content -->
<?php roots_content_after(); ?>
<?php do_action('cp_content_after'); ?>
<?php get_footer(); ?>
<!-- <?php echo basename( __FILE__ ); ?> -->