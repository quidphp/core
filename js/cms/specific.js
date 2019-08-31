/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// specific
// script of behaviours for the specific form page of the CMS
$(document).ready(function() {
	
	// specific
	$(this).on('route:specific', function() {
		$(this).trigger('route:specificCommon');
		$(this).trigger('route:specificTrigger');
	})
	
	// specificAdd
	.on('route:specificAdd', function(event) {
		$(this).trigger('route:specificCommon');
		$(this).trigger('route:specificTrigger');
	})
	
	// specificCommon
	.on('route:specificCommon', function(event) {
		var formWrapper = $(".specific .container > .form");
		var panel = formWrapper.find(".panel");
		var form = formWrapper.find("form");
		var fields = form.find(".element input,.element textarea");
		var labels = form.find(".element.hasColPopup > .left .label");
		
		// submitConfirm
		var submitConfirm = form.find("button[type='submit'][data-confirm]");
		submitConfirm.confirm('click');
		
		// unload
		form.on('form:getTarget', function(event) {
			return $(this).find("input, select, textarea").not("[name^='-']");
		}).formUnload();
		
		// block
		form.block('submit').on('submit', function() {
			$(this).trigger('block');
		});
		
		// fields
		fields.fieldValidateFull();
		
		// labels
		labels.hoverClass('hover');
		
		// preparable
		formWrapper.on('form:getPreparable', function(event) {
			r = null;
			
			if(panel.length > 1)
			r = panel;
			
			else
			r = $(this);
			
			return r;
		});
		
		// prepare
		$(this).trigger('route:specificPrepare');
	})
	
	// specificTrigger
	.on('route:specificTrigger', function(event) {
		var formWrapper = $(".specific .container > .form");
		var form = formWrapper.find("form");
		var panel = $(".specific .form .inside .panel");
		var date = form.find(".element.date input[type='text']");
		var enumSet = form.find(".element .searchEnumSet");
		var mediaAction = form.find(".element.media .block .action,.element.medias .block .action");
		var mediaCancelAction = form.find(".element.media .block .message .close,.element.medias .block .message .close");
		var addRemove = form.find(".element.addRemove");
		var checkboxSortable = form.find(".element.sortable");
		var tableRelation = $(this).find(".element.tableRelation");
		
		// avec panel
		if(panel.length > 1)
		$(this).trigger('route:specificCommon:panel',[formWrapper,panel])
		
		else
		formWrapper.trigger('specificForm:prepare');
		
		// date
		if(date.length)
		date.dateInput();
		
		// enumSet
		if(enumSet.length)
		enumSet.enumSetFull();
		
		// mediaAction
		if(mediaAction.length)
		{
			mediaAction.confirm('click').on('confirmed', function(event) {
				var parent = $(this).parents(".block");
				var input = parent.find("input[type='file']");
				var name = input.attr('name');
				var hidden = parent.find("input[type='hidden'][name='"+name+"']");
				var actionText = parent.find(".actionText");
				var value = JSON.parse(hidden.val());
				value.action = $(this).data('action');
				parent.addClass('withAction');
				input.hide();
				hidden.prop('disabled',false);
				hidden.val(JSON.stringify(value));
				actionText.html($(this).data('text'));
			})
		}
		
		// mediaCancelAction
		if(mediaCancelAction.length)
		{
			mediaCancelAction.on('click', function(event) {
				var parent = $(this).parents(".block");
				var input = parent.find("input[type='file']");
				var name = input.attr('name');
				var hidden = parent.find("input[type='hidden'][name='"+name+"']");
				var actionText = parent.find(".actionText");
				var value = JSON.parse(hidden.val());
				value.action = null;
				parent.removeClass('withAction');
				input.show();
				hidden.prop('disabled',true);
				hidden.val(JSON.stringify(value));
				actionText.html('');
			});
		}
		
		// tableRelation
		if(tableRelation.length)
		tableRelation.tableRelationToTextarea();
		
		// addRemove
		if(addRemove.length)
		addRemove.addRemove();
		
		// checkboxSortable
		if(checkboxSortable.length)
		checkboxSortable.verticalSorting(".choice",'parent');
	})
	
	// route:specificCommon:panel
	.on('route:specificCommon:panel', function(event,form,panel) {
		var panelNav = $(".specific .form .top .left ul li a");
		var panelDescription = $(".specific .form .top .left .description");
		
		// panel
		panel.tabNav(panelNav).fragment().on('tab:init', function(event) {
			$(this).trigger('specificForm:prepare');
		})
		.on('tab:open', function() {
			var nav = $(this).triggerHandler('link:getNav');
			var description = nav.data('description') || '';
			var fragment = $(this).triggerHandler('fragment:get');
			var isFirst = (form.triggerHandler('tab:getIndex',[$(this)]) === 0)? true:false;
			var current = $.fragment();
			
			panelNav.removeClass('selected');
			nav.addClass('selected');
			
			if(panelDescription.length)
			panelDescription.text(description);
			
			$(this).show();
			
			if(isFirst === false || $.isStringNotEmpty(current))
			$(this).trigger('fragment:update');
			
			$("a.hashFollow").hrefChangeHash(fragment);
			form.triggerHandler('tab:getInput').val(($.isStringNotEmpty(fragment))? fragment:'');
		})
		.on('tab:close', function() {
			panelNav.trigger('unselected');
			panel.hide();
		})
		.on('fragment:updated', function(event,fragment) {
			form.trigger('hash:change',[fragment]);
		});
		
		// form
		form.hashchange().on('tab:getTarget', function() {
			return panel;
		})
		.on('tab:getInput', function(event) {
			return $(this).find("input[name='-panel-']");
		})
		.on('tab:findHash', function(event,fragment) {
			return ($.isStringNotEmpty(fragment))? panel.filter("[data-fragment='"+fragment+"']"):panel.first();
		})
		.on('tab:init', function(event) {
			var target = $(this).triggerHandler('tab:findHash',[$.fragment()]);
			$(this).trigger('tab:changeOrFirst',[target]);
		})
		.on('hash:change', function(event,fragment) {
			var target = $(this).triggerHandler('tab:findHash',[fragment]);
			
			if(target.length === 1 && !$(this).triggerHandler('tab:isCurrent',[target]))
			target.trigger('tab:change');
		})
		.tab().trigger('tab:closeAll').trigger('tab:init');
	});
});