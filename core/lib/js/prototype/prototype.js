
/**
 *	Extend element prototype
 */
Element.addMethods({

	/**
	 * Replace a class name on an element
	 *
	 * @param	{Element}	element
	 * @param	{String}	className
	 * @param	{String}	replacement
	 */
	replaceClassName: function(element, className, replacement){
		if (!(element = $(element))) {return;}
		return element.removeClassName(className).addClassName(replacement);
	},



	/**
	 * Get class names of an element
	 *
	 * @param	{Element}	element
	 */
	getClassNames: function(element) {
		return $w(element.className);
	},



	/**
	 * Scroll to an element but consider the fixed header
	 *
	 * @param	{Element}	element
	 */
	scrollToElement: function(element) {
		Todoyu.Ui.scrollToElement(element);

		return element;
	},



	/**
	 * Convert node to HTML string
	 *
	 * @param	{Element}	element
	 * @return	{String}
	 */
	toHTML: function(element) {
		var dummy = new Element('div');
		dummy.insert(element);

		return dummy.innerHTML;
	}

});


/**
 * Extend ajax response prototype
 */
Ajax.Response.addMethods({
	/**
	 * Get todoyu style http headers (prefixed by 'Todoyu-')
	 *
	 * @param	{String}		name
	 */
	getTodoyuHeader: function(name) {
		var header = this.getHeader('Todoyu-' + name);

		return header === null ? header : header.isJSON() ? header.evalJSON() : header;
	},



	/**
	 * Check whether a todoyu header was sent
	 *
	 * @param	{String}
	 */
	hasTodoyuHeader: function(name) {
		return this.getTodoyuHeader(name) !== null;
	},



	/**
	 * Check whether todoyu error was sent
	 *
	 * @return	{Boolean}
	 */
	hasTodoyuError: function() {
		return this.getTodoyuHeader('error') == 1;
	},


	/**
	 * Get todoyu error message
	 *
	 * @return	{String}
	 */
	getTodoyuErrorMessage: function() {
		return this.getTodoyuHeader('errorMessage');
	},



	/**
	 * Check whether no access header was sent
	 *
	 * @return	{Boolean}
	 */
	hasNoAccess: function() {
		return this.getTodoyuHeader('noAccess') == 1;
	},



	/**
	 * Check whether notLoggedIn header was sent
	 *
	 * @return	{Boolean}
	 */
	isNotLoggedIn: function() {
		return this.getTodoyuHeader('notLoggedIn') == 1;
	},



	/**
	 * Check whether a PHP error header was sent
	 *
	 * @return	{Boolean}
	 */
	hasPhpError: function() {
		return this.getPhpError() !== null;
	},



	/**
	 * Get the PHP error header
	 *
	 * @return	{String}
	 */
	getPhpError: function() {
		return this.getTodoyuHeader('Php-Error');
	},



	/**
	 * Get number of AC result items
	 *
	 * @return	{Number}
	 */
	getNumAcElements: function() {
		return Todoyu.Helper.intval(this.getTodoyuHeader('acElements'));
	},



	/**
	 * Check whether the result is an empty autocompleter result
	 *
	 * @return	{Boolean}
	 */
	isEmptyAcResult: function() {
		return this.getNumAcElements() === 0;
	}
});

/**
 * Add days to a date
 *
 * @param	{Number}	days		Amount of day
 * @param	{Boolean}	newDate		Create a new date instead updating this one
 * @return	{Date}
 */
Date.prototype.addDays = function(days, newDate) {
	var date = newDate ? new Date(this) : this;

	date.setDate(this.getDate() + days);

	return date;
};

/**
 * Check whether current date is today
 *
 * @return	{Boolean}
 */
Date.prototype.isToday = function() {
	var today = new Date();

	return this.getFullYear() === today.getFullYear() && this.getMonth() === today.getMonth() && this.getDate() === today.getDate();
};

/**
 * Set date to week start (monday morning)
 *
 * @retrun	{Date}
 */
Date.prototype.setToWeekStart = function() {
	var day		= this.getDay();
	var shift	= (day+6)%7;

	this.addDays(-shift, false);
	this.setHours(0, 0, 0);

	return this;
};

/**
 * Set date to week end (sunday night)
 *
 * @return	{Date}
 */
Date.prototype.setToWeekEnd = function() {
	var day = this.getDay();

	this.addDays(7-day, false);
	this.setHours(23, 59, 59);

	return this;
};

