jQuery(document).ready( function($) {

	$('.cases-box-content').on('click', 'input[type="checkbox"]', function() {
		var checked = $(this).closest('tbody').find(':checkbox').filter(':checked'),
			labels = $(this).closest('tr').find('a[href*="/label/"]');

		$('#link-labels').toggle( checked.length > 0 );

		// alert( labels.length );
		if ( $(this).prop('checked') ) {
			labels.each( function() {
				var href = $(this).attr('href');

				if ( 0 == $('.tagchecklist a[href="' + href + '"]').length )
					$('.tagchecklist').append( $(this).clone(), ' ' );
			});
		} else {
			labels.each( function() {
				var href = $(this).attr('href');

				if ( 0 == checked.closest('tr').find('a[href="' + href + '"]').length )
					$('.tagchecklist a[href="' + href + '"]').remove();
			});
		}
	});

	$('.ckpp-label-box').on('click', '.tagadd', function(event) {
		var checked = $('.cases-box-content').find(':checkbox').filter(':checked');

		event.preventDefault();

/*
		if ( 0 == checked.length ) {
			$.post( cwlAjax.ajaxurl, {
				action: 'create_label',
				labels: $('#new-tag-labels').val()
			}, function(data) {
				$('#new-tag-labels').val('');
				$('.ckpp-label-list').html(data);
			});
		} else {
*/
			var posts = [];
			checked.closest('tr').find('td:eq(1)').each( function() {
				posts.push( $(this).text() );
			});

			$.post( cwlAjax.ajaxurl, {
				action: 'add_labels',
				labels: $('#new-tag-labels').val(),
				posts: posts
			}, function(data) {
				$('#new-tag-labels').val('');
				$('.ckpp-label-list').html(data);
			});
//		}

	});

});
