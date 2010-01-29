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
	 * @param	unknown_type	hours
	 * @param	unknown_type	minutes
	 * @param	unknown_type	seconds
	 * @param	unknown_type	separator
	 * @return	String
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
	 * @param	String	time
	 * @param	String	separator
	 * @return	String
	 */
	timeFormatSeconds: function(time, separator) {
		var timeParts = this.getTimeParts(time);

		return this.timeFormat(timeParts.hours, timeParts.minutes, timeParts.seconds, separator);
	},



	/**
	 * Parse given time string to seconds
	 *
	 * @param	String	timeString
	 * @return	String
	 */
	parseTimeToSeconds: function(timeString) {
		var parts	= timeString.stripTags().split(':');

		return Todoyu.Helper.intval(parts[0]) * 3600 + (Todoyu.Helper.intval(parts[1]) * 60) + Todoyu.Helper.intval(parts[2]);
	},



	/**
	 * Get time parts of given (timestamp) time
	 *
	 * @param	Integer		time
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
	 * @todo	comment
	 */		
	getShiftedTime: function(baseTime, step, direction) {
		var factor	= (direction === 'up' ? 1 : -1);
		var baseTime= this.getDayStart(baseTime);
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
	 * @param	Integer	time
	 * @return	Integer
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
	 * @param	Integer	time
	 * @return	Integer
	 */
	getWeekStart: function(time) {
		var date = new Date(time * 1000);

		date.setHours(0);
		date.setMinutes(0);
		date.setSeconds(0);

		var time = parseInt(date.getTime() / 1000, 10);
		var shift = (((date.getDay() % 7) - 1) * -1);

		time += shift * this.seconds.day;

		return time
	},



	/**
	 * Get todays date
	 *
	 * @return	Integer		microtime timestamp
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
	 * @param	Integer	time
	 * @return	Integer
	 */
	getDaysInMonth: function(time) {
		var date	= new Date(time * 1000);
		var year	= date.getFullYear();
		var month	= date.getMonth();
		
		return 32 - new Date(year, month, 32).getDate();
	}

};