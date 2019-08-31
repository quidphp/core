/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// ajax
// script with some additional logic over the jQuery ajax object
(function ($, document, window) {
	
	// ajax
	// charge un lien ou un formulaire via ajax
	// un événement est requis
	$.fn.ajax = function(type) 
	{
		if($.isStringNotEmpty(type))
		{
			$(this).on(type, function(event) {
				event.stopPropagation();
				$(this).trigger('ajax:trigger',event);
			})
			.on('ajax:beforeSend', function(event) {
				event.stopPropagation();
			})
			.on('ajax:before', function(event) {
				event.stopPropagation();
			})
			.on('ajax:success', function(event) {
				event.stopPropagation();
			})
			.on('ajax:error', function(event,jqXHR) {
				event.stopPropagation();
			})
			.on('ajax:complete', function(event) {
				event.stopPropagation();
			})
			.on('ajax:trigger', function(event,typeEvent) {
				event.stopPropagation();
				var tagName = $(this).tagName();
				var uri = $(this).triggerHandler('ajax:getHref');
				var method = $(this).triggerHandler('ajax:getMethod');
				var data = $(this).triggerHandler('ajax:getData');
				
				if(tagName === 'form')
				{
					uri = uri || $(this).prop("action");
					method = method || $(this).prop("method") || 'get';
					data = data || $(this).serialize();
				}
				
				else
				{
					uri = uri || $(this).prop("href") || $(this).data('href');
					method = method || $(this).data("method") || 'get';
					data = data || $(this).data('data');
				}
				
				if($.isStringNotEmpty(uri) && $.isStringNotEmpty(method))
				{
					if($(this).triggerHandler('ajax:confirm') !== false)
					{
						if(typeEvent)
						{
							typeEvent.stopImmediatePropagation();
							typeEvent.preventDefault();
						}
						
						var $this = $(this);
						var ajax = {
							url: uri,
							type: method.toUpperCase(),
							data: data,
							beforeSend: function(jqXHR,settings) {
								$this.trigger('ajax:beforeSend',[jqXHR,settings]);
							},
							success: function(data,textStatus,jqXHR) {
								$this.trigger('ajax:success',[data,textStatus,jqXHR]);
							},
							error: function(jqXHR,textStatus,errorThrown) {
								$this.trigger('ajax:error',[jqXHR,textStatus,errorThrown]);
							},
							complete: function(jqXHR,textStatus) {
								$this.trigger('ajax:complete',[jqXHR,textStatus]);
							}
						};
						
						$(this).trigger('ajax:before',[ajax]);
						$.ajax(ajax);
					}
				}
				
				return false;
			});
		}
		
		return this;
	}
	
	
	// ajaxBlock
	// intègre la logique ajax, block et loading via une même méthode
	$.fn.ajaxBlock = function(type)
	{
		if($.isStringNotEmpty(type))
		{
			$(this).block(type).ajax(type)
			.on('ajax:before', function(event) {
				$(this).removeClass('error');
				$(this).addClass('loading');
				$(this).trigger('block');
			})
			.on('ajax:error', function(event,jqXHR,textStatus,errorThrown) {
				$(this).addClass("error");
			})
			.on('ajax:complete', function(event) {
				$(this).trigger('unblock');
				$(this).removeClass('loading');
			});
		}
		
		return this;
	}
	
}(jQuery, document, window));