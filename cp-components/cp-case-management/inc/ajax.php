<?php

	function cmmngt_get_lfc() {
		// Get component
		global $cmmngt;
		// Prepare data
		$term = is_numeric( $_GET['term'] ) ? $_GET['term'] : false;
		$life_cycle = lfc_get_life_cycle( $term, 'term' );
		$terms = array( 'states' => array( ), 'results' => array( ) );
		$data = array( 'states' => array( ), 'results' => array( ) );
		// Check the cycle
		if ( !is_array( $life_cycle ) )
			return;
		// Get states terms
		$terms['states'] = get_terms( 'state', array(
			'hide_empty' => 0,
			'include' => ( array ) $life_cycle['states']
			) );
		// Terms are found, fill options array
		if ( count( $terms['states'] ) )
			foreach ( $terms['states'] as $term )
				$data['states'][] = array(
					'id' => $term->term_id,
					'text' => $term->name
				);
		// Get results terms
		$terms['results'] = get_terms( 'results', array(
			'hide_empty' => 0,
			'include' => ( array ) $life_cycle['results']
			) );
		// Terms are found, fill options array
		if ( count( $terms['results'] ) )
			foreach ( $terms['results'] as $term )
				$data['results'][] = array(
					'id' => $term->term_id,
					'text' => $term->name
				);
		// Prepend default options
		array_unshift( $data['states'], array(
			'id' => '0',
			'text' => __( 'Not selected', $cmmngt->textdomain )
		) );
		array_unshift( $data['results'], array(
			'id' => '0',
			'text' => __( 'Not selected', $cmmngt->textdomain )
		) );
		// Print json-encoded data
		die( json_encode( $data ) );
	}

	add_action( 'wp_ajax_cmmngt_get_lfc', 'cmmngt_get_lfc' );

	/**
	 * Get options for building chosen and displaying in popover
	 */
	function cmmngt_get_options() {
		// Prepare data
		global $cmmngt;
		$field = $_REQUEST['field'];
		$options = array( );
		$life_cycle = lfc_get_life_cycle( $_REQUEST['id'], 'post' );
		// Get case categories (functions)
		if ( $field == 'function' ) {
			// Get terms
			$terms = get_terms( 'functions', array(
				'hide_empty' => 0
				) );
			// If terms recieved, create options array
			if ( count( $terms ) )
				foreach ( $terms as $term )
					$options[$term->term_id] = $term->name;
			// Add default option
			$options[0] = __( 'Not selected', $cmmngt->textdomain );
		}
		// Priority
		elseif ( $field == 'priority' ) {
			$options = cmmngt_get_priorities();
		}
		// State
		elseif ( $field == 'state' ) {
			// Get terms
			$terms = get_terms( 'state', array(
				'hide_empty' => 0,
				'include' => ( array ) $life_cycle['states']
				) );
			// If terms recieved, create options array
			if ( count( $terms ) )
				foreach ( $terms as $term )
					$options[$term->term_id] = $term->name;
			// Add default option
			$options[0] = __( 'Not selected', $cmmngt->textdomain );
		}
		// Result
		elseif ( $field == 'result' ) {
			// Get terms
			$terms = get_terms( 'results', array(
				'hide_empty' => 0,
				'include' => ( array ) $life_cycle['results']
				) );
			// If terms recieved, create options array
			if ( count( $terms ) )
				foreach ( $terms as $term )
					$options[$term->term_id] = $term->name;
			// Add default option
			$options[0] = __( 'Not selected', $cmmngt->textdomain );
		}
		// Print formatted result
		die( json_encode( $options ) );
	}

	add_action( 'wp_ajax_cmmngt_get_options', 'cmmngt_get_options' );

	function cmmngt_get_edit_form_backend() {

		global $cmmngt;
		$post_id = $_POST['post_id'];
		$parent_id = $_POST['parent_id'];
		$function = $_POST['functions'];

		// Functions
		/*
		  $functions = wp_dropdown_categories( 'show_option_none=' . __( 'Not selected', $cmmngt->textdomain ) . '&hide_empty=0&name=functions&id=cmmngt-functions&class=cmmngt-init-chosen&selected=' . intval( $function ) . '&hierarchical=1&taxonomy=functions&echo=0' );
		  $functions = str_replace( '&nbsp;', '', $functions );
		 */
		$selected_functions = array_shift( get_the_terms( $post_id, 'functions' ) );
		$selected_function = !empty( $selected_functions ) ? $selected_functions->term_id
				: 80;
		$functions = wp_dropdown_categories( 'show_option_none=' . __( 'Not selected', $cmmngt->textdomain ) . '&hide_empty=0&name=functions&id=cmmngt-functions&class=cmmngt-init-chosen&selected=' . intval( $selected_function ) . '&hierarchical=1&taxonomy=functions&echo=0' );
		$functions = str_replace( '&nbsp;', '', $functions );

		// Priority
		$priorities = array(
			array( '1', 'Критичный' ),
			array( '2', 'Высокий' ),
			array( '3', 'Нормальный' ),
			array( '4', 'Низкий' ),
			array( '5', 'Планируемый' )
		);
		$current_priority = ( get_post_meta( $post_id, 'prioritet', 'true' ) && get_post_meta( $post_id, 'prioritet', 'true' ) != -1 )
				? get_post_meta( $post_id, 'prioritet', true ) : 3;

		// States
		$selected_states = array_shift( get_the_terms( $post_id, 'state' ) );
		$selected_state = (!empty( $selected_states ) ) ? $selected_states->term_id : false;
		$states = wp_dropdown_categories( 'show_option_none=' . __( 'Not selected', $cmmngt->textdomain ) . '&hide_empty=0&name=state&id=cmmngt-state&class=cmmngt-init-chosen&selected=' . intval( $selected_state ) . '&hierarchical=1&taxonomy=state&echo=0' );
		$states = str_replace( '&nbsp;', '', $states );

		// Result
		$selected_results = array_shift( get_the_terms( $post_id, 'results' ) );
		$selected_result = (!empty( $selected_results ) ) ? $selected_results->term_id
				: false;
		$results = wp_dropdown_categories( 'show_option_none=' . __( 'Not selected', $cmmngt->textdomain ) . '&hide_empty=0&name=results&id=cmmngt-results&class=cmmngt-init-chosen&selected=' . intval( $selected_result ) . '&hierarchical=1&taxonomy=results&echo=0' );
		$results = str_replace( '&nbsp;', '', $results );

		// Date deadline
		$date_deadline = ( get_post_meta( $post_id, 'date_deadline', true ) ) ? get_post_meta( $post_id, 'date_deadline', true )
				: null;
		?>
		<p><strong><?php _e( 'Case function', $cmmngt->textdomain ); ?></strong></p>
		<p><?php echo $functions; ?></p>
		<p><strong><?php _e( 'Case priority', $cmmngt->textdomain ); ?></strong></p>
		<p><select name="prioritet" id="cmmngt-priority" class="cmmngt-init-chosen">
				<option value="-1"><?php _e( 'Not selected', $cmmngt->textdomain ); ?></option>
				<?php
				foreach ( $priorities as $priority )
					echo '<option value="' . $priority[0] . '"', $current_priority == $priority[0]
							? ' selected="selected"' : '', '>' . $priority[1] . '</option>';
				?>
			</select></p>
		<p><strong><?php _e( 'Case state', $cmmngt->textdomain ); ?></strong></p>
		<p><?php echo $states; ?></p>
		<p><strong><?php _e( 'Case result', $cmmngt->textdomain ); ?></strong></p>
		<p><?php echo $results; ?></p>
		<p><strong><?php _e( 'Case date', $cmmngt->textdomain ); ?></strong></p>
		<p><input type="text" name="date_deadline" class="cmmngt-init-datepicker" value="<?php echo $date_deadline; ?>" size="16" /></p>
		<p><strong><?php _e( 'Case parent', $cmmngt->textdomain ); ?></strong></p>
		<p>
			<?php
			cmmngt_dropdown_cases( array(
				'selected' => $parent_id,
				'name' => 'parent_id',
				'id' => 'cmmngt-parent-id',
				'placeholder' => __( 'Select parent case...', $cmmngt->textdomain ),
				'post_type' => 'cases'
			) );
			?>
		</p>
		<input type="hidden" name="cmmngt_nonce" value="<?php echo wp_create_nonce( cmmngt_get_nonce() ); ?>" />
		<?php
		die();
	}

	add_action( 'wp_ajax_cmmngt_get_edit_form_backend', 'cmmngt_get_edit_form_backend' );

	function cmmngt_ajax_get_options() {
		die( json_encode( ( array ) cmmngt_get_cases( array( 's' => $_REQUEST['s'] ) ) ) );
	}

	add_action( 'wp_ajax_cmmngt_ajax_get_options', 'cmmngt_ajax_get_options' );

	/**
	 * AJAX handler that returns select tag with list of persons
	 */
	function cmmngt_get_persons_select() {
		// Prepare data
		$persons = cmmngt_get_persons();
		$options = array( );
		// Fill select with options
		foreach ( $persons as $person )
			$options[] = ( $person['name'] ) ? '<option value="' . $person['id'] . '">' . $person['name'] . '</option>'
					: '';
		// Print select
		die( '<select name="delegate" id="cmmngt-delegate-select">' . implode( '', $options ) . '</select>' );
	}

	add_action( 'wp_ajax_cmmngt_get_persons_select', 'cmmngt_get_persons_select' );
?>