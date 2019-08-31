/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// fn
// script with some common fn functions added to jQuery
(function ($, document, window) {
	
	// tagName
	// retourne le nom de la tag en lowerCase
	$.fn.tagName = function() 
	{
		return $(this).prop("tagName").toLowerCase();
	};
	
	
	// heightWithPadding
	// retourne la hauteur avec le padding top et bottom
	$.fn.heightWithPadding = function()
	{
		return $(this).height() + parseInt($(this).css("padding-top")) + parseInt($(this).css("padding-bottom"));
	}
	
	
	// outerHtml
	// retourne le outerHtml d'un élément jQuery
	// si pas de outerHtml, peut aussi retourner le html ou le texte
	$.fn.outerHtml = function(htmlOrText)
	{
		var r = $(this).prop('outerHTML');
		
		if(htmlOrText === true && r == null)
		r = $(this).html() || $(this).text();
		
		return r;
	}
	
	
	// removeClassStart
	// enlève les classes des éléments qui commencent par
	$.fn.removeClassStart = function(prefix)
	{
		if($.isStringNotEmpty(prefix))
		{
			$(this).removeClass(function(index,classNames) {
				var array = [];
				$(classNames.split(" ")).each(function(index, value) {
					if($.isStringStart(prefix,value))
					array.push(value);
				});
				return array.join(" ");
			});
		}
		
		return this;
	}
	
	
	// offsetCorner
	// retourne l'objet offset du premier élément avec les propriétés y, x, topBottom, leftRight et corner en plus
	$.fn.offsetCorner = function()
	{
		var r = $(this).offset();
		r.y = r.top - $(window).scrollTop();
		r.x = r.left - $(window).scrollLeft();
		r.topBottom = (r.y > ($(window).height() / 2))? 'bottom':'top';
		r.leftRight = (r.x > ($(window).width() / 2))? 'right':'left';
		r.corner = r.topBottom+$.ucfirst(r.leftRight);
		
		return r;
	}
	
	
	// hasHandler
	// retourne vrai si le ou les éléments ont un handler pour l'événement donné en argument
	$.fn.hasHandler = function(type) 
	{
		var r = false;
		
		if($.isStringNotEmpty(type))
		{
			$(this).each(function(index) {
				var events = $._data($(this)[0],'events');
				r = events.hasOwnProperty(type);
				return r;
			});
		}
		
		return r;
	}
	
	
	// triggerHandlerTrue
	// appel un événement via triggerHandler
	// retourne true si un des éléments retourne true
	$.fn.triggerHandlerTrue = function(type)
	{
		var r = false;
		
		if($.isStringNotEmpty(type))
		{
			$(this).each(function(index) 
			{
				if($(this).triggerHandler(type))
				{
					r = true;
					return false;
				}
			});
		}
		
		return r;
	}
	
	
	// triggerHandlerFalse
	// appel un événement
	// retourne faux si un des éléments retourne faux
	$.fn.triggerHandlerFalse = function(type)
	{
		var r = false;
		
		if($.isStringNotEmpty(type))
		{
			$(this).each(function(index) {
				return r = $(this).triggerHandler(type);
			});
		}
		
		return r;
	}
	
	
	// dataHrefReplaceChar
	// permet de changer le caractère de remplacement sur une balise avec attribut data-href
	$.fn.dataHrefReplaceChar = function(replace,replace2) 
	{
		var r = null;
		
		if($.isNumeric(replace))
		replace = String(replace);
		
		if($.isNumeric(replace2))
		replace2 = String(replace2);
		
		if($(this).length === 1 && $.isStringNotEmpty(replace))
		{
			var href = $(this).data("href");
			var char = $(this).data("char");
			
			if($.isStringNotEmpty(href) && $.isStringNotEmpty(char))
			{
				r = href.replace(char,replace);
				
				if($.isStringNotEmpty(replace2))
				r = r.replace(char,replace2);
			}
		}
		
		return r;
	}
	
	
	// valSet
	// prend un set de input et textarea et crée un set avec les valeurs
	// un séparateur peut être fourni
	$.fn.valSet = function(separator,trim) 
	{
		var r = '';
		
		if($.isStringNotEmpty(separator))
		{
			$(this).each(function(index) {
				r += (r.length)? separator:"";
				r += $(this).inputValue(trim);
			});
		}
		
		return r;
	}
	
	
	// anchorCorner
	// applique une clase à l'élément, sert à identifier le coin de l'écran dans lequel se trouve l'élément
	$.fn.anchorCorner = function(type)
	{
		if($.isStringNotEmpty(type))
		{
			$(this).on(type, function(event) {
				var offset = $(this).offsetCorner();
				$(this).removeClass("topLeft topRight bottomLeft bottomRight");
				$(this).addClass(offset.corner);
			});
		}
		
		return this;
	}


	// hrefChangeHash
	// change le hash sur des balises a avec attribut href
	$.fn.hrefChangeHash = function(fragment)
	{
		if($.isString(fragment))
		{
			$(this).each(function() {
				$(this)[0].hash = fragment;
			});
		}
		
		return this;
	}
	

	// inputGroup
	// retourne un objet jQuery avec tous les inputs de même type et même nom
	$.fn.inputGroup = function()
	{
		var $this = $();
		
		$(this).each(function(index) {
			var name = $(this).prop("name");
			var type = $(this).prop("type");
			
			if($.isStringNotEmpty(name) && $.isStringNotEmpty(type))
			$this = $this.add($("[type='"+type+"'][name='"+name+"']"));
		});
		
		return $this;
	}
	

	// block
	// bloque l'événement sur le ou les éléments s'il y a la data blocked
	$.fn.block = function(type) 
	{
		if($.isStringNotEmpty(type))
		{
			$(this).on(type, function(event) 
			{
				if($(this).data("blocked") != null)
				{
					event.stopImmediatePropagation();
					event.preventDefault();
					$(this).trigger('blocked');
					return false;
				}
			})
			.on('block', function(event) {
				event.stopImmediatePropagation();
				$(this).data("blocked",true);
			})
			.on('unblock', function(event) {
				event.stopImmediatePropagation();
				$(this).removeData("blocked");
			});
		}
		
		return this;
	}
	
	
	// enterBlock
	// bloque la touche enter sur keypress et keyup, associé à l'élément
	$.fn.enterBlock = function()
	{
		$(this).on('keypress', function(event) {
			$(this).trigger('enter:block',[event]);
		})
		.on('keyup', function(event) {
			$(this).trigger('enter:block',[event]);
		})
		.on('enter:block', function(event,keyEvent) {
			if(keyEvent.keyCode === 10 || keyEvent.keyCode === 13)
			{
				keyEvent.stopImmediatePropagation();
				keyEvent.preventDefault();
				$(this).trigger('enter:blocked');
			}
		});
		
		return this;
	}
	
	
	// arrowCatch
	// attrape les touches de flèche sur le clavier
	$.fn.arrowCatch = function(preventDefault) 
	{
		$(this).each(function()
		{
			var $this = $(this);
			
			$(document).keydown(function(event) {
				if($.inArray(event.keyCode,[37,38,39,40]) !== -1)
				{
					if(event.keyCode === 38)
					$this.trigger('arrowUp:catched',[event]);
					
					else if(event.keyCode === 40)
					$this.trigger('arrowDown:catched',[event]);
					
					else if(event.keyCode === 37)
					$this.trigger('arrowLeft:catched',[event]);
					
					else if(event.keyCode === 39)
					$this.trigger('arrowRight:catched',[event]);
					
					if(preventDefault === true)
					{
						event.preventDefault();
						return false;
					}
				}
			});
			
			$(this).find("input,textarea").keydown(function(event) {
				if($.inArray(event.keyCode,[37,38,39,40]) !== -1)
				event.stopImmediatePropagation();
			});
		});
		
		return this;
	}
	
	
	// escapeCatch
	// attrape la touche escape sur keyup, associé au document
	$.fn.escapeCatch = function()
	{
		$(this).each(function()
		{
			var $this = $(this);
			
			$(document).keyup(function(event) {
				if(event.keyCode === 27)
				$this.trigger('escape:catched',[event]);
			});
		});
		
		return this;
	}
	
	
	// timeout
	// permet d'appliquer un timeout sur un événement
	$.fn.timeout = function(type,timeout)
	{
		if($.isStringNotEmpty(type))
		{
			$(this).each(function(index) 
			{
				var delay = timeout || $(this).data(type+"Delay");
				
				if($.isNumeric(delay))
				{
					$(this).on(type+':setTimeout',function() {
						var $this = $(this);
						var $type = type;
						$(this).trigger(type+':clearTimeout');
						$(this).data(type+"Timeout",setTimeout(function() {
							$this.trigger($type+':onTimeout');
						},delay));
					})
					.on(type+':clearTimeout',function() {
						var oldTimeout = $(this).data(type+"Timeout");
						
						if(oldTimeout != null)
						clearTimeout(oldTimeout);
					})
					.on(type, function() {
						$(this).trigger(type+':setTimeout');
					});
				}
			});
		}
		
		return this;
	}
	
	
	// fragment
	// gère les changements de fragment
	$.fn.fragment = function() 
	{
		$(this).on('fragment:get', function(event) {
			return $(this).data('fragment');
		})
		.on('fragment:update', function(event) {
			var current = $.fragment();
			var fragment = $(this).triggerHandler('fragment:get');
			
			if(current !== fragment)
			{
				if($.isStringNotEmpty(fragment))
				window.location.hash = '#'+fragment;
				else
				$(this).trigger('fragment:remove');
				
				$(this).trigger('fragment:updated',[fragment]);
			}
		})
		.on('fragment:remove', function(event) {
			window.location.hash = '';
		});
		
		return this;
	}
	
	
	// hashchange
	// renvoie l'événement haschange aux objets jquerys
	$.fn.hashchange = function()
	{
		var $this = $(this);
		$(window).on('hashchange', function(event,sourceEvent) {
			$this.trigger('hash:change',[$.fragment(),sourceEvent]);
		});
		
		return this;
	}
	
	
	// alert
	// lance un message d'alerte lorsqu'un événement est triggé
	$.fn.alert = function(type)
	{
		if($.isStringNotEmpty(type))
		{
			$(this).on(type, function(event) {
				var alertText = $(this).data('alert');
				
				if($.isStringNotEmpty(alertText))
				alert(alertText);
			});
		}
		
		return this;
	}
	
	
	// confirm
	// demande une confirmation avant d'envoyer le formulaire
	// empêche le submit si confirm est faux
	$.fn.confirm = function(type) 
	{
		if($.isStringNotEmpty(type))
		{
			$(this).on(type, function(event) {
				var confirmText = $(this).data('confirm');
				
				if($.isStringNotEmpty(confirmText) && !confirm(confirmText))
				{
					event.stopImmediatePropagation();
					event.preventDefault();
					$(this).trigger('notConfirmed',[event]);
					return false;
				}
				
				else
				$(this).trigger('confirmed',[event]);
			});
		}
		
		return this;
	}
	

	// resizeChange
	// permet de notifier un objet jQuery du redimensionnement de l'écran
	$.fn.resizeChange = function()
	{
		var $this = $(this);
		$(window).on('scroll', function(event) {
			$this.trigger('resize:change');
		});
		
		return this;
	}
	
	
	// scrollChange
	// permet de notifier un objet jQuery du changement de scroll
	$.fn.scrollChange = function()
	{
		var $this = $(this);
		$(window).on('scroll', function(event) {
			$this.trigger('scroll:change');
		});
		
		return this;
	}
	
	
	// focusFirst
	// met le focus sur le premier input vide
	$.fn.focusFirst = function()
	{
		$(this).filter(function() {
			return !$(this).inputValue(true);
		}).first().focus();
		
		return this;
	}
	
	
	// labels
	// retourne le ou les labels liés à un élément de formulaire
	$.fn.labels = function()
	{
		var r = $();
		var id = $(this).prop('id');
		
		if($.isScalar(id))
		r = $(document).find("label[for='"+id+"']");
		
		return r;
	}
	
	
	// addIds
	// ajoute un id aux éléments contenus dans l'objet qui n'en ont pas
	$.fn.addIds = function(base)
	{
		$(this).not("[id]").each(function(index, el) {
			var newId = base+$.uniqueInt();
			var labels = $(this).labels();
			$(this).prop('id',newId);
			labels.prop('for',newId);
		});
		
		return this;
	}
	
	
	// refreshIds
	// rafraîchis tous les ids contenus dans l'objet
	$.fn.refreshIds = function()
	{
		$(this).find("[id]").each(function(index, el) {
			var id = $(this).prop('id');
			var labels = $(this).labels();
			var newId = id+$.uniqueInt();
			$(this).prop('id',newId);
			labels.prop('for',newId);
		});
		
		return this;
	}
	
	
	// inputValue
	// retourne la valeur pour un input ou un fakeinput
	// la valeur retourné peut être trim
	$.fn.inputValue = function(trim)
	{
		var r = null;

		if($(this).triggerHandler('isFakeInput') === true)
		r = $(this).triggerHandler('getValue');
		
		else
		r = $(this).val();
		
		if(r == null)
		r = '';
		
		if(trim === true)
		r = String(r).trim();
		
		return r;
	}
	
	
	// clickRemove
	// sur click, fadeOut l'élément et ensuite efface le
	$.fn.clickRemove = function()
	{
		$(this).on('click', function(event) {
			$(this).fadeOut('slow',function() {
				$(this).remove();
			});
		});
		
		return this;
	}
	
	
	// wrapConsecutiveSiblings
	// permet d'enrobber toutes les prochaines balises répondant à until dans une balise html
	$.fn.wrapConsecutiveSiblings = function(until,html)
	{
		if(until && $.isStringNotEmpty(html))
		{
			$(this).each(function(index, el) {
				var nextUntil = $(this).nextUntil(until);
				$(this).add(nextUntil).wrapAll(html);
			});
		}
		
		return this;
	}
	
	
	// hoverClass
	// permet d'ajouter et retirer une classe au mouseover/mouseout
	$.fn.hoverClass = function(className)
	{
		if($.isStringNotEmpty(className))
		{
			$(this).on('mouseenter', function(event) {
				$(this).addClass(className);
			})
			.on('mouseleave', function(event) {
				$(this).removeClass(className);
			});
		}
		
		return this;
	}
	
	
	// aExternalBlank
	// ajout target _blank à tous les liens externes qui n'ont pas la target
	$.fn.aExternalBlank = function()
	{
		$(this).find("a:external[target!='_blank']").each(function(index, el) {
			$(this).prop('target','_blank');
		});
		
		return this;
	}
	
	
	// smallWindow
	// permet l'ouverture d'une smallWindow
	// tous les paramètres de la window sont dans la balise
	$.fn.smallWindow = function()
	{
		$(this).addIds('smallWindow');
		$(this).on('click', function(event) {
			var win = window;
			var href = $(this).attr('href');
			var id = $(this).prop('id');
			var width = $(this).data('width') || 1000;
			var height = $(this).data('height') || 1000;
			var x = $(this).data('x') || 0;
			var y = $(this).data('y') || 0;
			
			if($.isNumeric(width) && $.isNumeric(height) && $.isNumeric(x) && $.isNumeric(y))
			{
				event.preventDefault();
				var param = "toolbar=no ,left="+x+",top="+y+",width="+width+",height="+height+",location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no";
				var child = win.open(href,id,param);
				child.focus();
				win.blur();
				return false;
			}
		});
		
		return this;
	}
	
	
	// addClassToLink
	// permet d'ajouter une classe à toutes les uris données en premier argument
	// uris doit être un tableau
	$.fn.addClassToLink = function(uris,classname)
	{
		if($.isArray(uris) && $.isStringNotEmpty(classname))
		{
			var $this = $(this);
			$(uris).each(function(index, uri) {
				$this.find("a[href='"+uri+"']").addClass(classname);
			});
		}
		
		return this;
	}
	
}(jQuery, document, window));