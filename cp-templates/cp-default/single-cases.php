<?php get_header(); ?>

<?php do_action('cp_content_before'); ?>
<div id="content" class="clearfix row-fluid">
	<?php do_action('cp_sidebar_before'); ?>
	<aside id="sidebar" class="fluid-sidebar sidebar span3" role="complementary">
		<?php do_action('cp_sidebar_inside_before'); ?>
		<div class="well">
			<?php dynamic_sidebar( 'cases' ); ?>
		</div>
		<?php do_action('cp_sidebar_inside_after'); ?>
	</aside><!-- /#sidebar -->
	<?php do_action('cp_sidebar_after'); ?>
	<?php do_action('cp_main_before'); ?>
	<div id="main" class="span9 clearfix" role="main">
		<?php do_action('cp_loop_before'); ?>
		<?php /* Start loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
		<?php do_action('cp_post_before'); ?>
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<?php do_action('cp_post_inside_before'); ?>
			<header>
				<a href="<?php the_permalink(); ?>">#<?php the_ID(); ?></a>
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<hr/>
			</header>
            <div id="main-case-box">
                <div class="case-sidebar">
					<?php do_action('case-sidebar'); ?>
				</div>
				<div class="entry-content">
					<?php do_action('cp_entry_content_before'); ?>

					<div class="entry-content-inner">
						<?php the_content(); ?>
					</div>
					<?php do_action('cp_entry_content_after'); ?>
				</div>
            </div>
			<footer>
				<?php do_action('cp_entry_footer_before'); ?>
				<?php do_action('cp_post_before_comments'); ?>
				<?php comments_template('', true); ?>
				<?php do_action('cp_post_after_comments'); ?>
				<?php do_action('cp_entry_footer_after'); ?>
			</footer>

			<?php do_action('cp_post_inside_after'); ?>
		</article>
		<?php do_action('cp_post_after'); ?>
	<?php endwhile; /* End loop */ ?>
		
		<?php do_action('cp_loop_after'); ?>
	</div><!-- /#main -->
	<?php do_action('cp_main_after'); ?>
</div><!-- /#content -->
<?php do_action('cp_content_after'); ?>
<?php get_footer(); ?>