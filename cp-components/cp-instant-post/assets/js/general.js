jQuery(document).ready(function ($) {
	// Menu links init
	$('#wp-admin-bar-cip-message a, #wp-admin-bar-cip-incoming a').on('click', function (e) {
		var type = $(this).parent('li').attr('id').replace('wp-admin-bar-cip-', '');
		$(this).flexo({
			id: 'cip',
			type: 'ajax',
			ajax: {
				type: 'get',
				url: cases_instant_post.ajaxurl,
				data: {
					action: 'cip_get_form',
					form_type: type
				}
			},
			title: cases_instant_post[type],
			footer: '<span class="cip-loading-indicator"></span><button id="cip-cancel" class="btn btn-mini" data-window="' + type + '">' + cases_instant_post.cancel + '</button><button id="cip-send" class="btn btn-mini btn-primary">' + cases_instant_post.publish + '</button>',
			width: 300,
			height: 500,
			position: {
				left: 20,
				bottom: 20
			}
		}).show().on('loaded.flexo', function () {
			$('.cip-init-chosen:not(.chzn-done)').chosen();
			$('#flexo-window-cip .flexo-footer button').addClass('cip-visible');
		});
		e.stopPropagation();
		e.preventDefault();
	});

	// Cancel button
	$('#cip-cancel').live('click', function (e) {
		var type = $(this).attr('data-window');
		if (type == 'message') $('#wp-admin-bar-cip-message a').flexo('close');
		else if (type == 'incoming') $('#wp-admin-bar-cip-incoming a').flexo('close');
	});
	// Submit button
	$('#cip-send').live('click', function (e) {
		var type = $(this).attr('data-window'),
			footer = $(this).parent('.flexo-footer'),
			loading = footer.find('.cip-loading-indicator'),
			buttons = footer.find('button');
		// Remove errors container
		$('#cip-errors').remove();
		// Run AJAX
		$.ajax({
			type: 'POST',
			url: cases_instant_post.ajaxurl,
			data: {
				action: 'cip_process_form',
				data: $('#cip-dialog-form').serialize()
			},
			success: function (data) {
				// Parse data
				data = data.split('|||');
				// Post published
				if (data[0] == 'ok') {
					// Put recieved content to box
					$('#cip-dialog').html('<div id="cip-success">' + data[1] + '</div>');
				}
				// There is erros occurred
				else {
					// Show errors
					$('#cip-dialog').prepend('<div id="cip-errors">' + data[1] + '</div>');
					// Show buttons
					buttons.addClass('cip-visible');
				}
			},
			beforeSend: function () {
				// Hide buttons
				buttons.removeClass('cip-visible');
				// Show loading animation
				loading.addClass('cip-visible');
			},
			complete: function () {
				// Hide loading animation
				loading.removeClass('cip-visible');
			},
			dataType: 'html'
		});
	});

	// Finish links: close this dialog
	$('#cip-dialog-close-message').live('click', function (event) {
		$('#wp-admin-bar-cip-message a').flexo('close');
		event.preventDefault();
	});
	$('#cip-dialog-close-incoming').live('click', function (event) {
		$('#wp-admin-bar-cip-incoming a').flexo('close');
		event.preventDefault();
	});
	// Finish links: add another message
	$('#cip-add-another-message').live('click', function (event) {
		$('#wp-admin-bar-cip-message a').click();
		event.preventDefault();
	});
	// Finish links: add another incoming case
	$('#cip-add-another-incoming').live('click', function (event) {
		$('#wp-admin-bar-cip-incoming a').click();
		event.preventDefault();
	});
	// Full message edit link
	$('a#cip-full-message').live('click', function (event) {
		// Get base edit url
		var url = cases_instant_post.full,
			title = encodeURIComponent($('#cip-message-title').val()),
			content = encodeURIComponent($('#cip-message-message').val()),
			functions = $('#cip-message-functions').val(),
			initiator = $('#cip-message-initiator').val(),
			responsible = $('#cip-message-responsible option:selected').val(),
			participants = ($('#cip-message-participants option:selected').length > 0) ? encodeURIComponent($('#cip-message-participants').val().join(',')) : '';
		// Add data to url
		url += '&csposter_title=' + title;
		url += '&content=' + content;
		url += '&csposter_function=' + functions;
		url += '&csposter_initiator=' + initiator;
		url += '&csposter_responsible=' + responsible;
		url += '&csposter_participants=' + participants;
		// Go to edit screen
		window.location.href = url;
		event.preventDefault();
	});
	// Full incoming edit link
	$('a#cip-full-incoming').live('click', function (event) {
		// Get base edit url
		var url = cases_instant_post.full,
			title = encodeURIComponent($('#cip-incoming-title').val()),
			content = encodeURIComponent($('#cip-incoming-message').val()),
			functions = $('#cip-incoming-functions option:selected').val(),
			initiator = $('#cip-incoming-initiator').val(),
			responsible = $('#cip-incoming-responsible option:selected').val(),
			participants = ($('#cip-incoming-participants option:selected').length > 0) ? encodeURIComponent($('#cip-incoming-participants').val().join(',')) : '';
		// Add data to url
		url += '&csposter_title=' + title;
		url += '&content=' + content;
		url += '&csposter_function=' + functions;
		url += '&csposter_initiator=' + initiator;
		url += '&csposter_responsible=' + responsible;
		url += '&csposter_participants=' + participants;
		// Go to edit screen
		window.location.href = url;
		event.preventDefault();
	});
});