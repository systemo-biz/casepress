<?php $triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="cp-component-triggable hide-if-js"' : ''; ?>
<tr<?php echo $triggable; ?>>
	<th scope="row" colspan="2"><h3 class="cp-component-title-box" style="margin:0"><?php echo $option['name']; ?></h3></th>
</tr>