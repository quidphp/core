/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */
 
// event
// script containing custom events for jQuery
(function ($, document, window) {

	// scrollStart
	// gère l'événement scrollStart dans jQuery
	$.event.special.scrollstart = {
		uid: $.uniqueInt(),
		latency: 250,
		setup: function(data) {
			var current = $.event.special.scrollstart;
			var timer;
			var data = $.extend({latency: current.latency}, data);

			var handler = function(event) {
				var $this = this;
				var args = arguments;
				var dispatch = $.getEventDispatch();
				
				if(timer)
				clearTimeout(timer);
				
				else 
				{
					event.type = 'scrollstart';
					dispatch.apply($this,args);
				}

				timer = setTimeout(function() {
					timer = null;
				},data.latency);
			};

			$(this).bind('scroll',handler).data(current.uid,handler);
		},
		teardown: function() {
			var current = $.event.special.scrollstart;
			$(this).unbind('scroll',$(this).data(current.uid));
		}
	};


	// scrollStop
	// gère l'événement scrollStop dans jQuery
	$.event.special.scrollstop = {
		uid: $.uniqueInt(),
		latency: 250,
		setup: function(data) {
			var current = $.event.special.scrollstop;
			var timer;
			var data = $.extend({latency: current.latency}, data);
			
			var handler = function(event) 
			{
				var $this = this;
				var args = arguments;
				var dispatch = $.getEventDispatch();
				
				if(timer)
				clearTimeout(timer);

				timer = setTimeout(function() {
					timer = null;
					event.type = 'scrollstop';
					dispatch.apply($this, args);
				},data.latency);
			};

			$(this).bind('scroll',handler).data(current.uid,handler);
		},
		teardown: function() {
			var current = $.event.special.scrollstop;
			$(this).unbind('scroll',$(this).data(current.uid));
		}
	};
	
}(jQuery, document, window));