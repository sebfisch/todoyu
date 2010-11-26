// Written by Michael Nelson
// Feel free to use / modify any of this

if(typeof Prototype == 'undefined')
  throw "Highcharts Adapter Error: Prototype has not been loaded.\n\tPlease load prototype prior to loading the highcharts adapter."

// Adapter interface between prototype and the highcarts charting library
var HighchartsAdapter = {
	effects_loaded : function(){return (typeof Effect != 'undefined');},
	
	// el needs an event to be attached. el is not necessarily a dom element
	addEvent: function (el, event, fn){
		try{
			Event.observe($(el), event, fn);
		} catch(e) {
			HighchartsAdapter._extend(el);
			el._highcharts_observe(event, fn);
		}
	},
	
	// motion makes things pretty. use it if effects is loaded, if not... still get to the end result.
	animate: function (el, params, options) {
	  if(options) $w('duration', 'delay').each(function(time){ options[time] = (!!(options[time]) ? options[time]/1000.0 : 0);});
	  var string = $H(params).collect(function(pair){ return [pair.key,pair.value].join(':');}).join(';');
	  
	  // higcharts 2.0+
	  if(el.element){
	    if(HighchartsAdapter.effects_loaded())
  	    for(key in params)
  	      new Effect.HighchartsTransition($(el), key, params[key], options);
	    else
	      for(key in params)
	        Element.writeAttribute($(el.element),  key, params[key]);
	  } else {
	    
		  if(HighchartsAdapter.effects_loaded() && $(el).morph)
    		$(el).morph(string, options);
    	else if($(el).setStyle)
    		$(el).setStyle(string)
		}
		
	},
	
	// this only occurs in higcharts 2.0+
	stop : function(el){
	  if(el._highcharts_extended && el._highchart_animation)
	    el._highchart_animation.cancel();
	},
	
	// um.. each
	each: function(arr, fn) { $A(arr).each(fn); },

	// fire an event based on an event name (event) and an object (el).
	// again, el may not be a dom element
	fireEvent: function(el, event, eventArguments, defaultFunction) { 
		if(event.preventDefault){
			defaultFunction = null;
		}
		
		try{
			el.fire(event, eventArguments);
		} catch(e) {
		  if(el._highcharts_extended)
			  el._highcharts_fire(event, eventArguments);
		}
		
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
		return str.replace(/([A-Z])/g, function(a, b){ return '-'+ b.toLowerCase() });
	},
	
	// um, map
	map: function(arr, fn) { return arr.map(fn); },
	
	// deep merge. merge({a : 'a', b : {b1 : 'b1', b2 : 'b2'}}, {b : {b2 : 'b2_prime'}, c : 'c'}) => {a : 'a', b : {b1 : 'b1', b2 : 'b2_prime'}, c : 'c'}
	merge: function(){
	  doCopy = function(copy, original) {
			var value;
			for (var key in original) {
				value = original[key];
				undef = typeof(value) === 'undefined';
				nil = value == null;
				same = original === copy[key];
				
				if(undef || nil || same) continue;
				
				obj = typeof(value) === 'object';
				arr = value && obj && value.constructor == Array;
				node = !!value.nodeType
				
				if(obj && !arr && !node) { 
					copy[key] = doCopy(copy[key] || {}, value);
				} else {
					copy[key] = original[key];
				}
			}
			return copy;
		}
		
		var args = arguments,
			retVal = {};

		for (var i = 0; i < args.length; i++) {
			retVal = doCopy(retVal, args[i])

		}
		return retVal;
	},
	
	warn : function(){
		try{ console.warn(arguments); } catch(e) {}
	},
	
	log : function(){
		try{ console.log(arguments); } catch(e) {}
	},
	
	// extend an object to handle highchart events (highchart objects, not svg elements). 
	// this is a very simple way of handling events but whatever, it works (i think)
	_extend : function(object){
		if(!object._highcharts_extended){
			Object.extend(object, { _highchart_events : {}, _highchart_animation : null, _highcharts_extended : true,
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
if(!HighchartsAdapter.effects_loaded())
	HighchartsAdapter.warn('effects.js was not loaded, animations will not occur.')
else {
  Effect.HighchartsTransition = Class.create(Effect.Base, {
    initialize : function(element, attribute, to, options){
      this.element = element.element ? $(element.element) : $(element);
      if (!this.element) throw(Effect._elementDoesNotExistError);
      var from = 0;
      try{ from = parseFloat(Element.readAttribute($(this.element), attribute));} catch(err) {};
      var opts = Object.extend((options || {}), {
        from : from,
        to : parseInt(to),
        attribute : attribute});
      this.start(opts);
    }, 
    setup : function(){
      HighchartsAdapter._extend(this.element);
      this.element._highchart_animation = this;
    },
    update : function(position){
      var el = $(this.element);
      var at = this.options.attribute;
      try{
        Element.writeAttribute(el, at, position);
      } catch(e) {
        eval("el." + at + " = " + position);
      }
    },
    finish : function(){
      this.element._highchart_animation = null;
    }
  });
}