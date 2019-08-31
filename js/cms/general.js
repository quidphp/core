/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// general
// script of behaviours for the general navigation page of the CMS
$(document).ready(function() {
	
	// generalCols
	// function pour gérer la gestion des colonnes à afficher
	$.fn.generalCols = function()
	{
		var colsPopup = $(this);
		var colsCheckboxes = colsPopup.find("input[type='checkbox']");
		var colsButton = colsPopup.find("button[name='cols']");
		
		// colsPopup
		colsPopup.on('invalid', function() {
			$(this).trigger('reset').addClass('invalid');
		})
		.on('valid', function() {
			$(this).trigger('reset');
			
			if(!colsButton.triggerHandler('isCurrent'))
			$(this).addClass('valid');
		})
		.on('reset', function() {
			$(this).removeClass("valid invalid");
		});
		
		// colsCheckboxes
		colsCheckboxes.fieldValidate().on('invalid', function() {
			colsPopup.trigger('invalid');
		})
		.on('valid', function() {
			colsPopup.trigger('valid');
		});
		
		// colsButton
		colsButton.block('click').on('getCheckboxSet',function() {
			if(colsCheckboxes.triggerHandlerFalse('isValid'))
			return colsCheckboxes.filter(":checked").valSet(colsButton.data("separator"),true);
		})
		.on('isCurrent',function() {
			return (colsButton.triggerHandler('getCheckboxSet') === colsButton.data('current'))? true:false;
		})
		.on('click', function() {
			$(this).trigger('redirect');
		})
		.on('redirect', function() {
			var href = $(this).dataHrefReplaceChar($(this).triggerHandler('getCheckboxSet'));
			
			if($.isStringNotEmpty(href) && href !== $.currentRelativeUri())
			{
				$(this).trigger('block');
				$(document).trigger('navigation:push',[href]);
			}
		});
		
		return this;
	}
	
	
	// generalRows
	// function pour gérer les actions reliés aux checkboxes de rows
	$.fn.generalRows = function()
	{
		var rowsCheckboxes = $(this);
		var rowsToggleAll = $(document).find(".general table th.rows .toggleAll");
		var rowsTool = $(document).find(".general .tool");
		var rowsInNotIn = $(document).find(".general .tool .in,.general .tool .notIn");

		// rowsToggleAll
		rowsToggleAll.on('click', function() {
			$(this).trigger('toggleAll');
		})
		.on('toggleAll', function() {
			var allChecked = rowsCheckboxes.triggerHandlerFalse('isChecked');
			$(this).trigger((allChecked === true)? 'uncheck':'check');
		})
		.on('check', function() {
			rowsCheckboxes.trigger('check');
		})
		.on('uncheck', function() {
			rowsCheckboxes.trigger('uncheck');
		})
		.on('allChecked', function() {
			$(this).addClass('allChecked');
		})
		.on('notAllChecked', function() {
			$(this).removeClass('allChecked');
		});
		
		// rowsTool
		rowsTool.on('show', function() {
			$(this).css('visibility','visible');
		})
		.on('hide', function() {
			$(this).css('visibility','hidden');
		});
		
		// rowsIn + notIn
		rowsInNotIn.block('click').on('getCheckboxSet',function() {
			var separator = $(this).data("separator");
			return rowsCheckboxes.filter(":checked").valSet(separator,true);
		})
		.on('click', function() {
			$(this).trigger('redirect');
		})
		.on('redirect', function() {
			var href = $(this).dataHrefReplaceChar($(this).triggerHandler('getCheckboxSet'));
			
			if($.isStringNotEmpty(href) && href !== $.currentRelativeUri())
			{
				$(this).trigger('block');
				$(document).trigger('navigation:push',[href]);
			}
		});
		
		// rowsCheckboxes
		rowsCheckboxes.on('isChecked',function() {
			return $(this).is(":checked");
		})
		.on('change', function() {
			$(this).trigger(($(this).triggerHandler('isChecked') === true)? 'check':'uncheck');
		})
		.on('check', function() {
			$(this).parents("tr").addClass('selected');
			$(this).prop('checked',true).trigger('update');
		})
		.on('uncheck', function() {
			$(this).parents("tr").removeClass('selected');
			$(this).prop('checked',false).trigger('update');
		})
		.on('update', function() {
			var oneChecked = rowsCheckboxes.triggerHandlerTrue('isChecked');
			var allChecked = rowsCheckboxes.triggerHandlerFalse('isChecked');
			rowsTool.trigger((oneChecked === true)? 'show':'hide');
			rowsToggleAll.trigger((allChecked === true)? 'allChecked':'notAllChecked');
		});
		
		return this;
	}
	
	
	// general
	$(this).on('route:general', function() {
		
		var search = $(this).find(".general .left > .search");
		var pageLimit = $(this).find(".general input[name='limit'],input[name='page']");
		var colsPopup = $(this).find(".general th.action .popup");
		var rowsCheckboxes = $(this).find(".general table td.rows input[type='checkbox']");
		var formTruncate = $(this).find(".general .truncate form");
		var multiDelete = $(this).find(".general .tool .multiDelete form");
		var multiDeletePrimaries = multiDelete.find("input[name='primaries']");
		var filter = $(this).find(".general th.filterable .filterOuter");
		
		// search
		if(search.length)
		{
			var searchInput = search.find(".form input[type='text']");
			var searchButton = search.find(".form button");
			var searchSlide = search.find(".in");
			searchInput.searchGeneralInput(searchButton).searchSlide(searchSlide);
		}
		
		// page + limit
		if(pageLimit.length)
		pageLimit.numericGeneralInput();
		
		// cols
		if(colsPopup.length)
		colsPopup.generalCols();
		
		// rows
		if(rowsCheckboxes.length)
		rowsCheckboxes.generalRows();
		
		// formTruncate
		if(formTruncate.length)
		{
			formTruncate.block('submit').confirm('submit').on('confirmed', function() {
				$(this).trigger('block');
			});
		}
		
		// multiDelete
		if(multiDelete.length)
		{
			multiDelete.block('submit').confirm('submit').on('confirmed', function(event,submit) {
				var separator = $(this).data('separator');
				var set = rowsCheckboxes.filter(":checked").valSet(separator,true);
				if($.isStringNotEmpty(set))
				{
					multiDeletePrimaries.val(set);
					$(this).trigger('block');
				}
				
				else
				submit.preventDefault();
			});
		}
		
		// filter
		if(filter.length)
		filter.filterGeneralFull(true,true)
	});
});