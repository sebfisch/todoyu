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
 * Observe a text field delayed
 * Prevents useless ajax requests until the user has finished typing in the textfield
 */
Todoyu.DelayedTextObserver = {

	/**
	 * Timeouts of delayed update events
	 * @var	{Object}
	 */
	timeouts: {},


	/**
	 * Observe an input field delayed
	 * Callback function will be called with new field value and the field reference (Ex: onChanged: function(value, field) {})
	 *
	 * @param	{Element}	inputField		Element or ID
	 * @param	{Function}	callback		Callback function to call after delay. You should bind it before you give it as a parameters
	 * @param	{Number}	delay			Number of seconds to delay the request in seconds. Default: 0.5s
	 */
	observe: function(inputField, callback, delay) {
		if( ! Todoyu.exists(inputField) ) {
			alert('DelayedTextObserver: unknown field to observe "' + inputField.toString() + '"');
			return false;
		}
		if( callback.constructor !== Function ) {
			alert('The callback needs to be a valid function');
			return false;
		}


		inputField	= $(inputField);
		delay		= delay || 0.5;

		inputField.observe('keyup', this._onChanged.bindAsEventListener(this, inputField, callback, delay));
	},



	/**
	 * Callback when the input field changes
	 * Clear older timeouts and start a new one
	 *
	 * @param	{Event}		event
	 * @param	{Element}	inputField
	 * @param	{Function}	callback
	 * @param	{Number}	delay
	 */
	_onChanged: function(event, inputField, callback, delay) {
		clearTimeout(this.timeouts[inputField.id]);

		this.timeouts[inputField.id] = this._callCallback.bind(this).delay(delay, inputField, callback);
	},



	/**
	 * Call the callback function with the field reference and the value
	 *
	 * @param	{Element}	inputField
	 * @param	{Function}	callback
	 */
	_callCallback: function(inputField, callback) {
		callback($F(inputField), inputField);
	}

};