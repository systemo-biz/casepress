// Wait DOM
jQuery(document).ready(function ($) {

	$.fn.editableform.buttons = '<button type="submit" class="btn btn-success editable-submit btn-mini"><i class="icon-ok icon-white"></i></button>' + '<button type="button" class="btn editable-cancel btn-mini"><i class="icon-remove"></i></button>';

	$('.cmmngt-editable-field').each(function (i) {
		var // Prepare data
		post_id = $('#cmmngt-meta-box-container').data('post-id'),
			container = $(this),
			trigger = container.find('strong:first'),
			field = container.find('span:first'),
			type = field.data('type'),
			name = field.data('name');
		// Apply x-editable
		field.editable({
			url: cmmngt.ajax_url,
			mode: 'inline',
			pk: 1,
			success: function (response, nv) {
				response = $.parseJSON(response);
				field.html(response.result);
			},
			autotext: 'never',
			showbuttons: false,
			display: false,
			datepicker: {
				firstDay: 1,
				onSelect: function () {
					container.find('form').submit();
				},
				showOn: 'focus'
			},
			select2: {
				formatSelection: function (s) {
					return s.text;
				},
				allowClear: true,
				quietMillis: 500,
				ajax: {
					url: cmmngt.ajax_url,
					dataType: 'json',
					data: function (term) {
						return {
							action: 'cmmngt_ajax_get_options',
							s: term
						};
					},
					results: function (data) {
						return {
							results: data
						};
					}
				},
				minimumInputLength: 3,
				formatNoMatches: function (s) {
					return cmmngt.s2.nomatches.replace('%s', s);
				},
				formatSearching: function () {
					return cmmngt.s2.searching;
				},
				formatInputTooShort: function (s, n) {
					return cmmngt.s2.tooshort.replace('%s', s).replace('%n', n);
				}
			},
			params: {
				action: 'cmmngt_save_editable',
				post_id: post_id
			},
			clear: true
		}).removeAttr('tabindex');
		// Apply show-event actions
		field.on('shown', function (e) {
			var $select;
			// Apply select2 to selects
			if (type == 'select') {
				// Prepare select element
				$select = container.find('select');
				$select.select2({
					formatNoMatches: function (s) {
						return cmmngt.s2.nomatches.replace('%s', s);
					},
					formatSearching: function () {
						return cmmngt.s2.searching;
					},
					formatInputTooShort: function (s, n) {
						return cmmngt.s2.tooshort.replace('%s', s).replace('%n', min);
					}
				});
				// Open select automatically
				window.setTimeout(function () {
					$select.select2('open');
				}, 100);
			}
			// Apply select2 actions
			else if (type == 'select2') {
				// Prepare select element
				$select = container.find('input:hidden.input-block-level');
				window.setTimeout(function () {
					$select.select2('open');
				}, 100);
				// Submit form when value is changed
				$select.on('change', function () {
					container.find('form').submit();
				});
			}
			// Setup clear button action
			container.find('span.editable-clear-x').on('click', function (e) {
				container.find('form').submit();
				e.preventDefault();
				e.stopPropagation();
			});
		});
		// Aplly invite-labels
		field.on('hidden', function (e, reason) {
			var val = field.editable('getValue')[name];
			if (val == '0' || val == '-1' || val == '' || val == undefined) {
				container.css({
					position: 'relative'
				}).animate({
					bottom: '-50px',
					opacity: 0
				}, 300, function () {
					$(this).addClass('cmmngt-dropped-field');
					$(this).css({
						bottom: 0,
						opacity: 1
					});
				});
				//				container.addClass('cmmngt-dropped-field');
				$('#cmmngt-field-invites span[data-invite="' + name + '"]').addClass('visible');
			}
		});
		// Trigger click event
		trigger.click(function (e) {
			e.stopPropagation();
			field.editable('toggle');
		});
	});
	// Apply invites
	$('#cmmngt-field-invites span').on('click', function (e) {
		// Prepare data
		var invite = $(this).data('invite'),
			$field = $('p.cmmngt-invited-field[data-field="' + invite + '"]');
		// Hide label
		$(this).removeClass('visible');
		// Show field
		$field.removeClass('cmmngt-dropped-field');
		// Activate field
		window.setTimeout(function () {
			$field.find('span').click();
		}, 300);
		e.preventDefault();
	});
	// Hide empty fields
	$('p.cmmngt-invited-field[data-empty="true"]').each(function () {
		var field = $(this).data('field');
		// Hide field
		$(this).addClass('cmmngt-dropped-field');
		// Show invite
		$('#cmmngt-field-invites span[data-invite="' + field + '"]').addClass('visible');
	});

	// Init datepicker
	$('.cmmngt-init-datepicker').datepicker({
		altFormat: 'yy-mm-dd'
	});

	$('.cmmngt-action').on('click', function (e) {
		// Prepare data
		var $form = $('form.cmmngt-form'),
			is_action = $(this).data('action') != '',
			is_publish = $(this).hasClass('cmmngt-save'),
			has_subs = $(this).hasClass('dropdown-toggle'),
			submit = $(this).data('submit');
		// Publish action
		if (is_publish) $('input:hidden[name="post_status"]').val('publish');
		// Is action
		else if (is_action) $('input:hidden[name="cmmngt_action"]').val($(this).data('action'));
		// This is menu trigger, prevent action
		if (has_subs) e.preventDefault();
		// This is action
		else {
			// Disable buttons
			if (submit) $('.cmmngt-container button').attr('disabled', true);
			// Show loading animation
			if (submit) $('.cmmngt-loading').addClass('visible');
			// Submit the form
			if (submit) $form.submit();
		}
	});
	$('.cmmngt-sub-action').on('click', function (e) {
		// Prepare data
		var $form = $('form.cmmngt-form'),
			submit = $(this).data('submit');
		// Set sub action value
		$('input:hidden[name="cmmngt_sub_action"]').val($(this).data('action'));
		// Disable buttons
		if (submit) $('.cmmngt-container button').attr('disabled', true);
		// Show loading animation
		if (submit) $('.cmmngt-loading').addClass('visible');
		// Submit the form
		if (submit) $form.submit();
		// Prevent #-click
		e.preventDefault();
	});

	// Additional actions
	$('.cmmngt-action[data-action="delegate"]').on('click', function (e) {
		var $box = $('#cmmngt-delegate-select-box'),
			$loading = $box.find('span.cmmngt-loading-persons');
		// Show select box
		$box.show();
		// Load persons select
		$.ajax({
			url: cmmngt.ajax_url,
			type: 'post',
			data: {
				action: 'cmmngt_get_persons_select'
			},
			beforeSend: function () {
				// Display loading animation
				$loading.addClass('visible');
			},
			success: function (data) {
				// Place select into box
				$box.append(data);
				// Prepare select
				var $select = $box.find('select');
				// Hide loading animation
				$loading.removeClass('visible');
				// Apply select2
				$select.css({
					width: '100%'
				}).select2({
					formatNoMatches: function (s) {
						return cmmngt.s2.nomatches.replace('%s', s);
					},
					formatSearching: function () {
						return cmmngt.s2.searching;
					},
					formatInputTooShort: function (s, n) {
						return cmmngt.s2.tooshort.replace('%s', s).replace('%n', min);
					}
				});
				// Open select
				$select.select2('open');
			},
			dataType: 'html'
		});
		e.preventDefault();
	});

	$('#cmmngt-delegate-select').live('change', function () {
		var $form = $('form.cmmngt-form');
		// Set action
		$('input:hidden[name="cmmngt_action"]').val('delegate');
		// Set subaction
		$('input:hidden[name="cmmngt_sub_action"]').val($(this).val());
		// Hide container
		$('#cmmngt-delegate-select-box').hide();
		// Disable buttons
		$('.cmmngt-container button').attr('disabled', true);
		// Show loading animation
		$('.cmmngt-loading').addClass('visible');
		// Submit the form
		$form.submit();
	});
});