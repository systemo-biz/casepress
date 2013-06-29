jQuery(document).ready(function($) {

	nb_init_ticker();

});

/*
 * Init news ticker
 */
function nb_init_ticker() {

	var // Define variables
	ticker = jQuery('#news-bar-ticker'),
	items = ticker.find('.news-bar-item'),
	delay = parseInt( ticker.attr('data-news-bar-delay') ),
	speed = parseInt( ticker.attr('data-news-bar-speed') );

	// Check number of items
	if ( items.length > 1 ) {

		// Hide all tweets except first
		items.css({
			position: 'absolute',
			left: '25px',
			top: '0px'
		}).not(':first').hide();

		// Run timer
		var timer = setInterval( function() {

			var // Get current and next item index
			current = parseInt( ticker.find('.news-bar-item:visible').index() ),
			next = ( ( current + 1 ) >= items.length ) ? 0 : current + 1;

			//alert( current + ' - ' + next );

			// Hide current tweet
			items.eq(current).fadeOut(speed);

			// Show next tweet
			items.eq(next).delay( Math.round( speed / 2 ) ).fadeIn(speed);
		}, delay );
	}
}