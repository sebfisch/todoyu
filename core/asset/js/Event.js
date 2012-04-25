/****************************************************************************
 * todoyu is published under the BSD License:
 * http://www.opensource.org/licenses/bsd-license.php
 *
 * Copyright (c) 2012, snowflake productions GmbH, Switzerland
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
 *	Todoyu event functions
 *
 * @class		Event
 * @namespace	Todoyu
 * @type		{Object}
 */
Todoyu.Event = {

	/**
	 * Fire event
	 *
	 * @method	fireEvent
	 * @param	{Element}		element
	 * @param	{String}		eventType e.g. 'click'
	 * @return	{String|Object}
	 */
	fireEvent: function(element, eventType, x, y){
		var evt;

		if( ! document.createEvent ){
				// Dispatch for IE 8 (9 works as normal browser)
			evt = document.createEventObject();

			return element.fireEvent('on' + eventType, evt);
		} else {
				// Dispatch for firefox + others
			evt = document.createEvent('HTMLEvents');
			evt.initEvent(eventType, true, true); // event type, bubbling, cancelable

			return ! element.dispatchEvent(evt);
		}
	}

};