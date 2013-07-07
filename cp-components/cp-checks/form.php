<?php
	
?>		<div class="cases-box cases-box-open">
				<div class="cases-box-header">
					<h3>
						<a href="#" class="cases-box-toggle">Заметки</a>
						<a href="#checks" name="checks" class="cases-box-anchor">#</a>
						<img id="check_ajax_load" src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) . 'ajax-loading.gif' ?>" style="padding-left: 20px; display: none;">
					</h3>
					<div class="cases-box-actions">
						
					</div>
				</div>
				
				<div class="cases-box-content">
					<div id="check-box-content">
						<form method="POST" onsubmit="return false;" id="my_check_form" onKeyDown="if(event.keyCode==9)event.returnValue = false;"  style="padding: 15px;" name="<?php echo $post_id; ?>">
							<?php
							$mycount = get_args_check($post_id,0);								
							?>						
						</form>
					</div>
				</div>
		</div>			
<?php 
	echo '<div id="page_id_checks" style="visibility: hidden; hidden:true;">'; 
	echo $post_id;
	echo '</div>';
	echo '<div id="page_id_count" style="">'; 
	echo $check_current_number;
	echo '</div>';
	
?>