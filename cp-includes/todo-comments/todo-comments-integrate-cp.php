<?php
/*
Plugin Name: Секция с комментариями на контроле
*/


function cases_display_todo_comments() {
    if(! is_singular('cases')) return;
    
    if (! shortcode_exists( 'todo_comments' ) ) return;
    
    ?>
	<section id="case_todo_comments_wrapper" class="cases-box">
		<header class="cases-box-header">
	    	<h1>Комментарии на контроле</h1>
	    	<hr>
		</header>
		<article class="cases-box-content">
            <?php echo do_shortcode('[todo_comments]'); ?>
		</article>
	</section>
<?php
				
} add_action( 'cp_entry_sections', 'cases_display_todo_comments', 30 );
