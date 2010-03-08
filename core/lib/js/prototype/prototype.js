
/**
 *	Extend prototype
 */

Element.addMethods({
  replaceClassName: function(element, className, replacement){
    if (!(element = $(element))) {return;}
    return element.removeClassName(className).addClassName(replacement);
  },

  /**
   * Scroll to an element but consider the fixed header
   *
   * @param	HtmlElement		element
   */
  scrollToElement: function(element) {
  	Todoyu.Ui.scrollToElement(element);

	return element;
  }
});


/**
 * Get todoyu style http headers (prefixed by 'Todoyu-')
 * @param	String		name
 */
Ajax.Response.addMethods({
	getTodoyuHeader: function(name) {
		return this.getHeader('Todoyu-' + name);
	},
	hasTodoyuError: function() {
		return this.getTodoyuHeader('error') == 1;
	},
	hasNoAccess: function() {
		//Todoyu.log('test');
		return this.getTodoyuHeader('noAccess') == 1;
	},
	hasPhpError: function() {
		return this.getPhpError() !== null;
	},
	getPhpError: function() {
		return this.getTodoyuHeader('Php-Error');
	}
});


/*
 * Orginal: http://adomas.org/javascript-mouse-wheel/
 * prototype extension by "Frank Monnerjahn" <themonnie@gmail.com>
 */
Object.extend(Event, {
	wheel: function (event){
		var delta = 0;
		if (!event) {
			event = window.event;
		}
		if( event.wheelDelta ) {
			delta = event.wheelDelta/120;
			if( window.opera ) {
				delta = -delta;
			}
		} else if (event.detail) { delta = -event.detail/3;	}
		return Math.round(delta); //Safari Round
	}
});
/*
 * enf of extension
 */


