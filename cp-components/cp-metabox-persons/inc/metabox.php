<?php

	/**
	 * Add frontend metabox
	 */
	function cmp_add_frontend() {
		global $cmp_plugin;
		// Check single case page
		if ( is_single() && get_post_type() == 'cases' ) {
			?>
			<div class="cases-box cases-box-open">
				<div class="cases-box-header">
					<h3>
						<a href="#" class="cases-box-toggle"><?php _e( 'Participants', $cmp_plugin->textdomain ); ?></a>
						<a href="#participants" name="participants" class="cases-box-anchor">#</a>
					</h3>
				</div>
				<div class="cases-box-content">
					<?php cmp_metabox(); ?>
				</div>
			</div>
			<?php
		}
	}

	add_action( 'roots_entry_content_before', 'cmp_add_frontend', 10 );

	/**
	 * Add backend metabox
	 *
	 * @global mixed $cmp_plugin Plugin object
	 */
	function cmp_add_backend() {
		// Get plugin object
		global $cmp_plugin, $post;
		// Register meta box
		add_meta_box( 'casepress-participants', __( 'Participants', $cmp_plugin->textdomain ), 'cmp_metabox', 'cases', 'normal', 'high', array( 'post' => $post ) );
	}

	add_action( 'add_meta_boxes', 'cmp_add_backend' );

	/**
	 * Metabox function
	 *
	 * @global mixed $cmp_plugin Plugin object
	 */
	function cmp_metabox( $post, $metabox ) {
		// Get plugin object
		global $cmp_plugin, $cmp_chooser, $current_user, $post;
		// Get current user data
		get_currentuserinfo();
		// Prepare vars
		$value = array( );
		$id = ( is_admin() ) ? (int) $metabox['args']['post']->ID : (int) $post->ID;
		$results = array( );
		$empty = '<strong>' . __( 'Not selected', $cmp_plugin->textdomain ) . '</strong>';
		$fields = array(
			'from' => array( __( 'Initiator', $cmp_plugin->textdomain ), false ),
			'responsible' => array( __( 'Responsible', $cmp_plugin->textdomain ), false ),
			'to' => array( __( 'Co-executors', $cmp_plugin->textdomain ), true )
		);
		$reg_roles = get_common_roles( 'cases' );
		$need_roles = array(
			'from' => $fields['from'][0],
			'responsible' => $fields['responsible'][0],
			'to' => $fields['to'][0]
		);
		// Create Memebrs Class instance
		$members = new CasePress_Members( $id );
		// Update common roles if needed
		if ( !is_array( $reg_roles ) || !in_array( array_keys( $need_roles ), array_keys( $reg_roles ) ) )
			foreach ( $need_roles as $role => $name )
				update_common_role( 'cases', $role, $name );
		// Prepare printable results
		foreach ( $fields as $field => $fdata ) {
			// Get and merge persons and terms for this loop-role [from, responsible, to]
			$results[$field] = array_merge( ( array ) $members->get_members( 'term', $field ), ( array ) $members->get_members( 'person', $field ) );
			// If persons or terms is set
			if ( count( $results[$field] ) )
				foreach ( $results[$field] as $result )
					$value[$field][] = cmp_get_link( $result );
			// Field value is empty
			else
				$value[$field] = array( $empty );
		}
		// Initiator is not set
		if ( !count( $results['from'] ) ) {
			// Get current person
			$person = get_person_by_user( $current_user->ID );
			// Set current person as default initiator
			$value['from'] = array( cmp_get_link( $person ) );
			// Save current person as default initiator
			$members->update_subject( array(
				'subject' => array( $person ),
				'subject_type' => 'person',
				'role' => 'from'
			) );
		}
		?>
		<div class="cmp-metabox">
			<?php
			// Print fields
			foreach ( $fields as $field => $fdata )
				cmp_render_field( $field, $fdata, $value[$field] );
			?>
			<div id="cmp-field-invites">
				<?php
				// Print invites
				foreach ( $fields as $field => $fdata )
					cmp_render_invite( $field, $fdata );
				?>
			</div>
		</div>
		<div id="cmp-chooser" data-post-id="<?php echo $id; ?>" data-title="<?php _e( 'Person selection', $cmp_plugin->textdomain ); ?>">
			<div class="cmp-instruction">
				<?php _e( 'Use search form to find persons. Also, you can use filters in left sidebar to filter persons by the categories. You can also add category completely by clicking on it\'s plus button.', $cmp_plugin->textdomain ); ?>
			</div>
			<?php
			// Show the Chooser form
			$cmp_chooser->render();
			?>
		</div>
		<?php
	}
?>