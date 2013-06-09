	jQuery(document).ready(function(){

		jQuery('#chart_report_debug_chekbox').live('click',function(){
			if (this.checked)
				jQuery('#chart_report_debug').val('1');
			else
				jQuery('#chart_report_debug').val('');
		});
	
	
		jQuery('#chart_report_source').live('change',function(){
			if ( jQuery("#chart_report_source :selected").val() == 'other' )
				jQuery('#other_report_source').css('display', '');
			else
				jQuery('#other_report_source').css('display', 'none');
		});
		
		
		jQuery('#chart_report_reverse_chekbox').live('click',function(){
			if (this.checked)
				jQuery('#chart_report_reverse').val('1');
			else
				jQuery('#chart_report_reverse').val('');
		});
		
		
		jQuery('#chart_report_select_filter_chekbox').live('click',function(){
			if (this.checked)
				jQuery('#chart_report_select_filter').val('1');
			else
				jQuery('#chart_report_select_filter').val('');
		});
		
		
		jQuery('#chart_report_pivot_chekbox').live('click',function(){
			if (this.checked)
			{   jQuery('#pivot_div').css('display', 'initial'); 
				jQuery('#chart_report_pivot').val('1');
			}
			else
			{
				jQuery('#chart_report_pivot').val('');
				jQuery('#pivot_div').css('display', 'none'); 
			}
		});
		
		
		jQuery('#chart_report_convert_month_chekbox').live('click',function(){
			if (this.checked)
				jQuery('#chart_report_convert_month').val('1');
			else
				jQuery('#chart_report_convert_month').val('');
		});
		
		
		jQuery('#chart_report_dashboard_on_chekbox').live('click',function(){
			if (this.checked)
			{   jQuery('#dashboard_settings_div').css('display', 'initial'); 
				jQuery('#chart_report_dashboard_on').val('1');
			}
			else
			{
				jQuery('#chart_report_dashboard_on').val('');
				jQuery('#dashboard_settings_div').css('display', 'none'); 
			}
		});
		
		
		
		jQuery('#chart_report_preview').live('click',function(){
			if (this.checked)
			{   jQuery('#chart_report_preview_div').css('display', 'block'); 
				
			}
			else
			{
				jQuery('#chart_report_preview_div').css('display', 'none'); 
			}
		});
		
		
		jQuery('#chart_report_series_last_line_chekbox').live('click',function(){
			if (this.checked)
				jQuery('#chart_report_series_last_line').val('1');
			else
				jQuery('#chart_report_series_last_line').val('');
		});
		
		
		
		
		jQuery('#get_preview').live('click',function(){
		
			jQuery.ajax({
			type: 'POST',
			url: mt_ajax.ajaxurl,
			data: {
				action: 'chart_report_get_preview'//,
				//test:test
			},
			success: function(data) {
				alert('some done');
				alert(data);
				document.getElementById('chart_report_preview_div_content').innerHTML=data;
				//jQuery('#chart_report_preview_div_content').html(data);
			},
			dataType: 'html'
			}); 
		});
		
		
		
	});
	

	