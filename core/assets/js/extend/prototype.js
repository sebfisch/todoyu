
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
  	element = $(element);
	element.scrollTo();

	if( Todoyu.exists('header') ) {
		var headerHeight = $('header').getHeight();
		scrollBy(0, -headerHeight);
	}

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
	}
});


