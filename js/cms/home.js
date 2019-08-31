/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// home
// script of behaviours for the homepage of the CMS
$(document).ready(function() {
	
	// home
	$(this).on('route:home', function() {
		
		var form = $(this).find(".home form");
		var field = form.find("[data-required],[data-pattern]");
		
		if(form.length)
		form.formAjaxPopup(field);
	});
});