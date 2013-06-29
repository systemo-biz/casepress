jQuery(document).ready(function() {
	 
    jQuery('a#have_acl_access').live('click', function(e) {
		//alert('1');
		var object_id = jQuery('#object_id').attr('value');
		var object_type = jQuery('#object_type').attr('value');
		var subject = jQuery('#subject').attr('value');
		var subject_type = jQuery('#subject_type').attr('value');
		var access_type = jQuery('#access_type').attr('value');

		$.ajax({
		type: 'POST',
		url: cases_acl.ajaxurl,
		data: {
			action: 'have_acl_access_ajax',
			object_id: object_id,
			object_type: object_type,
			subject: subject,
			subject_type: subject_type
		},
		success: function(data) {
            alert(data);	
		},
		dataType: 'html'
		});
    return false;
	 
    });
	
	
	
	jQuery('a#remove_acl_access').live('click', function(e) {
		//alert('1');
		var object_id = jQuery('#object_id').attr('value');
		var object_type = jQuery('#object_type').attr('value');
		var subject = jQuery('#subject').attr('value');
		var subject_type = jQuery('#subject_type').attr('value');
		var access_type = jQuery('#access_type').attr('value');

		$.ajax({
		type: 'POST',
		url: cases_acl.ajaxurl,
		data: {
			action: 'remove_acl_access_ajax',
			object_id: object_id,
			object_type: object_type,
			subject: subject,
			subject_type: subject_type
		},
		success: function(data) {
            alert(data);	
		},
		dataType: 'html'
		});
    return false;
	 
    });
	
	
	
	
	jQuery('a#get_acl_access_by_object').live('click', function(e) {
		//alert('1');
		var object_id = jQuery('#object_id').attr('value');
		var object_type = jQuery('#object_type').attr('value');
		var subject = jQuery('#subject').attr('value');
		var subject_type = jQuery('#subject_type').attr('value');
		var access_type = jQuery('#access_type').attr('value');

		$.ajax({
		type: 'POST',
		url: cases_acl.ajaxurl,
		data: {
			action: 'get_acl_access_by_object_ajax',
			object_id: object_id,
			object_type: object_type,
			subject: subject,
			subject_type: subject_type
		},
		success: function(data) {
            alert(data);	
		},
		dataType: 'html'
		});
    return false;
	 
    });
	
	jQuery('a#append_acl_access').live('click', function(e) {
		//alert('1');
		var object_id = jQuery('#object_id').attr('value');
		var object_type = jQuery('#object_type').attr('value');
		var subject = jQuery('#subject').attr('value');
		var subject_type = jQuery('#subject_type').attr('value');
		var access_type = jQuery('#access_type').attr('value');

		$.ajax({
		type: 'POST',
		url: cases_acl.ajaxurl,
		data: {
			action: 'append_acl_access_ajax',
			object_id: object_id,
			object_type: object_type,
			subject: subject,
			subject_type: subject_type,
			access_type:access_type
		},
		success: function(data) {
            alert(data);	
		},
		dataType: 'html'
		});
    return false;
	 
    });
	
	
	jQuery('a#have_subs_acl_acceess').live('click', function(e) {
		//alert('1');
		var object_id = jQuery('#object_id').attr('value');
		var object_type = jQuery('#object_type').attr('value');
		var subject = jQuery('#subject').attr('value');
		var subject_type = jQuery('#subject_type').attr('value');
		var access_type = jQuery('#access_type').attr('value');

		$.ajax({
		type: 'POST',
		url: cases_acl.ajaxurl,
		data: {
			action: 'have_subs_acl_acceess_ajax',
			object_id: object_id,
			object_type: object_type,
			subject: subject,
			subject_type: subject_type,
			access_type:access_type
		},
		success: function(data) {
            alert(data);	
		},
		dataType: 'html'
		});
    return false;
	 
    });
	
	
		jQuery('a#remove_acl_access_by_role').live('click', function(e) {
		//alert('1');
		var object_id = jQuery('#object_id').attr('value');
		var object_type = jQuery('#object_type').attr('value');
		var subject = jQuery('#subject').attr('value');
		var subject_type = jQuery('#subject_type').attr('value');
		var access_type = jQuery('#access_type').attr('value');

		$.ajax({
		type: 'POST',
		url: cases_acl.ajaxurl,
		data: {
			action: 'remove_acl_access_by_role_ajax',
			object_id: object_id,
			object_type: object_type,
			subject: subject,
			subject_type: subject_type,
			access_type:access_type
		},
		success: function(data) {
            alert(data);	
		},
		dataType: 'html'
		});
    return false;
	 
    });
	
	
	jQuery('a#convert_acl').live('click', function(e) {
		$.ajax({
		type: 'POST',
		url: cases_acl.ajaxurl,
		data: {
			action: 'convert_acl'
		},
		success: function(data) {
            jQuery('div#convert_acl').html(data);
		},
		dataType: 'html'
		});
    return false;
	 
    });
	
	
	
	
});