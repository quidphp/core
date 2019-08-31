/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// box
// script with behaviours for a box widget (popup in a fixed div)
(function ($, document, window) {
	
	// jsBox
	// gère les comportents pour une l'ouverture et la fermeture d'un overlay style colorbox
	$.fn.jsBox = function()
	{
		if($(this).length === 1)
		{
			var jsBox = $(this);
			
			$(this).block('jsBox:get').escapeCatch().on('jsBox:getInner', function(event) {
				return $(this).find(".inner");
			})
			.on('click', '.close', function(event) {
				jsBox.trigger('jsBox:close');
				event.stopImmediatePropagation();
			})
			.on('jsBox:isEmpty', function(event) {
				return ($(this).triggerHandler('jsBox:getInner').html().length > 0)? false:true;
			})
			.on('jsBox:hasText', function(event) {
				return ($(this).triggerHandler('jsBox:getInner').text().length > 0)? true:false;
			})
			.on('jsBox:getOpenPromise', function(event) {
				if($(this).data('jsbox-promise') == null)
				$(this).trigger('jsbox:open');
				return $(this).data('jsbox-promise')
			})
			.on('jsBox:open', function(event,className) {
				$(this).addClass('loading');
				if($.isStringNotEmpty(className))
				{
					$(this).data('jsbox-className',className);
					$(this).addClass(className);
					$(this).data('route',className);
				}
				
				var promise = $(this).fadeIn(500).delay(250).promise();
				$(this).data('jsbox-promise',promise);
			})
			.on('jsBox:opened', function(event) {
				$(this).removeClass('loading');
				$(this).removeData('promise');
			})
			.on('jsBox:html', function(event,data,callback) {
				var promise = $(this).triggerHandler('jsBox:getOpenPromise');
				promise.done(function() {
					$(this).trigger('jsBox:opened');
					$(this).triggerHandler('jsBox:getInner').html(data);
					
					if($.isFunction(callback))
					callback.call();
				});
			})
			.on('jsBox:close', function(event) {
				$(this).fadeOut(500, function() {
					var className = $(this).data('jsbox-className');
					$(this).triggerHandler('jsBox:getInner').html("");
					$(this).removeData('route');
					if($.isStringNotEmpty(className))
					$(this).removeClass(className);
				});
			})
			.on('jsBox:openSelf', function(event,className) {
				$(this).trigger('jsBox:open',[className]);
				$(this).trigger('jsBox:opened');
			})
			.on('jsBox:get', function(event,href,args,className) {
				if($.isStringNotEmpty(href))
				{
					$(this).trigger('block');
					$(this).trigger('jsBox:open',[className]);
					$.ajax(href,{
						data: args,
						method: 'get',
						success: function(data,textStatus,jqXHR) {
							var callback = function() {
								jsBox.trigger('jsBox:route');
								jsBox.trigger('jsBox:success',[jsBox]);
							};
							jsBox.trigger('jsBox:html',[data,callback]);
							jsBox.trigger('unblock');
						},
						error: function(jqXHR,textStatus,errorThrown) {
							var text = jqXHR.responseText || textStatus;
							jsBox.trigger('jsBox:html',[text]);
							jsBox.trigger('unblock');
						}
					});
				}
			})
			.on('jsBox:route', function(event) {
				var route = $(this).data('route');
				if($.isStringNotEmpty(route))
				$(document).trigger('jsBox:'+route,[$(this)]);
			})
			.on('escape:catched', function(event) {
				$(this).trigger('jsBox:close');
			})
			.on('click', function(event) {
				$(this).trigger('jsBox:close');
			});
			
			// inner
			$(this).triggerHandler('jsBox:getInner').on('click', function(event) {
				event.stopPropagation();
			})
			.on('click', '.close', function(event) {
				jsBox.trigger('jsBox:close');
				event.stopPropagation();
			});
		}
		
		return this;
	}
	
	
	// jsBoxAjax
	// gère les comportements pour les éléments qui ouvre le jsBox et y injecte du contenu via ajax
	$.fn.jsBoxAjax = function(jsBox)
	{
		$(this).block('click').ajax('click')
		.on('ajax:beforeSend', function() {
			$(this).trigger('block');
			jsBox.trigger('jsBox:open',[$(this).data('jsBox')]);
		})
		.on('ajax:success', function(event,data,textStatus,jqXHR) {
			$this = $(this);
			var callback = function() {
				jsBox.trigger('jsBox:route');
				$this.trigger('jsBox:success',[jsBox]);
			};
			jsBox.trigger('jsBox:html',[data,callback]);
			$(this).trigger('unblock');
		})
		.on('ajax:error', function(event,jqXHR,textStatus,errorThrown) {
			var text = jqXHR.responseText || textStatus;
			jsBox.trigger('jsBox:html',[text]);
			$(this).trigger('unblock');
		});
		
		return this;
	}
	
	
	// externalJsBox
	// permet de gérer l'ouverture du jsBox lors du clique sur un lien externe
	$.fn.externalJsBox = function(jsBox,href,className)
	{
		if($.isStringNotEmpty(href))
		{
			$(this).find("a:external:not(.external)").off('click').on('click', function(event) {
				event.preventDefault();
				var uri = $(this).attr('href');
				jsBox.trigger('jsBox:get',[href,{v: uri},className]);
			});
		}
		
		return this;
	}
	
	
	// mailtoJsBox
	// permet de gérer l'ouverture du jsBox lors du clique sur un lien mailto
	$.fn.mailtoJsBox = function(jsBox,href,className)
	{
		if($.isStringNotEmpty(href))
		{
			$(this).find("a[href^='mailto:']:not(.mailto)").off('click').on('click', function(event) {
				event.preventDefault();
				var email = $.mailto($(this).attr('href'));
				jsBox.trigger('jsBox:get',[href,{v: email},className])
			});
		}
		
		return this;
	}
	
}(jQuery, document, window));