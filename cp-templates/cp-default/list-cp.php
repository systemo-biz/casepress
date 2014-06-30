<?php get_header(); ?>
	<div id="primary" class='<?php echo apply_filters( 'alienship_primary_container_class', 'content-area col-md-9' ); ?>'>
		<div id="main" class="site-main" role="main">
			<?php
			do_action( 'page_title_cp' );

			if ( have_posts() ) {

				?>
				<table>
					<thead>
						<tr>
							<th>ID</th>
							<th>Описание</th>
						</tr>
					</thead>
						<tbody>
				<?php
				// Start the Loop
				while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */?>
						<tr>
							<td><a href="<?php the_permalink();?>"><span class="post_id"><?php the_ID();?></span></a></td>
							<td><a href="<?php the_permalink();?>"><?php the_title(); ?></a>
								<?php
									$cp_excerpt = strip_tags(get_the_content( $more_link_text = "..." ));
									if(! empty($cp_excerpt)) {
										?>
										<span> | </span><span><?php echo $cp_excerpt; ?><a href="<?php the_permalink();?>">...</a></span>
										<?php
									}
								?>
							</td>
						</tr>

				<?php endwhile; ?>
					</tbody>
				</table>

			<?php } else {

				// No results
				echo '<h1>Записи не найдены. Попробуйте другой запрос</h1>';

			} //have_posts ?>
		</div><!-- #main -->

	</div><!-- #primary -->

	<aside id="sidebar" class="sidebar col-md-3" role="complementary">
		<div class="well">
			<?php do_action('sidebar_cp'); ?>
		</div>
	</aside><!-- /#sidebar -->
<?php get_footer(); ?>