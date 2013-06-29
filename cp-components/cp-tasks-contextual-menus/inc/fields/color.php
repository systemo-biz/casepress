<?php $triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="gndev-plugin-triggable hide-if-js"' : ''; ?>
<tr<?php echo $triggable; ?>>
	<th scope="row"><label for="gndev-plugin-field-<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label></th>
	<td>
		<div class="gndev-plugin-color-picker">
			<input type="text" value="<?php echo $settings[$option['id']]; ?>" name="<?php echo $option['id']; ?>" id="gndev-plugin-field-<?php echo $option['id']; ?>" class="regular-text gndev-plugin-color-picker-value gndev-plugin-prevent-clickout" style="width:90px" />
			<span class="gndev-plugin-color-picker-preview gndev-plugin-clickout"></span>
		</div>
		<span class="description"><?php echo $option['desc']; ?></span>
	</td>
</tr>