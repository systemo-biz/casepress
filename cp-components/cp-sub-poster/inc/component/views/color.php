<?php $triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="cp-component-triggable hide-if-js"' : ''; ?>
<tr<?php echo $triggable; ?>>
	<th scope="row"><label for="cp-component-field-<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label></th>
	<td>
		<div class="cp-component-color-picker">
			<input type="text" value="<?php echo $settings[$option['id']]; ?>" name="<?php echo $option['id']; ?>" id="cp-component-field-<?php echo $option['id']; ?>" class="regular-text cp-component-color-picker-value cp-component-prevent-clickout" style="width:100px" />
			<span class="cp-component-color-picker-preview cp-component-clickout"></span>
		</div>
		<span class="description"><?php echo $option['desc']; ?></span>
	</td>
</tr>