<?php


//Add list cases for persons and organizations

// Add list for outbox
function list_cases_outbox_for_subjects($content){
    if(is_singular(array('organizations', 'persons'))):
    
    $post = get_post();
    
    $items = get_posts(array(
							'post_type' => 'cases',
							'meta_query' => array(
								array(
									'key' => 'cp_from',
									'value' => $post->ID,
								)
							)
						));
    
        if($items):
            $url_list = add_query_arg( array('post_type'=>'cases','meta_cp_from'=>$post->ID), get_site_url());
            ob_start();
            ?>    
                <section class="list_cases_from">
                    <header>
                        <h1>Исходящие дела</h1>
                        <small>Дела, которые направлены от данного субъекта</small>
                    </header>
                    <ul>
                        <?php foreach( $items as $post ): setup_postdata($post);?>
                            <li>
                                <a href="<?php echo get_permalink( $post->ID ); ?>">
                                    <h2 class="entry-title"><?php echo get_the_title( $post->ID ); ?></h2>
                                </a>
                                <div>
                                    <ul class="list-inline">
                                        <?php do_action('case_meta_top_add_li'); ?>
                                    </ul> 
                                </div>
                            </li>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </ul>
                    <footer>
                        <a href="<?php echo $url_list ?>" class='btn btn-default'>Все исходящие</a>
                    </footer>
                </section>
            <?php
            $html = ob_get_contents();
             ob_get_clean();
             $content .= $html;
        endif;
    endif;
    
    return $content;
} add_filter('the_content', 'list_cases_outbox_for_subjects');


// Add list for inbox
function list_cases_inbox_for_subjects($content){
    if(is_singular(array('organizations', 'persons'))):
    
    $post = get_post();
    
    $items = get_posts(array(
							'post_type' => 'cases',
							'meta_query' => array(
								array(
									'key' => 'cp_to',
									'value' => $post->ID,
								)
							)
						));
    
        if($items):
            $url_list = add_query_arg( array('post_type'=>'cases','meta_cp_to'=>$post->ID), get_site_url());
            ob_start();
            ?>    
                <section class="list_cases_to">
                    <header>
                        <h1>Входящие дела</h1>
                        <small>Дела, которые направлены в адрес данного субъекта</small>
                    </header>
                    <ul>
                        <?php foreach( $items as $post ): setup_postdata($post);?>
                            <li>
                                <a href="<?php echo get_permalink( $post->ID ); ?>">
                                    <h2 class="entry-title"><?php echo get_the_title( $post->ID ); ?></h2>
                                </a>
                                <div>
                                    <ul class="list-inline">
                                        <?php do_action('case_meta_top_add_li'); ?>
                                    </ul> 
                                </div>
                            </li>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </ul>
                    <footer>
                        <a href="<?php echo $url_list ?>" class='btn btn-default'>Все входящие</a>
                    </footer>
                </section>
            <?php
            $html = ob_get_contents();
             ob_get_clean();
             $content .= $html;
        endif;
    endif;
    
    return $content;
} add_filter('the_content', 'list_cases_inbox_for_subjects');