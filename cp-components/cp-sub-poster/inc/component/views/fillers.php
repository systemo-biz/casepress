<tr>
	<td colspan="2">
		<?php
			// Get fillers
			$fillers = ( count( $settings[$option['id']] ) > 0 ) ? $settings[$option['id']] : false;
		?>
		<div id="casepress-component-fillers">
			<div class="casepress-component-fillers-actions">
				<a href="#" id="casepress-component-add-filler" class="button button-primary hide-if-no-js">+ <?php _e( 'Add filler', $this->textdomain ); ?></a>
				<a href="#" id="casepress-component-expand-fillers" class="button hide-if-no-js"><?php _e( 'Expand all', $this->textdomain ); ?></a>
				<a href="#" id="casepress-component-collapse-fillers" class="button hide-if-js hide-if-no-js"><?php _e( 'Collapse all', $this->textdomain ); ?></a>
			</div>
			<div id="casepress-component-filler-template">
				<div class="casepress-component-filler casepress-component-filler-editing">
					<div class="casepress-component-filler-menu">
						<a href="#" class="casepress-component-edit-filler"><span class="edit"><?php _e( 'Edit code', $this->textdomain ); ?></span><span class="close"><?php _e( 'Close', $this->textdomain ); ?></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="#" class="casepress-component-generate-filler-link" title="<?php _e( 'Generate link', $this->textdomain ); ?>" data-required-message="<?php _e( 'You must enter field name to continue!', $this->textdomain ); ?>"><?php _e( 'Generate link', $this->textdomain ); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="#" class="casepress-component-remove-filler" title="<?php _e( 'Are you sure? You can not to restore this filler!', $this->textdomain ); ?>"><?php _e( 'Remove filler', $this->textdomain ); ?></a>
					</div>
					<input type="text" name="<?php echo $option['id']; ?>[__INDEX__][0]" value="" class="regular-text" placeholder="<?php _e( 'Field name', $this->textdomain ); ?>" /><br/>
					<textarea id="<?php echo $option['id']; ?>__INDEX__" name="<?php echo $option['id']; ?>[__INDEX__][1]" rows="10"></textarea>
				</div>
			</div>
			<div id="casepress-component-active-fillers">
				<?php
					// Fillers exists
					if ( $fillers ) {
						// Fillers loop
						for ( $i = 0; $i < count( $fillers ); $i++ ) {
							?>
							<div class="casepress-component-filler">
								<div class="casepress-component-filler-menu">
									<a href="#" class="casepress-component-edit-filler"><span class="edit"><?php _e( 'Edit code', $this->textdomain ); ?></span><span class="close"><?php _e( 'Close', $this->textdomain ); ?></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;
									<a href="#" class="casepress-component-generate-filler-link" title="<?php _e( 'Generate link', $this->textdomain ); ?>" data-required-message="<?php _e( 'You must enter field name to continue!', $this->textdomain ); ?>"><?php _e( 'Generate link', $this->textdomain ); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
									<a href="#" class="casepress-component-remove-filler" title="<?php _e( 'Are you sure? You can not to restore this filler!', $this->textdomain ); ?>"><?php _e( 'Remove filler', $this->textdomain ); ?></a>
								</div>
								<input type="text" name="<?php echo $option['id'] . '[' . $i . ']'; ?>[0]" value="<?php echo $fillers[$i][0]; ?>" class="regular-text" placeholder="<?php _e( 'Field name', $this->textdomain ); ?>" /><br/>
								<textarea id="<?php echo $option['id'] . $i; ?>" name="<?php echo $option['id'] . '[' . $i . ']'; ?>[1]" rows="10"><?php echo stripslashes( $fillers[$i][1] ); ?></textarea>
							</div>
							<?php
						}
					}
				?>
			</div>
			<div id="casepress-component-link-template" style="display:none">
				<div class="wrap form-table">
					<h4><?php _e( 'Plain link', $this->textdomain ); ?></h4>
					<textarea class="regular-text widefat casepress-component-generated-link" rows="5"><a href="<?php echo admin_url( '/post-new.php?csposter&amp;csposter_%FIELD%=%value%' ); ?>"><?php _e( 'Open link', $this->textdomain ); ?></a></textarea>
					<div class="description"><?php _e( 'Replace %value% with your value', $this->textdomain ); ?></div>
					<h4><?php _e( 'Lightbox link', $this->textdomain ); ?></h4>
					<textarea class="regular-text widefat casepress-component-generated-link" rows="5"><a href="<?php echo admin_url( '/post-new.php?csposter&amp;csposter_%FIELD%=%value%&amp;TB_iframe=true&amp;width=900&amp;height=500' ); ?>" class="thickbox" title="<?php _e( 'Lightbox name', $this->textdomain ); ?>"><?php _e( 'Open lightbox', $this->textdomain ); ?></a></textarea>
					<div class="description"><?php _e( 'Replace %value% with your value', $this->textdomain ); ?></div>
				</div>
			</div>
			<div id="casepress-component-show-link" style="display:none"></div>
		</div>
	</td>
</tr>