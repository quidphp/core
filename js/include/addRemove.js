/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// addRemove
// script of behaviours for an add-remove input (like jsonArray)
(function ($, document, window) {
	
	// addRemove
	// génère un input add remove, comme jsonArray
	$.fn.addRemove = function()
	{
		$(this).on('addRemove:getInsert', function(event) {
			return $(this).find(".insert");
		})
		.on('addRemove:getContainer', function(event) {
			return $(this).find(".container");
		})
		.on('addRemove:getElements', function(event) {
			return $(this).triggerHandler('addRemove:getContainer').find(".ele");
		})
		.on('addRemove:getElementsUnbind', function(event) {
			return $(this).triggerHandler('addRemove:getElements').filter(function() {
				return !$(this).data("addRemoveBind");
			});
		})
		.on('addRemove:insert', function(event) {
			var insert = $(this).triggerHandler('addRemove:getInsert');
			var container = $(this).triggerHandler('addRemove:getContainer');
			var html = insert.data('html');
			
			if($.isStringNotEmpty(html))
			{
				container.append(html);
				var elements = $(this).triggerHandler('addRemove:getElements');
				var inserted = elements.last();
				
				$(this).trigger('addRemove:inserted',[inserted]);
				$(this).trigger('addRemove:bind');
			}
		})
		.on('addRemove:bind', function(event) {
			var $this = $(this);
			var elements = $(this).triggerHandler('addRemove:getElementsUnbind');
			
			elements.each(function(index, el) {
				$(this).data("addRemoveBind",true);
				$(this).find(".remove").confirm('click').on('confirmed', function(event) {
					var parent = $(this).parents(".ele");
					var container = $(this).triggerHandler('addRemove:getContainer');
					var index = parent.index(container);
					$this.trigger('addRemove:remove',[index]);
				});
			});
		})
		.on('addRemove:remove', function(event,index) {
			if($.isNumeric(index))
			{
				var elements = $(this).triggerHandler('addRemove:getElements');
				var ele = elements.eq(index);
				ele.remove();
				
				if(!$(this).triggerHandler('addRemove:getElements').length)
				$(this).trigger('addRemove:insert');
			}
		})
		.on('addRemove:prepare', function(event) {
			var $this = $(this);
			var insert = $(this).triggerHandler('addRemove:getInsert');
			
			insert.on('click', function(event) {
				$this.trigger('addRemove:insert');
			});
			
			$(this).trigger('addRemove:bind');
			
			if($(this).find(".move").length)
			$(this).triggerHandler('addRemove:getContainer').verticalSorting(".ele");
		})
		.trigger('addRemove:prepare');
		
		return this;
	}
	
}(jQuery, document, window));