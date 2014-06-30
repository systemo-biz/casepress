<?php

/*
Redirect template to table or custom template in theme folder
*/

add_action('template_redirect', 'template_list_load_cp');

function template_list_load_cp(){

	if(is_archive() || is_search() || is_home()) {
		$tmpl_file_name = 'list-cp.php';
		if ( $overridden_template = locate_template( array($tmpl_file_name, 'templates/'.$tmpl_file_name) ) ) {
			load_template( $overridden_template );
		} else {
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		exit;
	}

}


add_action('template_redirect', 'template_item_load_cp');

function template_item_load_cp(){

	//$post_types = apply_filters( 'change_load_tmpl_for_post_type_cp',  array('report','cases','persons', 'page', 'post', 'process'));

	if(is_single()) {
		$tmpl_file_name = 'item-cp.php';
		if ( $overridden_template = locate_template( array($tmpl_file_name, 'templates/'.$tmpl_file_name) ) ) {
			load_template( $overridden_template );
		} else {
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		exit;
	}	
	
}

/*
Тут определяем тип страницы и то какой сайдбар загружать
*/

add_action( $tag = 'sidebar_cp', $function_to_add = 'get_sidebar_cp' );
function get_sidebar_cp() {
	
	$sidebar_name = 'general-cp';

	if (is_singular('persons') || is_post_type_archive( 'persons' )) $sidebar_name = 'persons';
	if (is_singular('cases') || is_post_type_archive( 'cases' )) $sidebar_name = 'cases';
	if (is_singular('process') || is_post_type_archive( 'process' )) $sidebar_name = 'general-cp';

	dynamic_sidebar( $sidebar_name );
}


add_filter( $tag = 'the_content', $function_to_add = 'add_sections_after_content_cp');
function add_sections_after_content_cp($content) {
global $post;
	$content = '<div class="cp_content_container">' . $content . '</div>';

	ob_start()
	?>
	<div class="sections_item_cp">
		<?php do_action( $tag = 'cp_entry_content_after', $post, $content );?>
	</div>
	<?php
	$content = $content . ob_get_contents();
	ob_get_clean();
	return $content;
}


add_filter( $tag = 'change_sidebar_cp', $function_to_add = 'change_sidebar_cp_callback' );
function change_sidebar_cp_callback($sidebar_name){
	if(is_post_type_archive('cases')){
		$sidebar_name = 'cases';
	}
	return $sidebar_name;
}

/*
Выводим название архива
*/
add_action( $tag = 'page_title_cp', $function_to_add = 'the_title_page_cp' );

function the_title_page_cp(){
	?>
	<header class="page-header">
		<h1 class="page-title">
			<?php
			if ( is_category() ) {
				single_cat_title();

			} elseif ( is_tag() ) {
				single_tag_title();

			} elseif ( is_author() ) {
				printf( __( 'Author: %s', 'alienship' ), '<span class="vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( "ID" ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );

			} elseif ( is_day() ) {
				printf( __( 'Day: %s', 'alienship' ), '<span>' . get_the_date() . '</span>' );

			} elseif ( is_month() ) {
				printf( __( 'Month: %s', 'alienship' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

			} elseif ( is_year() ) {
				printf( __( 'Year: %s', 'alienship' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

			} elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
				_e( 'Asides', 'alienship' );

			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				_e( 'Galleries', 'alienship');

			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				_e( 'Images', 'alienship');

			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				_e( 'Videos', 'alienship' );

			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				_e( 'Quotes', 'alienship' );

			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				_e( 'Links', 'alienship' );

			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				_e( 'Statuses', 'alienship' );

			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				_e( 'Audios', 'alienship' );

			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				_e( 'Chats', 'alienship' );

			} elseif ( is_archive() ) {
				echo post_type_archive_title('',false);

			} elseif ( is_home() ) {
				echo "Блог";

			} else {
				_e( 'Archives', 'alienship' );

			} ?>
		</h1>

		<?php
		// show an optional category description
		$term_description = term_description();
		if ( ! empty( $term_description ) )
			printf( '<div class="taxonomy-description">%s</div>', $term_description ); ?>

	</header>
	<?php
}

function cp_template_redirect() {
	global $post;

	// cases category (functions)
	if (is_tax('functions')){
		
		$tmpl_file_name = 'taxonomy-functions.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}

	// subjects_category
	if (is_tax('subjects_category')){
		
		$tmpl_file_name = 'taxonomy-subjects_category.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}

	// organization_structure
	if (is_tax('organization_structure')){
		
		$tmpl_file_name = 'taxonomy-organization_structure.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}

	// persons_category
	if (is_tax('persons_category')){
		
		$tmpl_file_name = 'taxonomy-persons_category.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}		
	
	// objects_category
	if (is_tax('objects_category')){
		
		$tmpl_file_name = 'taxonomy-objects_category.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}		

	
	// reports category
	if (is_tax('report_cat')){
		
		$tmpl_file_name = 'taxonomy-report_cat.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}		
	
	// reports list
	if ($post->ID == get_option('page_for_reports_list') || is_post_type_archive('reports')){
		$tmpl_file_name = 'archive-report.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			
			load_template( plugin_dir_path(__FILE__).'cp-default/'.$tmpl_file_name, true );
		}
		die;
	}
	// reports single
	if (is_singular('report')) {
		$tmpl_file_name = 'single-report.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			load_template( plugin_dir_path(__FILE__).'cp-default/'.$tmpl_file_name, true );
		}
		die;
	}
	
	// persons list
	if ($post->ID == get_option('page_for_persons_list') || is_post_type_archive('persons')){
		$tmpl_file_name = 'archive-persons.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}
	// persons single
	if (is_singular('persons')) {
		$tmpl_file_name = 'single-persons.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}
	
	// objects list
	if ($post->ID == get_option('page_for_objects_list') || is_post_type_archive('objects')){
		$tmpl_file_name = 'archive-objects.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}

	// object single
	if (is_singular('objects')) {
		$tmpl_file_name = 'single-objects.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}
	
	// organizations list
	if ($post->ID == get_option('page_for_organizations_list') || is_post_type_archive('organizations')){
		$tmpl_file_name = 'archive-organizations.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}

	// organization single
	if (is_singular('organizations')) {
		$tmpl_file_name = 'single-organizations.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}
	
	// cases list
	if ($post->ID == get_option('page_for_cases_list') || is_post_type_archive('cases')){
		$tmpl_file_name = 'archive-cases.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}

	// case single
	if (is_singular('cases')){
		$tmpl_file_name = 'single-cases.php';
		if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
			load_template( $overridden_template );
		} else {
			
			load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
		}
		die;
	}

}