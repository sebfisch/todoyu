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
 * Helper functions for date fields
 * Requires the jsCalendar functions Date.parseDate and Date.print
 *
 * @class		DateField
 * @namespace	Todoyu
 */
Todoyu.DateField = {

	/**
	 * Validators for date field
	 */
	validators: {},

	/**
	 * Install date field onchange observation for input validation
	 *
	 * @method	addValidator
	 * @param	{String}	idField
	 * @param	{String}	format
	 */
	addValidator: function(idField, format) {
		this.removeValidator(idField);

		this.validators[idField] = $(idField).on('change', 'input', this.validateDateFormat.bind(this, format));
	},



	/**
	 * Remove a validator if registered
	 *
	 * @method	removeValidator
	 * @param	{String}	idField
	 */
	removeValidator: function(idField) {
		if( this.validators[idField] && this.validators[idField].stop ) {
			this.validators[idField].stop();
			delete this.validators[idField];
		}
	},



	/**
	 * Change/replace a calendar config
	 *
	 * @method	changeCalendarConfig
	 * @param	{String|Element}	field
	 * @param	{Object}			newOptions
	 */
	changeCalendarConfig: function(field, newOptions) {
		var date	= this.getDate(field);
		var oldOptions	= Todoyu.Ui.getCalendarOptions(field);
		var options		= Object.extend(oldOptions, newOptions);

		Todoyu.Ui.initCalendar(options);

		this.setDate(field, date);
	},



	/**
	 * Change calendar date format
	 *
	 * @method	changeCalendarFormat
	 * @param	{String|Element}	field
	 * @param	{String}			newFormat
	 */
	changeCalendarFormat: function(field, newFormat) {
		this.changeCalendarConfig(field, {
			ifFormat: newFormat
		});
	},



	/**
	 * Check date format on value change
	 *
	 * @method	validateDateFormat
	 * @param	{String}	format
	 * @param	{Event}		event
	 * @param	{Element}	input
	 */
	validateDateFormat: function(format, event, input) {
		var dateValue	= $F(input).strip();

			// Remove all errors
		Todoyu.Form.setFieldErrorStatus(input, false);
		Todoyu.Notification.closeTypeNotes('date.formaterror');

			// Empty is valid too
		if( dateValue.empty() ) {
			return;
		}

			// Is date in valid format?
		if( ! Todoyu.Time.isDateString(dateValue, format) ) {
			Todoyu.notifyError('[LLL:core.date.warning.dateformat.invalid]', 'date.formaterror');

			Todoyu.Form.setFieldErrorStatus(input, true);
		} else {
				// Set date to result the parsing of the current value will have
			var parts		= Todoyu.Time.getDateTimeStringParts(dateValue, format);
			var understoodDate	= new Date(parts.year, parts.month, parts.day, parts.hours, parts.minutes);

			this.setDate(input.id, understoodDate);
		}
	},






	/**
	 * Get format for the date field with a JsCalendar config
	 *
	 * @method	getFormat
	 * @param	{String|Element}	field		ID or Element
	 * @return	{String}			Format string
	 */
	getFormat: function(field) {
		return Todoyu.Ui.getCalendarOptions(field).ifFormat;
	},



	/**
	 * Get date object from a field with a date in string based on the internal format
	 *
	 * @method	getDate
	 * @param	{String|Element}	field
	 * @return	{Date}
	 */
	getDate: function(field) {
		return Date.parseDate($F(field), this.getFormat(field));
	},



	/**
	 * Set date for field (requires a registered field config)
	 *
	 * @method	setDate
	 * @param	{Element|String}	field
	 * @param	{Date}				date
	 */
	setDate: function(field, date) {
		$(field).value = date.print(this.getFormat(field));
	},



	/**
	 * Set formatted time string in a dateTime field from given values
	 *
	 * @method	setTime
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
	 * Set input field value to formatted string of given date values
	 *
	 * @method	setDate
	 * @param	{String|Element}	field
	 * @param	{Number}			year
	 * @param	{Number}			month
	 * @param	{Number}			day
	 */
	setDateByDay: function(field, year, month, day) {
		var date	= this.getDate(field);

		date.setFullYear(year);
		date.setMonth(month);
		date.setDate(day);

		this.setDate(field, date);
	},



	/**
	 * Set input field value to formatted string of given date time values
	 *
	 * @method	setDateTime
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