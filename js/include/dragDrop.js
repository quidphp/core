/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// dragDrop
// script with a function to activate vertical sorting (using jquery-ui)
(function ($, document, window) {
	
	// verticalSorting
	// active le verticalSorting sur un élément
	// nécessite jqueryUi
	$.fn.verticalSorting = function(items,containment)
	{
		if($.isStringNotEmpty(items))
		{
			$(this).each(function() {
				sortContainment = containment || $(this);
				$(this).sortable({
					axis: "y",
					handle: '.move',
					items: items,
					cursor: "move",
					tolerance: 'pointer',
					opacity: 0.5,
					containment: sortContainment
				});
			});
		}
		
		return this;
	}
	
}(jQuery, document, window));