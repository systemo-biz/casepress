jQuery(document).ready(function($) {

	$('.flexo-iframe').live('click', function (e) {
		var $el = $(this),
		is_mobile = typeof $.browser.mobile === 'boolean' && $.browser.mobile;
		if (!is_mobile) {
			$el.flexo({
				type: 'iframe',
				title: $el.attr('title'),
				content: $el.attr('href'),
				width: '90%',
				height: '90%'
			}).show();
			e.preventDefault();
		}
	});

});