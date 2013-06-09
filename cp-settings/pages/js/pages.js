	jQuery(document).ready(function ($) {
		
		jQuery('#cp_save_page_settings').live('click',function(){

		//	alert(cp_pages_settings.ajaxurl);
			var cp_cases_page = $("#cp_cases_page option:selected").val();
			
			jQuery.ajax({
			type: "POST",
			cash: false,
			url: cp_pages_settings.ajaxurl,
			data: {
				action:'cp_settings_pages_save',
				cp_cases_page:cp_cases_page
			},
			success: function(data) {
				alert(data);
				window.location.reload();
			},
			dataType: "text"
			}); 
			
			
		});
		
		
		

		

	});