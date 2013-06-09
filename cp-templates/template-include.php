<?php

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

		// organizations category
		if (is_tax('organizations_category')){
			
			$tmpl_file_name = 'taxonomy-organizations_category.php';
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
		
		// reports list
		if ($post->ID == get_option('page_for_reports_list') || is_post_type_archive('reports')){
			$tmpl_file_name = 'archive-sql_report.php';
			if ( $overridden_template = locate_template( 'cp-default/'.$tmpl_file_name ) ) {
				load_template( $overridden_template );
			} else {
				//include_once 'hooks-roots.php';
				load_template( plugin_dir_path(__FILE__).'cp-default/'.$tmpl_file_name, true );
			}
			die;
		}
		// reports single
		if (is_singular('reports')) {
			$tmpl_file_name = 'single-reports.php';
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
				include_once 'hooks-roots.php';
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
				include_once 'hooks-roots.php';
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
				include_once 'hooks-roots.php';
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
				include_once 'hooks-roots.php';
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
				include_once 'hooks-roots.php';
				load_template( plugin_dir_path(__FILE__).'/cp-default/'.$tmpl_file_name, true );
			}
			die;
		}

	}
	add_action('template_redirect', 'cp_template_redirect', 10);
?>