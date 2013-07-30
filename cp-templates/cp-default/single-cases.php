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
				<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			</header>
            <div id="main-case-box">
                </br>
                <?php do_action('case-sidebar'); ?>
                <div class="after-case-sidebar">
                    <div class="entry-content">
                        <?php do_action('cp_entry_content_before'); ?>

                        <div class="entry-content-inner">
                            <?php the_content(); ?>
                        </div>
                        <?php do_action('cp_entry_content_after'); ?>
                    </div>
                    <footer>
                        <?php do_action('cp_entry_footer_before'); ?>
                        <?php $tags = get_the_tags();
                        if ( $tags ) {
                            ?><p><?php the_tags(); ?></p><?php } ?>
                        <?php do_action('cp_entry_footer_after'); ?>
                    </footer>
                </div>
            </div>
            <div id="cp_before_comment">
                <?php do_action('cp_post_before_comments'); ?>
            </div>
            <div id="cp_comments">
                <?php comments_template(); ?>
            </div>
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