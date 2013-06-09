jQuery(document).ready(function() {
	jQuery('input.check_acl').click(function(){
	var replaceable=document.getElementById('replaceable');
	var substitutional=document.getElementById('substitutional');
	var date_start=document.getElementById('date_start');
	var date_end=document.getElementById('date_end');
		$.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {
			action: 'sub_acl_update',
			replaceable: replaceable.value,
			substitutional: substitutional.value,
			date_start: date_start.value,
			date_end: date_end.value
		},
		success: function(data) {
		//alert(data);
            	document.getElementById('substitution_table').innerHTML=data;	
		},
		dataType: 'html'
		});
     return false;
    });
    
    	jQuery('input.close_subst').live('click', function(e) {
	var id_sub=this.id;
		$.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {
			action: 'sub_acl_close',
			id_sub: id_sub
		},
		success: function(data) {
            	document.getElementById('substitution_table').innerHTML=data;	
		},
		dataType: 'html'
		});
     return false;
    });
    
        jQuery('input.open_subst').live('click', function(e) {
	var id_sub=this.id;
		$.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {
			action: 'sub_acl_open',
			id_sub: id_sub
		},
		success: function(data) {
            	document.getElementById('substitution_table').innerHTML=data;	
		},
		dataType: 'html'
		});
     return false;
    });
});