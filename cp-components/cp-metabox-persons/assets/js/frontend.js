jQuery(document).ready(function ($) {
	// Enable Chooser's JS
	var Chooser = new CasePress_Chooser('flexo-window-cmp-chooser');
	// Prepare elements
	var $form = $('#cmp-chooser');
	// Prepare flexo state
	window.cmp_$flexo = false;

	// --------------------------------------------------------------------------
	//		SELECT2 MODE
	// --------------------------------------------------------------------------

	// Prevent links clicks
	$('.cmp-field > p > span > a').live('click', function (e) {
		$(this).parent('span').parent('p').children('strong').click();
		e.preventDefault();
	});

	// Process triggers
	$('.cmp-field > p > strong').click(function (e) {
		// Prepare data
		var $field = $(this).parent('p').parent('div'),
			$value = $field.find('span'),
			role = $field.data('role'),
			selected = parse_selected(role),
			$s2 = $field.find('b'),
			format = [],
			$s2_input, is_multiple = $field.data('multiple'),
			$flexo_trigger = $(this).children('i');

		// Save field
		window.cmp_$field = $field;
		// Run ajax to get options and build S2
		window.cmp_get_s2 = $.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				action: 'cmp_get_s2'
			},
			dataType: 'json',
			beforeSend: function (xhr) {
				// Abort previous requests
				if (typeof window.cmp_get_s2 == 'object') window.cmp_get_s2.abort();
				// Prepare S2 input
				$s2.addClass('cmp-hidden').html('<input type="hidden" style="width:100%" />');
				$s2_input = $s2.find('input');
				// Show loading animation
				$value.addClass('loading').removeClass('cmp-hidden');
			},
			success: function (data) {
				// Hide value and loading animation
				$value.addClass('cmp-hidden').removeClass('loading');
				// Show S2 container
				$s2.removeClass('cmp-hidden');
				// Extend data with empty option
				data.push({
					id: '',
					text: ''
				});
				// Fill and open S2
				$s2_input.select2({
					placeholder: '',
					multiple: is_multiple,
					data: data,
					allowClear: true,
					matcher: function (term, text, opt) {
						// Prepare data
						var i = 0,
							terms = term.split(' '),
							result = true;
						// Loop by words
						for (i; i < terms.length; i++) {
							// Word contain part of query, continue the loop
							if (text.toUpperCase().indexOf(terms[i].toUpperCase()) >= 0) {
								result = true;
								continue;
							}
							// Word does not contain part of the query, stop the loop
							else {
								result = false;
								break;
							}
						}
						return result;
					},
					initSelection: function (element, callback) {
						var data = [];
						$(element.val().split(',')).each(function (i) {
							data[i] = {
								id: this.split(';')[0],
								text: this.split(';')[1]
							};
						});
						if (is_multiple) callback(data);
						else callback(data[0]);
					}
				});
				// Update selection in S2
				$(selected).each(function (i) {
					if (selected[i].type == 'person') format.push(selected[i].id + ';' + selected[i].name.replace(/,/g, ''));
					else if (selected[i].type == 'term') format.push(selected[i].tax + ':' + selected[i].id + ';' + selected[i].name.replace(/,/g, ''));
				});
				if (format.length > 0) $s2_input.select2('val', format);
				// Open S2
				$s2_input.select2('open');
				// Show Flexo Mode Trigger
				$flexo_trigger.removeClass('cmp-hidden');
				// Add S2 event handlers
				$s2_input.bind({
					// Selection is changed
					change: function (e) {
						// Blur non-multiple fields
						if (!is_multiple) $(this).trigger('blur');
					},
					blur: function () {
						// Save data
						if (!$s2.hasClass('cmp-hidden')) save_field(role, 's2', $(this).select2('val'));
						window.setTimeout(function () {
							// Hide Flexo Mode Trigger
							$flexo_trigger.addClass('cmp-hidden');
						}, 500);
					}
				});
			}
		});
		e.preventDefault();
	});

	// --------------------------------------------------------------------------
	//		CHOOSER/FLEXO MODE
	// --------------------------------------------------------------------------
	$('.cmp-field i').on({
		mousedown: function (e) {
			var // Prepare data
			$field = $(this).parent('strong').parent('p').parent('div'),
				$value = $field.find('> p > span'),
				$s2 = $field.find('> p > b');
			// Save field
			window.cmp_$field = $field;
			// Hide trigger
			$(this).addClass('cmp-hidden');
			// Clear and hide S2
			$s2.html('').addClass('cmp-hidden');
			$('#select2-drop, #select2-drop-mask').hide();
			// Abort AJAX
			if (typeof window.cmp_get_s2 == 'object') window.cmp_get_s2.abort();
			// Show value and loading animation
			$value.removeClass('loading').removeClass('cmp-hidden');
			// Click event
			$form.flexo({
				position: 'center',
				controls: {
					drag: false,
					resize: false
				},
				width: '50%',
				height: '90%',
				title: $form.data('title')
			});
		},
		click: function (e) {
			e.preventDefault();
			e.stopPropagation();
		}
	});

	// Update form when flexo is shown
	$form.on('ready.flexo', function () {
		// Check plugin action
		if (typeof window.cmp_$field != 'object') return;
		// Save open state
		window.cmp_$flexo = true;
		// Prepare data
		var $field = window.cmp_$field,
			role = $field.data('role'),
			results = parse_selected(role),
			is_multiple = $field.data('multiple');
		// Update Chooser form state and load table
		Chooser.updateState({
			multiple: is_multiple
		}, false);
		// Set Chooser results
		Chooser.results.set(results);
		// Reload table
		Chooser.table.load();
		// Init jsTree
		Chooser.tree.init();
	});

	// OK button
	$('.cmp-ok').live('click', function (e) {
		var $field = window.cmp_$field,
			role = $field.data('role');
		// Save data
		save_field(role, 'chooser', Chooser.results.get());
		// Close popup window
		$form.flexo('close');
		e.preventDefault();
	});

	// Cancel button
	$('.cmp-cancel').live('click', function (e) {
		// Close popup window
		$form.flexo('close');
		e.preventDefault();
	});

	// --------------------------------------------------------------------------
	//		INVITES & FIELDS
	// --------------------------------------------------------------------------

	// Invite click
	$('#cmp-field-invites span').live('click', function (e) {
		var // Prepare data
		$field = $('.cmp-field[data-role="' + $(this).data('invite') + '"]');
		// Show field
		$field.removeClass('cmp-dropout');
		// Hide invite
		$(this).hide();
		// Trigger click on field
		$field.find('> p > strong').trigger('click');
	});

	// Update invites when flexo is closed
	$form.on('close.flexo', function () {
		// Save closed state
		window.cmp_$flexo = false;
		// Update invites
		update_invites();
	});

	// Update invites when DOM is ready
	update_invites();

	/**
	 * Update invite fields
	 */
	function update_invites() {
		// Loop through fields
		$('.cmp-field').each(function (i) {
			var // Prepare data
			is_empty = $(this).find('> p > span > strong').length > 0,
				is_loading = $(this).find('> p > span.loading').length > 0,
				is_flexo_open = window.cmp_$flexo;
			// Value is empty and not in loading state and flexo is not open
			if (is_empty && !is_loading && !is_flexo_open) {
				// Hide field
				$(this).addClass('cmp-dropout');
				// Show invite
				$('#cmp-field-invites span[data-invite="' + $(this).data('role') + '"]').show();
			}
		});
	}

	/**
	 * Parse current selection by role
	 */
	function parse_selected(role) {
		var results = [];
		$('.cmp-field[data-role="' + role + '"]').find('[data-type]').each(function (i) {
			results[i] = {
				id: $(this).data('id'),
				name: $(this).text(),
				type: $(this).data('type'),
				tax: $(this).data('tax')
			};
		});
		return results;
	}

	/**
	 * Save current selection
	 *
	 * @param role User role to save
	 * @param mode Saving mode. Used on server to correct data parsing
	 * @param data The data to save
	 */
	function save_field(role, mode, data) {
		var // Prepare data
		$field = $('.cmp-field[data-role="' + role + '"]'),
			$value = $field.find('> p > span'),
			$s2 = $field.find('> p > b'),
			is_multiple = $field.data('multiple'),
			post_id = $form.data('post-id');
		// Prepare data. Convert it to array
		if (typeof data === 'string') data = new Array(data);
		// AJAX request
		window.cmp_save_field = $.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				action: 'cmp_save_field',
				role: role,
				mode: mode,
				data: data,
				multiple: is_multiple,
				post_id: post_id
			},
			dataType: 'html',
			beforeSend: function () {
				// Abort previous requests
				if (typeof window.cmp_save_field == 'object') window.cmp_save_field.abort();
				// Hide S2 container
				$s2.addClass('cmp-hidden');
				// Show value and loading animation
				$value.addClass('loading').removeClass('cmp-hidden');
			},
			success: function (data) {
				// Update value and hide loading animation
				$value.html(data).removeClass('loading');
				// Update field invites
				update_invites();
			}
		});
	}
});