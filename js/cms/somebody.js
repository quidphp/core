$(document).ready(function() {

	// changePassword
	$(this).on('jsBox:dialogAccountChangePassword', function(event,jsBox) {
		var form = jsBox.find("form");
		form.formValidate().find("[data-required],[data-pattern]").focusFirst();
	});
});