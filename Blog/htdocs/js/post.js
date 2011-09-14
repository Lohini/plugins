
function formResponse(payload) {
	jQuery.nette.success(payload, function() {
		var $form = $('form[id*="commentForm"]');
		var $errs = $form.find('span.error');

		if ($errs.length) {
			$errs.hide().fadeIn();
		} else {
			$('#flashes').hide();
			$form.append('<div id="commentFormHide"></div>');
			$('#commentFormHide').hide().fadeIn(800, function() {
				$('#flashes').center().fadeIn(1000).delay(2500).fadeOut(1000);
			});
		}
	});
}


function sendForm() {
	var $form = $('form[id*="commentForm"]');
	var $errs = $form.find('span.error');

	$form.find('input[type="submit"]').after('<span class="spinner"></span>');

	if ($errs.length) {
		$errs.fadeOut(400, function() {
			// check if other elements are still animating
			if ($form.find('span.error:animated').length == 1) {
				$form.ajaxSubmit(formResponse);
			}
		});
	} else {
		$form.ajaxSubmit(formResponse);
	}
}


$(function() {

	// Submit button clicked
	$('form[id*="commentForm"]').find('input[type="submit"]').live('click', (function(e) {
		e.preventDefault();
		sendForm();
	}));

});
