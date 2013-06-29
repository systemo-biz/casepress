// Wait DOM
jQuery(document).ready(function($) {


	// ########## Tabs ##########

	// Nav tab click
	$('#casepress-component-nav-tabs span').click(function(event) {

		// Hide tips
		$('.casepress-component-spin, .casepress-component-success-tip').hide();

		// Remove active class from all tabs
		$('#casepress-component-nav-tabs span').removeClass('nav-tab-active');

		// Hide all panes
		$('.casepress-component-nav-pane').hide();

		// Add active class to current tab
		$(this).addClass('nav-tab-active');

		// Show current pane
		$('.casepress-component-nav-pane:eq(' + $(this).index() + ')').show();

		// Save tab to cookies
		createCookie( pagenow + '_last_tab', $(this).index(), 100 );
	});

	// Auto-open tab by link with hash
	if ( strpos( document.location.hash, '#tab-' ) !== false )
		$('#casepress-component-nav-tabs span:eq(' + document.location.hash.replace('#tab-','') + ')').trigger('click');

	// Auto-open tab by cookies
	else if ( readCookie( pagenow + '_last_tab' ) != null )
		$('#casepress-component-nav-tabs span:eq(' + readCookie( pagenow + '_last_tab' ) + ')').trigger('click');


	// ########## Ajaxed form ##########

	$('#casepress-component-options-form').ajaxForm({
		beforeSubmit: function() {
			$('.casepress-component-success-tip').hide();
			$('.casepress-component-spin').fadeIn(200);
			$('.casepress-component-submit').attr('disabled', true);
		},
		success: function() {
			$('.casepress-component-spin').hide();
			$('.casepress-component-success-tip').show();
			setTimeout(function() {
				$('.casepress-component-success-tip').fadeOut(200);
			}, 2000);
			$('.casepress-component-submit').attr('disabled', false);
		}
	});


	// ########## Reset settings confirmation ##########

	$('.casepress-component-reset').click(function() {
		if (!confirm($(this).attr('title')))
			return false;
		else
			return true;
	});


	// ########## Notifications ##########

	$('.casepress-component-notification').css({
		cursor: 'pointer'
	}).live('click', function(event) {
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

		// Hide all related triggables
		$('tr.casepress-component-triggable[data-triggable^="' + name + '="]').hide();

		// Show selected triggable
		$('tr.casepress-component-triggable[data-triggable="' + name + '=' + index + '"]').show();

		$(this).change(function() {

			index = $(this).find(':selected').index();

			// Hide all related triggables
			$('tr.casepress-component-triggable[data-triggable^="' + name + '="]').hide();

			// Show selected triggable
			$('tr.casepress-component-triggable[data-triggable="' + name + '=' + index + '"]').show();
		});
	});

	// Radio
	$('tr[data-trigger-type="radio"] .casepress-component-radio-group').each(function(i) {

		var // Input data
		name = $(this).find(':checked').attr('name'),
		index = $(this).find(':checked').parent('label').parent('div').index();

		// Hide all related triggables
		$('tr.casepress-component-triggable[data-triggable^="' + name + '="]').hide();

		// Show selected triggable
		$('tr.casepress-component-triggable[data-triggable="' + name + '=' + index + '"]').show();

		$(this).find('input:radio').each(function(i2) {

			$(this).change(function() {

				// Hide all related triggables
				$('tr.casepress-component-triggable[data-triggable^="' + name + '="]').hide();

				// Show selected triggable
				$('tr.casepress-component-triggable[data-triggable="' + name + '=' + i2 + '"]').show();
			});
		});
	});


	// ########## Clickuts ##########

	$(document).live('click', function(event) {
		if ( $('.casepress-component-prevent-clickout:hover').length == 0 )
			$('.casepress-component-clickout').hide();
	});


	// ########## Icon picker ##########

	// Textfield focus
	$('.casepress-component-icon-picker-value').focus(function(event) {

		event.stopPropagation();

		// Show dropdown
		$(this).parent('.casepress-component-icon-picker').children('.casepress-component-icon-picker-dropdown').show();
	});

	// Textfield blur
	$('.casepress-component-icon-picker-value').blur(function(event) {

		event.stopPropagation();

		var dropdown = jQuery(this).parent('.casepress-component-icon-picker').children('.casepress-component-icon-picker-dropdown');

		// Hide dropdown
		setTimeout(function() {
			dropdown.hide();
		}, 300);
	});

	// Preview icon
	$('.casepress-component-icon-picker-preview').click(function(event) {

		event.stopPropagation();

		$('.casepress-component-icon-picker-dropdown').hide();

		// Show dropdown
		$(this).parent('.casepress-component-icon-picker').children('.casepress-component-icon-picker-dropdown').toggle();
	});

	// Select icon
	$('.casepress-component-icon-picker-dropdown img').click(function(event) {

		event.stopPropagation();

		// Copy image src to textfield
		$(this).parent('.casepress-component-icon-picker-dropdown').parent('.casepress-component-icon-picker').children('.casepress-component-icon-picker-value').val($(this).attr('src'));

		// Copy image src to preview
		$(this).parent('.casepress-component-icon-picker-dropdown').hide().parent('.casepress-component-icon-picker').children('.casepress-component-icon-picker-preview').attr('src',$(this).attr('src'));
	});

	// ########## Upload buttons ##########

	$('.casepress-component-icon-picker-upload, .casepress-component-upload-button').click(function(event) {

		event.stopPropagation();

		// Define upload field
		window.nb_current_upload = $(this).attr('rel');

		// Show thickbox with uploader
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');

		// Prevent click
		event.preventDefault();
	});

	window.send_to_editor = function(html) {
		var url = jQuery('img',html).attr('src');

		// Update upload textfield and icon-picker value
		$('#casepress-component-field-' + window.nb_current_upload).val(url);

		// Update icon-picker preview
		$('.casepress-component-icon-picker-preview[data-id="' + window.nb_current_upload + '"]').attr('src',url);

		// Hide icon-picker dropdown
		$('.casepress-component-icon-picker-dropdown').hide();

		// Hide thickbox
		tb_remove();
	}


	// ########## Color picker ##########

	$('.casepress-component-color-picker-preview').each(function(index) {
		//var id = $(this).attr('data-picker-id');

		$(this).farbtastic('.casepress-component-color-picker-value:eq(' + index + ')');

		$('.casepress-component-color-picker-value:eq(' + index + ')').focus(function(event) {
			$('.casepress-component-color-picker-preview').hide();
			$('.casepress-component-color-picker-preview:eq(' + index + ')').show();
		});
	});


	// ########## Fillers ##########

	// Edit code link
	$('.casepress-component-edit-filler').live('click',function(event) {
		$(this).parent('.casepress-component-filler-menu').parent('.casepress-component-filler').toggleClass('casepress-component-filler-editing');
		event.preventDefault();
	});

	// Remove filler link
	$('.casepress-component-remove-filler').live('click',function(event) {
		if ( confirm($(this).attr('title')) )
			$(this).parent('.casepress-component-filler-menu').parent('.casepress-component-filler').remove();
	});

	// Generate filler link
	$('.casepress-component-generate-filler-link').live('click',function(event) {

		var // Field name value
		field = $(this).parent('.casepress-component-filler-menu').parent('.casepress-component-filler').find('input:text:first').val();

		// If field name is filled
		if ( field != '' ) {

			// Replace field names in links
			$('#casepress-component-show-link').html( $('#casepress-component-link-template').clone().html().replace(new RegExp("%FIELD%", 'g'), field) );

			// Show lightbox with links
			tb_show( $(this).attr('title'), '#TB_inline?inlineId=casepress-component-show-link' );
		}

		// Field name is not filled
		else {

			// Required message
			alert( $(this).attr('data-required-message') );
		}

		// Prevent click
		event.preventDefault();
	});

	// Links textareas
	$('.casepress-component-generated-link').live('focus',function() {
		$(this).select();
	});

	// Run codemirror
	$('#casepress-component-active-fillers .casepress-component-filler textarea').each(function(i) {

		var textarea = $(this);

		var custom_editor = CodeMirror.fromTextArea(document.getElementById($(this).attr('id')), {
			lineNumbers: true,
			lineWrapping: true,
			onChange: function() {
				textarea.html(custom_editor.getValue());
			}
		});
	});

	// Remove template from form
	$('#casepress-component-filler-template').appendTo(document.body);

	// Add filler button
	$('#casepress-component-add-filler').live('click',function(event) {

		var // Template data
		index = $('#casepress-component-active-fillers .casepress-component-filler').length,
		template = $('#casepress-component-filler-template').html(),
		insert = template.replace(new RegExp("__INDEX__", 'g'), index),
		id = $(insert).find('textarea:first').attr('id');

		// Insert new filler
		$('#casepress-component-active-fillers').prepend(insert);

		var custom_editor = CodeMirror.fromTextArea(document.getElementById(id), {
			lineNumbers: true,
			lineWrapping: true,
			onChange: function() {
				$('textarea#' + id).html(custom_editor.getValue());
			}
		});

		// Focus first field
		$('#casepress-component-active-fillers input:text:first').focus();

		// Prevent click
		event.preventDefault();
	});

	// Expand all button
	$('#casepress-component-expand-fillers').live('click', function(event) {

		// Add editing class
		$('#casepress-component-active-fillers .casepress-component-filler').addClass('casepress-component-filler-editing');

		// Hide this button and show other
		$(this).hide();
		$('#casepress-component-collapse-fillers').show();

		// Prevent click
		event.preventDefault();
	});

	// Collapse all button
	$('#casepress-component-collapse-fillers').live('click', function(event) {

		// Add editing class
		$('#casepress-component-active-fillers .casepress-component-filler').removeClass('casepress-component-filler-editing');

		// Hide this button and show other
		$(this).hide();
		$('#casepress-component-expand-fillers').show();

		// Prevent click
		event.preventDefault();
	});
});


// ########## Cookie utilities ##########

function createCookie(name,value,days){
	if(days){
		var date=new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires="; expires="+date.toGMTString()
	}else var expires="";
	document.cookie=name+"="+value+expires+"; path=/"
}
function readCookie(name){
	var nameEQ=name+"=";
	var ca=document.cookie.split(';');
	for(var i=0;i<ca.length;i++){
		var c=ca[i];
		while(c.charAt(0)==' ')c=c.substring(1,c.length);
		if(c.indexOf(nameEQ)==0)return c.substring(nameEQ.length,c.length)
	}
	return null
}
function eraseCookie(name){
	createCookie(name,"",-1)
};


// ########## Strpos tool ##########

function strpos( haystack, needle, offset) {
	var i = haystack.indexOf( needle, offset );
	return i >= 0 ? i : false;
}