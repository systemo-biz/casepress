<?php
	$triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="cp-component-triggable hide-if-js"' : '';
	$checked = ( $settings[$option['id']] == 'on' ) ? ' checked="checked"' : '';
?>
<tr<?php echo $triggable; ?>>
	<th scope="row"><label for="cp-component-field-<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label></th>
	<td>
		<label><input type="checkbox" name="<?php echo $option['id']; ?>" id="cp-component-field-<?php echo $option['id']; ?>"<?php echo $checked; ?> /> <?php echo $option['label']; ?></label>
		<span class="description"><?php echo $option['desc']; ?></span>
	</td>
</tr>