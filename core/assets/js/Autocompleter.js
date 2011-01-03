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
 *	Todoyu autocompleter (extended prototype autocompleter)
 */
Todoyu.Autocompleter = Class.create(Ajax.Autocompleter, {

	clearDelay: null,


	/**
	 * Initialize autocompleter
	 *
	 * @param	{Function}	$super		Constructor of Ajax.Autocompleter
	 * @param	{Element}	inputField
	 * @param	{Element}	suggestDiv
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	initialize: function($super, inputField, suggestDiv, url, options) {
		options.afterUpdateElement	= options.afterUpdateElement || Prototype.emptyFunction;

		options.afterUpdateElement = options.afterUpdateElement.wrap(this.onSelected.bind(this));


		$super(inputField, suggestDiv, url, options);



		$(inputField).on('blur', this.onBlur.bind(this));
		$(inputField).on('keyup', this.onKeyup.bind(this));
	},



	/**
	 * Handle completion of autocompleter suggestion retrieval
	 *
	 * @param	{Ajax.Response}		response
	 */
	onComplete: function(response) {
			// If a custom onComplete defined
		if( this.options.onCompleteCustom ) {
			var funResult = Todoyu.callUserFunction(this.options.onCompleteCustom, response, this);

				// If the custom function returns an object, override response
			if( typeof(funResult) === 'object' ) {
				response = funResult;
			}
		}

		if( response.getTodoyuHeader('acElements') == 0 ) {
			this.onEmptyResult(response);
		}

			// Call default ac handler
		this.updateChoices(response.responseText);
	},



	/**
	 * Handle reception of empty result (no suggestion found)
	 *
	 * @param	{Ajax.Response}		response
	 */
	onEmptyResult: function(response) {
		new Effect.Highlight(this.element, {
			'startcolor':	'#ff0000',
			'endcolor':		'#ffffff',
			'duration':		2.0
		});

		this.clearValue();

		if( ! this.options.onCompleteCustom ) {
			Todoyu.notifyInfo('[LLL:form.ac.noResults]');
		}
	},



	/**
	 * Handler when a suggested item was selected
	 *
	 * @param	{Element}	inputField
	 * @param	{Element}	selectedListElement
	 */
	onSelected: function(callOriginal, inputField, selectedListElement) {
		clearTimeout(this.clearDelay);

		var selectedValue	= selectedListElement.id;
		var updateValueField= true;

		this.valid = true;

		if( this.options.onSelectCustom ) {
			var result = Todoyu.callUserFunction(this.options.onSelectCustom, inputField, this.getValueField(), selectedValue, selectedListElement.innerHTML, this);

			if( result === false ) {
				updateValueField = false;
			}
		}

		if( updateValueField ) {
			this.setValue(selectedValue);
		}

		callOriginal(inputField, selectedListElement);
	},



	/**
	 * If focus leaves autocompleter field
	 *
	 * @param	{Event}		event
	 */
	onBlur: function(event) {
		if( this.valid === false ) {
			this.clear();
		}

		this.hideSuggestions();
	},


	/**
	 * Keyup event
	 * Element is invalid because new text was entered. Space will be ignored
	 *
	 * @param	{Event}		event
	 */
	onKeyup: function(event) {
		if( event.which != 32 ) {
			this.valid = false;
		}

		if( this.isEmpty() ) {
			this.clear();
		}
	},


	/**
	 * Hide suggestion div (delayed)
	 */
	hideSuggestions: function() {
		var sug	= this.element.up('div').down('.acResultContainer');

		sug.hide.bind(sug).delay(0.5);
	},



	/**
	 * Check whether the input field is empty
	 */
	isEmpty: function() {
		return $F(this.element).strip() === '';
	},



	/**
	 * Clear the input field
	 * Delayed to allow selection of an element from the suggestion list
	 *
	 * @param	{Boolean}	noDelay
	 */
	clear: function(noDelay) {
		clearTimeout(this.clearDelay);
		if( noDelay !== true ) {
			this.clearDelay = this.clear.bind(this, true).delay(0.1);
			return ;
		}

		this.valid = true;

		this.clearText();
		this.clearValue();

	},


	/**
	 * Clear text of AC field
	 */
	clearText: function() {
		this.setText('');
	},


	/**
	 * Clear value of AC field
	 */
	clearValue: function() {
		this.setValue('');
	},



	/**
	 * Set text of AC field
	 *
	 * @param	{String}	text
	 */
	setText: function(text) {
		this.element.value = text;
	},



	/**
	 * Set value of AC field
	 *
	 * @param	{String}	value
	 */
	setValue: function(value) {
		this.getValueField().value = value;
	},



	/**
	 * Get hidden value field
	 */
	getValueField: function() {
		return this.element.up('div').down('input[type=hidden]');
	}

});