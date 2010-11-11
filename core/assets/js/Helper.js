/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * @module	Core
 */

/**
 * General helper functions
 *
 * @class		Helper
 * @namespace	Todoyu
 */
Todoyu.Helper = {

	/**
	 * Convert value to Integer
	 *
	 * @method	intval
	 * @param	{String|Boolean|Number}		mixedvar
	 * @return	{Number}
	 */
	intval: function(mixedvar) {
		var type = typeof( mixedvar );
		var temp;

		switch(type) {
			case 'boolean':
				return mixedvar ? 1 : 0;

			case 'string':
				temp = parseInt(mixedvar, 10);
				return isNaN(temp) ? 0 : temp;

			case 'number':
				return Math.floor(mixedvar);

			default:
				return 0;
		}
	},



	/**
	 * Convert to 2-digit value (possibly add leading zero)
	 *
	 * @method	twoDigit
	 * @param	{String|Number}		number
	 * @return	{String}
	 */
	twoDigit: function(number) {
		number = parseInt(number, 10);

		if( number < 10 ) {
			number = '0' + number;
		}

		return number;
	},



	/**
	 * Toggle source of image
	 *
	 * @method	toggleImage
	 * @param	{String}		idImage
	 * @param	{String}		src1
	 * @param	{String}		src2
	 */
	toggleImage: function(idImage, src1, src2) {
		var image = $(idImage);

		if( image.src.indexOf(src1) === -1 ) {
			image.src = src1;
		} else {
			image.src = src2;
		}
	},



	/**
	 * Round with given precision
	 *
	 * @method	round
	 * @param	{Number}		value
	 * @param	{Number}	precision
	 * @return	{Number}
	 */
	round: function(value, precision) {
		value		= parseFloat(value);
		precision	= this.intval(precision);
		var factor	= Math.pow(10, precision);

		return Math.round((value*factor))/factor;
	},



	/**
	 * Uppercase the first character of every word in a string
	 *
	 * @method	ucwords
	 * @param	{String}	str
	 * @return	{String}
	 */
	ucwords: function(str) {
		return (str + '').replace(/^(.)|\s(.)/g, function ($1) {
			return $1.toUpperCase();
		});
	},



	/**
	 * Wraps buffer to selected number of characters using string break char
	 *
	 * Borrowed from phpjs	http://phpjs.org/functions/wordwrap
	 * version: 909.322
	 *
	 * @method	wordwrap
	 * @param	{String}		str
	 * @param	{Number}		int_width
	 * @param	{String}		str_break
	 * @param	{Boolean}		cut
	 * @return	{String}
	 */
	wordwrap: function(str, int_width, str_break, cut) {
		var m = ((arguments.length >= 2) ? arguments[1] : 75 );
		var b = ((arguments.length >= 3) ? arguments[2] : "\n" );
		var c = ((arguments.length >= 4) ? arguments[3] : false);

		var i, j, l, s, r;

		str += '';
		if(m < 1) {
			return str;
		}
		 for (i = -1, l = (r = str.split(/\r\n|\n|\r/)).length; ++i < l; r[i] += s) {
			for (s = r[i], r[i] = ""; s.length > m; r[i] += s.slice(0, j) + ((s = s.slice(j)).length ? b : "")){
				j = c == 2 || (j = s.slice(0, m + 1).match(/\S*(\s)?$/))[1] ? m : j.input.length - j[0].length || c == 1 && m || j.input.length + (j = s.slice(m).match(/^\S*/)).input.length;
			}
		}
		return r.join("\n");
	},



	/**
	 * Fire event
	 *
	 * @method	fireEvent
	 * @param	{Element}		element
	 * @param	{String}		event e.g. 'click'
	 * @return	{String|Object}
	 */
	fireEvent: function(element, event){
		var evt;

		if(document.createEventObject){
				// dispatch for IE
			evt = document.createEventObject();

			return element.fireEvent('on' + event, evt);
		} else {
				// dispatch for firefox + others
			evt = document.createEvent('HTMLEvents');
			evt.initEvent(event, true, true ); // event type, bubbling, cancelable

			return ! element.dispatchEvent(evt);
		}
	},



	/**
	 * Check whether client is given browser (e.g. 'chrome', 'safari')
	 *
	 * @method	isNavigatorUserAgent
	 * @param	{String}	browserName
	 * @return	{Boolean}
	 */
	isNavigatorUserAgent: function(browserName) {
		browserName	= browserName.toLowerCase();

		return navigator.userAgent.toLowerCase().indexOf(browserName) > -1;
	},



	/**
	 * Check whether used client browser is google chrome
	 *
	 * @method	isChrome
	 * @return	{Boolean}
	 */
	isChrome: function() {
		return this.isNavigatorUserAgent('chrome');
	},



	/**
	 * Check whether used client browser is apple safari
	 *
	 * @method	isSafari
	 * @return	{Boolean}
	 */
	isSafari: function() {
		return this.isNavigatorUserAgent('safari');
	},



	/**
	 * Set element scrollTop, circumventing refresh bug in safari + chrome
	 *
	 * @method	setScrollTop
	 * @param	{Element}	element
	 * @param	{Number}	position
	 */
	setScrollTop: function(element, position) {
		element.scrollTop = position;

		if( this.isChrome() || this.isSafari() ) {
			this.onUpdateChromeSafariScrollTop(element.id, 0);
		}
	},



	/**
	 * Safari + Chrome workaround: defered window refresh to update after modification of scrollTop
	 *
	 * @method	onUpdateChromeSafariScrollTop
	 * @param	{String}	elementID
	 * @param	{Number}	step
	 */
	onUpdateChromeSafariScrollTop: function(elementID, step) {
		switch(step) {
			case 0: case 1:
				$(elementID).style.overflow = ( step == 0 ) ? 'scroll' : '';
				break;
			case 2: case 3:
				window.scrollBy(0,( step == 2 ) ? 1 : -1 );
				break;
		}

		step++;
		if( step < 4 ) {
			this.onUpdateChromeSafariScrollTop.defer(elementID, step)
		}
	}

};