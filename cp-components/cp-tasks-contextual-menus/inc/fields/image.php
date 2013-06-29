<?php $triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="gndev-plugin-triggable hide-if-js"' : ''; ?>
<tr<?php echo $triggable; ?>>
	<th scope="row"><label for="gndev-plugin-field-<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label></th>
	<td>
		<div style="width:300px">
			<input type="text" value="<?php echo $settings[$option['id']]; ?>" name="<?php echo $option['id']; ?>" id="gndev-plugin-field-<?php echo $option['id']; ?>" class="regular-text" style="width:230px" />
			<a href="#" rel="<?php echo $option['id']; ?>" class="button alignright gndev-plugin-upload-button hide-if-no-js" style="width:60px;text-align:center;overflow:hidden;padding-left:0;padding-right:0"><?php _e( 'Upload', $this->textdomain ); ?></a>
		</div>
		<span class="description"><?php echo $option['desc']; ?></span>
	</td>
</tr>