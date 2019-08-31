/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// clickOpen
// script with some basic logic for a clickOpen widget (click trigger to show, click body to hide)
(function ($, document, window) {
	
	// clickOpen
	// gère les comportements pour un élément clickOpen, clique sur un trigger et affiche un container
	$.fn.clickOpen = function()
	{
		var clickOpen = $(this);
		$(this).on('clickOpen:isOpen', function(event) {
			return $(this).hasClass("active");
		})
		$(this).on('clickOpen:isInit', function(event) {
			return ($(this).data('clickOpen:init') === true)? true:false;
		})
		.on('clickOpen:getTrigger', function(event) {
			return $(this).find("> .trigger").first();
		})
		.on('clickOpen:getTitle', function(event) {
			return $(this).triggerHandler('clickOpen:getTrigger').find(".title").first();
		})
		.on('clickOpen:getPopup', function(event) {
			return $(this).find(".popup").first();
		})
		.on('clickOpen:getParentContainer', function(event) {
			var r = $(this).parents(".popup").first();
			r = (!r.length)? $("body"):r;
			return r;
		})
		.on('clickOpen:open', function(event) {
			event.stopPropagation();
			if($(this).triggerHandler('clickOpen:isOpen') !== true)
			{
				if($(this).triggerHandler('clickOpen:isInit') !== true)
				{
					$(this).triggerHandler('clickOpen:firstOpen');
					$(this).data('clickOpen:init',true);
				}
				$(this).addClass('active hasClickOpen');
			}
		})
		.on('clickOpen:close', function(event) {
			event.stopPropagation();
			if($(this).triggerHandler('clickOpen:isOpen') === true)
			$(this).removeClass('active hasClickOpen');
		})
		.on('clickOpen:prepare', function(event) {
			event.stopPropagation();
			var $this = $(this);
			var trigger = $(this).triggerHandler('clickOpen:getTrigger');
			var container = $(this).triggerHandler('clickOpen:getPopup');
			var parent = $(this).triggerHandler('clickOpen:getParentContainer');
			
			if(trigger.length)
			{
				trigger.on('click', function(event) {
					event.stopPropagation();
					var isOpen = $this.triggerHandler('clickOpen:isOpen');
					parent.find(".hasClickOpen").trigger('clickOpen:close');
					if(isOpen === false)
					$this.trigger('clickOpen:open');
				});
			}
			
			container.on('click', 'a', function(event) {
				event.stopPropagation();
				$(document).trigger('navigation:clickEvent',[event]);
			})
			.on('click', function(event) {
				event.stopPropagation();
				$(this).find(".hasClickOpen").trigger('clickOpen:close');
			});
		})
		.on('clickOpen:setTitle', function(event,value) {
			event.stopPropagation();
			$(this).triggerHandler('clickOpen:getTitle').text(value);
		})
		.trigger('clickOpen:prepare');
		
		$(document).on('click', function(event) {
			clickOpen.trigger('clickOpen:close');
		});
		
		return this;
	}
	
	
	// fakeselect
	// crée les comportements pour un input fakeSelect, pouvant avoir un inputHidden lié
	// fakeselect étend clickOpen
	$.fn.fakeselect = function()
	{
		$(this).clickOpen().on('fakeselect:getChoices', function(event) {
			return $(this).triggerHandler('clickOpen:getPopup').find("li");
		})
		.on('isFakeInput', function(event) {
			return true;
		})
		.on('fakeselect:getInput', function(event) {
			return $(this).find("input[type='hidden']");
		})
		.on('fakeselect:getSelected', function(event) {
			return $(this).find("li.selected");
		})
		.on('fakeselect:getValue', function(event) {
			return $(this).triggerHandler('fakeselect:getSelected').data('value');
		})
		.on('getValue', function(event) {
			return $(this).triggerHandler('fakeselect:getValue');
		})
		.on('fakeselect:prepare', function(event) {
			var $this = $(this);
			var choices = $(this).triggerHandler('fakeselect:getChoices');
			var selected = $(this).triggerHandler('fakeselect:getSelected');
			
			choices.on('click', function(event) {
				event.stopPropagation();
				$this.trigger('fakeselect:choose',[$(this)]);
			});
			
			if(selected.length)
			$(this).trigger('fakeselect:choose',[selected]);
		})
		.on('fakeselect:choose', function(event,selected) {
			var input = $(this).triggerHandler('fakeselect:getInput');
			var choices = $(this).triggerHandler('fakeselect:getChoices');
			var value = selected.data("value");
			var current = input.inputValue(true);
			choices.removeClass('selected');
			selected.addClass('selected');
			input.val(value);
			$(this).trigger('clickOpen:setTitle',selected.text());
			$(this).trigger('clickOpen:close');
			
			if(current !== value)
			{
				$(this).trigger('change');
				$(this).trigger('fakeselect:changed',[value,selected]);
			}
		})
		.trigger('fakeselect:prepare');
		
		return this;
	}
	
	
	// selectToFake
	// transforme des tags select en fakeselect
	$.fn.selectToFake = function(anchorCorner)
	{
		$(this).each(function(index, el) {
			if($(this).tagName() === 'select')
			{
				var name = $(this).prop('name');
				var required = $(this).data('required');
				var title = $(this).find("option:selected").text() || "&nbsp;";
				var options = $(this).find("option");
				var value = $(this).inputValue(true);
				var html = '';
				
				html += "<div class='fakeselect";
				if(anchorCorner === true)
				html += " anchorCorner";
				html += "'";
				if(required)
				html += " data-required='1'";
				html += "><div class='trigger'>";
				html += "<div data-title'='"+title+"' class='title'>"+title+"</div>";
				html += "<div class='ico'></div>";
				html += "</div>";
				html += "<div class='popup'>";
				html += "<ul>";
				
				options.each(function(index, el) {
					var val = $(this).prop('value');
					var text = $(this).text() || "&nbsp;";
					html += "<li";
					if(val != null)
					{
						if(val === value)
						html += " class='selected'";
						
						html += " data-value='"+val+"'";
					}
					
					html += ">"+text;
					html += "</li>";
				});
				
				html += "</ul>";
				html += "</div>";
				html += "<input name='"+name+"' type='hidden' value='"+value+"'/>";
				html += "</div>";

				$(this).after(html);
				var fakeselect = $(this).next('.fakeselect');
				fakeselect.fakeselect();
				
				if(anchorCorner === true)
				fakeselect.anchorCorner('mouseover');
				
				$(this).remove();
			}
		});
		
		return this;
	}
	
}(jQuery, document, window));