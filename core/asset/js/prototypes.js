/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
 * Add round with precision parameter to number type
 */
Object.extend(Number.prototype, {
	round: function(precision) {
		var factor	= Math.pow(10, precision || 0);
		return Math.round(factor * this)/factor;
	}
});


/**
 * Array.sum()
 */
Array.prototype.sum = function(){
	for(var i=0,sum=0;i<this.length;sum+=this[i++]){};
	return sum;
};



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


