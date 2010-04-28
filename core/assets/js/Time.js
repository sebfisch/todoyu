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

Todoyu.Time = {

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
	 * @param	{String}	timeString
	 * @return	{String}
	 */
	parseTimeToSeconds: function(timeString) {
		var parts	= timeString.stripTags().split(':');

		return Todoyu.Helper.intval(parts[0]) * 3600 + (Todoyu.Helper.intval(parts[1]) * 60) + Todoyu.Helper.intval(parts[2]);
	},



	/**
	 * Get time parts of given (timestamp) time
	 *
	 * @param	{Number}		time
	 * @return	Array
	 */
	getTimeParts: function(time) {
		time = Todoyu.Helper.intval(time);

		var hours	= Math.floor(time / 3600);
		var minutes	= Math.floor((time - hours * 3600) / 60);
		var seconds	= time - (hours * 3600) - (minutes * 60);

		return {
			'hours':	hours,
			'minutes':	minutes,
			'seconds':	seconds
		};
	},



	/**
	 * Get shifted time
	 *
	 * @param	{Number}		baseTime
	 * @param	{String}		step		'month' / other		@todo change into boolean if there's no more values
	 * @param	{String}		direction	'up' / 'down'
	 * @return	{Number}
	 */		
	getShiftedTime: function(baseTime, step, direction) {
		baseTime	= this.getDayStart(baseTime);
		
		var factor	= (direction === 'up' ? 1 : -1);
		var date	= new Date(baseTime * 1000);

		if( step === 'month' ) {
			if( direction === 'up' && date.getMonth() == 11 ) {
				date.setYear(date.getFullYear() + 1);
				date.setMonth(0);
			} else if( direction === 'down' && date.getMonth() == 0 ) {
				date.setYear(date.getFullYear() - 1);
				date.setMonth(11);		
			} else {
				date.setMonth(date.getMonth() + factor);
			}
		} else {
			date.setTime(date.getTime() + (factor * this.seconds[step] * 1000));
		}

		return parseInt(date.getTime() / 1000, 10);
	},



	/**
	 * Get timestamp at start of day
	 *
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
	 * @param	{Number}		time
	 */
	getDateString: function(time) {
		var date = new Date(time*1000);
		
		return date.getFullYear() + '-' + Todoyu.Helper.twoDigit(date.getMonth()+1) + '-' + date.getDate();
	},
	
	
	/**
	 * Get date string with time part in format YYYY-MM-DD HH:MM
	 * 
	 * @param	{Number}		time
	 */
	getDateTimeString: function(time) {
		var date = new Date(time*1000);
		
		return date.getFullYear() + '-' + Todoyu.Helper.twoDigit(date.getMonth()+1) + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes();
	}

};