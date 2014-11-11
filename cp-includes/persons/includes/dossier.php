<?php

function add_dossier_to_person_page($content){
	global $post;

	if(! is_singular('persons')) return $content;
	
	$query = new WP_Query('post_type=cases&meta_key=members-cp-posts-sql&meta_value='.$post->ID);


	ob_start();
	
	?>
	<section id="person_dossier" class="cases-box">
		<div class="cases-box-header">
	    	<h1>Досье</h1>
	    	<hr>
		</div>
		<div class="cases-box-content">
			<?php

				while ( $query->have_posts() ) : $query->the_post();
					get_template_part( '/templates/row' );
				endwhile;
				wp_reset_postdata();
			?>

		</div>
		<footer>
			<a href="<?php echo add_query_arg( array('post_type'=>'cases','meta_members-cp-posts-sql'=>$post->ID), get_site_url()); ?>" class='btn btn-info'>Все дела</a>
		</footer>
	</section>
	
	<?php
	
	$content .= ob_get_contents();
	ob_get_clean();
	
	return $content;
}

add_filter('the_content', 'add_dossier_to_person_page', 20);