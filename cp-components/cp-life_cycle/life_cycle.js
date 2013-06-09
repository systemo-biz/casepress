	jQuery(document).ready(function ($) {

		var post_id = $('#post_id').val();
		//СОСТОЯНИЯ
			//var state = $('#state_cur2').val();
			//alert(state);
		$.ajax({
			type: 'POST',
			url: life_cycle.ajaxurl,
			data: {
				action: 'get_states_life_cycle_ajax',
				post_id: post_id

			},
			success: function(dt) {
				res = $.parseJSON(dt);
			//	alert(dt);
				$("#state_cur").select2({
					createSearchChoice:function(term, data)
					{
						if ($(data).filter(function()
							{ return this.text.localeCompare(term)===0; }).length===0)
								{return {id:term, text:term};}
					},
					multiple: true,
					data: res,
					maximumSelectionSize: 100
				});


				$("#state_cur").select2("container").find("ul.select2-choices").sortable({
					containment: 'parent',
					start: function() { $("#state_cur").select2("onSortStart"); },
					update: function() { $("#state_cur").select2("onSortEnd"); }
				});

			},
			dataType: 'html'
		});


		//ФУНКЦИИ

		$.ajax({
			type: 'POST',
			url: life_cycle.ajaxurl,
			data: {
				action: 'get_functions_life_cycle_ajax',
				post_id: post_id
			},
			success: function(dt) {
				res = $.parseJSON(dt);
				$("#functions_cur").select2({
					multiple: true,
					data: res,
					maximumSelectionSize: 100
				});


			},
			dataType: 'html'
		});


		//РЕЗУЛЬТАТЫ
		$.ajax({
			type: 'POST',
			url: life_cycle.ajaxurl,
			data: {
				action: 'get_results_life_cycle_ajax',
				post_id: post_id
			},
			success: function(dt) {
				res = $.parseJSON(dt);
				$("#results_cur").select2({
					createSearchChoice:function(term, data)
					{
						if ($(data).filter(function()
							{ return this.text.localeCompare(term)===0; }).length===0) {return {id:term, text:term};}
					},
					multiple: true,
					data: res,
					maximumSelectionSize: 100
				});


				$("#results_cur").select2("container").find("ul.select2-choices").sortable({
					containment: 'parent',
					start: function() { $("#results_cur").select2("onSortStart"); },
					update: function() { $("#results_cur").select2("onSortEnd"); }
				});

			},
			dataType: 'html'
		});



	});