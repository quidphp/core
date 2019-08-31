/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// somebody
// script of behaviours for all pages where the user is logged in the CMS
$(document).ready(function() {

	// changePassword
	$(this).on('jsBox:dialogAccountChangePassword', function(event,jsBox) {
		var form = jsBox.find("form");
		form.formValidate().find("[data-required],[data-pattern]").focusFirst();
	});
});