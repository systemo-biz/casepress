	jQuery(document).ready(function(){
	
		jQuery('a#get_common_roles').live('click', function(e) {

		var object_id = jQuery('#object_id').attr('value');
		var object_type = jQuery('#object_type').attr('value');
		var subject = jQuery('#subject').attr('value');
		var subject_type = jQuery('#subject_type').attr('value');
		var role = jQuery('#role').attr('value');
		var term_id = jQuery('#term_id').attr('value');
		
		$.ajax({
		type: 'POST',
		url: member_metabox_ajax.ajaxurl,
		data: {
			action: 'get_common_roles_ajax',
			object_id: object_id,
			object_type: object_type,
			subject: subject,
			subject_type: subject_type,
			role:role,
			term_id:term_id
		},
		success: function(data) {
            alert(data);	
		},
		dataType: 'html'
		});
    return false;
	 
    });
		
		jQuery('a#update_subject_to_object').live('click', function(e) {

		var object_id = jQuery('#object_id').attr('value');
		var object_type = jQuery('#object_type').attr('value');
		var subject = jQuery('#subject').attr('value');
		var subject_type = jQuery('#subject_type').attr('value');
		var role = jQuery('#role').attr('value');
		var term_id = jQuery('#term_id').attr('value');
		
		$.ajax({
		type: 'POST',
		url: member_metabox_ajax.ajaxurl,
		data: {
			action: 'update_subject_to_object_ajax',
			object_id: object_id,
			object_type: object_type,
			subject: subject,
			subject_type: subject_type,
			role:role,
			term_id:term_id
		},
		success: function(data) {
            alert(data);	
		},
		dataType: 'html'
		});
    return false;
	 
    });
	
	
			jQuery('a#update_tax_role').live('click', function(e) {

		var object_id = jQuery('#object_id').attr('value');
		var object_type = jQuery('#object_type').attr('value');
		var subject = jQuery('#subject').attr('value');
		var subject_type = jQuery('#subject_type').attr('value');
		var role = jQuery('#role').attr('value');
		var term_id = jQuery('#term_id').attr('value');
		var desc = jQuery('#desc').attr('value');
		
		$.ajax({
		type: 'POST',
		url: member_metabox_ajax.ajaxurl,
		data: {
			action: 'update_tax_role_ajax',
			object_id: object_id,
			object_type: object_type,
			subject: subject,
			subject_type: subject_type,
			role:role,
			desc:desc,
			term_id:term_id
		},
		success: function(data) {
            alert(data);	
		},
		dataType: 'html'
		});
    return false;
	 
    });
	
	
	jQuery('a#get_subject_by_role').live('click', function(e) {

		var object_id = jQuery('#object_id').attr('value');
		var subject_type = jQuery('#subject_type').attr('value');
		var role = jQuery('#role').attr('value');
		
		$.ajax({
		type: 'POST',
		url: member_metabox_ajax.ajaxurl,
		data: {
			action: 'get_subject_by_role_ajax',
			object_id: object_id,
			subject_type: subject_type,
			role:role
		},
		success: function(data) {
            alert(data);	
		},
		dataType: 'html'
		});
    return false;
	 
    });
	
	
	
	
		jQuery('a#get_tax_roles').live('click', function(e) {

		var term_id = jQuery('#term_id').attr('value');

		
		$.ajax({
		type: 'POST',
		url: member_metabox_ajax.ajaxurl,
		data: {
			action: 'get_tax_roles_ajax',
			term_id:term_id
		},
		success: function(data) {
            alert(data);	
		},
		dataType: 'html'
		});
    return false;
	 
    });
		
		
		
		
	}); //jquery ready
	


