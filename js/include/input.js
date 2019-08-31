/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// input
// script containing logic for some simple inputs
(function ($, document, window) {
	
	// searchGeneralInput
	// gère les comportements pour un input search
	$.fn.searchGeneralInput = function(button)
	{
		$(this).block('change').enterBlock().timeout('keyup').fieldValidateFull()
		.on('enter:blocked', function(event) {
			$(this).trigger('change');
		})
		.on('change', function() {
			$(this).trigger('redirect');
		})
		.on('keyup:onTimeout', function() {
			$(this).trigger('pattern');
		})
		.on('redirect', function() {
			var val = $(this).inputValue(true);
			var href = $(this).data("href");
			var current = $(this).data("current") || '';
			
			if($(this).triggerHandler('isValid') && val !== current)
			{
				$(this).trigger('block');
				
				if($.isStringNotEmpty(val))
				{
					var char = $(this).data("char");
					val = encodeURIComponent(val);
					href += "?"+char+"="+val;
				}
				
				$(document).trigger('navigation:push',[href])
			}
		});
		
		if(button instanceof jQuery && button.length)
		{
			var $this = $(this);
			button.on('click', function(event) {
				$this.trigger('change');
			});
		}
		
		return this;
	}
	
	
	// numericGeneralInput
	// gère les comportements pour un input numérique comme page ou limit
	$.fn.numericGeneralInput = function()
	{
		$(this).block('change').timeout('keyup').fieldValidateFull()
		.on('focus', function() {
			$(this).val("");
		})
		.on('invalid', function() {
			var current = String($(this).data("current"));
			$(this).val(current);
			$(this).trigger('valid');
		})
		.on('change', function() {
			if(!$(this).inputValue(true))
			$(this).trigger('invalid');
			$(this).trigger('redirect');
		})
		.on('keyup:onTimeout', function() {
			$(this).trigger('change');
		})
		.on('redirect', function() {
			var val = $(this).inputValue(true);
			var current = String($(this).data("current"));
			var max = $(this).data('max');
			
			if($(this).triggerHandler('isValid') && val !== current)
			{
				if($.isNumeric(max) && val > max)
				val = max;
				
				var href = $(this).dataHrefReplaceChar(val);
				if($.isStringNotEmpty(href))
				{
					$(this).trigger('block');
					$(document).trigger('navigation:push',[href])
				}
			}
		});
		
		return this;
	}
	
}(jQuery, document, window));