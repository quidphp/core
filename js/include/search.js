/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// search
// script with behaviours for search inputs
(function ($, document, window) {

	// formAjaxPopup
	// gère un formulaire à un champ qui s'envoie via ajax et dont le résultat s'affiche dans un popup
	$.fn.formAjaxPopup = function(field)
	{
		if(field instanceof jQuery && field.length === 1)
		{
			var $this = $(this);
			$(this).clickOpen().block('submit').validateBlock('submit').ajax('submit')
			.on('ajax:getData', function(event) {
				var r = {};
				r[field.data('query')] = field.inputValue(true);
				return r;
			})
			.on('ajax:before', function() {
				$(this).trigger('block');
				$(this).trigger('clickOpen:open');
			})
			.on('ajax:success', function(event,data,textStatus,jqXHR) {
				$(this).triggerHandler('clickOpen:getPopup').html(data);
			})
			.on('ajax:error', function(event,jqXHR,textStatus,errorThrown) {
				$(this).triggerHandler('clickOpen:getPopup').html(textStatus);
			})
			.on('ajax:complete', function() {
				$(this).removeClass("loading");
				$(this).trigger('unblock');
			})
			.on('clickOpen:open', function() {
				$(this).addClass("loading");
				$(this).triggerHandler('clickOpen:getPopup').html("");
			});
			
			field.timeout('keyup').fieldValidateFull()
			.on('click', function(event) {
				event.stopPropagation();
			})
			.on('keyup:onTimeout', function() {
				$(this).trigger('pattern');
			});
		}
		
		return this;
	}
	

	// searchLoad
	// génère un input searchLoad
	$.fn.searchLoad = function()
	{
		$(this).ajaxBlock('ajax:init').on('searchLoad:getInput', function(event) {
			return $(this).find("input[type='text']");
		})
		.on('searchLoad:getQuery', function(event) {
			return $(this).data('query');
		})
		.on('searchLoad:getButton', function(event) {
			return $(this).find("button");
		})
		.on('searchLoad:getScroller', function(event) {
			return $(this).find(".scroller");
		})
		.on('searchLoad:replace', function(event,html) {
			if($.isStringNotEmpty(html))
			{
				var scroller = $(this).triggerHandler('searchLoad:getScroller');
				scroller.html(html);
			}
		})
		.on('ajax:getData', function(event) {
			var r = {};
			var input = $(this).triggerHandler('searchLoad:getInput');
			var query = $(this).triggerHandler('searchLoad:getQuery');
			r[query] = input.inputValue(true);
			return r;
		})
		.on('ajax:before', function(event) {
			var scroller = $(this).triggerHandler('searchLoad:getScroller');
			scroller.html('');
		})
		.on('ajax:success', function(event,data,textStatus,jqXHR) {
			$(this).trigger('searchLoad:replace',data);
		})
		.on('searchLoad:prepare', function(event) {
			var $this = $(this);
			var input = $(this).triggerHandler('searchLoad:getInput');
			var button = $(this).triggerHandler('searchLoad:getButton');
			
			input.timeout('keyup',500).on('keyup:onTimeout', function(event) {
				$this.trigger('ajax:init');
			});
			
			button.on('click', function(event) {
				$this.trigger('ajax:init');
			});
		})
		.trigger('searchLoad:prepare');
		
		return this;
	}
	
	
	// searchSlide
	// permet de slideDown/up une target lors du focus sur un input
	$.fn.searchSlide = function(target) 
	{
		if($(this).length === 1 && target instanceof jQuery)
		{
			$(this).on('focus', function() {
				target.slideDown("fast");
			})
			.on('focusout', function() {
				target.slideUp("fast");
			});
		}
		
		return this;
	}
	
}(jQuery, document, window));