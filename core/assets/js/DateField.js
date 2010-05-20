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
 * Helper functions for date fields
 * Requires the jscalendar functions Date.parseDate and Date.print
 */
Todoyu.DateField = {

	/**
	 *
	 * @param	{String}		format
	 */
	format: null,


	/**
	 * Get format for the date field with a JsCalendar config
	 *
	 * @param	{String|Element}	field		ID or Element
	 */
	getFormat: function(field) {
		return Todoyu.JsCalFormat[$(field).id];
	},



	/**
	 * Get date object from a field with a date in string based on the internal format
	 *
	 * @param	{String|Element}	field
	 * @return	{Date}
	 */
	getDate: function(field) {
		return Date.parseDate($F(field), this.getFormat(field));
	},



	/**
	 * Change time in a datetime field
	 *
	 * @param	{String|Element}	field
	 * @param	{Number}			hour
	 * @param	{Number}			minute
	 */
	setTime: function(field, hour, minute) {
		var date	= this.getDate(field);

		date.setHours(hour);
		date.setMinutes(minute);

		$(field).value = date.print(this.getFormat(field));
	},



	/**
	 * Set a new date for a field
	 *
	 * @param	{String|Element}	field
	 * @param	{Number}			year
	 * @param	{Number}			month
	 * @param	{Number}			day
	 */
	setDate: function(field, year, month, day) {
		var date	= this.getDate(field);

		date.setFullYear(year);
		date.setMonth(month);
		date.setDate(day);

		$(field).value = date.print(this.getFormat(field));
	},


	
	/**
	 * Set a new time for a field
	 *
	 * @param	{String|Element}	field
	 * @param	{Number}			year
	 * @param	{Number}			month
	 * @param	{Number}			day
	 * @param	{Number}			hour
	 * @param	{Number}			minute
	 * @param	{Number}			second
	 */
	setDateTime: function(field, year, month, day, hour, minute, second) {
		var date	= new Date(year, month, day, hour, minute, second);

		$(field).value = date.print(this.getFormat(field));
	}

};