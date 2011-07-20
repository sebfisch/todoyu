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
	 * @return	{Object}
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
	 * Get result of given base timestamp shifted into future/past by given factor
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
	 * Get today's date
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
	 * Get amount of days in february of given year.
	 *
	 * @method	getDaysInFebruary
	 * @param	{Number}	year
	 * @return	{Number}
	 */
	getDaysInFebruary: function(year) {
			// February has 29 days in any year evenly divisible by four, except for centurial years which are not dividable by 400
		return (year % 4 == 0) && (!(year % 100 == 0) || (year % 400 == 0)) ? 29 : 28;
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
	},



	/**
	 * Get parts (month, day, year, hours, minutes, seconds) of date string when parsed (possibly corrected) by JS calendar
	 *
	 * @method	getDateTimeStringParsedParts
	 * @param	{String}	dateString
	 * @param	{String}	format
	 * @return	{Array}
	 */
	getDateTimeStringPartsParsed: function(dateString, format) {
		var dateObj	= Date.parseDate(dateString, format);

		return {
			'month':	dateObj.getMonth(),
			'day':		dateObj.getDate(),
			'year':		dateObj.getFullYear(),
			'hours':	dateObj.getHours(),
			'minutes':	dateObj.getMinutes(),
			'seconds':	dateObj.getSeconds()
		};
	},



	/**
	 * Extract parts (month, day, year, hours, minutes, seconds) out of datetime string w/o correction
	 *
	 * @method	getDateTimeStringParts
	 * @param	{String}	datetimeString
	 * @param	{String}	format
	 * @return	{Array}
	 */
	getDateTimeStringParts: function(datetimeString, format) {
		var today = new Date();

		var year	= 0;
		var month	= -1;
		var day		= 0;
		var hours	= 0;
		var minutes = 0;
		var seconds	= 0;

		var a = datetimeString.split(/\W+/);
		var b = format.match(/%./g);
		var i = 0, j = 0;

			// Extract parts
		for(i = 0; i < a.length; ++i) {
			if( ! a[i] )
				continue;

			switch (b[i]) {
					// Extract day
				case "%d":
				case "%e":
					day = parseInt(a[i], 10);
					break;

					// Extract month
				case "%m":
					month = parseInt(a[i], 10) - 1;
					break;

				case "%b":
				case "%B":
					for (j = 0; j < 12; ++j) {
						if( Calendar._MN[j].substr(0, a[i].length).toLowerCase() == a[i].toLowerCase() ) {
							month = j;
							break;
						}
					}
					break;

					// Extract year
				case "%Y":
				case "%y":
					year = parseInt(a[i], 10);
					(year < 100) && (year += (year > 29) ? 1900 : 2000);
					break;

					// Extract hours
				case "%H":
				case "%I":
				case "%k":
				case "%l":
					hours = parseInt(a[i], 10);
					break;

				case "%P":
				case "%p":
					if( /pm/i.test(a[i]) && hours < 12 ) {
						hours += 12;
					} else if( /am/i.test(a[i]) && hours >= 12 ) {
						hours -= 12;
					}
					break;

				case "%M":
				minutes = parseInt(a[i], 10);
				break;
			}
		}

		return {
			'month':	month,
			'day':		day,
			'year':		year,
			'hours':	hours,
			'minutes':	minutes,
			'seconds':	seconds
		};
	},



	/**
	 * Check whether given date string contains a correct date (will not be corrected/changed when parsed)
	 *
	 * @method	isDateString
	 * @param	{String}	dateString
	 * @param	{String}	format
	 * @return	{Boolean}
	 */
	isDateString: function(dateString, format) {
		var parts		= this.getDateTimeStringParts(dateString, format);
		var partsParsed	= this.getDateTimeStringPartsParsed(dateString, format);

			// Isn't date string if any part differs unparsed from parsed
		return ! (	parts.year != partsParsed.year ||
					parts.month != partsParsed.month ||
					parts.day != partsParsed.day ||
					parts.hours != partsParsed.hours ||
					parts.minutes != partsParsed.minutes );
	},



	/**
	 * Get (javaScript) timestamp of start (00:00) of given day
	 *
	 * @method	getStartOfDay
	 * @param	{Date}		day
	 * @return	{Number}
	 */
	getStartOfDay: function(day) {
		var y	= day.getFullYear();
		var m	= day.getMonth();
		var d	= day.getDate();

		var dateStart	= new Date(y, m, d, 0, 0);

		return dateStart.getTime();
	},



	/**
	 * Get (javaScript) timestamp of end (23:59) of given day
	 *
	 * @method	getEndOfDay
	 * @param	{Date}		day
	 * @return	{Number}
	 */
	getEndOfDay: function(day) {
		var y	= day.getFullYear();
		var m	= day.getMonth();
		var d	= day.getDate();

		var dateStart	= new Date(y, m, d, 23, 59);

		return dateStart.getTime();
	},



	/**
	 * Get (timestamps at 00:00 of) days inside given timespan
	 *
	 * @method	getDayTimestampsInRange
	 * @param	{Date}		dateStart
	 * @param	{Date}		dateEnd
	 * @return	{Array}
	 */
	getDayTimestampsInRange: function(dateStart, dateEnd) {
		dateStart	= this.getStartOfDay(dateStart);
		dateEnd		= this.getEndOfDay(dateEnd);

		var timestamp	= dateStart;

		var days		= new Array();
		while( timestamp <= dateEnd ) {
			days.push(timestamp);
			timestamp	+= this.seconds.day * 1000;
		}

		return days;
	}

};