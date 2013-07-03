(function($){

	$('select#cp_case_category_select').live('change', function(){
		// vars
		var values = [];
		
		$(this).find(':selected').each(function(){
			values.push( $(this).val() );
		});

		acf.screen.post_category = values;
		acf.screen.taxonomy = values;

		$(document).trigger('acf/update_field_groups');
		
	});
	
})(jQuery);