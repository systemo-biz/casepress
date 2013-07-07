<?php
    global $tabs;
    $template_name = (isset($tabs) && $tabs) ? 'single-tabs' : 'single';
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
			<?php dynamic_sidebar( 'cases' ); ?>
		</div>
		<?php roots_sidebar_inside_after(); ?>
		<?php do_action('cp_sidebar_inside_after'); ?>
	</aside><!-- /#sidebar -->
	<?php roots_sidebar_after(); ?>
	<?php do_action('cp_sidebar_after'); ?>
	<?php roots_main_before(); ?>
	<?php do_action('cp_main_before'); ?>
	<div id="main" class="<?php echo MAIN_CLASSES; ?>" role="main">
		<?php roots_loop_before(); ?>
		<?php do_action('cp_loop_before'); ?>
		
		<?php /* Start loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
		<?php roots_post_before(); ?>
		<?php do_action('cp_post_before'); ?>
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<?php roots_post_inside_before(); ?>
			<?php do_action('cp_post_inside_before'); ?>
			<header>
				<a href="<?php the_permalink(); ?>">#<?php the_ID(); ?></a>
				<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			</header>
            <?php do_action('case-sidebar'); ?>
            <div class="after-case-sidebar">
                <div class="entry-content">
                    <?php roots_entry_content_before(); ?>
                    <?php do_action('cp_entry_content_before'); ?>

                    <div class="entry-content-inner">
                        <?php the_content(); ?>
                    </div>
                    <?php roots_entry_content_after(); ?>
                    <?php do_action('cp_entry_content_after'); ?>
                </div>
                <footer>
                    <?php roots_entry_footer_before(); ?>
                    <?php do_action('cp_entry_footer_before'); ?>
                    <?php wp_link_pages( array( 'before' => '<nav id="page-nav"><p>' . __( 'Pages:', 'roots' ), 'after' => '</p></nav>' ) ); ?>
                    <?php $tags = get_the_tags();
                    if ( $tags ) {
                        ?><p><?php the_tags(); ?></p><?php } ?>
                    <?php roots_entry_footer_after(); ?>
                    <?php do_action('cp_entry_footer_after'); ?>
                </footer>
            </div>
            <div>
                <?php do_action('cp_post_before_comments'); ?>
            </div>
            <div id="cp_comments">
                <?php comments_template(); ?>
            </div>
		<?php roots_post_inside_after(); ?>
		<?php do_action('cp_post_inside_after'); ?>
		</article>
		<?php roots_post_after(); ?>
		<?php do_action('cp_post_after'); ?>
	<?php endwhile; /* End loop */ ?>
		
		
		<?php roots_loop_after(); ?>
		<?php do_action('cp_loop_after'); ?>
	</div><!-- /#main -->
	<?php roots_main_after(); ?>
	<?php do_action('cp_main_after'); ?>
</div><!-- /#content -->
<?php roots_content_after(); ?>
<?php do_action('cp_content_after'); ?>
<?php get_footer(); ?>