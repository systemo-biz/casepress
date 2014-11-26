<?php

add_action( 'cp_entry_sections', 'cases_display_childs_cp', 20 );

function cases_display_childs_cp() {
    global $post;
    $query = new WP_Query('post_type=cases&post_parent='.$post->ID);
	
    if(! is_singular('cases')) return;

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
                    <a href="<?php the_permalink(); ?>"><h2 class="entry-title">#<?php the_ID(); ?> <?php the_title(); ?></h2></a>
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
	</section>
<?php
				
}