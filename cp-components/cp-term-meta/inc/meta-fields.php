<?php

	function ctmeta_add_field_datatable_params() {

		// Get pugin object
		global $ctmeta_plugin;

		// Edit term
		if ( $_GET['action'] == 'edit' ) {
			?>
			<table class="form-table">
				<tr class="form-field">
					<th scope="row" valign="top">
						<label for="ctmeta-datatable-params"><?php _e( 'DataTable params', $ctmeta_plugin->textdomain ); ?></label>
					</th>
					<td>
						<textarea name="ctmeta_datatable_params" id="ctmeta-datatable-params" rows="5" cols="50" class="large-text"><?php echo ctmeta_get_meta( 'ctmeta_datatable_params', $_GET['taxonomy'], $_GET['tag_ID'] ); ?></textarea>
						<br/>
						<span class="description"><?php _e( 'Enter custom DataTable params. These params will override defaults for these term.', $ctmeta_plugin->textdomain ); ?><br/><?php _e( 'Ex.', $ctmeta_plugin->textdomain ); ?>: <code>fields="ID:int, post_title:link, initiator:post"</code></span>
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row" valign="top">
						<label for="ctmeta-title-template"><?php _e( 'Title template', $ctmeta_plugin->textdomain ); ?></label>
					</th>
					<td>
						<input type="text" name="ctmeta_title_template" value="<?php echo htmlspecialchars( ctmeta_get_meta( 'ctmeta_title_template', $_GET['taxonomy'], $_GET['tag_ID'] ) ); ?>" id="ctmeta-title-template" class="large-text" />
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row" valign="top">
						<label for="ctmeta-content-template">Шаблон заголовка</label>
					</th>
					<td>
						<input type="text" name="ctmeta_content_template" value="<?php echo htmlspecialchars( ctmeta_get_meta( 'ctmeta_content_template', $_GET['taxonomy'], $_GET['tag_ID'] ) ); ?>" id="ctmeta-content-template" class="large-text" />
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row" valign="top">
						<label for="ctmeta-default-responsible"><?php _e( 'Default responsible', $ctmeta_plugin->textdomain ); ?></label>
					</th>
					<td>
						<?php
						ctmeta_dropdown_persons( array(
							'selected' => ctmeta_get_meta( 'ctmeta_default_responsible', $_GET['taxonomy'], $_GET['tag_ID'] ),
							'name' => 'ctmeta_default_responsible',
							'id' => 'ctmeta-default-responsible',
							'post_type' => 'persons',
							'placeholder' => __( 'Select person...', $ctmeta_plugin->textdomain )
						) );
						?>
						<br/>
						<span class="description"><?php _e( 'Select default responsible for this term.', $ctmeta_plugin->textdomain ); ?></span>
					</td>
				</tr>
				<tr id="ctmeta-deadline" class="form-field">
					<th scope="row" valign="top">
						<label for="ctmeta-default-deadline"><?php _e( 'Default deadline', $ctmeta_plugin->textdomain ); ?></label>
					</th>
					<td>
						<input type="number" name="ctmeta_default_deadline" value="<?php echo ctmeta_get_meta( 'ctmeta_default_deadline', $_GET['taxonomy'], $_GET['tag_ID'] ); ?>" id="ctmeta-default-deadline" /> <small><?php _e( 'hours', $ctmeta_plugin->textdomain ); ?></small><br />
						<label class="ctmeta-checkbox"><input type="checkbox" name="ctmeta_deadline_priority_toggle" id="ctmeta-deadline-priority-toggle" <?php echo ( ctmeta_get_meta( 'ctmeta_deadline_priority_toggle', $_GET['taxonomy'], $_GET['tag_ID'] ) == 'on' ) ? ' checked="checked"' : ''; ?> class="checkbox" /> <?php _e( 'Deadline depends on the priority', $ctmeta_plugin->textdomain ); ?></label>
						<div id="ctmeta-deadline-priorities" class="<?php echo ( ctmeta_get_meta( 'ctmeta_deadline_priority_toggle', $_GET['taxonomy'], $_GET['tag_ID'] ) == 'on' ) ? '' : 'gndev-plugin-hidden'; ?>">
							<p class="description"><span class="ctmeta-warning"><?php _e( 'Warning', $ctmeta_plugin->textdomain ); ?>:</span> <?php _e( 'If you set deadlines for priorities, you still need to specify default deadline!', $ctmeta_plugin->textdomain ); ?></p>
							<p class="ctmeta-deadline-priority"><label><input type="number" name="ctmeta_deadline_priority[1]" value="<?php echo ctmeta_get_meta( 'ctmeta_deadline_priority_1', $_GET['taxonomy'], $_GET['tag_ID'] ); ?>" id="ctmeta-deadline-priority-1" /> <?php _e( 'Critical', $ctmeta_plugin->textdomain ); ?></label></p>
							<p class="ctmeta-deadline-priority"><label><input type="number" name="ctmeta_deadline_priority[2]" value="<?php echo ctmeta_get_meta( 'ctmeta_deadline_priority_2', $_GET['taxonomy'], $_GET['tag_ID'] ); ?>" id="ctmeta-deadline-priority-2" /> <?php _e( 'High', $ctmeta_plugin->textdomain ); ?></label></p>
							<p class="ctmeta-deadline-priority"><label><input type="number" name="ctmeta_deadline_priority[3]" value="<?php echo ctmeta_get_meta( 'ctmeta_deadline_priority_3', $_GET['taxonomy'], $_GET['tag_ID'] ); ?>" id="ctmeta-deadline-priority-3" /> <?php _e( 'Normal', $ctmeta_plugin->textdomain ); ?></label></p>
							<p class="ctmeta-deadline-priority"><label><input type="number" name="ctmeta_deadline_priority[4]" value="<?php echo ctmeta_get_meta( 'ctmeta_deadline_priority_4', $_GET['taxonomy'], $_GET['tag_ID'] ); ?>" id="ctmeta-deadline-priority-4" /> <?php _e( 'Low', $ctmeta_plugin->textdomain ); ?></label></p>
							<p class="ctmeta-deadline-priority"><label><input type="number" name="ctmeta_deadline_priority[5]" value="<?php echo ctmeta_get_meta( 'ctmeta_deadline_priority_5', $_GET['taxonomy'], $_GET['tag_ID'] ); ?>" id="ctmeta-deadline-priority-5" /> <?php _e( 'Planning', $ctmeta_plugin->textdomain ); ?></label></p>
						</div>
						<br/>
						<span class="description"><?php _e( 'Select default deadline for this term. The value must be strongly in hours from current moment.', $ctmeta_plugin->textdomain ); ?><br/><code><?php _e( 'Example', $ctmeta_plugin->textdomain ); ?>: 24</code></span>
					</td>
				</tr>
			</table>
			<!-- Action priority: 10 -->
			<?php
		}

		// Add term
		else {
			?>
			<div class="form-field">
				<label for="ctmeta-datatable-params"><?php _e( 'DataTable params', $ctmeta_plugin->textdomain ); ?></label>
				<textarea name="ctmeta_datatable_params" id="ctmeta-datatable-params" rows="5" cols="40"><?php echo stripslashes( ctmeta_get_meta( 'ctmeta_datatable_params', $_GET['taxonomy'], $_GET['tag_ID'] ) ); ?></textarea>
				<p><?php _e( 'Enter custom DataTable params. These params will override defaults for these term.', $ctmeta_plugin->textdomain ); ?><br/><?php _e( 'Ex.', $ctmeta_plugin->textdomain ); ?>: <code>fields="ID:int, post_title:link, initiator:post"</code></p>
			</div>
			<div class="form-field">
				<label for="ctmeta-title-template"><?php _e( 'Title template', $ctmeta_plugin->textdomain ); ?></label>
				<input type="text" name="ctmeta_title_template" value="<?php echo htmlspecialchars( ctmeta_get_meta( 'ctmeta_title_template', $_GET['taxonomy'], $_GET['tag_ID'] ) ); ?>" id="ctmeta-title-template" class="large-text" />
				<p>
					<?php _e( 'Enter title template for new posts.', $ctmeta_plugin->textdomain ); ?><br/><?php _e( 'You can use next variables', $ctmeta_plugin->textdomain ); ?>:<br/>
					<code>%post_id%</code> - <?php _e( 'the ID of case', $ctmeta_plugin->textdomain ); ?><br/>
					<code>%function%</code> - <?php _e( 'the function of case', $ctmeta_plugin->textdomain ); ?><br/>
				</p>
			</div>
			<div class="form-field">
				<label for="ctmeta-default-responsible"><?php _e( 'Default responsible', $ctmeta_plugin->textdomain ); ?></label>
				<?php
				ctmeta_dropdown_persons( array(
					'selected' => ctmeta_get_meta( 'ctmeta_default_responsible', $_GET['taxonomy'], $_GET['tag_ID'] ),
					'name' => 'ctmeta_default_responsible',
					'id' => 'ctmeta-default-responsible',
					'post_type' => 'persons',
					'placeholder' => __( 'Select person...', $ctmeta_plugin->textdomain )
				) );
				?>
				<p><?php _e( 'Select default responsible for this term.', $ctmeta_plugin->textdomain ); ?></p>
			</div>
			<div id="ctmeta-deadline" class="form-field">
				<label for="ctmeta-default-deadline"><?php _e( 'Default deadline', $ctmeta_plugin->textdomain ); ?></label>
				<input type="number" name="ctmeta_default_deadline" value="<?php echo ctmeta_get_meta( 'ctmeta_default_deadline', $_GET['taxonomy'], $_GET['tag_ID'] ); ?>" id="ctmeta-default-deadline" /> <small><?php _e( 'hours', $ctmeta_plugin->textdomain ); ?></small><br />
				<label class="ctmeta-checkbox"><input type="checkbox" name="ctmeta_deadline_priority_toggle" id="ctmeta-deadline-priority-toggle" <?php echo ( ctmeta_get_meta( 'ctmeta_deadline_priority_toggle', $_GET['taxonomy'], $_GET['tag_ID'] ) == 'on' ) ? ' checked="checked"' : ''; ?> class="checkbox" /> <?php _e( 'Deadline depends on the priority', $ctmeta_plugin->textdomain ); ?></label>
				<div id="ctmeta-deadline-priorities" class="<?php echo ( ctmeta_get_meta( 'ctmeta_deadline_priority_toggle', $_GET['taxonomy'], $_GET['tag_ID'] ) == 'on' ) ? '' : 'gndev-plugin-hidden'; ?>">
					<p class="description"><span class="ctmeta-warning"><?php _e( 'Warning', $ctmeta_plugin->textdomain ); ?>:</span> <?php _e( 'If you set deadlines for priorities, you still need to specify default deadline!', $ctmeta_plugin->textdomain ); ?></p>
					<p class="ctmeta-deadline-priority"><label><input type="number" name="ctmeta_deadline_priority[1]" value="<?php echo ctmeta_get_meta( 'ctmeta_deadline_priority_1', $_GET['taxonomy'], $_GET['tag_ID'] ); ?>" id="ctmeta-deadline-priority-1" /> <?php _e( 'Critical', $ctmeta_plugin->textdomain ); ?></label></p>
					<p class="ctmeta-deadline-priority"><label><input type="number" name="ctmeta_deadline_priority[2]" value="<?php echo ctmeta_get_meta( 'ctmeta_deadline_priority_2', $_GET['taxonomy'], $_GET['tag_ID'] ); ?>" id="ctmeta-deadline-priority-2" /> <?php _e( 'High', $ctmeta_plugin->textdomain ); ?></label></p>
					<p class="ctmeta-deadline-priority"><label><input type="number" name="ctmeta_deadline_priority[3]" value="<?php echo ctmeta_get_meta( 'ctmeta_deadline_priority_3', $_GET['taxonomy'], $_GET['tag_ID'] ); ?>" id="ctmeta-deadline-priority-3" /> <?php _e( 'Normal', $ctmeta_plugin->textdomain ); ?></label></p>
					<p class="ctmeta-deadline-priority"><label><input type="number" name="ctmeta_deadline_priority[4]" value="<?php echo ctmeta_get_meta( 'ctmeta_deadline_priority_4', $_GET['taxonomy'], $_GET['tag_ID'] ); ?>" id="ctmeta-deadline-priority-4" /> <?php _e( 'Low', $ctmeta_plugin->textdomain ); ?></label></p>
					<p class="ctmeta-deadline-priority"><label><input type="number" name="ctmeta_deadline_priority[5]" value="<?php echo ctmeta_get_meta( 'ctmeta_deadline_priority_5', $_GET['taxonomy'], $_GET['tag_ID'] ); ?>" id="ctmeta-deadline-priority-5" /> <?php _e( 'Planning', $ctmeta_plugin->textdomain ); ?></label></p>
				</div>
				<p><?php _e( 'Select default deadline for this term. The value must be strongly in hours from current moment.', $ctmeta_plugin->textdomain ); ?><br/><code><?php _e( 'Example', $ctmeta_plugin->textdomain ); ?>: 24</code></p>
			</div>
			<!-- Action priority: 10 -->
			<?php
		}
	}

	add_action( 'functions_edit_form', 'ctmeta_add_field_datatable_params', 10 );
	add_action( 'functions_add_form_fields', 'ctmeta_add_field_datatable_params', 10 );
?>