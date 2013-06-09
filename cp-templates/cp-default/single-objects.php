<?php
	get_header();
?>

<div id="content" class="<?php echo CONTAINER_CLASSES; ?>">


	<aside id="sidebar" class="<?php echo SIDEBAR_CLASSES; ?>" role="complementary">
		<div class="well">
			<?php dynamic_sidebar( 'objects' ); ?>
		</div>
	</aside><!-- /#sidebar -->

	
	<div id="main" class="<?php echo MAIN_CLASSES; ?>" role="main">
		<?php do_action('cp_loop_before'); ?>
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
			<?php echo get_the_term_list( get_the_ID(), 'objects_category', 'Категории: ', ', ', '' ); ?> 
			<?php do_action('cp_entry_footer_after'); ?>
		</footer>
	</article>
	<?php do_action('cp_post_after'); ?>
	</div><!-- /#main -->
	<?php do_action('cp_main_after'); ?>
	
</div><!-- /#content -->
<?php do_action('cp_content_after'); ?>

<?php get_footer(); ?>