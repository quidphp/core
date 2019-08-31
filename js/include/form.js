/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// form
// script with behaviours related to form and field validation
(function ($, document, window) {
	
	// validate
	// validate un élément de formulaire
	// utilise required et pattern
	$.fn.validate = function() 
	{
		var r = false;
		
		if($(this).length)
		{
			r = $(this).required();
			
			if(r === true)
			r = $(this).pattern();
		}
		
		return r;
	}
	
	
	// required
	// validate un élément de formulaire, uniquemment avec required
	// required peut être numérique pour checkbox et radio, à ce moment c'est un min count
	// les input disabled ne sont pas considéré
	$.fn.required = function() 
	{
		var r = false;
		
		if($(this).length)
		{
			r = true;
			
			$(this).each(function(index) {
				var disabled = $(this).prop('disabled');
				var required = $(this).data("required");
				
				if(!disabled && required && $.isNumeric(required))
				{
					if($(this).is("[type='checkbox'],[type='radio']"))
					{
						var checked = ($(this).prop("checked") === true)? 1:0;
						var group = $(this).inputGroup();
						var amount = group.filter(":checked").not($(this)).length;
						
						if((checked + amount) < required)
						r = false;
					}
					
					else if(!$(this).inputValue(true).length)
					r = false;
				}
				
				return r;
			});
		}
		
		return r;
	}
	
	
	// pattern
	// validate un élément de formulaire, uniquemment avec pattern
	// les input disabled ne sont pas considéré
	$.fn.pattern = function() 
	{
		var r = false;
		
		if($(this).length)
		{
			r = true;
			
			$(this).each(function(index) {
				var disabled = $(this).prop('disabled');
				var pattern = $(this).data("pattern");
				var val = $(this).inputValue(true);
				
				if(!disabled && $.isStringNotEmpty(pattern) && val.length)
				{
					pattern = new RegExp(pattern);
					r = pattern.test(val);
				}
				
				return r;
			});
		}
		
		return r;
	}
	
	
	// validateBlock
	// valide l'élément ou tous les éléments contenus dans le formulaire lors d'un événement
	// bloque l'événement si la validation échoue
	$.fn.validateBlock = function(type,fields) 
	{
		$(this).on(type, function(event) {
			
			if(!(fields instanceof jQuery))
			{
				var pattern = "[data-required],[data-pattern]";
				if($(this).is(pattern))
				fields = $(this);
				else
				fields = $(this).find(pattern);
			}

			if(fields.length)
			{
				var validate = fields.validate();
				
				if(validate !== true)
				{
					event.stopImmediatePropagation();
					event.preventDefault();
					fields.trigger("validate");
					$(this).trigger('validate:failed',[event]);
					return false;
				}
				
				else
				$(this).trigger('validate:success',[event]);
			}
		});
		
		return this;
	}
	
	
	// fieldValidate
	// gère les événements relatifs à la validation d'un champ
	$.fn.fieldValidate = function() 
	{
		$(this).on('isValid',function() {
			return ($(this).data('invalid') === true)? false:true;
		})
		.on('change', function() {
			$(this).trigger($(this).inputValue(true)? 'validate':'pattern');
		})
		.on('focus', function() {
			$(this).trigger("valid");
		})
		.on('focusout', function() {
			$(this).trigger("change");
		})
		.on('required', function() {
			$(this).trigger(($(this).required() === true)? 'valid':'invalid');
		})
		.on('pattern', function() {
			$(this).trigger(($(this).pattern() === true)? 'valid':'invalid');
		})
		.on('validate', function() {
			$(this).trigger(($(this).validate() === true)? 'valid':'invalid');
		})
		.on('valid', function() {
			if($(this).is("[type='checkbox'],[type='radio']"))
			$(this).inputGroup().not($(this)).removeData('invalid');
			
			$(this).removeData('invalid');
		})
		.on('invalid', function() {
			$(this).data('invalid',true);
		})
		
		return this;
	}
	
	
	// fieldValidateFull
	// gère les événements relatifs à la validation d'un champ, y compris l'ajout des classes
	$.fn.fieldValidateFull = function() 
	{
		$(this).fieldValidate().on('invalid', function() {
			$(this).addClass('invalid');
		})
		.on('valid', function() {
			if($(this).is("[type='checkbox'],[type='radio']"))
			$(this).inputGroup().not($(this)).removeClass('invalid');
			
			$(this).removeClass('invalid');
		});
		
		return this;
	}
	
	
	// formValidate
	// gère une validation standard pour un formulaire
	$.fn.formValidate = function(block,childs)
	{
		$(this).each(function(index, el) {
			var pattern = "[data-required],[data-pattern]";
			var fields = childs || $(this).find(pattern);
			$(this).validateBlock('submit',fields);
			
			fields.fieldValidateFull();
			
			if(block === true)
			{
				$(this).block('submit').on('submit', function() {
					if(block === true)
					$(this).trigger('block');
				})
			}
		});
		
		return this;
	}
	
	
	// formHasChanged
	// permet de détecter si un formulaire a changé ou non
	$.fn.formHasChanged = function()
	{
		$(this).on('form:hasChanged', function(event) {
			var r = false;
			var target = $(this).triggerHandler('form:getTarget') || $(this);
			var serialize = target.serialize();
			var original = $(this).data('form:serialize');
			
			if(serialize !== original)
			r = true;
			
			return r;
		})
		.on('form:preparehasChanged', function(event) {
			var target = $(this).triggerHandler('form:getTarget') || $(this);
			var serialize = target.serialize();
			$(this).data('form:serialize',serialize);
		})
		.trigger('form:preparehasChanged');
		
		return this;
	}
	
	
	// formUnload
	// permet d'ajouter un message d'alerte si le formulaire a changé et on tente de changer la page (unload)
	$.fn.formUnload = function()
	{
		var $this = $(this);
		$(this).formHasChanged().on('submit', function(event) {
			$(window).off('beforeunload');
		});;
		
		$(window).off('beforeunload').on('beforeunload', function() {
			if($this.triggerHandler('form:hasChanged'))
			return $this.data('unload');
		});
		
		return this;
	}
	
}(jQuery, document, window));