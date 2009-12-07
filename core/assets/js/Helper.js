/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2,
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

Todoyu.Helper = {

	/**
	 *	Convert value to Integer
	 *
	 *	@param	Mixed	mixedvar
	 */
	intval: function(mixedvar) {
		var type = typeof( mixedvar );
		var temp;

		switch(type) {
			case 'boolean':
				return mixedvar ? 1 : 0;
				break;


			case 'string':
				temp = parseInt(mixedvar, 10);
				return isNaN(temp) ? 0 : temp;
				break;


			case 'number':
				return Math.floor(mixedvar);
				break;

			default:
				return 0;
		}
	},



	/**
	 *	Convert to 2-digit value (possibly add leading zero)
	 *
	 *	@param	Mixed	number
	 */
	twoDigit: function(number) {
		number = parseInt(number, 10);

		if( number < 10 ) {
			number = '0' + number;
		}

		return number;
	},



	/**
	 *	Toggle source of image
	 *
	 *	@param	unknown_type	idImage
	 *	@param	unknown_type	src1
	 *	@param	unknown_type	src2
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
	 *	Round with given precision
	 *
	 *	@param	unknown_type	value
	 *	@param	unknown_type	precision
	 */
	round: function(value, precision) {
		value		= parseFloat(value);
		precision	= this.intval(precision);
		var factor	= Math.pow(10, precision);

		return Math.round((value*factor))/factor;
	},



	/**
	 *	Check whether given obj. is set
	 *
	 *	@param	unknown_type	objToTest
	 */
	isset: function(objToTest) {
		if (null === objToTest || 'undefined' == typeof(objToTest)) {
			return false;
		}

		return true;
	},



	/**
	 *	Fire event
	 *
	 *	@param	Element		element
	 *	@param	String		event e.g. 'click'
	 */
	fireEvent: function(element, event){
		if (document.createEventObject){
				// dispatch for IE
			var evt = document.createEventObject();
			return element.fireEvent('on'+event,evt)
		} else {
				// dispatch for firefox + others
			var evt = document.createEvent("HTMLEvents");
			evt.initEvent(event, true, true ); // event type,bubbling,cancelable
			return !element.dispatchEvent(evt);
		}
	}

};