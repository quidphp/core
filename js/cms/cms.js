/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// cms
// script of common behaviours for all pages of the CMS
$(document).ready(function() {
	
	// window
	$(window).on("pageshow", function(event) {
		if(event.originalEvent.persisted)
	    window.location.href = window.location.href;
	});
	
	// route:common
	$(this).on('route:common', function(event) {
		var body = $(this).find("body");
		var jsBox = $(this).find(".jsBox");
		var jsBoxAnchor = $(this).find("a[data-js-box]");
		var anchorCorner = $(this).find(".anchorCorner");
		var calendar = $(this).find("main .popup .calendar");
		var aConfirm = $(this).find("a[data-confirm]");
		var print = $(this).find(".submit.print");
		var burger = $(this).find("header .burgerMenu");
		
		// jsBoxAjax
		if(jsBox.length === 1)
		{
			jsBox.jsBox();
			jsBoxAnchor.jsBoxAjax(jsBox);
		}
		
		// anchorCorner
		if(anchorCorner.length)
		anchorCorner.anchorCorner('mouseover');
		
		// calendar
		calendar.calendar();
		
		// aConfirm
		aConfirm.confirm('click');
		
		// com
		$(this).trigger('route:commonCom');
		
		// print
		print.on('click', function(event) {
			window.print();
		});
		
		// burger
		burger.on('click', function(event) {
			body.toggleClass('responsiveMenuOpen');
		});
	})
	
	// route:common:com
	.on('route:commonCom', function(event) {
		
		var com = $(this).find("body #wrapper > .com .box");
		
		com.block('click').on('click', '.close', function() {
			com.trigger('com:close');
		})
		.on('click', '.date', function(event) {
			com.trigger(com.hasClass('slideClose')? 'com:slideDown':'com:slideUp');
		})
		.on('click', ".row.insert > span,.row.update > span", function(event) {
			var parent = $(this).parent();
			var table = parent.data('table');
			var primary = parent.data('primary');
			com.trigger('redirect',[table,primary]);
		})
		.on('com:slideUp', function(event) {
			$(this).addClass('slideClose');
			$(this).find('.bottom').stop(true,true).slideUp('fast');
		})
		.on('com:slideDown', function(event) {
			$(this).removeClass('slideClose');
			$(this).find('.bottom').stop(true,true).slideDown('fast');
		})
		.on('com:close', function(event) {
			$(this).parent(".com").stop(true,true).fadeOut("slow");
		})
		.on('redirect', function(event,table,primary) {
			var href = $(this).dataHrefReplaceChar(table);
			
			if($.isStringNotEmpty(href))
			{
				$(this).trigger('block');
				href = href.replace($(this).data('char'),primary);
				$(document).trigger('navigation:push',[href]);
			}
		});
	});
});