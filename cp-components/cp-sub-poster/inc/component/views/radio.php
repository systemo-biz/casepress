<?php
	$trigger = ( $option['trigger'] ) ? ' data-trigger="true" data-trigger-type="radio"' : '';
	$triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="cp-component-triggable hide-if-js"' : '';
?>
<tr<?php echo $trigger, $triggable; ?>>
	<th scope="row"><label for="cp-component-field-<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label></th>
	<td class="cp-component-radio-group">
		<?php
			foreach ( $option['options'] as $value => $label ) {
				$checked = ( $settings[$option['id']] == $value ) ? ' checked="checked"' : '';
				?>
				<div>
					<label><input type="radio" name="<?php echo $option['id']; ?>" value="<?php echo $value; ?>"<?php echo $checked; ?> /> <?php echo $label; ?></label>
				</div>
				<?php
			}
		?>
		<p class="description"><?php echo $option['desc']; ?></p>
	</td>
</tr>