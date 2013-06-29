// Wait DOM
jQuery(document).ready(function ($) {

	// Init AJAX'ed select2s
	$('.cmmngt-init-select2-ajax').each(function (i) {
		var $input = $(this);
		$input.select2({
			initSelection: function (element, callback) {
				var val = $(element).val();
				if (val !== '') {
					$.ajax(ajaxurl, {
						data: {
							action: 'cmmngt_ajax_get_title',
							post_id: val
						},
						dataType: 'json'
					}).done(function (data) {
						callback(data);
					});
				}
			},
			formatSelection: function (s) {
				return s.text;
			},
			allowClear: true,
			quietMillis: 500,
			ajax: {
				url: ajaxurl,
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
		});
	});

	// Init select2
	$('.cmmngt-init-select2').select2({
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
	// Init datepicker
	$('.cmmngt-init-datepicker').datepicker({
		altFormat: 'yy-mm-dd',
		showMinute: false
	});
	// Aplly invite-labels
	$('p.cmmngt-invited-field').each(function (e) {
		var $field = $(this),
			$selects = $field.find('select'),
			$dates = $field.find('input:text.hasDatepicker'),
			name = $field.data('field');
		$selects.live('change blur', function (e, manual) {
			if (typeof manual !== 'undefined') return;
			var val = $(this).val();
			if (val == '0' || val == '-1' || val == '') {
				$field.css({
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
				$('#cmmngt-field-invites span[data-invite="' + name + '"]').addClass('visible');
			}
		});
		$dates.on('blur', function () {
			var val = $(this).val(),
				ondate = $('#ui-datepicker-div:hover').length > 0;
			if ((val == '0' || val == '-1' || val == '') && !ondate) {
				$field.css({
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
				$('#cmmngt-field-invites span[data-invite="' + name + '"]').addClass('visible');
			}
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
			$field.find('select').select2('open');
			$field.find('input:text').focus();
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


	$('.cmmngt-action').on('click', function (e) {
		// Prepare data
		var $form = $('form#post'),
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
			// Unblock buttons in 5 sec
			window.setTimeout(function () {
				$('.cmmngt-container button').attr('disabled', false);
			}, 5000);
		}
	});
	$('.cmmngt-sub-action').on('click', function (e) {
		// Prepare data
		var $form = $('form#post'),
			submit = $(this).data('submit');
		// Set sub action value
		$('input:hidden[name="cmmngt_sub_action"]').val($(this).data('action'));
		// Disable buttons
		if (submit) $('.cmmngt-container button').attr('disabled', true);
		// Show loading animation
		if (submit) $('.cmmngt-loading').addClass('visible');
		// Submit the form
		if (submit) $form.submit();
		// Unblock buttons in 5 sec
		window.setTimeout(function () {
			$('.cmmngt-container button').attr('disabled', false);
		}, 5000);
		// Prevent #-click
		e.preventDefault();
	});

	// Additional actions
	$('.cmmngt-action[data-action="delegate"]').on('click', function (e) {
		var $box = $('#cmmngt-delegate-select-box'),
			$loading = $box.find('span.cmmngt-loading-persons'),
			loaded = $box.data('loaded') === true;
		// Show select box
		$box.show();
		// Load persons select
		if (!loaded) $.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				action: 'cmmngt_get_persons_select'
			},
			beforeSend: function () {
				// Display loading animation
				$loading.addClass('visible');
			},
			success: function (data) {
				// Save loaded state
				$box.data('loaded', true);
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
		var $form = $('form#post');
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

	// Reload states and results when category is changed
	jQuery('#cmmngt-functions').on('change', function (e) {
		// Function is changed
		if (e.target.id === 'cmmngt-functions') {
			// Prepare data
			var $state = $('select#cmmngt-state'),
				$result = $('select#cmmngt-results'),
				term = $(this).val();
			// Query states and results
			window.cmmngt_get_lfc = $.ajax({
				url: ajaxurl,
				type: 'get',
				data: {
					action: 'cmmngt_get_lfc',
					term: term
				},
				dataType: 'json',
				beforeSend: function () {
					// Abort previous requests
					if (typeof window.cmmngt_get_lfc === 'object') window.cmmngt_get_lfc.abort();
					// Show loading animation
					$state.parent('p').addClass('cmmngt-field-loading');
					$result.parent('p').addClass('cmmngt-field-loading');
				},
				success: function (data) {
					// Update states
					$state.children('option').remove();
					$(data.states).each(function (i) {
						$state.append('<option value="' + data.states[i].id + '">' + data.states[i].text + '</option>');
					});
					$state.trigger('change', true);
					// Update results
					$result.children('option').remove();
					$(data.results).each(function (i) {
						$result.append('<option value="' + data.results[i].id + '">' + data.results[i].text + '</option>');
					});
					$result.trigger('change', true);
					// Hide loading animation
					$state.parent('p').removeClass('cmmngt-field-loading');
					$result.parent('p').removeClass('cmmngt-field-loading');
				}
			});
		}
	});
});