<?php
/**
 * The main template file.
 *
 * @package Alien Ship
 * @since Alien Ship 0.1
 */

get_header(); 
?>

		<div id="primary" class="<?php echo apply_filters( 'alienship_primary_container_class', 'content-area col-sm-12' ); ?>">

			<?php do_action( 'alienship_main_before' ); ?>
			<main id="main" class="site-main" role="main">
				<?php if ( have_posts() ) {

				// Start the Loop
				while ( have_posts() ) : the_post();
					do_action( 'alienship_loop_before' );

					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( '/templates/parts/content', get_post_format() );

					do_action( 'alienship_loop_after' );
				endwhile;

			} //have_posts ?>
		</main><!-- #main -->


	</div><!-- #primary -->
<?php get_footer(); ?>