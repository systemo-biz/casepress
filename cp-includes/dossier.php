<?php

add_action( 'cp_entry_content_after', 'cases_display_childs', 60 );

function cases_display_childs() {

		if ( is_single() && get_post_type() == 'cases' ) {
			if ( function_exists( 'datatable_generator' ) ) {
			
				//$cp_components_url = plugin_dir_url(__FILE__);
				wp_enqueue_script('datatable', plugin_dir_url(__FILE__).'../cp-components/cp-datatable/assets/dt.js', array('jquery'));
				wp_enqueue_script('datatable.tt', plugin_dir_url(__FILE__).'../cp-components/cp-datatable/assets/dt.tableTools.js', array('datatable'));
				wp_enqueue_script('datatable.rg', plugin_dir_url(__FILE__).'../cp-components/cp-datatable/assets/dt.rowGrouping.js', array('datatable'));
				wp_enqueue_script('datatable.tg', plugin_dir_url(__FILE__).'../cp-components/cp-datatable/assets/dt.treeGrid.js', array('datatable'));
				wp_enqueue_script('datatable.init', plugin_dir_url(__FILE__).'../cp-components/cp-datatable/assets/init.js', array('datatable'));
				wp_enqueue_style('datatable', plugin_dir_url(__FILE__).'../cp-components/cp-datatable/assets/theme.css');
		
				global $post;

				?>
				<div class="cases-box-dossier">
					<div class="cases-box-header-dossier">
						<h1>Досье</h1>
						<hr />
					</div>
					<div class="cases-box-content" id="cases_dossie">
					</div>
					<script type='text/javascript'>						
						jQuery(document).ready(function(){							
							jQuery.ajax({
								type: 'POST',
								url: ajaxurl,
								data: {
									action: 'get_case_dossier_datatable',
									current_id: <?php echo $post->ID; ?>
								},
								success: function(data) {
									// Put new HTML into container
									jQuery('#cases_dossie').html(data);
								},
								dataType: 'html'
							});						
						});
					</script>
				</div>
				
				<!-- Action priority: 60 -->
				<?php
			}
			//get_template_part( 'template', 'acf-form' );
		}
	}

    
	function get_case_dossier_datatable(){
	
	
		if (isset($_POST['current_id'])) $postid = $_POST['current_id'];
		if ( function_exists( 'datatable_generator' ) )
		datatable_generator( array('parent'=>$postid,'class'=>'tax-all','tax'=>''));
		//echo '<div style="width: 50px; height: 50px; color: red;">atata</div>';
		die();
	}
	add_action('wp_ajax_get_case_dossier_datatable','get_case_dossier_datatable');
	
?>