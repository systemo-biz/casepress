<?php
		$trigger = ( $option['trigger'] ) ? ' data-trigger="true" data-trigger-type="select"' : '';
		$triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="gndev-plugin-triggable hide-if-js"' : '';
?>
<tr<?php echo $trigger, $triggable; ?>>
	<th scope="row"><label for="gndev-plugin-field-<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label></th>
	<td>
		<select name="<?php echo $option['id']; ?>" id="gndev-plugin-field-<?php echo $option['id']; ?>" style="width:300px">
			<?php
				foreach ( $option['options'] as $value => $label ) {
					$selected = ( $settings[$option['id']] == $value ) ? ' selected="selected"' : '';
					?>
					<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $label; ?></option>
					<?php
				}
			?>
		</select>
		<span class="description"><?php echo $option['desc']; ?></span>
	</td>
</tr>