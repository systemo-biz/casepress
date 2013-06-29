<div id="cmmngt-meta-box-container" class="cases-box cases-box-open" data-no-results-text="<?php _e( 'No results match', $cmmngt->textdomain ); ?>" data-post-id="<?php the_ID(); ?>">
	<div class="cases-box-header">
		<h3>
			<a href="#" class="cases-box-toggle"><?php _e( 'Management', $cmmngt->textdomain ); ?></a>
			<a href="#management" name="management" class="cases-box-anchor">#</a>
		</h3>
	</div>
	<div class="cases-box-content">
		<div id="cmmngt-metabox-info">
			<p class="muted"><strong><?php _e( 'Note', $cmmngt->textdomain ) ?>:</strong> <?php _e( 'to edit characteristics click on fields names', $cmmngt->textdomain ); ?></p>
			<div class="cmmngt-pretty-labels">
				<p class="cmmngt-invited-field cmmngt-editable-field" data-field="function"<?php cmmngt_empty_field_marker( $selected_function, 'function' ); ?>>
					<strong title="<?php _e( 'Click to edit', $cmmngt->textdomain ); ?>">
						<em><?php _e( 'Case function', $cmmngt->textdomain ); ?></em>
					</strong>
					<span data-type="select" data-name="function" data-value="<?php echo $selected_function; ?>" data-source="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=cmmngt_get_options&field=function" data-original-title="<?php _e( 'Case function selection', $cmmngt->textdomain ); ?>" data-inputclass="input-block-level"><?php cmmngt_term_permalink( $selected_function, 'functions' ); ?></span>
				</p>
				<p class="cmmngt-invited-field cmmngt-editable-field" data-field="priority"<?php cmmngt_empty_field_marker( $current_priority, 'priority' ); ?>>
					<strong title="<?php _e( 'Click to edit', $cmmngt->textdomain ); ?>">
						<em><?php _e( 'Case priority', $cmmngt->textdomain ); ?></em>
					</strong>
					<span data-type="select" data-name="priority" data-value="<?php echo $current_priority; ?>" data-source="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=cmmngt_get_options&field=priority" data-original-title="<?php _e( 'Priority selection', $cmmngt->textdomain ); ?>" data-inputclass="input-block-level"><?php echo $current_priority_display; ?></span>
				</p>
				<p class="cmmngt-invited-field cmmngt-editable-field" data-field="state"<?php cmmngt_empty_field_marker( $selected_state, 'state' ); ?>>
					<strong title="<?php _e( 'Click to edit', $cmmngt->textdomain ); ?>">
						<em><?php _e( 'Case state', $cmmngt->textdomain ); ?></em>
					</strong>
					<span data-type="select" data-name="state" data-value="<?php echo $selected_state; ?>" data-source="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=cmmngt_get_options&field=state&id=<?php echo $post_id; ?>" data-inputclass="input-block-level"><?php cmmngt_term_title( $selected_state, 'state' ); ?></span>
				</p>
				<p class="cmmngt-invited-field cmmngt-editable-field" data-field="result"<?php cmmngt_empty_field_marker( $selected_result, 'result' ); ?>>
					<strong title="<?php _e( 'Click to edit', $cmmngt->textdomain ); ?>">
						<em><?php _e( 'Case result', $cmmngt->textdomain ); ?></em>
					</strong>
					<span data-type="select" data-name="result" data-value="<?php echo $selected_result; ?>" data-source="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=cmmngt_get_options&field=result&id=<?php echo $post_id; ?>" data-inputclass="input-block-level"><?php cmmngt_term_title( $selected_result, 'results' ); ?></span>
				</p>
				<p class="cmmngt-invited-field cmmngt-editable-field cmmngt-editable-field-date" data-field="deadline"<?php cmmngt_empty_field_marker( $date_deadline, 'deadline' ); ?>>
					<strong title="<?php _e( 'Click to edit', $cmmngt->textdomain ); ?>">
						<em><?php _e( 'Case date', $cmmngt->textdomain ); ?></em>
					</strong>
					<span data-type="date" data-name="deadline" data-viewformat="dd.mm.yyyy" data-value="<?php echo $date_deadline; ?>" data-inputclass="input-small"><?php cmmngt_pretty_date( $date_deadline ); ?></span>
				</p>
				<p class="cmmngt-invited-field cmmngt-editable-field cmmngt-editable-field-date" data-field="date_register"<?php cmmngt_empty_field_marker( $date_register, 'date_register' ); ?>>
					<strong title="<?php _e( 'Click to edit', $cmmngt->textdomain ); ?>">
						<em><?php _e( 'Case date register', $cmmngt->textdomain ); ?></em>
					</strong>
					<span data-type="date" data-name="date_register" data-viewformat="dd.mm.yyyy" data-value="<?php echo $date_register; ?>" data-inputclass="input-small"><?php cmmngt_pretty_date( $date_register ); ?></span>
				</p>
				<p class="cmmngt-invited-field cmmngt-editable-field cmmngt-editable-field-date" data-field="date_start"<?php cmmngt_empty_field_marker( $date_start, 'date_start' ); ?>>
					<strong title="<?php _e( 'Click to edit', $cmmngt->textdomain ); ?>">
						<em><?php _e( 'Case date start', $cmmngt->textdomain ); ?></em>
					</strong>
					<span data-type="date" data-name="date_start" data-viewformat="dd.mm.yyyy" data-value="<?php echo $date_start; ?>" data-inputclass="input-small"><?php cmmngt_pretty_date( $date_start ); ?></span>
				</p>
				<p class="cmmngt-invited-field cmmngt-editable-field cmmngt-editable-field-date" data-field="date_end"<?php cmmngt_empty_field_marker( $date_end, 'date_end' ); ?>>
					<strong title="<?php _e( 'Click to edit', $cmmngt->textdomain ); ?>">
						<em><?php _e( 'Case date end', $cmmngt->textdomain ); ?></em>
					</strong>
					<span data-type="date" data-name="date_end" data-viewformat="dd.mm.yyyy" data-value="<?php echo $date_end; ?>" data-inputclass="input-small"><?php cmmngt_pretty_date( $date_end ); ?></span>
				</p>
				<p class="cmmngt-invited-field cmmngt-editable-field" data-field="parent_id"<?php cmmngt_empty_field_marker( $parent_id, 'parent_id' ); ?>>
					<strong title="<?php _e( 'Click to edit', $cmmngt->textdomain ); ?>">
						<em><?php _e( 'Case parent', $cmmngt->textdomain ); ?></em>
					</strong>
					<span data-type="select2" data-name="parent_id" data-value="<?php echo $parent_id; ?>" data-inputclass="input-block-level"><?php echo cmmngt_case_link( $parent_id ); ?></span>
				</p>
			</div>
			<div id="cmmngt-field-invites">
				<span data-invite="function"><?php _e( 'Case function', $cmmngt->textdomain ); ?></span>
				<span data-invite="priority"><?php _e( 'Case priority', $cmmngt->textdomain ); ?></span>
				<span data-invite="state"><?php _e( 'Case state', $cmmngt->textdomain ); ?></span>
				<span data-invite="result"><?php _e( 'Case result', $cmmngt->textdomain ); ?></span>
				<span data-invite="deadline"><?php _e( 'Case date', $cmmngt->textdomain ); ?></span>
				<span data-invite="date_register"><?php _e( 'Case date register', $cmmngt->textdomain ); ?></span>
				<span data-invite="date_start"><?php _e( 'Case date start', $cmmngt->textdomain ); ?></span>
				<span data-invite="date_end"><?php _e( 'Case date end', $cmmngt->textdomain ); ?></span>
				<span data-invite="parent_id"><?php _e( 'Case parent', $cmmngt->textdomain ); ?></span>
			</div>
		</div>

		<form action="" method="post" class="cmmngt-form">
			<div class="cmmngt-container">
				<div class="btn-toolbar">
					<?php cmmngt_print_buttons( $buttons ); ?>
					<span class="cmmngt-loading"></span>
					<?php cmmngt_print_buttons_after(); ?>
					<div id="cmmngt-delegate-select-box" class="well"><strong><?php _e( 'Select person that will be set as responsible', $cmmngt->textdomain ); ?></strong>:<br /><span class="cmmngt-loading-persons"></span></div>
				</div>
			</div>
			<div class="cmmngt-hidden">
				<?php echo $hidden; ?>
			</div>
		</form>

	</div>
</div>
<!-- Action priority: 10 -->