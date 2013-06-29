<?php $triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="gndev-plugin-triggable hide-if-js"' : ''; ?>
<tr<?php echo $triggable; ?>>
	<th scope="row"><label for="gndev-plugin-field-<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label></th>
	<td>
		<div class="gndev-plugin-icon-picker">
			<input type="text" value="<?php echo $settings[$option['id']][0]; ?>" name="<?php echo $option['id']; ?>[]" id="gndev-plugin-field-<?php echo $option['id']; ?>" class="regular-text gndev-plugin-icon-picker-value gndev-plugin-prevent-clickout" style="width:270px" />
			<img src="<?php echo $settings[$option['id']][0]; ?>" alt="<?php _e( 'Preview', $this->textdomain ); ?>" class="gndev-plugin-icon-picker-preview" data-id="<?php echo $option['id']; ?>" /><br/>
			<input type="text" value="<?php echo $settings[$option['id']][1]; ?>" name="<?php echo $option['id']; ?>[]" id="gndev-plugin-field-2-<?php echo $option['id']; ?>" class="regular-text" style="margin-top:5px" placeholder="<?php _e( 'Enter the url', $this->textdomain ); ?>" />
			<div class="gndev-plugin-icon-picker-dropdown gndev-plugin-clickout">
				<?php
					foreach ( $option['icons'] as $icon_id => $icon )
						echo '<img src="' . $icon . '" alt="' . $icon_id . '" title="' . $icon_id . '" />';
				?>
				<a href="#" rel="<?php echo $option['id']; ?>" class="gndev-plugin-icon-picker-upload"><strong><?php _e( 'Upload custom icon', $this->textdomain ); ?></strong></a>
			</div>
		</div>
		<span class="description"><?php echo $option['desc']; ?></span>
	</td>
</tr>