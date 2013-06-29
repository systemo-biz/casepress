<?php $triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="gndev-plugin-triggable hide-if-js"' : ''; ?>
<tr<?php echo $triggable; ?>>
	<th scope="row"><label for="gndev-plugin-field-<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label></th>
	<td>
		<input type="text" value="<?php echo $settings[$option['id']]; ?>" name="<?php echo $option['id']; ?>" id="gndev-plugin-field-<?php echo $option['id']; ?>" class="regular-text" />
		<span class="description"><?php echo $option['desc']; ?></span>
	</td>
</tr>