// Wait DOM
jQuery(document).ready(function($) {


	// ########## Tabs ##########

	// Nav tab click
	$('#cp-component-tabs span').click(function(event) {
		// Hide tips
		$('.cp-component-spin, .cp-component-success-tip').hide();
		// Remove active class from all tabs
		$('#cp-component-tabs span').removeClass('nav-tab-active');
		// Hide all panes
		$('.cp-component-pane').hide();
		// Add active class to current tab
		$(this).addClass('nav-tab-active');
		// Show current pane
		$('.cp-component-pane:eq(' + $(this).index() + ')').show();
		// Save tab to cookies
		cpcomponentCreateCookie( pagenow + '_last_tab', $(this).index(), 365 );
	});

	// Auto-open tab by link with hash
	if ( cpcomponentStrpos( document.location.hash, '#tab-' ) !== false )
		$('#cp-component-tabs span:eq(' + document.location.hash.replace('#tab-','') + ')').trigger('click');
	// Auto-open tab by cookies
	else if ( cpcomponentReadCookie( pagenow + '_last_tab' ) != null )
		$('#cp-component-tabs span:eq(' + cpcomponentReadCookie( pagenow + '_last_tab' ) + ')').trigger('click');
	// Open first tab by default
	else
		$('#cp-component-tabs span:eq(0)').trigger('click');


	// ########## Ajaxed form ##########

	$('#cp-component-options-form').ajaxForm({
		beforeSubmit: function() {
			$('.cp-component-success-tip').hide();
			$('.cp-component-spin').fadeIn(200);
			$('.cp-component-submit').attr('disabled', true);
		},
		success: function() {
			$('.cp-component-spin').hide();
			$('.cp-component-success-tip').show();
			setTimeout(function() {
				$('.cp-component-success-tip').fadeOut(200);
			}, 2000);
			$('.cp-component-submit').attr('disabled', false);
		}
	});


	// ########## Reset settings confirmation ##########

	$('.cp-component-reset').click(function() {
		if (!confirm($(this).attr('title')))
			return false;
		else
			return true;
	});


	// ########## Notifications ##########

	$('.cp-component-notification').css({
		cursor: 'pointer'
	}).on('click', function(event) {
		$(this).fadeOut(100, function() {
			$(this).remove();
		});
	});


	// ########## Triggables ##########

	// Select
	$('tr[data-trigger-type="select"] select').each(function(i) {

		var // Input data
		name = $(this).attr('name'),
		index = $(this).find(':selected').index();

		//alert( name + ' - ' + index );

		// Hide all related triggables
		$('tr.cp-component-triggable[data-triggable^="' + name + '="]').hide();

		// Show selected triggable
		$('tr.cp-component-triggable[data-triggable="' + name + '=' + index + '"]').show();

		$(this).change(function() {

			index = $(this).find(':selected').index();

			// Hide all related triggables
			$('tr.cp-component-triggable[data-triggable^="' + name + '="]').hide();

			// Show selected triggable
			$('tr.cp-component-triggable[data-triggable="' + name + '=' + index + '"]').show();
		});
	});

	// Radio
	$('tr[data-trigger-type="radio"] .cp-component-radio-group').each(function(i) {

		var // Input data
		name = $(this).find(':checked').attr('name'),
		index = $(this).find(':checked').parent('label').parent('div').index();

		// Hide all related triggables
		$('tr.cp-component-triggable[data-triggable^="' + name + '="]').hide();

		// Show selected triggable
		$('tr.cp-component-triggable[data-triggable="' + name + '=' + index + '"]').show();

		$(this).find('input:radio').each(function(i2) {

			$(this).change(function() {

				alert();

				// Hide all related triggables
				$('tr.cp-component-triggable[data-triggable^="' + name + '="]').hide();

				// Show selected triggable
				$('tr.cp-component-triggable[data-triggable="' + name + '=' + i2 + '"]').show();
			});
		});
	});


	// ########## Clickouts ##########

	$(document).on('click', function(event) {
		if ( $('.cp-component-prevent-clickout:hover').length == 0 )
			$('.cp-component-clickout').hide();
	});


	// ########## Upload buttons ##########

	$('.cp-component-upload-button').click(function(event) {

		// Define upload field
		window.cpcomponent_current_upload = $(this).attr('rel');

		// Show thickbox with uploader
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');

		// Prevent click
		event.preventDefault();
	});

	window.send_to_editor = function(html) {

		var url;

		if ( jQuery(html).filter('img:first').length > 0 )
			url = jQuery(html).filter('img:first').attr('src');
		else
			url = jQuery(html).filter('a:first').attr('href');

		// Update upload textfield value
		$('#cp-component-field-' + window.cpcomponent_current_upload).val(url);

		// Hide thickbox
		tb_remove();
	}


	// ########## Color picker ##########

	$('.cp-component-color-picker-preview').each(function(index) {
		$(this).farbtastic('.cp-component-color-picker-value:eq(' + index + ')');
		$('.cp-component-color-picker-value:eq(' + index + ')').focus(function(event) {
			$('.cp-component-color-picker-preview').hide();
			$('.cp-component-color-picker-preview:eq(' + index + ')').show();
		});
	});

});


// ########## Cookie utilities ##########

function cpcomponentCreateCookie(name,value,days){
	if(days){
		var date=new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires="; expires="+date.toGMTString()
	}else var expires="";
	document.cookie=name+"="+value+expires+"; path=/"
}
function cpcomponentReadCookie(name){
	var nameEQ=name+"=";
	var ca=document.cookie.split(';');
	for(var i=0;i<ca.length;i++){
		var c=ca[i];
		while(c.charAt(0)==' ')c=c.substring(1,c.length);
		if(c.indexOf(nameEQ)==0)return c.substring(nameEQ.length,c.length)
	}
	return null
}


// ########## Strpos tool ##########

function cpcomponentStrpos( haystack, needle, offset) {
	var i = haystack.indexOf( needle, offset );
	return i >= 0 ? i : false;
}