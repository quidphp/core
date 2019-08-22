(function ($, document, window) {
	
	// internal
	// expression qui retourne vrai si l'uri est interne
	$.expr[':'].internal = function(obj,index,meta,stack)
	{
		var r = false;
		
		if($(obj).is("[href]"))
		r = $.isUriInternal($(obj).attr("href"));
		
		return r;
	};
	
	
	// external
	// expression qui retourne vrai si l'uri est externe
	// ne tient pas compte de target _blank
	$.expr[':'].external = function(obj,index,meta,stack)
	{
		var r = false;
		
		if($(obj).is("[href]"))
		r = $.isUriExternal($(obj).attr("href"));
		
		return r;
	};
	
}(jQuery, document, window));