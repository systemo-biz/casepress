<?php

	/**
	 * Add menu links
	 *
	 * @global mixed $wp_admin_bar Admin bar object
	 * @global mixed $ctcm_plugin Plugin object
	 */
	function ctcm_add_admin_bar_links() {
		global $wp_admin_bar, $ctcm_plugin;

		// Add sub-task
		if ( is_singular() && get_post_type() == 'cases' ) {

			$id = get_the_ID();
			$title = urlencode( '>> ' . get_the_title( get_the_ID() ) );
			$url = admin_url( 'post-new.php?post_type=cases&amp;csposter&amp;csposter_parent_id=' . $id . '&amp;csposter_title=' . $title . '&amp;width=900&amp;height=500&amp;TB_iframe=1' );

			$wp_admin_bar->add_menu( array(
				'parent' => false,
				'id' => 'ctcm_actions_menu',
				'title' => __( 'Actions', $ctcm_plugin->textdomain ),
				'href' => '#',
				'meta' => false
			) );

			$wp_admin_bar->add_menu( array(
				'parent' => 'ctcm_actions_menu',
				'id' => 'ctcm_add_sub_task',
				'title' => __( 'Add sub-task', $ctcm_plugin->textdomain ),
				'href' => $url,
				'meta' => array( 'onclick' => 'tb_show("' . __( 'Add sub-task', $ctcm_plugin->textdomain ) . '","' . $url . '"); jQuery("#TB_window").css({width:"900px",height:"500px",marginLeft:"-450px"}); jQuery("#TB_iframeContent").css("width","900px"); return false;' )
			) );
		}

		// Create case
		elseif ( is_tax( 'functions' ) ) {

			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

			$url = admin_url( 'post-new.php?post_type=cases&amp;csposter&amp;csposter_function=' . $term->term_id . '&amp;width=900&amp;height=500&amp;TB_iframe=1' );

			$wp_admin_bar->add_menu( array(
				'parent' => false,
				'id' => 'ctcm_actions_menu',
				'title' => __( 'Actions', $ctcm_plugin->textdomain ),
				'href' => '#',
				'meta' => false
			) );

			$wp_admin_bar->add_menu( array(
				'parent' => 'ctcm_actions_menu',
				'id' => 'ctcm_create_case',
				'title' => __( 'Create case', $ctcm_plugin->textdomain ),
				'href' => $url,
				'meta' => array( 'onclick' => 'tb_show("' . __( 'Create case', $ctcm_plugin->textdomain ) . '","' . $url . '"); jQuery("#TB_window").css({width:"900px",height:"500px",marginLeft:"-450px"}); jQuery("#TB_iframeContent").css("width","900px"); return false;' )
			) );
		}

		// Appoint as responsible and send message
		elseif ( is_singular() && get_post_type() == 'persons' ) {

			$person_id = get_the_ID();

			$url_appoint = admin_url( 'post-new.php?post_type=cases&amp;csposter&amp;csposter_appoint_as_responsible=' . $person_id . '&amp;width=900&amp;height=500&amp;TB_iframe=1' );
			$url_message = admin_url( 'post-new.php?post_type=cases&amp;csposter&amp;csposter_personal_message=' . $person_id . '&amp;width=900&amp;height=500&amp;TB_iframe=1' );

			$wp_admin_bar->add_menu( array(
				'parent' => false,
				'id' => 'ctcm_actions_menu',
				'title' => __( 'Actions', $ctcm_plugin->textdomain ),
				'href' => '#',
				'meta' => false
			) );

			// Appoint as responsible link
			$wp_admin_bar->add_menu( array(
				'parent' => 'ctcm_actions_menu',
				'id' => 'ctcm_appoint_as_responsible',
				'title' => __( 'Appoint as responsible', $ctcm_plugin->textdomain ),
				'href' => $url_appoint,
				'meta' => array( 'onclick' => 'tb_show("' . __( 'Appoint as responsible', $ctcm_plugin->textdomain ) . '","' . $url_appoint . '"); jQuery("#TB_window").css({width:"900px",height:"500px",marginLeft:"-450px"}); jQuery("#TB_iframeContent").css("width","900px"); return false;' )
			) );

			// Personal message
			$wp_admin_bar->add_menu( array(
				'parent' => 'ctcm_actions_menu',
				'id' => 'ctcm_personal_message',
				'title' => __( 'Send message', $ctcm_plugin->textdomain ),
				'href' => $url_message,
				'meta' => array( 'onclick' => 'tb_show("' . __( 'Send message', $ctcm_plugin->textdomain ) . '","' . $url_message . '"); jQuery("#TB_window").css({width:"900px",height:"500px",marginLeft:"-450px"}); jQuery("#TB_iframeContent").css("width","900px"); return false;' )
			) );
		}
	}

	add_action( 'wp_before_admin_bar_render', 'ctcm_add_admin_bar_links' );
?>