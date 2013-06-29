<?php $triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="cp-component-triggable hide-if-js"' : ''; ?>
<tr<?php echo $triggable; ?>>
	<td colspan="2"><?php echo $option['html']; ?></td>
</tr>