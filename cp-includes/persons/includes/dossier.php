<?php

function add_dossier_to_person_page(){
    global $post;

	if(! is_singular('persons')) return;

	
	$query = new WP_Query('post_type=cases&meta_key=members-cp-posts-sql&meta_value='.$post->ID);
	?>
	<section id="person_dossier" class="cases-box">
		<header class="cases-box-header">
	    	<h1>Досье</h1>
	    	<hr>
		</header>
		<article class="cases-box-content">
            <ul>
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <li role="article" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <a href="<?php the_permalink(); ?>">
                        <h2 class="entry-title"><?php the_title(); ?></h2>
                    </a>
                </header>
                <section id='meta-case'>
                    <ul class="list-inline">
                        <?php do_action('case_meta_top_add_li'); ?>
                    </ul> 
                </section>
                
            </li><!-- #post-<?php the_ID(); ?> -->    

            <?php endwhile; wp_reset_postdata(); ?>
            </ul>

		</article>
		<footer>
            <div class="btn-group" role="group" aria-label="...">
                <a href="<?php echo add_query_arg( array('post_type'=>'cases','meta_members-cp-posts-sql'=>$post->ID), get_site_url()); ?>" class='btn btn-default'>Дела в которых участвует</a>
                <a href="<?php echo add_query_arg( array('post_type'=>'cases','meta_responsible-cp-posts-sql'=>$post->ID), get_site_url()); ?>" class='btn btn-default'>Дела за которые отвечает</a>
            </div>

		</footer>
	</section>
	
	<?php
}

add_action('cp_entry_sections', 'add_dossier_to_person_page', 20);