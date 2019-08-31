/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// _helper
// script with a set of helper functions appended to the jQuery object
(function ($, document, window) {
	
	// isScalar
	// retourne vrai si la valeur est scalar
	$.isScalar = function(value) 
	{
		return (/boolean|number|string/).test(typeof value);
	}


	// isString
	// retourne vrai si la valeur est une string
	$.isString = function(value) 
	{
		return ($.type(value) === 'string')? true:false;
	};
	
	
	// isStringNotEmpty
	// retourne vrai si la valeur est une string non vide
	$.isStringNotEmpty = function(value) 
	{
		return ($.isString(value) && value)? true:false;
	};
	
	
	// isStringStart
	// retourne vrai si la string commence par needle
	$.isStringStart = function(needle,value)
	{
		return ($.isString(needle) && $.isString(value))? (value.slice(0,needle.length) == needle):false;
	};
	
	
	// isStringEnd
	// retourne vrai si la string finit par needle
	$.isStringEnd = function(needle,value)
	{
		return ($.isString(needle) && $.isString(value))? (value.slice(-needle.length) == needle):false;
	};
	
	
	// ucfirst
	// met la première lettre de la string uppercase
	$.ucfirst = function(value)
	{
		return ($.isStringNotEmpty(value))? value.charAt(0).toUpperCase() + value.slice(1):null;
	}
	
	
	// lcfirst
	// met la première lettre de la string lowercase
	$.lcfirst = function(value)
	{
		return ($.isStringNotEmpty(value))? value.charAt(0).toLowerCase() + value.slice(1):null;
	}
	

	// currentRelativeUri
	// retourne l'uri relative courante
	$.currentRelativeUri = function() 
	{
		return window.location.pathname + window.location.search;
	}
	
	
	// currentScheme
	// retourne le scheme courant
	$.currentScheme = function()
	{
		return location.protocol.substr(0, location.protocol.indexOf(':'));
	}
	
	
	// fragment
	// retourne le fragment de l'uri sans le hash
	$.fragment = function() 
	{
		return $.formatHash(window.location.hash);
	}
	
	
	// formatHash
	// permet de faire une hash avec ou sans le hash
	$.formatHash = function(value,symbol)
	{
		var r = null;
		
		if($.isStringNotEmpty(value))
		{
			r = value;
			var hasHash = (r.charAt(0) === "#")? true:false;
			
			if(symbol === true && !hasHash)
			r = "#"+r;
			
			else if(hasHash)
			r = r.substring(1);
		}
		
		return r;
	}
	
	
	// first
	// split une string et retourne le premier élément du split
	$.first = function(value,delimiter)
	{
		var r = null;
		
		if($.isStringNotEmpty(value) && $.isStringNotEmpty(delimiter))
		{
			value = value.split(delimiter);
			if($.isStringNotEmpty(value[0]))
			r = value[0];
		}
		
		return r;
	}
	
	
	// timestamp
	// retourne le timestamp courant
	$.timestamp = function() 
	{
	  return (new Date).getTime();
	}
	
	
	// uniqueInt
	// retourne un int jamais utilisé, utile pour générer des ids unique
	$.uniqueInt = (function(value)
	{
		var i = 0;
		return function() {
			return i++;
		};
	})()
	
	
	// indexer
	// retourne l'index du nouvel élément
	$.indexer = function(value,current,max)
	{
		var r = null;
		
		if(value instanceof jQuery && max instanceof jQuery)
		r = max.index(value);
		
		else
		{
			if(max instanceof jQuery)
			{
				if(current instanceof jQuery)
				current = max.index(current);
				
				max = max.length;
			}
			
			if($.isNumeric(max) && max > 0)
			{
				if(value === 'loopPrev')
				{
					if(!$.isNumeric(current) || current === 0)
					value = 'last';
					
					else
					value = 'prev';
				}
				
				else if(value === 'loopNext')
				{
					if(!$.isNumeric(current) || current === (max - 1))
					value = 'first';
					
					else
					value = 'next';
				}
				
				if(value === 'first')
				r = 0;
				
				else if(value ==='last')
				r = (max - 1);
				
				else if(value === 'next' && $.isNumeric(current))
				r = ((current + 1) < max)? (current+1):max;
				
				else if(value === 'prev' && $.isNumeric(current))
				r = ((current - 1) > 0)? (current - 1):0;
				
				else if(value === 'nextStrict' && $.isNumeric(current))
				r = ((current + 1) <= max)? (current+1):null;
				
				else if(value === 'prevStrict' && $.isNumeric(current))
				r = ((current - 1) >= 0)? (current - 1):null;
				
				else if($.isNumeric(value) && value >= 0 && value < max)
				r = value;
			}
		}
		
		return r;
	}
	
	
	// relativeUri
	// retourne une uri relative à partir d'une uri absolut
	$.relativeUri = function(uri,hash)
	{
		var r = null;
		
		if($.isString(uri))
		{
			var parse = $.parseUri(uri);
			r = parse.path;
			
			if(parse.query)
			r += "?"+parse.query;
			
			if(parse.hash && hash === true)
			r += "#"+parse.hash;
		}
		
		return r;
	}
	
	
	// isUriInternal
	// retourne vrai si l'uri et la comparaison ont le même scheme et host
	$.isUriInternal = function(uri,compare)
	{
		var r = false;
		
		if($.isString(uri))
		{
			compare = ($.isString(compare))? $.parseUri(compare):$.parseCurrentUri();			
			var parse = $.parseUri(uri);
			
			if(parse.scheme === compare.scheme && parse.host === compare.host)
			r = true;
		}
		
		return r;
	}
	
	
	// isUriExternal
	// retourne vrai si l'uri et la comparaison n'ont pas le même scheme et host
	$.isUriExternal = function(uri,compare)
	{
		return ($.isUriInternal(uri,compare))? false:true;
	}
	
	
	// isSamePathQuery
	// retourne vrai si l'uri est la même que la comparaison
	// compare path et query
	$.isSamePathQuery = function(uri,compare)
	{
		var r = false;
		
		if($.isString(uri))
		{
			compare = ($.isString(compare))? $.parseUri(compare):$.parseCurrentUri();			
			var parse = $.parseUri(uri);
			
			if(parse.path === compare.path && parse.query === compare.query)
			r = true;
		}
		
		return r;
	}
	
	
	// isSamePathQueryHash
	// retourne vrai si l'uri est la même que la comparaison
	// compare path, query et hash
	$.isSamePathQueryHash = function(uri,compare)
	{
		var r = false;
		
		if($.isString(uri))
		{
			compare = ($.isString(compare))? $.parseUri(compare):$.parseCurrentUri();			
			var parse = $.parseUri(uri);
			
			if(parse.path === compare.path && parse.query === compare.query && parse.hash === compare.hash)
			r = true;
		}
		
		return r;
	}
	
	
	// isHashChange
	// retourne vrai si l'uri est la même que la comparaison mais que le hash change
	$.isHashChange = function(uri,compare)
	{
		var r = false;
		
		if($.isString(uri))
		{
			compare = ($.isString(compare))? $.parseUri(compare):$.parseCurrentUri();
			var parse = $.parseUri(uri);
			
			if(parse.scheme === compare.scheme && parse.host === compare.host && parse.path === compare.path && parse.query === compare.query)
			{
				if($.isStringNotEmpty(parse.hash) && parse.hash !== compare.hash)
				r = true;
			}
		}
		
		return r;
	}
	
	
	// isHashSame
	// retourne vrai si l'uri est la même que la comparaison, que l'uri a un hash et que le hash est identique
	$.isHashSame = function(uri,compare)
	{
		var r = false;
		
		if($.isString(uri))
		{
			compare = ($.isString(compare))? $.parseUri(compare):$.parseCurrentUri();
			var parse = $.parseUri(uri);
			
			if(parse.scheme === compare.scheme && parse.host === compare.host && parse.path === compare.path && parse.query === compare.query)
			{
				if($.isStringNotEmpty(parse.hash) && parse.hash === compare.hash)
				r = true;
			}
		}
		
		return r;
	}
	
	
	// uriExtension
	// retourne l'extension du path de l'uri
	$.uriExtension = function(uri)
	{
		var r = null;
		
		if($.isString(uri))
		{
			var regex = /(?:\.([^.]+))?$/;
			var parse = $.parseUri(uri);
			var result = regex.exec(parse.path);
			
			if($.isArray(result) && result.length === 2)
			r = result[1];
		}
		
		return r;
	}
	
	
	// isUriHash
	// retourne vrai si l'uri a un hash
	$.isUriHash = function(uri)
	{
		var r = false;
		
		if($.isString(uri))
		{
			var parse = $.parseUri(uri);
			
			if($.isStringNotEmpty(parse.hash))
			r = true;
		}
		
		return r;
	}
	
	
	// isUriExtension
	// retourne vrai si l'uri a une extension
	$.isUriExtension = function(uri)
	{
		return ($.uriExtension(uri) != null)? true:false;
	}
	
	
	// parseCurrentUri
	// retourne un objet avec les différentes parties de l'uri courante séparés
	$.parseCurrentUri = function()
	{
		return {
			scheme: $.currentScheme(), 
			host: location.hostname, 
			path: location.path, 
			query: location.query, 
			hash: location.hash
		};
	}
	
	
	// parseUri
	// retourne un objet avec les différentes parties d'une uri séparés
	$.parseUri = function(uri)
	{
		var r = {};
		
		if($.isString(uri))
		{
			$dom = document.createElement('a');
			$dom.href = uri;
			
			r.scheme = $dom.protocol.substr(0, $dom.protocol.indexOf(':')) || $.currentScheme();
			r.host = $dom.hostname || location.hostname;
			r.port = $dom.port;
			r.path = $dom.pathname;
			r.query = $dom.search.substr($dom.search.indexOf('?') + 1);
			r.hash = $dom.hash.substr($dom.hash.indexOf('#'));
			
			$dom = null;
		}
		
		return r;
	}
	
	
	// parseHtmlDocument
	// parse une string html, retourne un objet jQuery
	// remplace les balises sensibles par des div (comme dans head et script)
	$.parseHtmlDocument = function(html)
	{
		var r = String(html);
		
		r = r.replace(/<\!DOCTYPE[^>]*>/i, '');
		r = r.replace(/<(html|head|body|title|meta|script|link)([\s\>])/gi,'<div data-tag="$1"$2');
		r = r.replace(/<\/(html|head|body|title|meta|script|link)\>/gi,'</div>');
		
		r = $.trim(r);
		r = $($.parseHTML(r));
		
		return r;
	}
	
	
	// parseDocument
	// parse une page html
	// retourne un objet avec les différents éléments décortiqués
	$.parseDocument = function(html)
	{
		var r = {
			doc: $.parseHtmlDocument(html),
			html: null,
			head: null,
			body: null,
			route: null,
			lang: null,
			classes: null,
			selected: null,
			title: '?',
			meta: null,
			bodyStyle: null,
			bodyClass: null
		};
		
		r.head = r.doc.find("[data-tag='head']").first();
		r.body = r.doc.find("[data-tag='body']").first();
		r.route = r.doc.attr("data-route") || null;
		r.selected = r.doc.attr("data-selected") || null;
		r.lang = r.doc.attr("lang") || null;
		r.classes = r.doc.attr('class') || null;
		
		if($.isStringNotEmpty(r.selected))
		r.selected = JSON.parse(r.selected);
		
		if(!r.body.length)
		{
			var newBody = "<div data-tag='body'>"+r.doc.outerHtml(true)+"</div>";
			r.body = $($.parseHTML(newBody));
		}
		
		if(r.head.length)
		{
			r.title = r.head.find("[data-tag='title']").first().text() || null;
			r.meta = r.head.find("[data-tag='meta']");
		}
		
		if(r.body.length)
		{
			r.bodyStyle = r.body.attr("style") || null;
			r.bodyClass = r.body.attr("class") || null;
		}
		
		return r;
	}
	
	
	// mailto
	// permet d'obtenir un email à partir d'un mailto (comme dans un href)
	$.mailto = function(value)
	{
		var r = null;
		
		if($.isStringNotEmpty(value))
		r = value.replace(/mailto:/,'');
		
		return r;
	}
	
	
	// isResponsive
	// retourne vrai si la fenêtre courante est responsive
	$.isResponsive = function() 
	{
		return ($(window).width() < 900)? true:false;
	}
	
	
	// isTouch
	// retourne vrai si le navigateur courant supporte le touch
	$.isTouch = function() 
	{
		return ($(document).data('isTouch') === true)? true:false;
	}
	
	
	// hasHistoryApi
	// retourne vrai si le navigateur courant supporte history API
	$.hasHistoryApi = function() 
	{
		var r = false;
		
		if(window.history && window.history.pushState && window.history.replaceState)
		{
			if(!navigator.userAgent.match(/((iPod|iPhone|iPad).+\bOS\s+[1-4]\D|WebApps\/.+CFNetwork)/))
			r = true;
		}
		
		return r;
	}
	
	
	// makeHistoryState
	// retourne un objet état d'historique (avec url, title et timestamp)
	$.makeHistoryState = function(uri,title) 
	{
		var r = null;
		
		if($.isString(uri))
		{
			r = {
				url: uri,
				title: title || null,
				timestamp: $.timestamp()
			};
		}
		
		return r;
	}
	
	
	// isHistoryState
	// retourne vrai si la valeur est un objet compatible pour un état d'historique
	$.isHistoryState = function(state)
	{
		var r = false;
		
		if($.isPlainObject(state) && $.isString(state.url) && $.isNumeric(state.timestamp))
		r = true;
		
		return r;
	}
	
	
	// isOldIe
	// retourne vrai si le navigateur est une vieille version de IE (IE8 ou moins)
	$.isOldIe = function() 
	{
		return (document.all && !document.addEventListener)? true:false;
	}
	
	
	// getEventDispatch
	// retourne le event dispatch de jquery, utilisé pour events
	$.getEventDispatch = function()
	{
		return $.event.dispatch || $.event.handle;
	}
	
	
	// isRegexNumericDash
	// retourne vrai si la valeur contient seulement des caractères numérique ou -
	$.isRegexNumericDash = function(value)
	{
		var r = false;
		var regex = new RegExp("^[0-9\-]+$");
		
		if($.isString(value) && regex.test(value))
		r = true;
		
		return r;
	}
	
	
	// areCookiesEnabled
	// retourne vrai si les cookies sont activés
	$.areCookiesEnabled = function()
	{
		var r = false;
		
		if(navigator.cookieEnabled) 
		r = true;
		
		else
		{
			document.cookie = "cookietest=1";
		    r = document.cookie.indexOf("cookietest=") != -1;
			document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";
		}

		return r;
	}
	
	
	// pour isTouch
	$(document).one('touchstart', function(event) {
		$(this).data('isTouch',true);
	});
	
	
}(jQuery, document, window));