$(document).ready(function() {
	
	// home
	$(this).on('route:home', function() {
		
		var form = $(this).find(".home form");
		var field = form.find("[data-required],[data-pattern]");
		
		if(form.length)
		form.formAjaxPopup(field);
	});
});