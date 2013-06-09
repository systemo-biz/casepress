<?php


class Cases_Workflow_Labels {
	var $person_id;

	function __construct() {
	//	add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_action( 'init', array( $this, 'set_person_id' ) );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'added_post_meta', array( $this, 'add_labels_to_new_cases' ), 10, 4 );
		add_action( 'updated_post_meta', array( $this, 'add_labels_to_new_cases' ), 10, 4 );

		// add_action( 'wp_ajax_create_label', array( $this, 'ajax_create_label' ) );
		add_action( 'wp_ajax_add_labels', array( $this, 'ajax_add_labels' ) );

		add_filter( 'get_terms_args', array( $this, 'filter_labels_by_person' ), 10, 2 );
		add_filter( 'get_terms', array( $this, 'filter_get_terms' ), 10, 3 );
		add_filter( 'get_the_terms', array( $this, 'filter_get_the_terms' ), 10, 3 );
		add_filter( 'get_term', array( $this, 'filter_get_term' ), 10, 2 );
		add_filter( 'single_term_title', array( $this, 'filter_single_term_title' ) );

		add_action( 'wp', array( $this, 'add_or_remove_from_inbox' ) );
		add_action( 'roots_entry_meta_before', array( $this, 'print_case_labels_box' ) );
	}



	function set_person_id() {
		if ( ! is_user_logged_in() )
			return;

		$email = wp_get_current_user()->user_email;

		$person = new WP_Query( array(
			'fields' => 'ids',
			'post_type' => 'persons',
			'meta_query' => array( array( 'key' => 'email', 'value' => $email ) ),
		) );

		$this->person_id = array_shift( $person->posts );
	}

	function enqueue_scripts() {
		if ( is_singular( 'persons' ) ) {
			wp_enqueue_style( 'cases-workflow-labels', plugins_url( 'includes/cases-workflow-labels.css', __FILE__ ), array(), '1.0' );

			wp_enqueue_script( 'cases-workflow-labels', plugins_url( 'includes/cases-workflow-labels.js', __FILE__ ), array( 'jquery' ), '1.0', true );
			wp_localize_script( 'cases-workflow-labels', 'cwlAjax', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			) );
		}
	}

	function register_widget() {
		include( 'includes/widget.php' );
		register_widget( 'Cases_Workflow_Labels_Widget' );
	}

	function add_labels_to_new_cases( $meta_id, $object_id, $meta_key, $meta_value ) {
		switch ( $meta_key ) {
			case 'initiator' :
				$label_name = $meta_value . '-Исходящие';
				wp_set_post_terms( $object_id, $label_name, 'labels', true );
				break;
			case 'responsible' :
			case 'participant' :
				$user_ids = explode( ',', $meta_value );
				foreach ( (array) $user_ids as $user_id ) {
					$label_name = $user_id . '-Входящие';
					wp_set_post_terms( $object_id, $label_name, 'labels', true );
				}
				break;
		}
	}

	function add_label_box() {
?>
<div id="labels" class="tagsdiv">
	<div class="jaxtag">
 		<div class="ajaxtag hide-if-no-js">
			<label for="new-tag-labels" class="screen-reader-text">Ярлыки</label>
			<div class="taghint" style="">Добавить новый ярлык</div>
			<p><input type="text" value="" autocomplete="off" size="16" class="newtag form-input-tip" name="newtag[labels]" id="new-tag-labels">
			<input type="button" tabindex="3" value="Добавить" class="button tagadd"></p>
		</div>
		<!-- <p class="howto">Ярлыки разделяются запятыми</p> -->
	</div>
	<div class="tagchecklist"></div>
</div>
<!-- <p class="hide-if-no-js"><a id="link-labels" class="tagcloud-link" href="#titlediv">Выбрать из часто используемых ярлыков</a></p> -->
<?php
	}

	function get_label_list( $args = '' ) {
		$defaults = array( 'style' => '', 'sep' => ' &middot; ', 'echo' => true );
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$terms = get_terms( 'labels', 'hide_empty=0' );
		$count = count( $terms );
		$i = 0;
		$term_list = '';

		if ( 'list' == $style )
			$term_list .= '<ul>';

		foreach ( (array) $terms as $term ) {
			$term_link = get_term_link( $term->slug, $term->taxonomy );
			if ( is_wp_error( $term_link ) )
				continue;

			if ( 'list' == $style )
				$term_list .= '<li>';
			$term_list .= sprintf( '<a href="%s" title="%s">%s</a> (%d)',
				$term_link,
				sprintf( 'Просмотреть все дела с ярлыком %s', $term->name ),
				$term->name,
				$term->count
			);

			if ( 'list' == $style )
				$term_list .= "</li>\n";
			elseif ( $count != ++$i )
				$term_list .= $sep;
		}

		if ( 'list' == $style )
			$term_list .= "</ul>\n";

		if ( $echo )
			echo $term_list;
		else
			return $term_list;
	}

	function ajax_create_label() {
		$tag = wp_insert_term( $_POST['labels'], 'labels' );

		$this->get_label_list();

		die();
	}

	function ajax_add_labels() {
		// echo 'test';
		// echo '<pre>'; print_r( $_POST ); echo '</pre>';
		// die();
		// echo '<pre>'; print_r( $posts ); echo '</pre>';

		if ( empty( $_POST['posts'] ) ) {
			wp_insert_term( $_POST['labels'], 'labels' );
		} else {
			foreach ( (array) $_POST['posts'] as $post_id )
				wp_set_post_terms( $post_id, $_POST['labels'], 'labels', true );
		}

		$this->get_label_list();

		die();
	}

	function filter_labels_by_person( $args, $taxonomies ) {
		if ( ! is_user_logged_in() || array( 'labels' ) !== $taxonomies )
			return $args;

		if ( empty( $args['name__like'] ) )
			$args['name__like'] = $this->person_id . '-';

		return $args;
	}

	function hide_prefix_from_term_names( $terms ) {
		if ( ! is_array( $terms ) )
			return $terms;

		foreach ( $terms as $key => $term )
			$terms[ $key ]->name = str_replace( $this->person_id . '-', '', $term->name );

		return $terms;
	}

	function filter_get_terms( $terms, $taxonomies, $args ) {
		if ( ! is_user_logged_in() || array( 'labels' ) !== $taxonomies )
			return $terms;

		return $this->hide_prefix_from_term_names( $terms );
	}

	function filter_get_the_terms( $terms, $post_id, $taxonomy ) {
		if ( ! is_user_logged_in() || 'labels' !== $taxonomy )
			return $terms;

		$filtered_terms = array();
		foreach ( $terms as $key => $term ) {
			if ( 0 !== strpos( $term->name, $this->person_id . '-' ) )
				 continue;
			$filtered_terms[] = $term;
		}

		return $this->hide_prefix_from_term_names( $filtered_terms );
	}

	function get_term_info_from_name( $term_name ) {
		preg_match( '/^([0-9]+)\-(.+)/', $term_name, $matches );
		if ( empty( $matches ) )
			return false;

		$term_info = new stdClass;
		$term_info->person_id = $matches[1];
		$term_info->term_name = $matches[2];

		return $term_info;
	}

	function filter_get_term( $term, $taxonomy ) {
		if ( 'labels' != $term->taxonomy )
			return $term;

		$term_info = $this->get_term_info_from_name( $term->name );
		if ( empty( $term_info ) )
			return $term;

		$person = get_post( $term_info->person_id );
		if ( ! $person )
			return $term;

		$term->name = sprintf( '%s (<a href="%s">%s</a>)', $term_info->term_name, get_permalink( $person->ID ), $person->post_title );

		return $term;
	}

	function filter_single_term_title( $term_name ) {
		return strip_tags( $term_name );
	}

	function add_or_remove_from_inbox() {
		global $post;

		if ( ! is_user_logged_in() || ! isset( $_GET['action'] ) || ! is_singular( 'cases' ) )
			return;

		switch ( $_GET['action'] ) {
			case 'remove-from-inbox' :
				$terms = wp_get_post_terms( $post->ID, 'labels', array( 'fields' => 'names' ) );
				$terms = array_diff( $terms, array( $this->person_id . '-Входящие' ) );

				wp_set_post_terms( $post->ID, $terms, 'labels' );

				wp_redirect( get_permalink() );
				exit();

				break;
			case 'add-to-inbox' :
				$terms = array( $this->person_id . '-Входящие' );

				wp_set_post_terms( $post->ID, $terms, 'labels', true );

				wp_redirect( get_permalink() );
				exit();

				break;
			case 'remove-from-starred' :
				$terms = wp_get_post_terms( $post->ID, 'labels', array( 'fields' => 'names' ) );
				$terms = array_diff( $terms, array( $this->person_id . '-Избранное' ) );

				wp_set_post_terms( $post->ID, $terms, 'labels' );

				wp_redirect( get_permalink() );
				exit();

				break;
			case 'add-to-starred' :
				$terms = array( $this->person_id . '-Избранное' );

				wp_set_post_terms( $post->ID, $terms, 'labels', true );

				wp_redirect( get_permalink() );
				exit();

				break;
		}
	}

	function print_case_labels_box() {
		global $post;

		if ( 'cases' != $post->post_type )
			return;

		if ( has_term( sanitize_title( $this->person_id . '-Входящие' ), 'labels', $post->ID ) ) {
			$url = add_query_arg( array( 'action' => 'remove-from-inbox' ), get_permalink() );
			echo sprintf( '<a class="btn btn-mini" href="%s">Убрать из Входящих</a> ', $url );
		} else {
			$url = add_query_arg( array( 'action' => 'add-to-inbox' ), get_permalink() );
			echo sprintf( '<a class="btn btn-mini" href="%s">Добавить во Входящие</a> ', $url );
		}

		if ( has_term( sanitize_title( $this->person_id . '-Избранное' ), 'labels', $post->ID ) ) {
			$url = add_query_arg( array( 'action' => 'remove-from-starred' ), get_permalink() );
			echo sprintf( '<a class="btn btn-mini" href="%s">Убрать из Избранного</a> ', $url );
		} else {
			$url = add_query_arg( array( 'action' => 'add-to-starred' ), get_permalink() );
			echo sprintf( '<a class="btn btn-mini" href="%s">Добавить в Избранное</a> ', $url );
		}

		the_terms( $post->ID, 'labels', 'Ярлыки дела: ' );
		echo '<br /><br />';
	}

}

$cases_workflow_labels = new Cases_Workflow_Labels;
?>