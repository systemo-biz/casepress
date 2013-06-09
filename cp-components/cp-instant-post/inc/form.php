<?php

	/**
	 * Quick message form
	 */
	function cip_message_form() {
		global $cip_plugin;
		?>
		<div id="cip-dialog" data-title="<?php _e( 'Add new message', $cip_plugin->textdomain ); ?>">
			<form action="" id="cip-dialog-form">
				<h4><label for="cip-message-responsible"><?php _e( 'Recipient', $cip_plugin->textdomain ); ?> <span class="cip-req">*</span></label></h4>
				<p>
					<?php
					cip_dropdown_persons( array(
						'selected' => '',
						'name' => 'responsible',
						'id' => 'cip-message-responsible',
						'placeholder' => __( 'Select recipient', $cip_plugin->textdomain ) . '&hellip;',
						'post_type' => 'persons'
					) );
					?>
				</p>
				<h4><label for="cip-message-participants"><?php _e( 'Participants', $cip_plugin->textdomain ); ?></label></h4>
				<p>
					<?php
					cip_dropdown_persons( array(
						'selected' => '',
						'name' => 'participant[]',
						'id' => 'cip-message-participants',
						'placeholder' => __( 'Choose participants', $cip_plugin->textdomain ) . '&hellip;',
						'post_type' => 'persons',
						'multiple' => true
					) );
					?>
				</p>
				<h4><label for="cip-message-title"><?php _e( 'Subject', $cip_plugin->textdomain ); ?> <span class="cip-req">*</span></label></h4>
				<p><input type="text" name="title" value="" id="cip-message-title" /></p>
				<h4><label for="cip-message-content"><?php _e( 'Message', $cip_plugin->textdomain ); ?></label></h4>
				<p><textarea name="content" id="cip-message-content" rows="6"></textarea></p>
				<p>
					<a href="<?php echo admin_url( '/post-new.php?post_type=cases' ) ?>" id="cip-full-message"><?php _e( 'Go to full edit mode', $cip_plugin->textdomain ); ?></a>
				</p>
				<input type="hidden" name="initiator" value="<?php echo cip_get_current_person(); ?>" id="cip-message-initiator" />
				<input type="hidden" name="category" value="5" id="cip-message-category" />
				<input type="hidden" name="prioritet" value="3" id="cip-message-prioritet" />
				<input type="hidden" name="action" value="message" />
			</form>
		</div>
		<?php
	}

	// Incoming case form
	function cip_incoming_form() {
		global $cip_plugin;
		?>
		<div id="cip-dialog" data-title="<?php _e( 'Add incoming case', $cip_plugin->textdomain ); ?>">
			<form action="" id="cip-dialog-form">
				<h4><label for="cip-incoming-category"><?php _e( 'Case category', $cip_plugin->textdomain ); ?> <span class="cip-req">*</span></label></h4>
				<p>
					<?php
					echo str_replace( '&nbsp;', '', wp_dropdown_categories( array(
							'show_option_none' => __( 'Not selected', $cip_plugin->textdomain ),
							'hide_empty' => 0,
							'name' => 'category',
							'id' => 'cip-incoming-category',
							'class' => 'cip-init-chosen',
							'selected' => 80,
							'hierarchical' => 1,
							'taxonomy' => 'functions',
							'echo' => 0
						) ) );
					?>
				</p>
				<h4><label for="cip-incoming-initiator"><?php _e( 'Initiator', $cip_plugin->textdomain ); ?> <span class="cip-req">*</span></label></h4>
				<p>
					<?php
					cip_dropdown_persons( array(
						'selected' => '',
						'name' => 'initiator',
						'id' => 'cip-incoming-initiator',
						'placeholder' => __( 'Select initiator', $cip_plugin->textdomain ) . '&hellip;',
						'post_type' => 'persons'
					) );
					?>
				</p>
				<h4><label for="cip-incoming-participants"><?php _e( 'Participants', $cip_plugin->textdomain ); ?></label></h4>
				<p>
					<?php
					cip_dropdown_persons( array(
						'selected' => '',
						'name' => 'participant[]',
						'id' => 'cip-incoming-participants',
						'placeholder' => __( 'Choose participants', $cip_plugin->textdomain ) . '&hellip;',
						'post_type' => 'persons',
						'multiple' => true
					) );
					?>
				</p>
				<h4><label for="cip-incoming-title"><?php _e( 'Title', $cip_plugin->textdomain ); ?> <span class="cip-req">*</span></label></h4>
				<p><input type="text" name="title" value="" id="cip-incoming-title" /></p>
				<h4><label for="cip-incoming-content"><?php _e( 'Description', $cip_plugin->textdomain ); ?></label></h4>
				<p><textarea name="content" id="cip-incoming-content" rows="6"></textarea></p>
				<p>
					<a href="<?php echo admin_url( '/post-new.php?post_type=cases' ) ?>" id="cip-full-incoming"><?php _e( 'Go to full edit mode', $cip_plugin->textdomain ); ?></a>
				</p>
				<input type="hidden" name="responsible" value="<?php echo cip_get_current_person(); ?>" id="cip-incoming-responsible" />
				<input type="hidden" name="prioritet" value="3" id="cip-incoming-prioritet" />
				<input type="hidden" name="action" value="incoming" />
			</form>
		</div>
		<?php
	}
?>