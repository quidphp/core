/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// scroll
// script containing logic related to window scrolling
(function ($, document, window) {
	
	// anchorScroll
	// gère les liens avec ancrage (changement de hash)
	$.fn.anchorScroll = function()
	{
		$(this).on('click', function(event) {
			if($.isSamePathQuery($(this).prop('href')))
			{
				var hash = this.hash;
				$.windowScrollToHash(hash,event);
				
				event.preventDefault();
				return false;
			}
		})
		.on('anchorScroll:setSelected', function(event,hash) {
			if(this.hash === hash)
			$(this).addClass('selected');
			else
			$(this).removeClass('selected');
		});
		
		return this;
	}
	

	// hashScrollTarget
	// gère un block comme target pour un hash scroll, chaque bloc est lié à un hash
	$.fn.hashScrollTarget = function()
	{
		var $this = $(this);
		
		$(this).arrowCatch(true).on('hashScrollTarget:getHash', function(event) {
			return $.formatHash($(this).data("id"),true);
		})
		.on('hashScrollTarget:getFragment', function(event) {
			return $.formatHash($(this).data("id"),false);
		})
		.on('hashScrollTarget:getIndex', function(event) {
			return $this.index($(this));
		})
		.on('hashScrollTarget:isFirst', function(event) {
			return ($(this).triggerHandler("hashScrollTarget:getIndex") === 0)? true:false;
		})
		.on('hashScrollTarget:isCurrent', function(event) {
			return ($(this).hasClass('current'))? true:false;
		})
		.on('hashScrollTarget:getPrev', function(event) {
			var index = $.indexer('prevStrict',$(this),$this);
			if($.isNumeric(index))
			return $this.eq(index);
		})
		.on('hashScrollTarget:getNext', function(event) {
			var index = $.indexer('nextStrict',$(this),$this);
			if($.isNumeric(index))
			return $this.eq(index);
		})
		.on('hashScrollTarget:enter',function(event) {
			$this.removeClass('current').removeData('hashScrollTarget:current');
			$(this).addClass('current').data('hashScrollTarget:current',true);
		})
		.on('arrowUp:catched', function(event) {
			if($(this).triggerHandler('hashScrollTarget:isCurrent'))
			{
				var prev = $(this).triggerHandler('hashScrollTarget:getPrev');
				if(prev != null)
				$.windowScrollToHash(prev);
			}
		})
		.on('arrowDown:catched', function(event) {
			if($(this).triggerHandler('hashScrollTarget:isCurrent'))
			{
				var next = $(this).triggerHandler('hashScrollTarget:getNext');
				if(next != null)
				$.windowScrollToHash(next);
			}
		});
		
		return this;
	}
	
	
	// windowHashScroll
	// gère le scroll sur window dans un contexte ou la page est composé de blocs liés à des hash
	$.windowHashScroll = function(type)
	{
		type = type || 'scroll';
		
		$(window).hashchange().on(type,function(event) {
			$(this).trigger('windowHashScroll:change',[true]);
		})
		.on('mousewheel DOMMouseScroll wheel MozMousePixelScroll', function(event) {
			if($(this).triggerHandler('windowHashScroll:isScrolling'))
			{
				event.stopImmediatePropagation();
				event.preventDefault();
				return false;
			}
		})
		.on('hash:change', function(event,fragment,sourceEvent) {
			if(sourceEvent)
			$.windowScrollToHash(fragment);
		})
		.on('windowHashScroll:getTargetAttr', function(event) {
			return 'data-id';
		})
		.on('windowHashScroll:canScroll', function(event) {
			return ($(this).triggerHandler('windowHashScroll:isScrolling') === false && $(document).triggerHandler('navigation:isActive') === false)? true:false;
		})
		.on('windowHashScroll:isScrolling', function(event) {
			return ($(this).data('hashScroll:animate') === true)? true:false;
		})
		.on('windowHashScroll:setTarget', function(event,target) {
			$(this).data('windowHashScroll:target',target);
		})
		.on('windowHashScroll:getTarget', function(event,target) {
			return $(this).data('windowHashScroll:target');
		})
		.on('windowHashScroll:getCurrentTarget', function(event,target) {
			var r = null;
			var current = $(this).triggerHandler('windowHashScroll:getTarget').filter(function() {
				return ($(this).data('hashScrollTarget:current') === true)? true:false;
			});
			
			if(current.length === 1)
			r = current;
			
			return r;
		})
		.on('windowHashScroll:findTarget', function(event,hash) {
			var r = null;
			var target = $(this).triggerHandler('windowHashScroll:getTarget');
			var attr = $(this).triggerHandler('windowHashScroll:getTargetAttr');
			if(target instanceof jQuery && $.isStringNotEmpty(hash) && $.isStringNotEmpty(attr))
			{
				var find = target.filter("["+attr+"='"+hash+"']");
				if(find.length === 1)
				r = find;
			}
			
			return r;
		})
		.on('windowHashScroll:getScrollTarget', function(event) {
			var r = null;
			var scrollTop = $(this).scrollTop();
			var windowHeight = $(this).height();
			var documentHeight = $(document).height();
			var windowHeightRatio = (windowHeight / 2);
			var target = $(this).triggerHandler('windowHashScroll:getTarget');
			
			if(target instanceof jQuery && target.length)
			{
				if(scrollTop <= windowHeightRatio)
				r = target.first();
				
				else
				{
					target.each(function(index) {
						var offset = $(this).offset().top;
						var height = $(this).heightWithPadding();
						var commit = false;
						
						if(scrollTop >= (offset - windowHeightRatio))
						{
							if(scrollTop < ((offset + height) - windowHeightRatio))
							commit = true;
						}
						
						if(commit === true)
						{
							r = $(this);
							return false;
						}
					});
				}
				
				if(r === null && target.length > 1)
				{
					if(scrollTop >= (documentHeight - windowHeight))
					r = target.last();
				}
			}
			
			return r;
		})
		.on('windowHashScroll:change', function(event,fromScroll) {

			if($(this).triggerHandler('windowHashScroll:canScroll'))
			{
				var currentTarget = $(this).triggerHandler('windowHashScroll:getScrollTarget');
				
				if(currentTarget instanceof jQuery)
				{
					var isFirst = currentTarget.triggerHandler('hashScrollTarget:isFirst');
					var hash = currentTarget.triggerHandler('hashScrollTarget:getHash');
					hash = ($.isStringNotEmpty(hash))? hash:'';
					
					if(hash !== location.hash)
					{
						$(this).removeData('windowHashScroll:noScroll');
						var oldHash = $.formatHash(location.hash,false);
						var old = $(this).triggerHandler('windowHashScroll:findTarget',[oldHash]);
						
						if(old !== null && old.triggerHandler('hashScrollTarget:isCurrent'))
						old.trigger('hashScrollTarget:leave');
						
						if(fromScroll === true && ($.isTouch() || $.isResponsive()))
						$(this).data('windowHashScroll:noScroll',true);
						
						if(isFirst === false || location.hash !== '')
						location.hash = hash;
						
						if(!currentTarget.triggerHandler('hashScrollTarget:isCurrent'))
						currentTarget.trigger('hashScrollTarget:enter');
						
						return false;
					}
				}
			}
		});
				
		$(document).on('click', "a[href*='#']", function(event) {
			if(!$(window).triggerHandler('windowHashScroll:canScroll'))
			{
				event.preventDefault();
				event.stopImmediatePropagation();
				return false;
			}
		});
		
		return this;
	}
	
	
	// windowScrollToHash
	// permet de scroller la window jusqu'au bloc du hash donné en argument
	$.windowScrollToHash = function(hash,event)
	{
		var r = false;
		var win = $(window);
		
		if(hash instanceof jQuery)
		hash = hash.attr(win.triggerHandler('windowHashScroll:getTargetAttr'));
		
		if(win.triggerHandler('windowHashScroll:canScroll'))
		{
			hash = $.formatHash(hash,false);
			var scrollTop = win.scrollTop();
			var top = null;
			var target = null;
			var newHash = null;
			var source = $("html,body");
			var current = win.triggerHandler('windowHashScroll:getCurrentTarget');
			win.removeData('hashScroll:animate');
			var noScroll = win.data('windowHashScroll:noScroll');
			win.removeData('windowHashScroll:noScroll');
			
			var callback = function() 
			{
				if(current && current.length === 1 && current.triggerHandler('hashScrollTarget:isCurrent'))
				current.trigger('hashScrollTarget:leave');
				
				if(location.hash !== newHash)
				location.hash = newHash;
				
				if(!target.triggerHandler('hashScrollTarget:isCurrent'))
				target.trigger('hashScrollTarget:enter');
			}
			
			if($.isStringNotEmpty(hash))
			{
				target = win.triggerHandler('windowHashScroll:findTarget',[hash]);
				if(target !== null)
				{
					top = target.offset().top;
					newHash = hash;
				}
			}
			
			else
			{
				target = source.first();
				top = 0;
				newHash = "";
			}
			
			if($.isNumeric(top) && top !== scrollTop && $.isString(newHash) && target)
			{
				r = true;
				var isFirst = target.triggerHandler('hashScrollTarget:isFirst');
				
				if(event != null)
				event.preventDefault();
				
				if(noScroll === true || (isFirst === true && scrollTop === 0))
				callback.call(this);
				else
				{
					win.data('hashScroll:animate',true);
					source.stop(true,true).animate({scrollTop: top}, 1000).promise().done(callback).done(function() {
						win.removeData('hashScroll:animate');
					});
				}
			}
		}
		
		return r;
	}
	
	
	// backToTop
	// element qui ramene en haut de page
	$.fn.backToTop = function()
	{
		$(this).scrollChange().on('click', function(event) {
			$("html,body").stop(true,true).animate({scrollTop: 0}, 500);
		})
		.on('backToTop:show', function(event) {
			$(this).addClass('active');
		})
		.on('backToTop:hide', function(event) {
			$(this).removeClass('active');
		})
		.on('scroll:change', function(event) {
			var scrollTop = $(window).scrollTop();
			$(this).trigger((scrollTop === 0)? 'backToTop:hide':'backToTop:show');
		})
		.trigger('scroll:change');
		
		return this;
	}
	
}(jQuery, document, window));