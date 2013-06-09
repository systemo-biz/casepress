	jQuery(document).ready(function(){
		jQuery('.update_chart_report_values').live('click',function(){
			var post_id=$(this).attr('id');
			div = $('div#charts');
			$.ajax({
			type: 'POST',
			url: chart_ajax.ajaxurl,
			data: {
				action: 'update_report_values',
				post_id:post_id
			},
			success: function(data) {
				//alert('success');
			//	div.html(data);
				location.reload();
				},
			dataType: 'html'
			}); 
		});	
		
	/*	jQuery('#chart_report_persons').live('keydown', function(e) {			
			if (e.which == 13  && !e.shiftKey) 
			{
				alert('13');
			}
		});
	*/	
		$("#chart_report_persons").on("change", function(e) { 
			var redirect_url= $(this).attr('cur_url');
			redirect_url=redirect_url+'&filter_id='+e.val;
			window.location.href=redirect_url;
			//alert(e.val); 
		
		});
		$("#chart_report_units").on("change", function(e) { 
			var redirect_url= $(this).attr('cur_url');
			redirect_url=redirect_url+'&filter_id='+e.val;
			window.location.href=redirect_url;
			//alert(e.val); 
		
		});
			
			
		jQuery('#chart_report_col_filter_btn').live('click',function(){
			var filter_vals = $('#chart_report_col_filter').val();
			var redirect_url = $(this).attr('cur_url');
			redirect_url=redirect_url+'&cols='+filter_vals;
			window.location.href=redirect_url;
			//alert(filter_vals);
			//alert(redirect_url);

		});
			
	});
	

	