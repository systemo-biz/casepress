jQuery(document).ready(function(){
	jQuery('#cmmngt-functions').live('change',function(){            
            jQuery.ajax({
                    type:'POST',
                    url: autohide.ajaxurl,
                    data:
                    {
                        action: 'autohide_function',
                        autohide_term_id: jQuery(this).val()
                    },
                    success: function(result)
                    {
                            if(result.title != '') jQuery('#titlewrap').hide(); else jQuery('#titlewrap').show();
                            if(result.content != '') jQuery('#postdivrich').hide(); else jQuery('#postdivrich').show();
                    },
                    dataType: 'json'
            });
        });
})
