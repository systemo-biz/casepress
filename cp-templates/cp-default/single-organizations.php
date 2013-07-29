<?php
	the_post();
	get_header();
?>

<div id="content" class="clearfix row-fluid">
	<?php do_action('cp_sidebar_before'); ?>
	<aside id="sidebar" class="fluid-sidebar sidebar span3" role="complementary">
		<?php do_action('cp_sidebar_inside_before'); ?>
		<div class="well">
			<?php dynamic_sidebar( 'organizations' ); ?>
		</div>
		<?php do_action('cp_sidebar_inside_after'); ?>
	</aside><!-- /#sidebar -->
	<?php do_action('cp_sidebar_after'); ?>

	<?php do_action('cp_main_before'); ?>		
	<div id="main" class="span9 clearfix" role="main">
		<?php do_action('cp_loop_before'); ?>
	<article <?php post_class() ?> id="post-<?php the_ID(); ?>">	
		<header>
			<a href="<?php the_permalink(); ?>">#<?php the_ID(); ?></a>
			<h1><?php the_title();	?></h1>
			<?php echo get_the_term_list( get_the_ID(), 'organizations_category', 'Категории организации: ', ', ', '' ); ?> 
			<hr/>
		</header>

		<div class="entry-content">
		<?php do_action('cp_entry_content_before'); ?>
			<div class="entry-content-inner">
			<?php
				the_content();
			?>
			</div>
		<?php do_action('cp_entry_content_after'); ?>
		<hr/>
		</div>

			<footer>
				<?php do_action('cp_entry_footer_before'); ?>
				<div id="cp_before_comment">
					<?php do_action('cp_post_before_comments'); ?>
				</div>
				<div id="cp_comments">
					<?php comments_template(); ?>
				</div>
				<?php do_action('cp_entry_footer_after'); ?>
			</footer>
	</article>
	<?php do_action('cp_post_after'); ?>
	</div><!-- /#main -->
	<?php do_action('cp_main_after'); ?>
	
</div><!-- /#content -->
<?php do_action('cp_content_after'); ?>

<?php get_footer(); ?>