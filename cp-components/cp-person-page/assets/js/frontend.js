jQuery(document).ready(function($) {

	/**
	 * Load default table
	 */
	window.setTimeout(function() {
		$('#ckpp-box-dossier .cases-box-actions button.btn-primary:first').removeClass('btn-primary').click();
	}, 1000);

	/**
	 * Dossier, person
	 */
	$('#ckpp-box-dossier .cases-box-actions button').on('click', function(event) {

		// Group button
		var is_groupby = $(this).is('#ckpp-groupby');

		// Check that button is inactive
		if ( $(this).is('.btn-primary') && $(this).is(':not(#ckpp-groupby)') )
			return false;

		// Change active button
		if ( is_groupby ) {
			$(this).toggleClass('btn-primary');
			if ( $(this).data('groupby') === true )
				$(this).data('groupby', false)
			else
				$(this).data('groupby', true);
		}

		else {
			$(this).parent('.btn-group').children('button').removeClass('btn-primary');
			$(this).addClass('btn-primary');
		}

		var // Prepare variables
		box = $('#ckpp-box-dossier'),
		container = $('#ckpp-box-dossier .cases-box-content'),
		loading_text = '<div id="ckpp-box-dossier-loading-text">' + box.data('loading-text') + '</div>',
		meta_query = $('#ckpp-box-dossier-roles .btn-primary').data('role') + ':' + $('#ckpp-box-dossier').data('person'),
		tax_query = $('#ckpp-box-dossier-states .btn-primary').data('state'),
		group = $('#ckpp-groupby').data('groupby');

		// Disable buttons
		$('#ckpp-box-dossier .cases-box-actions button').attr('disabled', true);

		// Empty container
		container.html(loading_text);

		// Open box forcibly
		box.removeClass('cases-box-closed').addClass('cases-box-open');

		// Add loading animation
		container.addClass('ckpp-loading-boxed');

		// Insert person data into form
		$.ajax({
			type: 'POST',
			url: cp_ajax.ajaxurl,
			data: {
				action: 'get_dossier_datatable',
				meta: meta_query,
				tax: tax_query,
				group: group
			},
			success: function(data) {

				// Remove loading animation
				container.removeClass('ckpp-loading-boxed');

				// Put new HTML into container
				container.html(data);
			},
			complete: function() {

				// Final removing loading animation
				container.removeClass('ckpp-loading-boxed');

				// Unblock buttons
				$('#ckpp-box-dossier .cases-box-actions button').attr('disabled', false);
			},
			dataType: 'html'
		});

		// Prevent click
		event.preventDefault();
	});

});