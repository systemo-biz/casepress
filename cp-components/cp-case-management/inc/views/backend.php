<div id="cmmngt-meta-box-container" data-no-results-text="<?php _e( 'No results match', $cmmngt->textdomain ); ?>" data-post-id="<?php the_ID(); ?>" data-post-parent="<?php echo $parent_id; ?>">
	<div id="cmmngt-metabox-edit">
		<div class="cmmngt-fields-set">
			<p class="cmmngt-invited-field" data-field="function"<?php cmmngt_empty_field_marker( $selected_function, 'function' ); ?>>
				<strong><?php _e( 'Case function', $cmmngt->textdomain ); ?></strong>
				<?php echo $functions; ?>
			</p>
			<p class="cmmngt-invited-field" data-field="priority"<?php cmmngt_empty_field_marker( $priority_marker, 'priority' ); ?>>
				<strong><?php _e( 'Case priority', $cmmngt->textdomain ); ?></strong>
				<select name="prioritet" id="cmmngt-priority" class="cmmngt-init-select2">
					<?php
						foreach ( $priorities as $priority_id => $priority_text )
							echo '<option value="' . $priority_id . '"', $current_priority == $priority_id ? ' selected="selected"' : '', '>' . $priority_text . '</option>';
					?>
				</select>
			</p>
			<p class="cmmngt-invited-field" data-field="state"<?php cmmngt_empty_field_marker( $selected_state, 'state' ); ?>>
				<strong><?php _e( 'Case state', $cmmngt->textdomain ); ?></strong>
				<?php echo $states; ?>
			</p>
			<p class="cmmngt-invited-field" data-field="result"<?php cmmngt_empty_field_marker( $selected_result, 'result' ); ?>>
				<strong><?php _e( 'Case result', $cmmngt->textdomain ); ?></strong>
				<?php echo $results; ?>
			</p>
			<p class="cmmngt-invited-field" data-field="date_deadline"<?php cmmngt_empty_field_marker( $date_deadline, 'date_deadline' ); ?>>
				<strong><?php _e( 'Case date', $cmmngt->textdomain ); ?></strong>
				<input type="text" name="date_deadline" class="cmmngt-init-datepicker" value="<?php echo $date_deadline; ?>" size="26" />
			</p>
			<p class="cmmngt-invited-field" data-field="date_register"<?php cmmngt_empty_field_marker( $date_register, 'date_register' ); ?>>
				<strong><?php _e( 'Case date register', $cmmngt->textdomain ); ?></strong>
				<input type="text" name="date_register" class="cmmngt-init-datepicker" value="<?php echo $date_register; ?>" size="26" />
			</p>
			<p class="cmmngt-invited-field" data-field="date_start"<?php cmmngt_empty_field_marker( $date_start, 'date_start' ); ?>>
				<strong><?php _e( 'Case date start', $cmmngt->textdomain ); ?></strong>
				<input type="text" name="date_start" class="cmmngt-init-datepicker" value="<?php echo $date_start; ?>" size="26" />
			</p>
			<p class="cmmngt-invited-field" data-field="date_end"<?php cmmngt_empty_field_marker( $date_end, 'date_end' ); ?>>
				<strong><?php _e( 'Case date end', $cmmngt->textdomain ); ?></strong>
				<input type="text" name="date_end" class="cmmngt-init-datepicker" value="<?php echo $date_end; ?>" size="26" />
			</p>
			<p class="cmmngt-invited-field" data-field="parent_id"<?php cmmngt_empty_field_marker( $parent_id, 'parent_id' ); ?>>
				<strong><?php _e( 'Case parent', $cmmngt->textdomain ); ?></strong>
				<input type="hidden" name="parent_id" value="<?php if ( $parent_id != 0 ) echo $parent_id; ?>" class="cmmngt-init-select2-ajax" data-placeholder="<?php _e( 'Parent case doesn\'t selected', $cmmngt->textdomain ); ?>" />
			</p>
		</div>
		<div id="cmmngt-field-invites">
			<span data-invite="function"><?php _e( 'Case function', $cmmngt->textdomain ); ?></span>
			<span data-invite="priority"><?php _e( 'Case priority', $cmmngt->textdomain ); ?></span>
			<span data-invite="state"><?php _e( 'Case state', $cmmngt->textdomain ); ?></span>
			<span data-invite="result"><?php _e( 'Case result', $cmmngt->textdomain ); ?></span>
			<span data-invite="date_deadline"><?php _e( 'Case date', $cmmngt->textdomain ); ?></span>
			<span data-invite="date_register"><?php _e( 'Case date register', $cmmngt->textdomain ); ?></span>
			<span data-invite="date_start"><?php _e( 'Case date start', $cmmngt->textdomain ); ?></span>
			<span data-invite="date_end"><?php _e( 'Case date end', $cmmngt->textdomain ); ?></span>
			<span data-invite="parent_id"><?php _e( 'Case parent', $cmmngt->textdomain ); ?></span>
		</div>
		<input type="hidden" name="cmmngt_nonce" value="<?php echo wp_create_nonce( cmmngt_get_nonce() ); ?>" />
	</div>

	<div class="cmmngt-container">
		<div class="btn-toolbar">
			<?php cmmngt_print_buttons( $buttons ); ?>
			<span class="cmmngt-loading">&nbsp;</span>
			<?php cmmngt_print_buttons_after(); ?>
			<div id="cmmngt-delegate-select-box" class="well"><strong><?php _e( 'Select person that will be set as responsible', $cmmngt->textdomain ); ?></strong>:<br /><span class="cmmngt-loading-persons"></span></div>
		</div>
	</div>
	<div class="cmmngt-hidden">
		<?php echo $hidden; ?>
	</div>
</div>