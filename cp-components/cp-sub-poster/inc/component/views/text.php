<?php $triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="cp-component-triggable hide-if-js"' : ''; ?>
<tr<?php echo $triggable; ?>>
	<th scope="row"><label for="cp-component-field-<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label></th>
	<td>
		<input type="text" value="<?php echo $settings[$option['id']]; ?>" name="<?php echo $option['id']; ?>" id="cp-component-field-<?php echo $option['id']; ?>" class="regular-text" />
		<p class="description"><?php echo $option['desc']; ?></p>
	</td>
</tr>