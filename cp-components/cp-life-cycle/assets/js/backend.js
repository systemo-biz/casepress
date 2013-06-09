jQuery(document).ready(function ($) {

	// Process fields
	$('.lfc-field.lfc-loading').each(function (i) {
		var $field = $(this),
			$value = $field.children('span'),
			$s2 = $value.children('input:hidden'),
			selection = $s2.val().split(','),
			field = $s2.attr('name');

		// Load S2 data
		$.ajax({
			url: ajaxurl,
			type: 'post',
			dataType: 'json',
			data: {
				action: 'lfc_get_s2',
				field: field
			},
			beforeSend: function () {
				// Show loading animation
				$field.addClass('lfc-loading');
			},
			success: function (data) {
				// Init S2
				$s2.select2({
					data: data,
					multiple: true,
					createSearchChoice: function (term, data) {
						return ($(data).filter(function () {
							return this.text.localeCompare(term) === 0;
						}).length === 0) ? {
							id: term,
							text: term
						} : undefined;
					},
					initSelection: function (element, callback) {
						$($s2.data('selection').split(';')).each(function (i) {
							selection[i] = {
								id: this.split(':')[0],
								text: this.split(':')[1]
							};
						});
						if (selection.length > 0) callback(selection);
					}
				});
				$s2.select2('container').find('ul.select2-choices').sortable({
					containment: 'parent',
					start: function () {
						$s2.select2('onSortStart');
					},
					update: function () {
						$s2.select2('onSortEnd');
					}
				});
				// Hide loading animation
				$field.removeClass('lfc-loading');
			}
		});
	});

	// Set as default
	$('.lfc-set-default').click(function (e) {
		// Prepare data
		var $link = $(this),
			$field = $link.parent('span').parent('.lfc-field');
		// Send request
		$.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				action: 'lfc_set_default',
				post_id: $link.data('post-id')
			},
			dataType: 'html',
			beforeSend: function () {
				// Show loading animation
				$field.addClass('lfc-loading');
			},
			success: function (data) {
				// Set link html
				$link.replaceWith(data);
				// Hide loading animation
				$field.removeClass('lfc-loading');
			}
		});
		e.preventDefault();
	});

});