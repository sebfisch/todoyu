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
 * Time related helper functions
 *
 * @class		Time
 * @namespace	Todoyu
 */
Todoyu.Time = {

	/**
	 * @property	seconds
	 * @type		Object
	 */
	seconds: {
		minute:	60,
		hour:	3600,
		day:	86400,
		week:	604800,
		month:	2592000
	},



	/**
	 * Format given time, to e.g. '13:50:20'
	 *
	 * @method	timeFormat
	 * @param	{Number}		hours
	 * @param	{Number}		minutes
	 * @param	{Number}		seconds
	 * @param	{String}		separator
	 * @return	{String}
	 */
	timeFormat: function(hours, minutes, seconds, separator) {
		if( Object.isUndefined(separator) ) {
			separator = ':';
		}

		return Todoyu.Helper.twoDigit(hours) + separator + Todoyu.Helper.twoDigit(minutes) + separator + Todoyu.Helper.twoDigit(seconds);
	},



	/**
	 * Format given time
	 *
	 * @method	timeFormatSeconds
	 * @param	{String}	time
	 * @param	{String}	separator
	 * @return	{String}
	 */
	timeFormatSeconds: function(time, separator) {
		var timeParts = this.getTimeParts(time);

		return this.timeFormat(timeParts.hours, timeParts.minutes, timeParts.seconds, separator);
	},



	/**
	 * Parse given time string to seconds
	 *
	 * @method	parseTimeToSeconds
	 * @param	{String}	timeString
	 * @return	{String}
	 */
	parseTimeToSeconds: function(timeString) {
		var parts	= timeString.stripTags().split(':');

		return Todoyu.Helper.intval(parts[0]) * this.seconds.hour + (Todoyu.Helper.intval(parts[1]) * this.seconds.minute) + Todoyu.Helper.intval(parts[2]);
	},



	/**
	 * Get time parts of given (timestamp) time
	 *
	 * @method	getTimeParts
	 * @param	{Number}		time
	 * @return	Array
	 */
	getTimeParts: function(time) {
		time = Todoyu.Helper.intval(time);

		var hours	= Math.floor(time / this.seconds.hour);
		var minutes	= Math.floor((time - hours * this.seconds.hour) / this.seconds.minute);
		var seconds	= time - (hours * this.seconds.hour) - (minutes * this.seconds.minute);

		return {
			'hours':	hours,
			'minutes':	minutes,
			'seconds':	seconds
		};
	},



	/**
	 * Get shifted time
	 *
	 * @method	getShiftedTime
	 * @param	{Number}		baseTime		Unit timestamp
	 * @param	{String}		tab
	 * @param	{Boolean}		up
	 * @return	{Number}		Unit timestamp
	 */
	getShiftedTime: function(baseTime, tab, up) {
		baseTime	= this.getDayStart(baseTime);

		var factor	= up ? 1 : -1;
		var date	= new Date(baseTime * 1000);
		var day		= 0;
		var month	= 0;

		switch( tab ) {
			case 'month':
				month	= factor;
				break;
			case 'week':
				day		= factor * 7;
				break;
			case 'day':
				day		= factor;
				break;
		}

		var newDate = new Date(date.getFullYear(), date.getMonth()+month, date.getDate()+day, date.getHours(), date.getMinutes(), date.getSeconds());

		return parseInt(newDate.getTime() / 1000, 10);
	},



	/**
	 * Get timestamp at start of day
	 *
	 * @method	getDayStart
	 * @param	{Number}	time
	 * @return	{Number}
	 */
	getDayStart: function(time) {
		var date = new Date(time * 1000);

		date.setHours(0);
		date.setMinutes(0);
		date.setSeconds(0);

		return parseInt(date.getTime() / 1000, 10);
	},



	/**
	 * Get timestamp at start of week
	 *
	 * @method	getWeekStart
	 * @param	{Number}		baseTime
	 * @return	{Number}
	 */
	getWeekStart: function(baseTime) {
		var date = new Date(baseTime * 1000);

		date.setHours(0);
		date.setMinutes(0);
		date.setSeconds(0);

		var newTime = parseInt(date.getTime() / 1000, 10);
		var shift = (((date.getDay() % 7) - 1) * -1);

		newTime += shift * this.seconds.day;

		return newTime;
	},



	/**
	 * Get todays date
	 *
	 * @method	getTodayDate
	 * @return	{Number}		microtime timestamp
	 */
	getTodayDate: function() {
		var date	= new Date();
		date.setHours(0);
		date.setMinutes(0);
		date.setSeconds(0);

		return date.getTime();
	},



	/**
	 * Get amount of days in month
	 *
	 * @method	getDaysInMonth
	 * @param	{Number}	time
	 * @return	{Number}
	 */
	getDaysInMonth: function(time) {
		var date	= new Date(time * 1000);
		var year	= date.getFullYear();
		var month	= date.getMonth();

		return 32 - new Date(year, month, 32).getDate();
	},



	/**
	 * Get date string in format YYYY-MM-DD
	 *
	 * @method	getDateString
	 * @param	{Number}		time
	 * @return	{String}
	 */
	getDateString: function(time) {
		var date = new Date(time * 1000);

		return date.getFullYear() + '-' + Todoyu.Helper.twoDigit(date.getMonth() + 1) + '-' + date.getDate();
	},



	/**
	 * Get date string with time part in format YYYY-MM-DD HH:MM
	 *
	 * @method	getDateTimeString
	 * @param	{Number}		time
	 * @return	{String}
	 */
	getDateTimeString: function(time) {
		time = parseInt(time, 10);

		var date = new Date(time * 1000);

		return date.getFullYear() + '-' + Todoyu.Helper.twoDigit(date.getMonth() + 1) + '-' + Todoyu.Helper.twoDigit(date.getDate()) + ' ' + Todoyu.Helper.twoDigit(date.getHours()) + ':' + Todoyu.Helper.twoDigit(date.getMinutes());
	},



	/**
	 * Convert date string (Y-m-d) into an timestamp
	 *
	 * @method	date2Time
	 * @param	{String}		date
	 * @return	{Number}
	 */
	date2Time: function(date) {
		var parts	= date.split('-');

		return Math.round((new Date(parts[0], parts[1]-1, parts[2], 0, 0, 0)).getTime()/1000);
	}

};