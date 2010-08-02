// Written by Michael Nelson http://www.mikenelsons.com
// Feel free to use / modify any of this

// whoa dude, load prototype... you fresh meat?
if(typeof Prototype == 'undefined')
{
	throw "Highcharts Adapter Error: Prototype has not been loaded.\n\tPlease load prototype prior to loading the highcharts adapter."
}

// Adapter interface between prototype and the highcarts charting library
var HighchartsAdapter = {
	
	// el needs an event to be attached. el is not necessarily a dom element
	addEvent: function (el, event, fn){
		if($(el).observe)
			Event.observe($(el), event, fn);
		else {
			HighchartsAdapter._extend(el);
			el._highcharts_observe(event, fn);
		}
	},
	
	// motion makes things pretty. use it if effects is loaded, if not... still get to the end result.
	animate: function (el, params, options) {
		
		var string = $H(params).collect(function(pair){ return [pair.key,pair.value].join(':');}).join(';');
		if(options) $w('duration', 'delay').each(function(time){ options[time] = (!!(options[time]) ? options[time]/1000.0 : 0);});
		if(typeof Effect != 'undefined' && $(el).morph)
			$(el).morph(string, options);
		else if($(el).setStyle)
			$(el).setStyle(string);
	},
	
	// um.. each
	each: function(arr, fn) { arr.each(fn); },
	
	// fire an event based on an event name (event) and an object (el).
	// again, el may not be a dom element
	fireEvent: function(el, event, eventArguments, defaultFunction) { 
		if(event.preventDefault){
			defaultFunction = null;
		}
		
		if($(el).observe)
			el.fire(event, eventArguments);
		else if(el._highcharts_extended)
			el._highcharts_fire(event, eventArguments);
		
		
		if(defaultFunction) defaultFunction(eventArguments);
	},
	
	removeEvent : function(el, event, handler){
	  if($(el).stopObserving)
	    el.stopObserving(el, event, handler);
	  else {
	    HighchartsAdapter._extend(el);
	    el._highcharts_stop_observing(event, handler);
	  }
	},
	
	// request data from <url>. for now, forcing json parsing even though this may change if highcharts uses other formats.
	// execute callback with the data
	getAjax: function (url, callback) { 
		new Ajax.Request(url, {
			method: 'get',
			evalJSON: 'force',
			onSuccess: function(obj) {
				callback(obj.responseJSON);
			}
		});
	},
	
	// um, grep
	grep: function(arr, fn) { return arr.findAll(fn); },
	
	// change leftPadding to left-padding
	hyphenate: function (str) { 
		return str.replace(/([A-Z])/g, function(a,b){ return '-' + a.charAt(0).toLowerCase(); });
	},
	
	// um, map
	map: function(arr, fn) { return arr.map(fn); },
	
	// deep merge. merge({a : 'a', b : {b1 : 'b1', b2 : 'b2'}}, {b : {b2 : 'b2_prime'}, c : 'c'}) => {a : 'a', b : {b1 : 'b1', b2 : 'b2_prime'}, c : 'c'}
	merge: function(){
		function doCopy(copy, original) {
			var value;
			for (var key in original) {
				value = original[key];
				if  (value && typeof value == 'object' && value.constructor != Array) { 
					copy[key] = doCopy(copy[key] || {}, value);

				} else {
					copy[key] = original[key];
				}
			}
			return copy;
		}
		function merge() {
			var args = arguments,
				retVal = {};

			for (var i = 0; i < args.length; i++) {
				retVal = doCopy(retVal, args[i])

			}
			return retVal;
		}
		return merge.apply(this, arguments);
	},
	
	warn : function(text){
		try{ console.warn(text); } catch(e) {}
	},
	
	log : function(thing){
		try{ console.log(thing); } catch(e) {}
	},
	
	// extend an object to handle higchart events. 
	// this is a very simple way of handling events but whatever, it works (i think)
	_extend : function(object){
		if(!object._highcharts_extended){
			Object.extend(object, { _highchart_events : {}, _highcharts_extended : true,
				_highcharts_observe : function(name, fn){
					this._highchart_events[name] = [this._highchart_events[name], fn].compact().flatten();
				},
				_highcharts_stop_observing : function(name, fn){
				  this._highchart_events[name] = [this._highchart_events[name]].compact().flatten().without(fn);
				},
				_highcharts_fire : function(name, args){
					(this._highchart_events[name] || []).each(function(fn){
						if(args && args.stopped) 
							return; // "throw $break" wasn't working. i think because of the scope of 'this'.
						fn.bind(this)(args);
					}.bind(this));
				}
			});
		}
	}
};


// warn about effects not being loaded
if(typeof Effect == 'undefined')
{
	HighchartsAdapter.warn('effects.js was not loaded, animations will not occur.')
}