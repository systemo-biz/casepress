// Wait DOM
jQuery(document).ready(function($) {

	// Init chosen selects
	$('select#parent, .ctmeta-init-chosen').css({
		width: '95%'
	}).chosen({
		no_results_text: $('.ctmeta-init-chosen:first').attr('data-no-results-text'),
		allow_single_deselect: true
	});

	// Init datepicker
	$('.ctmeta-init-datepicker').datepicker({
		dateFormat: 'yy-mm-dd'
	});

	$('#ctmeta-deadline-priority-toggle').on('change', function() {
		if ( $(this).is(':checked') )
			$('#ctmeta-deadline-priorities').removeClass('gndev-plugin-hidden');
		else
			$('#ctmeta-deadline-priorities').addClass('gndev-plugin-hidden');
	});
});