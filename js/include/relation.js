/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// relation
// script containing logic and methods for relation-related inputs (like enumSet and filters)
(function ($, document, window) {
	
	// enumSet
	// gère les comportements pour le popup enumSet
	$.fn.enumSet = function()
	{
		$(this).on('getCurrent', function(event) {
			return $(this).find(".current");
		})
		.on('getResult', function(event) {
			return $(this).triggerHandler('clickOpen:getPopup').find(".results");
		})
		.on('getChoice', function(event) {
			return $(this).triggerHandler('getCurrent').find('.choice');
		})
		.on('getInput', function(event) {
			return $(this).find(".input input[type='text']");
		})
		.on('getSelect', function(event) {
			return $(this).triggerHandler('clickOpen:getPopup').find("select");
		})
		.on('getCheckbox', function(event) {
			return $(this).triggerHandler('getChoice').find("input[type='checkbox']");
		})
		.on('getMode', function(event) {
			return $(this).data('mode');
		})
		.on('getChoiceCount', function(event) {
			return $(this).triggerHandler('getChoice').length;
		})
		.on('isChoiceIn', function(event,value) {
			return (($.isScalar(value)) && $(this).triggerHandler('getCurrent').find("input[type='checkbox'][value='"+value+"']").length)? true:false;
		})
		.on('clickOpen:open', function(event) {
			$(this).addClass('loading');
			$(this).triggerHandler('getResult').html('');
		})
		.on('clickOpen:close', function(event) {
			$(this).removeClass('loading');
			$(this).triggerHandler('getResult').html('');
		})
		.on('choice:append', function(event,value,html) {
			var mode = $(this).triggerHandler('getMode');

			if($.isStringNotEmpty(html) && $.isScalar(value) && $.isStringNotEmpty(mode))
			{
				var ele = $(this).triggerHandler('clickOpen:getPopup').find("li[data-value='"+value+"']");
				if($(this).triggerHandler('isChoiceIn',[value]))
				ele.addClass('alreadyIn');
				
				else
				{
					if(mode === 'enum')
					$(this).trigger('choice:empty');
					else
					ele.removeClass('alreadyIn');
					
					$(this).triggerHandler('getCurrent').append(html);
					$(this).trigger('clickOpen:close');
				}
			}
		})
		.on('choice:empty', function(event) {
			$(this).triggerHandler('getChoice').remove();
			$(this).triggerHandler('clickOpen:getPopup').find("li.alreadyIn").removeClass('alreadyIn');
		})
		.on('change', "input[type='checkbox']", function(event) {
			if($(this).prop('checked') === false)
			$(this).parents(".choice").remove();
		})
		
		return this;
	}
	

	// enumSetInput
	// gère les comportements pour champ enumSet avec recherche de relation
	$.fn.enumSetInput = function()
	{
		$(this).enterBlock().validateBlock('ajax:init').block('ajax:init').timeout('keyup',500).ajax('ajax:init')
		.on('enter:blocked', function(event) {
			$(this).trigger('ajax:beforeInit',[true]);
		})
		.on('keyup:onTimeout', function(event) {
			$(this).trigger('ajax:beforeInit',[true]);
		})
		.on('click', function(event) {
			event.stopPropagation();
			$(this).trigger('ajax:beforeInit',[true]);
		})
		.on('ajax:beforeInit', function(event,validate) {
			var val = $(this).inputValue(true);
			$(this).removeClass('invalid');
			
			if(validate !== true || $.isStringNotEmpty(val))
			{
				$(this).trigger('keyup:clearTimeout');
				$(this).trigger('ajax:init');
			}
			
			else
			$(this).triggerHandler('getParent').trigger('clickOpen:close');
		})
		.on('validate:failed', function(event) {
			$(this).triggerHandler('getPopup').trigger('clickOpen:close');
		})
		.on('getParent', function(event) {
			return $(this).parents(".searchEnumSet");
		})
		.on('getPopup', function(event) {
			return $(this).triggerHandler('getParent').triggerHandler('clickOpen:getPopup');
		})
		.on('getResult', function(event) {
			return $(this).triggerHandler('getParent').triggerHandler('getResult');
		})
		.on('getSelect', function(event) {
			return $(this).triggerHandler('getParent').triggerHandler('getSelect');
		})
		.on('ajax:getHref', function(event) {
			var parent = $(this).triggerHandler('getParent');
			var select = $(this).triggerHandler('getSelect');
			var checkboxes = parent.triggerHandler('getCheckbox');
			var separator = $(this).data("separator");
			var selected = checkboxes.filter(":checked").valSet(separator,true) || separator;
			var selectVal = select.inputValue(true);
			var order = (select.length && selectVal)? selectVal:separator;
			return $(this).dataHrefReplaceChar(selected,order);
		})
		.on('ajax:getData', function(event) {
			var r = {};
			r[$(this).data('query')] = $(this).inputValue(true);
			return r;
		})
		.on('ajax:before', function() {
			$(this).trigger('block');
			$(this).triggerHandler('getParent').trigger('clickOpen:open');
		})
		.on('ajax:success', function(event,data,textStatus,jqXHR) {
			var popup = $(this).triggerHandler('getPopup');
			var result = $(this).triggerHandler('getResult');
			result.html(data);
			popup.trigger('clickOpen:ready');
		})
		.on('ajax:error', function(event,jqXHR,textStatus,errorThrown) {
			var text = jqXHR.responseText || textStatus;
			$(this).triggerHandler('getResult').html(text);
		})
		.on('ajax:complete', function() {
			$(this).triggerHandler('getParent').removeClass('loading');
			$(this).trigger('unblock');
		})
		
		return this;
	}
	
	
	// enumSetFull
	// input enumSet complet avec popup et bouton
	$.fn.enumSetFull = function(appendContainer)
	{
		var $this = this;
		$(this).each(function(index, el) {
			var enumSet = $(this);
			
			// enumSet
			$(this).clickOpen().enumSet().on('clickOpen:open', function(event) {
				$this.not($(this)).trigger('clickOpen:close');
			})
			.on('clickOpen:ready', function(event) {
				$(this).triggerHandler('clickOpen:getPopup').trigger('feed:bind');
			});
			
			// popup
			$(this).triggerHandler('clickOpen:getPopup').appendContainer().on('feed:target', function(event) {
				return $(this).find(".results ul");
			})
			.on('feed:parseData', function(event,data) {
				return $.parseHtmlDocument(data).find("li");
			})
			.on('click', 'li', function(event) {
				$(this).parents(".searchEnumSet").trigger('choice:append',[$(this).data('value'),$(this).data('html')]);
				event.stopPropagation();
			});
			
			// input
			$(this).triggerHandler('getInput').enumSetInput();
			$(this).triggerHandler('getSelect').on('change', function(event) {
				setTimeout(function() {
					enumSet.triggerHandler('getInput').trigger('ajax:beforeInit',[false]);
				},50);
			});
			
			// button
			$(this).find("button").on('getInput', function(event) {
				return $(this).parents(".searchEnumSet").triggerHandler('getInput');
			})
			.on('click',function(event) {
				event.stopPropagation();
				var input = $(this).triggerHandler('getInput');
				input.val("");
				input.trigger('ajax:beforeInit');
			});
		});
		
		return this;
	}
	
	
	// filterGeneral
	// gère les comportements pour un filtre avec popup
	$.fn.filterGeneral = function()
	{
		$(this).block('ajax:init').ajax('ajax:init')
		.on('getResult', function(event) {
			return $(this).find(".result");
		})
		.on('getInput', function(event) {
			return $(this).find("input[type='text']");
		})
		.on('getSelect', function(event) {
			return $(this).find(".order select");
		})
		.on('filter:prepare', function(event) {
			var filter = $(this);
			
			$(this).triggerHandler('getInput').validateBlock('ajax:input').fieldValidateFull().timeout('keyup',500)
			.on('keyup:onTimeout', function(event) {
				$(this).trigger('ajax:input');
			})
			.on('ajax:input', function(event) {
				filter.trigger('ajax:init');
			});
			
			$(this).triggerHandler('getSelect').on('change',function(event) {
				setTimeout(function() {
					filter.trigger('ajax:init');
				},50);
			});
		})
		.on('ajax:getHref', function(event) {
			var separator = $(this).data('separator');
			var select = $(this).triggerHandler('getSelect');
			var selectVal = select.inputValue(true);
			var order = (select.length && selectVal)? selectVal:separator;
			return $(this).dataHrefReplaceChar(order);
		})
		.on('ajax:getData', function(event) {
			var r = {};
			r[$(this).data('query')] = $(this).triggerHandler('getInput').inputValue(true);
			return r;
		})
		.on('ajax:before', function() {
			$(this).trigger('block');
			$(this).addClass('loading');
			$(this).triggerHandler('getResult').html("");
		})
		.on('ajax:success', function(event,data,textStatus,jqXHR) {
			$(this).triggerHandler('getResult').html(data);
		})
		.on('ajax:error', function(event,jqXHR,textStatus,errorThrown) {
			var text = jqXHR.responseText || textStatus;
			$(this).triggerHandler('getResult').html(text);
		})
		.on('ajax:complete', function() {
			$(this).trigger('unblock');
			$(this).removeClass('loading');
			$(this).triggerHandler('getInput').focus();
		}).trigger('filter:prepare');
		
		return this;
	}
	
	
	// filterGeneralFull
	// gère les comportements complets pour un filtre general avec popup
	$.fn.filterGeneralFull = function(appendContainer,removeOnClose)
	{
		$(this).clickOpen().filterGeneral();
				
		if(appendContainer === true)
		{
			$(this).each(function(index, el) {
				$(this).on('ajax:complete', function(event) {
					$(this).triggerHandler('clickOpen:getPopup').trigger('feed:bind');
				})
				.triggerHandler('clickOpen:getPopup').appendContainer().on('feed:target', function(event) {
					return $(this).find("ul.list");
				})
				.on('feed:parseData', function(event,data) {
					return $.parseHtmlDocument(data).find("ul.list li");
				});
			});
		}
		
		if(removeOnClose === true)
		{
			$(this).on('clickOpen:open', function(event) {
				$(this).trigger('ajax:init');
			})
			.on('clickOpen:close', function(event) {
				$(this).triggerHandler('getResult').html("");
			});
		}
		
		else
		{
			$(this).on('clickOpen:firstOpen', function(event) {
				$(this).trigger('ajax:init');
			})
			.on('clickOpen:open', function(event) {
				$(this).triggerHandler('getInput').focus();
			});
		}
		
		return this;
	}
	
	
	// tableRelationToTextarea
	// permet de gérer des menus de sélection tableRelation dont le contenu peut être inséré dans un textarea
	$.fn.tableRelationToTextarea = function()
	{
		$(this).each(function(index, el) {
			var filters = $(this).find(".relations .filter");
			var textarea = $(this).find("textarea").first();
			
			textarea.on('textarea:insert', function(event,html) {
				var r = false;
				
				if($.isStringNotEmpty(html))
				{
					r = true;
					var current = $(this).val();
					textarea.val(current+html);
				}
				
				return r;
			});
			
			filters.filterGeneralFull(true,true).each(function(index, el) {
				$(this).triggerHandler('getResult').on('click', '.insert', function(event) {
					var parent = $(this).parents('.clickOpen');
					var html = $(this).data('html');
					textarea.triggerHandler('textarea:insert',html);
					parent.trigger('clickOpen:close');
					event.stopPropagation();
				});
			});
		});
		
		return this;
	}
	
	
}(jQuery, document, window));