<?php
//this hooks added for backward compatibility with the old theme of roots http://www.rootstheme.com/
// header.php
if(!function_exists('roots_head'))
   { function roots_head() { do_action('roots_head'); } }
if(!function_exists('roots_wrap_before'))
    { function roots_wrap_before() { do_action('roots_wrap_before'); }  }
if(!function_exists('roots_header_before'))
    { function roots_header_before() { do_action('roots_header_before'); }  }
if(!function_exists('roots_header_inside'))
    { function roots_header_inside() { do_action('roots_header_inside'); }  }
if(!function_exists('roots_header_after'))
    { function roots_header_after() { do_action('roots_header_after'); }  }

// 404.php, archive.php, front-page.php, index.php, loop-page.php, loop-single.php,
// loop.php, page-custom.php, page-full.php, page.php, search.php, single.php
if(!function_exists('roots_content_before'))
    { function roots_content_before() { do_action('roots_content_before'); }  }
if(!function_exists('roots_content_after'))
    { function roots_content_after() { do_action('roots_content_after'); }  }
if(!function_exists('roots_main_before'))
    { function roots_main_before() { do_action('roots_main_before'); }  }
if(!function_exists('roots_main_after'))
    { function roots_main_after() { do_action('roots_main_after'); }  }
if(!function_exists('roots_post_before'))
    { function roots_post_before() { do_action('roots_post_before'); }  }
if(!function_exists('roots_post_after'))
    { function roots_post_after() { do_action('roots_post_after'); }  }
if(!function_exists('roots_post_inside_before'))
    { function roots_post_inside_before() { do_action('roots_post_inside_before'); }  }
if(!function_exists('roots_post_inside_after'))
    { function roots_post_inside_after() { do_action('roots_post_inside_after'); }  }
if(!function_exists('roots_loop_before'))
    { function roots_loop_before() { do_action('roots_loop_before'); }  }
if(!function_exists('roots_loop_after'))
    { function roots_loop_after() { do_action('roots_loop_after'); }  }
if(!function_exists('roots_sidebar_before'))
    { function roots_sidebar_before() { do_action('roots_sidebar_before'); }  }
if(!function_exists('roots_sidebar_inside_before'))
    { function roots_sidebar_inside_before() { do_action('roots_sidebar_inside_before'); }  }
if(!function_exists('roots_sidebar_inside_after'))
    { function roots_sidebar_inside_after() { do_action('roots_sidebar_inside_after'); }  }
if(!function_exists('roots_sidebar_after'))
    { function roots_sidebar_after() { do_action('roots_sidebar_after'); }  }
	
	
	
if(!function_exists('roots_entry_meta_before'))	
	{ function roots_entry_meta_before() { do_action('roots_entry_meta_before'); }}
if(!function_exists('roots_entry_meta_after'))
	{ function roots_entry_meta_after() { do_action('roots_entry_meta_after'); }}
if(!function_exists('roots_entry_content_before'))
	{ function roots_entry_content_before() { do_action('roots_entry_content_before'); }}
if(!function_exists('roots_entry_content_after'))
	{ function roots_entry_content_after() { do_action('roots_entry_content_after'); }}
if(!function_exists('roots_entry_footer_before'))
	{ function roots_entry_footer_before() { do_action('roots_entry_footer_before'); }}
if(!function_exists('roots_entry_footer_after'))
	{ function roots_entry_footer_after() { do_action('roots_entry_footer_after'); }}



// footer.php
if(!function_exists('roots_footer_before'))
    { function roots_footer_before() { do_action('roots_footer_before'); }  }
if(!function_exists('roots_footer_inside'))
    { function roots_footer_inside() { do_action('roots_footer_inside'); }  }
if(!function_exists('roots_footer_after'))
    { function roots_footer_after() { do_action('roots_footer_after'); }  }
if(!function_exists('roots_footer'))
    { function roots_footer() { do_action('roots_footer'); }  }
	
	if(!function_exists('roots_entry_meta'))
	{
		function roots_entry_meta() {
			echo '<time class="updated" datetime="'. get_the_time('c') .'" pubdate>'. sprintf(__('Posted on %s at %s.', 'roots'), get_the_date(), get_the_time()) .'</time>';
			echo '<p class="byline author vcard">'. __('Written by', 'roots') .' <a href="'. get_author_posts_url(get_the_author_meta('ID')) .'" rel="author" class="fn">'. get_the_author() .'</a></p>';
		}
	}
	