<?php

	/**
	 * Save meta values on form submitting
	 */
	function ctmeta_save_meta( $term_id ) {

		// Get current taxonomy
		$taxonomy = $_POST['taxonomy'];

		// Check revision and permissions
		if ( !isset( $term_id ) || !current_user_can( 'manage_categories' ) )
			return $term_id;

		// Save datatable fields, titles & rows
		if ( isset( $_POST['ctmeta_datatable_params'] ) || isset( $_POST['ctmeta_title_template'] ) || isset( $_POST['ctmeta_default_responsible'] ) ||  isset( $_POST['ctmeta_default_deadline'] ) ) {
			ctmeta_update_meta( 'ctmeta_datatable_params', $_POST['ctmeta_datatable_params'], $taxonomy, $term_id );
			ctmeta_update_meta( 'ctmeta_title_template', $_POST['ctmeta_title_template'], $taxonomy, $term_id );
			ctmeta_update_meta( 'ctmeta_content_template', $_POST['ctmeta_content_template'], $taxonomy, $term_id);
			ctmeta_update_meta( 'ctmeta_default_responsible', $_POST['ctmeta_default_responsible'], $taxonomy, $term_id );
			ctmeta_update_meta( 'ctmeta_default_deadline', $_POST['ctmeta_default_deadline'], $taxonomy, $term_id );
			ctmeta_update_meta( 'ctmeta_deadline_priority_toggle', $_POST['ctmeta_deadline_priority_toggle'], $taxonomy, $term_id );
			ctmeta_update_meta( 'ctmeta_deadline_priority_1', $_POST['ctmeta_deadline_priority'][1], $taxonomy, $term_id );
			ctmeta_update_meta( 'ctmeta_deadline_priority_2', $_POST['ctmeta_deadline_priority'][2], $taxonomy, $term_id );
			ctmeta_update_meta( 'ctmeta_deadline_priority_3', $_POST['ctmeta_deadline_priority'][3], $taxonomy, $term_id );
			ctmeta_update_meta( 'ctmeta_deadline_priority_4', $_POST['ctmeta_deadline_priority'][4], $taxonomy, $term_id );
			ctmeta_update_meta( 'ctmeta_deadline_priority_5', $_POST['ctmeta_deadline_priority'][5], $taxonomy, $term_id );
			ctmeta_update_meta( 'ctmeta_term_id',$term_id, $taxonomy, $term_id);
		}
	}

	add_action( 'edited_functions', 'ctmeta_save_meta' );
	add_action( 'created_functions', 'ctmeta_save_meta' );
?>