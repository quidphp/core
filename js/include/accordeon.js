/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// accordeon
// script of behaviours for an accordeon-related widgets
(function ($, document, window) {
	
	// carousel
	// crée un carousel qui slide up ou down
	$.fn.carousel = function(arg,multi)
	{
		$(this).each(function(index, el) {
			var target = arg;
			
			if(multi === true && target instanceof jQuery)
			target = target.eq(index);
			
			$(this).on('click', function(event) {
				$(this).trigger('carousel:toggle');
			})
			.on('carousel:isClose', function(event) {
				return $(this).triggerHandler('carousel:getTarget').is(":hidden");
			})
			.on('carousel:isOpen', function(event) {
				return $(this).triggerHandler('carousel:getTarget').is(":visible");
			})
			.on('carousel:isEmpty', function(event) {
				return $(this).triggerHandler('carousel:getTarget').is(":empty");
			})
			.on('carousel:getTarget', function(event) {
				return target;
			})
			.on('carousel:setContent', function(event,html) {
				$(this).triggerHandler('carousel:getTarget').html(html);
			})
			.on('carousel:toggle', function(event) {
				$(this).trigger($(this).triggerHandler('carousel:isOpen')? 'carousel:close':'carousel:open');
			})
			.on('carousel:open', function(event) {
				$(this).addClass("active");
				$(this).triggerHandler('carousel:getTarget').stop(true,true).slideDown("fast");
			})
			.on('carousel:close', function(event) {
				$(this).removeClass("active");
				$(this).triggerHandler('carousel:getTarget').stop(true,true).slideUp("fast");
			});
		});
		
		return this;
	}
	
	
	// accordeon
	// génère un accordeon simple
	$.fn.accordeon = function(until,closeAll,wrap)
	{	
		var $this = $(this);
		
		$(this).on('click', function(event) {
			if(closeAll === true)
			$this.trigger('accordeon:close');
			
			if($(this).triggerHandler('accordeon:isOpen'))
			$(this).trigger('accordeon:close');
			
			else
			$(this).trigger('accordeon:open');
		})
		.on('accordeon:getContents', function(event) {
			return $(this).nextUntil(until);
		})
		.on('accordeon:getActiveClass', function(event) {
			return 'active';
		})
		.on('accordeon:getOpenClass', function(event) {
			return 'open';
		})
		.on('accordeon:isOpen', function(event) {
			var openClass = $(this).triggerHandler('accordeon:getOpenClass');
			return $(this).hasClass(openClass);
		})
		.on('accordeon:close', function(event) {
			var openClass = $(this).triggerHandler('accordeon:getOpenClass');
			var activeClass = $(this).triggerHandler('accordeon:getActiveClass');
			$(this).removeClass(openClass).removeClass(activeClass);
			$(this).triggerHandler('accordeon:getContents').removeClass(activeClass);
			
			if($.isStringNotEmpty(wrap))
			$(this).parent().removeClass(openClass);
		})
		.on('accordeon:open', function(event) {
			var openClass = $(this).triggerHandler('accordeon:getOpenClass');
			var activeClass = $(this).triggerHandler('accordeon:getActiveClass');
			$(this).addClass(openClass).addClass(activeClass);
			$(this).triggerHandler('accordeon:getContents').addClass(activeClass);
			
			if($.isStringNotEmpty(wrap))
			$(this).parent().addClass(openClass);
		});
		
		if($.isStringNotEmpty(wrap))
		{
			var html = "<div class='"+wrap+"'></div>";
			$(this).wrapConsecutiveSiblings(until,html);
		}
		
		return this;
	}
		
}(jQuery, document, window));