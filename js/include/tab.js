/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// tab
// script with behaviours for a tab system and a slider
(function ($, document, window) {
	
	// tab
	// s'occupe de l'ouverture et la fermeture de tab
	$.fn.tab = function()
	{
		$(this).on('tab:getIndex', function(event,value) {
			return $.indexer(value,$(this).triggerHandler('tab:getCurrent'),$(this).triggerHandler('tab:getTarget'));
		})
		.on('tab:indexer', function(event,value) {
			var index = $(this).triggerHandler('tab:getIndex',[value]);
			if($.isNumeric(index))
			$(this).trigger('tab:change',[index]);
		})
		.on('tab:first', function() {
			$(this).trigger('tab:indexer',['first']);
		})
		.on('tab:prev', function() {
			$(this).trigger('tab:indexer',['prev']);
		})
		.on('tab:next', function() {
			$(this).trigger('tab:indexer',['next']);
		})
		.on('tab:last', function() {
			$(this).trigger('tab:indexer',['last']);
		})
		.on('tab:index', function(event,index) {
			$(this).trigger('tab:indexer',['index']);
		})
		.on('tab:loopNext', function(event) {
			$(this).trigger('tab:indexer',['loopNext']);
		})
		.on('tab:loopPrev', function(event) {
			$(this).trigger('tab:indexer',['loopPrev']);
		})
		.on('tab:target', function(event,target) {
			$(this).trigger('tab:indexer',[target]);
		})
		.on('tab:getCurrent', function() {
			return $(this).data('tab-current');
		})
		.on('tab:isCurrent', function(event,value) {
			var current = $(this).triggerHandler('tab:getCurrent');
			var index = ($.isNumeric(value))? value:$(this).triggerHandler('tab:getTarget').index(value);
			return ($.isNumeric(current) && index === current)? true:false;
		})
		.on('tab:closeAll', function() {
			var target = $(this).triggerHandler('tab:getTarget');
			$(this).data('tab-current',null);
			target.each(function(index) {
				$(this).trigger('tab:close');
			});
		})
		.on('tab:change', function(event,index) {
			var target = $(this).triggerHandler('tab:getTarget');
			var current = $(this).triggerHandler('tab:getCurrent');
			
			if(target.length && $.isNumeric(index))
			{
				if(index !== current)
				{
					var indexTarget = target.eq(index);
					var currentTarget = ($.isNumeric(current))? target.eq(current):null;
					
					if(indexTarget.length)
					{
						if(currentTarget !== null)
						currentTarget.trigger('tab:close');
						
						$(this).data('tab-current',index);
						indexTarget.trigger('tab:open');
						
						if(indexTarget.data('tab-init') == null)
						{
							indexTarget.data('tab-init',true);
							indexTarget.trigger('tab:init');
						}
					}
					
					else
					$(this).trigger('tab:notExist');
				}
				
				else
				$(this).trigger('tab:noChange');
			}
		})
		.on('tab:changeOrFirst', function(event,target) {
			if(target !== null && target.length === 1)
			target.trigger('tab:change');
			else
			$(this).trigger('tab:first');
		})
		.on('tab:prepare', function() {
			var tab = $(this);
			var target = $(this).triggerHandler('tab:getTarget');
			
			target.on('tab:getIndex', function() {
				return target.index($(this));
			})
			.on('tab:getTarget', function() {
				return target;
			})
			.on('tab:getCurrent', function() {
				return tab.triggerHandler('tab:getCurrent');
			})
			.on('tab:get', function() {
				return tab;
			})
			.on('tab:change', function() {
				tab.trigger('tab:target',[$(this)]);
			})
			.on('tab:open', function() {
				var index = $(this).triggerHandler('tab:getIndex');
				tab.data('tab-current',index);
			});
		}).trigger('tab:prepare');
		
		return this;
	}
	

	// tabNav
	// lie des target de tab à des éléments de nav, via index
	$.fn.tabNav = function(navs)
	{
		if(navs instanceof jQuery && navs.length === $(this).length)
		{
			var targets = $(this);
			
			$(this).each(function(index) 
			{
				var nav = navs.eq(index);
				
				if(nav.length)
				{
					$(this).data('link-nav',nav).on('link:getIndex', function() {
						return targets.index($(this));
					})
					.on('link:getNav', function() {
						return $(this).data('link-nav');
					});
					
					nav.data('link-target',$(this)).on('link:getIndex', function() {
						return navs.index($(this));
					})
					.on('link:getTarget', function() {
						return $(this).data('link-target');
					});
				}
			});
		}
		
		return this;
	}
	
	
	// slider
	// génère un slider complet avec bouton next et previous
	$.fn.slider = function(timeout,navs,className,showIfOne)
	{
		var func = function() {
			className = ($.isStringNotEmpty(className))? className:".slide";
			$(this).removeClass('loading');
			var tab = $(this);
			var prev = $(this).find(".prev");
			var next = $(this).find(".next");
			var target = $(this).find(className);
			
			target.on('tab:open', function(event) {
				$(this).addClass("active");
			})
			.on('tab:close', function(event) {
				$(this).removeClass("active");
			});
			
			if(target.length > 1 || showIfOne === true)
			{
				if(next.length)
				{
					next.on('click', function(event) {
						tab.trigger('tab:loopNext');
					});
				}
				
				if(prev.length)
				{
					prev.on('click', function(event) {
						tab.trigger('tab:loopPrev');
					});
				}
				
				if(navs instanceof jQuery && navs.length)
				{
					target.tabNav(navs);
					target.on('tab:open', function(event) {
						var nav = $(this).triggerHandler('link:getNav');
						navs.removeClass('active');
						nav.addClass('active');
					});
					navs.on('click', function(event) {
						var target = $(this).triggerHandler('link:getTarget');
						target.trigger('tab:change');
					});
				}
				
				if($.isNumeric(timeout))
				{
					$(this).timeout('tab:change',timeout)
					.on('tab:change:onTimeout', function(event) {
						$(this).trigger('tab:loopNext');
					})
					.on('mouseover', function(event) {
						$(this).trigger('tab:change:clearTimeout');
					})
					.on('mouseleave', function(event) {
						$(this).trigger('tab:change:setTimeout');
					});
				}
			}
			
			else
			{
				if(next.length)
				next.hide();
				
				if(prev.length)
				prev.hide();
				
				if(navs instanceof jQuery && navs.length)
				navs.hide();
			}
			
			$(this).on('tab:getTarget', function(event) {
				return target;
			}).tab().trigger('tab:loopNext');
		};
		
		$(this).each(function(index, el) {
			$(this).addClass("loading");
			func.call(this);
		});
		
		return this;
	}
	
	
	// mediaSlider
	// gère le js pour un media slider
	$.fn.mediaSlider = function()
	{
		$(this).slider().find(".slide").on('tab:close', function(event) {
			var iframe = $(this).find("iframe");
			if(iframe.length)
			iframe.attr('src',iframe.attr('src'));
		});
		
		return this;
	}
	
}(jQuery, document, window));