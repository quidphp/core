/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// dimension
// script with a function related to window dimension
(function ($, document, window) {
	
	// dynamicHeight
	$.fn.dynamicHeight = function() 
	{
		$(this).each(function(index, el) {
			var $this = $(this);
			$(window).on('resize', function(event) {
				$this.trigger('dynamicHeight:refresh');
			});
			
			$(this).on('dynamicHeight:refresh', function(event) {
				$(this).css("height","auto");
				var height = $(this).height();
				$(this).css("height",height);
				
			}).trigger('dynamicHeight:refresh');
		});
		return this;
	}
	
}(jQuery, document, window));