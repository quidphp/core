$(document).ready(function() {
	
	// login
	$(this).on('route:login', function() {
		$(this).trigger('route:nobodyCommon');
	})
	
	// resetPassword
	.on('route:resetPassword', function(event) {
		$(this).trigger('route:nobodyCommon');
	})
	
	// register
	.on('route:register', function(event) {
		$(this).trigger('route:nobodyCommon');
	})
	
	// common
	.on('route:nobodyCommon', function(event) {
		var browscap = $(this).find(".nobody .browscap");
		var form = $(this).find(".nobody form");
		
		form.formValidate().find("[data-required],[data-pattern]").focusFirst();
		
		if(!$.areCookiesEnabled())
		browscap.find(".cookie").show();
	});
});