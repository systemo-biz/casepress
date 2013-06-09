<?php

	/**
	 * AJAX DataTable generator
	 */
	function ckpp_ajax_get_dossier_datatable() {
		// Params is sent
		if ( ( isset( $_POST['meta'] ) && $_POST['meta'] ) && ( isset( $_POST['tax'] ) && $_POST['tax'] ) ) {
			// Define params
			$params = array(
				'fields' => 'ID:link, post_title:link, prioritet, state:tax, post_date, functions:tax',
				'tax' => 'results',
				'meta' => $_POST['meta'],
				'view' => 'id:dt_person_' . str_replace( ':', '_', $_POST['filter'] )
			);
			if ( isset( $_POST['group'] ) && $_POST['group'] == 'true' )
				$params['group'] = 'prioritet';
			// Correct tax param
			switch ( $_POST['tax'] ) {
				case 'open':
					$params['tax'] = 'results:NONE';
					break;
				case 'closed':
					$params['fields'] = $params['fields'] . ', results';
					$params['tax'] = 'results:ALL';
					break;
				case 'all':
					$params['fields'] = $params['fields'] . ', results';
					$params['tax'] = '';
					break;
			}
		}
		// Generate datatable
		if ( function_exists( 'datatable_generator' ) && is_array( $params ) )
			datatable_generator( $params );
		// Prevent unwanted output
		die();
	}

	add_action( 'wp_ajax_get_dossier_datatable', 'ckpp_ajax_get_dossier_datatable' );
?>