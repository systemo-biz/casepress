jQuery(document).ready(function(){
	//All needed
	var added_check_title;
	var added_check_lvl;
	var my_ul_size;
	var my_old_ul_size;
	var current_post_id = 1;
	var myflag = 0;
	var current_parent_id;
	var previous_check_id;
	var new_parent;
	var new_parent_id;
	var new_index;
	var check_value_tabbed;
	var global_check_ajax_value = 0;
	
	
	// Ajax image.gif, and flag
	jQuery('#check_ajax_load').bind({
		ajaxStart: function() 
		{ 
			jQuery(this).show();
						
		},
		ajaxStop: function() 
		{ 
			jQuery(this).hide(); 
			global_check_ajax_value = 0;
		}
	
	});
	
	jQuery('.hidding_check').hide();
	jQuery('.check_ok_image').hide();
	current_parent_id = jQuery('#my_check_form').children('ul').attr('id');
	jQuery('#my_check_form').children('ul').css('padding-left','0px');
	jQuery('#my_check_form').children('ul').attr('class','check_ul_sortable');
	my_ul_size = jQuery('#my_check_form').children('ul').children('li').size();

	jQuery('#my_check_form').append('<a href="#" onclick="return false;" id="add_new_check_a">Добавить новую заметку!</a>');
	
	// This function provides result of clicking, "add new note" link.
	jQuery('#add_new_check_a').live('click', function(){
		jQuery('.check_ok_image').hide();
		//jQuery('.check_del_image').hide();
		jQuery('.hidding_check').show();
		jQuery('.showing_check').hide();
		jQuery('#just_added_check').parent().remove();
		jQuery('#my_check_form').children('ul').append('<li class="check_li_test"><div id="ch_id'+current_parent_id+'_'+my_ul_size+'" class="div_check_container" name="'+current_parent_id+'"><input class="check_form"></div><ul id="just_added_check"></ul> </li>');
		update_relatives_indexes(current_parent_id);
		jQuery('#just_added_check').prev().children('input').focus();
	});
	
	jQuery('.check_li_test').live('hover',function(){
		
		jQuery('.check_del_image').hide();
		
		jQuery(this).children('div').children('.check_del_image').show();
	}); 
	
	jQuery('.check_form').live('blur',function(){
		if ($(this).parent().next().attr('id') != 'just_added_check')
		{
			check_update_post_data(jQuery(this).parent().parent().attr('name'),$(this).val());
			$(this).next().html($(this).val());
			jQuery('.hidding_check').show();
			jQuery('.showing_check').hide();
		}
		if ($(this).attr('class')=='check_start_form') $(this).parent().parent().remove();
	});

	
	jQuery('.check_form').live('keydown', function(e) {			
				// Tab key
				if (e.which == 9  && !e.shiftKey) 
				{
					
					current_index = jQuery(this).parent().parent().index();					
					if (current_index != 0)
					{
						if (global_check_ajax_value == 0)
						{	
							index_of_prev = jQuery(this).parent().parent().index()-1;
							current_parent_id = jQuery(this).parent().attr('name');
							new_parent_id = jQuery('#ch_id'+current_parent_id+'_'+index_of_prev).parent().attr('name');
							jQuery('#'+new_parent_id).append('<li class="check_li_test">'+jQuery(this).parent().parent().html()+'</li>');
							check_value_tabbed = jQuery(this).val();
							jQuery(this).parent().parent().remove();
							new_to_add_div = '#'+jQuery('#ch_id'+current_parent_id+'_'+current_index).attr('id');
							jQuery(new_to_add_div).children('input').focus();
							jQuery(new_to_add_div).attr('name',new_parent_id);
							new_index = jQuery('#ch_id'+current_parent_id+'_'+current_index).parent().index();
							jQuery(new_to_add_div).attr('id','ch_id'+new_parent_id+'_'+new_index);
							update_relatives_indexes(new_parent_id);
							if ( jQuery(this).attr('name') == 'loaded') check_update_post_relative(jQuery('#ch_id'+new_parent_id+'_'+new_index).parent().attr('name'),new_parent_id,new_index);
						}
						else alert('Идёт Ajax запрос, попробуйте попытаться снова');
					}
				}
				// Tab key + shift
				if (e.which == 9 && e.shiftKey)
				{
					current_parent_id = jQuery(this).parent().attr('name');					
					if (current_parent_id != jQuery('#my_check_form').children('ul').attr('id'))
					{
						if (global_check_ajax_value == 0)
						{
							current_index = jQuery(this).parent().parent().index();
							jQuery(this).parent().parent().addClass('check_li_test');
							new_parent_id = jQuery('#'+current_parent_id).prev().attr('name');
							jQuery('#'+current_parent_id).parent().after('<li class="check_li_test">'+jQuery(this).parent().parent().html()+'</li>');
							jQuery(this).parent().parent().remove();
							new_to_add_div = '#'+jQuery('#ch_id'+current_parent_id+'_'+current_index).attr('id');
							jQuery(new_to_add_div).children('input').focus();
							jQuery(new_to_add_div).attr('name',new_parent_id);
							new_index = jQuery('#ch_id'+current_parent_id+'_'+current_index).parent().index();
							jQuery(new_to_add_div).attr('id','ch_id'+new_parent_id+'_'+new_index);
							update_relatives_indexes(current_parent_id);
							update_relatives_indexes(new_parent_id);
							if ( jQuery(this).attr('name') == 'loaded') check_update_post_relative(jQuery('#ch_id'+new_parent_id+'_'+new_index).parent().attr('name'),new_parent_id,new_index);
						}
						else alert('Идёт Ajax запрос, попробуйте попытаться снова');
					}
				}
				// Enter key	
				if (e.which == 13) 
				{
					if ( jQuery(this).attr('name') == 'loaded' )
					{
						//jQuery('#just_added_check').parent().remove();
						current_parent_id = jQuery(this).parent().attr('name');
						my_ul_size = jQuery(this).parent().parent().index()+1;
						my_old_ul_size = jQuery(this).parent().parent().index();
						jQuery(this).parent().parent().after('<li class="check_li_test"><div id="ch_id'+current_parent_id+'_'+my_ul_size+'" class="div_check_container" name="'+current_parent_id+'"><input class="check_form"></div><ul id="just_added_check"></ul> </li>');
						jQuery('.check_del_image').hide();
						jQuery('.check_ok_image').hide();
						jQuery('.hidding_check').show();
						jQuery('.showing_check').hide();
						jQuery('#just_added_check').prev().children('input').focus();
						jQuery(this).next().text(jQuery(this).val());
						update_relatives_indexes(current_parent_id);
						update_relatives_indexes(new_parent_id);
						check_update_post_data(jQuery(this).parent().parent().attr('name'),jQuery(this).val(),my_old_ul_size);
						
					}
					
					else
					{
						jQuery(this).removeClass('check_start_form');
						jQuery(this).attr('name','loaded');
						jQuery(this).hide();
						jQuery(this).before('<img/><img src="/wp-content/plugins/cases-metabox-checks/unchecked.png" class="check_unchecked_image">');
						jQuery(this).after('<span class="check_form_sp">'+jQuery(this).val()+'</span><img src="/wp-content/plugins/cases-metabox-checks/accept.png" class="check_ok_image" style="display: none;"><img src="/wp-content/plugins/cases-metabox-checks/delete.png" class="check_del_image" style="display: none;">');
						jQuery('.check_ok_image').hide();
						current_parent_id = jQuery(this).parent().attr('name');
						my_ul_size = jQuery(this).parent().parent().index()+1;
						my_old_ul_size = jQuery(this).parent().parent().index();
						added_check_title = jQuery(this).val();	
						
						jQuery(this).parent().parent().after("<li class='check_li_test' id='ch_li_"+current_parent_id+"_"+my_ul_size+"'><div id='ch_id"+current_parent_id+"_"+my_ul_size+"' class='div_check_container' name='"+current_parent_id+"'><input class='check_form check_start_form'></div><ul id='just_added_check_cur'></ul></li>");
						update_relatives_indexes(current_parent_id);
						jQuery('#ch_id'+current_parent_id+'_'+my_ul_size).children('input').focus();
						global_check_ajax_value = 1;
						jQuery.ajax({
							type:'POST',
							url: ajaxurl,			
							data: 
							{
								action: 'save_my_check_please', 
								check_title: added_check_title,
								check_parent_id: current_parent_id,
								check_added_order: my_old_ul_size
							},
							success: function(result) 
							{						
								jQuery("#just_added_check").parent().attr('name',result);
								jQuery("#just_added_check").attr('ID',result);
								jQuery("#just_added_check_cur").attr('ID','just_added_check');								
								global_check_ajax_value = 0;
							},
							dataType: 'html'													
						});		
						e.preventDefault();	
					}
				}
				
				if (e.which == 46 && e.shiftKey){
					current_post_check_id = jQuery(this).parent().parent().attr('name');
					current_post_parent = jQuery(this).parent().attr('name');
					if (current_post_check_id != 'just_added_check')
					{
						current_post_child_data = jQuery('#'+current_post_check_id).html();
						jQuery(this).parent().parent().after(current_post_child_data);
						jQuery(this).parent().parent().remove();
						update_relatives_indexes(current_post_parent);
						jQuery.ajax({
							type:'POST',
							url: ajaxurl,			
							data: 
							{
								action: 'remove_my_check_please', 
								ch_remove_id: current_post_check_id,
								ch_post_parent: current_post_parent
							},
							success: function(result) 
							{				
							},
							dataType: 'html'
						});
					}
				}
	
		});
		
	//Delete
	jQuery('.check_del_image').live('click', function(){
		current_post_check_id = jQuery(this).parent('div').parent().attr('name');
		current_post_parent = jQuery(this).parent('div').attr('name');
		if (current_post_check_id != 'just_added_check')
		{
			current_post_child_data = jQuery('#'+current_post_check_id).html();
			jQuery(this).parent('div').parent('li').after(current_post_child_data);
			jQuery(this).parent('div').parent('li').remove();
			update_relatives_indexes(current_post_parent);
			jQuery.ajax({
				type:'POST',
				url: ajaxurl,			
				data: 
				{
					action: 'remove_my_check_please', 
					ch_remove_id: current_post_check_id,
					ch_post_parent: current_post_parent
				},
				success: function(result) 
				{				
				},
				dataType: 'html'
			});
		}		
	});
	// Open to edit
	jQuery('.check_form_sp').live('click', function(){
		if (global_check_ajax_value == 0)
		{
			current_parent = jQuery(this).parent().attr('name');
			jQuery('#just_added_check').parent().remove();
			jQuery('.check_ok_image').hide();
			jQuery('.check_del_image').hide();
			jQuery('.hidding_check').show();
			jQuery('.showing_check').hide();
			//jQuery(this).next().show();
			jQuery('.hidding_check').removeClass('hidding_check');
			jQuery('.showing_check').removeClass('showing_check');
			jQuery(this).addClass('hidding_check');
			jQuery(this).prev().removeClass('hidding_check');
			jQuery(this).prev().addClass('showing_check');
			jQuery('.hidding_check').hide();
			//jQuery(this).next().next().show();
			jQuery('.showing_check').show();
			jQuery(this).prev().focus();
			update_relatives_indexes(current_parent);
		}
		else alert('Невозможно выполнить операцию, дождитесь окончания предыдущего запроса');
	});
	
	// Ok (save without adding new checkbox input)
	jQuery('.check_ok_image').live('click',function(){
		var current_check_id = jQuery(this).parent().parent().attr('name');
		var current_check_title = jQuery(this).prev().prev().val();
		if (jQuery(this).prev().text() != current_check_title)
		{
			jQuery(this).prev().text(current_check_title);
			
			check_update_post_data(current_check_id,current_check_title);
		}
		
		jQuery('.showing_check').hide();
		jQuery('.hidding_check').show();
		jQuery('.check_ok_image').hide();
		jQuery('.check_del_image').hide();
		
	});
	
	// Make the unchecked check checked :D
	jQuery('.check_unchecked_image').live('click',function(){
		jQuery(this).attr('class','check_checked_image');
		jQuery(this).attr('src','/wp-content/plugins/cases-metabox-checks/checked.png');
		ch_check_value_id_param = jQuery(this).parent().parent().attr('name');
		jQuery(this).next().next().css('text-decoration','line-through');
		jQuery.ajax({
			type:'POST',
			url: ajaxurl,			
			data: 
			{
				action: 'change_check_value', 
				ch_check_value: 1,
				ch_check_value_id: ch_check_value_id_param
			},
			dataType: 'html'

		});
	});
	
	// Make the checked check unchecked 
	jQuery('.check_checked_image').live('click',function(){
		jQuery(this).attr('class','check_unchecked_image');
		jQuery(this).attr('src','/wp-content/plugins/cases-metabox-checks/unchecked.png');
		jQuery(this).next().next().css('text-decoration','none');
		ch_uncheck_value_id_param = jQuery(this).parent().parent().attr('name');
		
		
		jQuery.ajax({
			type:'POST',
			url: ajaxurl,			
			data: 
			{
				action: 'change_uncheck_value', 
				ch_uncheck_value: 0,
				ch_uncheck_value_id: ch_uncheck_value_id_param
			},
			dataType: 'html'

		});
		
	});
	
	//Updates the current check title (content) 
	function check_update_post_data(check_post_id,check_post_title_new){
		jQuery.ajax({
			type:'POST',
			url: ajaxurl,			
			data: 
			{
								action: 'check_update_post_data', 
								check_id: check_post_id,
								check_new_title: check_post_title_new
			},
			success: function(result) 
			{						
			},
			dataType: 'html'						
		});
	}
	
	
	// Updates the relatives of current post, include his parent,and position
	function check_update_post_relative(){
		jQuery('.check_li_test').each(function(){
			jQuery.ajax({
				type:'POST',
				url: ajaxurl,			
				data: 
				{
									action: 'check_update_post_relative', 
									check_id: $(this).attr('name'),
									check_new_parent_id: $(this).parent().parent().attr('name'),
									chek_new_index_value: $(this).index()
				},
				success: function(result) 
				{						
					//alert(result);
				},
				dataType: 'html'						
			});
		});
	}
	

	
	//Update the DOM indexes upon any ID
	function update_relatives_indexes(relative_id){
		jQuery('#'+relative_id).children('li').each(function(){
			new_index = jQuery(this).index();
			jQuery(this).attr('id','ch_li_'+relative_id+'_'+jQuery(this).index());
			jQuery(this).children('div').attr('id','ch_id'+relative_id+'_'+new_index);
			jQuery(this).children('div').attr('name',jQuery(this).parent().attr('id'));	
		});
	}
	var old_post_index;
	var old_post_parent_id;
	jQuery('.check_ul_sortable').nestedSortable({
			listType: 'ul',
			disableNesting: 'no-nest',
			forcePlaceholderSize: true,
			
			helper:	'clone',
			items: 'li',
			maxLevels: 5,
			opacity: .6,
			placeholder: 'check_state_highlight',
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div',
			start: function(event,ui){
				old_post_index = ui.item.index();
				old_post_parent_id = ui.item.parent().parent().attr('name');
			},
			stop: function(event, ui) {
				if (old_post_index == ui.item.index() && old_post_parent_id == ui.item.parent().parent().attr('name')){
					
				}
				else 
				{
					update_relatives_indexes(ui.item.attr('name')); 
					update_relatives_indexes(ui.item.parent().parent().attr('name'));
					//check_update_post_relative(ui.item.attr('name'),ui.item.parent().parent().attr('name'),ui.item.index());	
					check_update_post_relative();
					
				}
				
			}
	});
	
	//jQuery('.ul_to_sort').sortable('option', 'cancel', 'ul');
	
});