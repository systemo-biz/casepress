	jQuery(document).ready(function(){
		
		jQuery('.cp_common_options_settings').live('click',function(){
		
			var post_type = jQuery(this).attr('id');
			jQuery.ajax({
			type: 'POST',
			url: member_ajax.ajaxurl,
			data: {
				action: 'cp_members_load_options',
				post_type:post_type
			},
			success: function(data) {
				//alert(data);
				jQuery('#options_content').html(data);
				},
			dataType: 'html'
			}); 
		});
		
		
		jQuery('#role_add').live('click',function(){
		
			var post_type = jQuery(this).attr('alt');
			var role_name = jQuery('#role_name').attr('value');
			var role_desc = jQuery('#role_desc').val();
		/*	alert(post_type);
			alert(role_name);
			alert(role_desc);*/
			jQuery.ajax({
			type: 'POST',
			url: member_ajax.ajaxurl,
			data: {
				action: 'cp_members_add_option',
				post_type:post_type,
				role_name:role_name,
				role_desc:role_desc
			},
			success: function(data) {
				//alert(data);
				jQuery('#options_content').html(data);
				},
			dataType: 'html'
			}); 
		});
		
		
	}); //jquery ready
	

	function print_r(arr, level) {
    var print_red_text = "";
    if(!level) level = 0;
    var level_padding = "";
    for(var j=0; j<level+1; j++) level_padding += "    ";
    if(typeof(arr) == 'object') {
        for(var item in arr) {
            var value = arr[item];
            if(typeof(value) == 'object') {
                print_red_text += level_padding + "'" + item + "' :\n";
                print_red_text += print_r(value,level+1);
		} 
            else 
                print_red_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
        }
    } 

    else  print_red_text = "===>"+arr+"<===("+typeof(arr)+")";
    return print_red_text;
}

