// Wait DOM
jQuery(document).ready(function($) {

	// show metaboxes for this post
	cacffs_custom_data = {
		action 			:	'get_input_metabox_ids',
		post_id			:	acf.post_id,
		page_template	:	false,
		page_parent		:	false,
		page_type		:	false,
		page			:	acf.post_id,
		post			:	acf.post_id,
		post_category	:	false,
		post_format		:	false,
		taxonomy		:	false
	};

	/*
	*  update_fields
	*
	*  @description: finds the new id's for metaboxes and show's hides metaboxes
	*  @created: 1/03/2011
	*/

	function cacffs_custom_update_fields() {

		//console.log('update_fields');
		$.ajax({
			url: ajaxurl,
			data: cacffs_custom_data,
			type: 'post',
			dataType: 'json',
			success: function(result){
				
				// hide all metaboxes
				$('#poststuff .acf_postbox').addClass('acf-hidden');
				$('#adv-settings .acf_hide_label').hide();
				
				
				// dont bother loading style or html for inputs
				if(result.length == 0)
				{
					return false;
				}
				
				
				// show the new postboxes
				$.each(result, function(k, v) {
					
					
					var postbox = $('#poststuff #acf_' + v);
					
					postbox.removeClass('acf-hidden');
					$('#adv-settings .acf_hide_label[for="acf_' + v + '-hide"]').show();
					
					// load fields if needed
					postbox.find('.acf-replace-with-fields').each(function(){
						
						var div = $(this);
						
						$.ajax({
							url: ajaxurl,
							data: {
								action : 'acf_input',
								acf_id : v,
								post_id : acf.post_id
							},
							type: 'post',
							dataType: 'html',
							success: function(html){
							
								div.replaceWith(html);
								
								$(document).trigger('acf/setup_fields', postbox);
								
							}
						});
						
					});
				});
				
				// load style
				$.ajax({
					url: ajaxurl,
					data: {
						action : 'get_input_style',
						acf_id : result[0]
					},
					type: 'post',
					dataType: 'html',
					success: function(result){
					
						$('#acf_style').html(result);
						
					}
				});
				
			}
		});
	}

	// taxonomy (select box)
	$('select#cmmngt-functions').live('change', function(){

		cacffs_custom_data.taxonomy = ['0'];

		$(this).find(':selected').each(function(){
			cacffs_custom_data.taxonomy.push($(this).val())
		});

		cacffs_custom_update_fields();

	});

});