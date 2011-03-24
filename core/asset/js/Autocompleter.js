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
 *	Todoyu autocompleter (extended prototype autocompleter)
 */
Todoyu.Autocompleter = Class.create(Ajax.Autocompleter, {

	clearTimeout: null,

	customOnComplete: null,

	config: {
		paramName: 'input',
		minChars: 2
	},


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
		options	= options	|| {};
		options.parameters	= options.parameters || '';
			// Store onComplete in internal property
		options.onCompleteCustom = Todoyu.getFunction(options.onComplete);
		delete options.onComplete;

			// Set empty function if no callback defined
		options.afterUpdateElement	= Todoyu.getFunction(options.afterUpdateElement).wrap(this.onElementSelected.bind(this));


			// Only add callback and default parameters if no action parameter defined (default)
		if( ! url.toQueryParams().action && (options.parameters == '' && ! options.parameters.toQueryParams().action) ) {
			options.parameters	= '&action=update&autocompleter=' + options.acName + '&element=' + $(inputField).id;
				// Add form name and data
			options.callback	= this.callbackModifyRequestParams.bind(this);
		}

		options.paramName	= options.paramName || this.config.paramName;
		options.minChars	= options.minChars || this.config.minChars;

			// Initialize original autocompleter
		$super(inputField, suggestDiv, url, options);

			// Install key handlers
		$(inputField).on('blur', this.onBlur.bind(this));
		$(inputField).on('keyup', this.onKeyup.bind(this));
			// Install change handler
		$(inputField).observe('change', this.onInputChange.bind(this));
	},



	/**
	 * Callback. Add form name and data to request
	 *
	 * @method	callbackModifyRequestParams
	 * @param	{Element}		inputElement
	 * @param	{String}		acParam
	 * @return	{String}
	 */
	callbackModifyRequestParams: function(inputElement, acParam) {
		var form	= this.element.up('form');

		if( form ) {
			var name	= form.readAttribute('name');
			var data	= form.serialize();

			acParam += '&form=' + name + '&data=' + data;
		}

		return acParam;
	},



	/**
	 * Called if input field has changed (blur)
	 *
	 * @method	onInputChange
	 * @param	{Event}		event
	 */
	onInputChange: function(event) {
			// If the change was called by a valid select, revert flag and do nothing
		if( this.selectedFromList ) {
			this.selectedFromList = false;
			return;
		}

			// Clear fields
		this.clear();
	},



	/**
	 * Handle completion of autocompleter suggestion retrieval
	 *
	 * @method	onComplete
	 * @param	{Function}			$super			Ajax.Autocompleter.onComplete
	 * @param	{Ajax.Response}		response
	 */
	onComplete: function($super, response) {
		$super(response);

			// Call custom method. If return value is false, don't show message
		var showMessage = this.options.onCompleteCustom(response, this) !== false;

			// Handle empty results
		if( response.isEmptyAcResult() ) {
			this.handleEmptyResult(showMessage);
		}
	},



	/**
	 * Handle reception of empty result (no suggestion found)
	 *
	 * @method	handleEmptyResult
	 * @param	{Boolean}	showMessage
	 */
	handleEmptyResult: function(showMessage) {
		new Effect.Highlight(this.element, {
			'startcolor':	'#ff0000',
			'endcolor':		'#ffffff',
			'duration':		2.0
		});

		this.clearValue();

		if( showMessage ) {
			Todoyu.notifyInfo('[LLL:core.global.form.ac.noResults]');
		}
	},



	/**
	 * Handler when a suggested item was selected
	 *
	 * @method	onElementSelected
	 * @param	{Element}	inputField
	 * @param	{Element}	selectedListElement
	 */
	onElementSelected: function(callOriginal, inputField, selectedListElement, noUpdate) {
		this.resetClearDelay();

		this.valid = true;
		this.selectedFromList = true;

		var updateValueField = this.callOnSelected(inputField, selectedListElement);

		if( updateValueField && noUpdate !== true ) {
			this.setValue(selectedListElement.id);
		}

		callOriginal(inputField, selectedListElement);
	},



	/**
	 * Call registered user function on selecting autocompleter suggestion
	 *
	 * @method	callOnSelected
	 * @param	{Element}	inputField
	 * @param	{Element}	selectedListElement
	 * @return	{Element}
	 */
	callOnSelected: function(inputField, selectedListElement) {
		var selectedValue	= selectedListElement.id;
		var selectedText	= selectedListElement.innerHTML.stripScripts().stripTags().strip();
		var updateValueField= true;
		var valueField		= this.getValueField();

			// Call custom onSelected method
		if( this.options.onSelected ) {
			var result = Todoyu.callUserFunction(this.options.onSelected, inputField, valueField, selectedValue, selectedText, this);

			if( result === false ) {
				updateValueField = false;
			}
		}

		return updateValueField;
	},



	/**
	 * Clear autocompleter delay
	 *
	 * @method	clearDelay
	 */
	resetClearDelay: function() {
		clearTimeout(this.clearTimeout);
	},



	/**
	 * If focus leaves autocompleter field
	 *
	 * @method	onBlur
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
	 * @method	onKeyup
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
	 *
	 * @method	hideSuggestions
	 */
	hideSuggestions: function() {
		var sug	= this.element.up('div').down('.acResultContainer');

		sug.hide.bind(sug).delay(0.5);
	},



	/**
	 * Check whether the input field is empty
	 *
	 * @method	isEmpty
	 */
	isEmpty: function() {
		return Todoyu.Validate.isEmpty($F(this.element).strip());
	},



	/**
	 * Clear the input field
	 * Delayed to allow selection of an element from the suggestion list
	 *
	 * @method	clear
	 * @param	{Boolean}	noDelay
	 */
	clear: function(noDelay) {
		this.resetClearDelay();
		if( noDelay !== true ) {
			this.clearTimeout = this.clear.bind(this, true).delay(0.1);
			return ;
		}

		this.valid = true;

		this.clearText();
		this.clearValue();

			// Call onCleared handler
		if( Object.isFunction(this.options.onCleared) ) {
			this.options.onCleared.call(null, this);
		}
	},



	/**
	 * Clear text of AC field
	 *
	 * @method	clearText
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
	 * @method	setText
	 * @param	{String}	text
	 */
	setText: function(text) {
		this.element.value = text;
	},



	/**
	 * Set value of AC field
	 *
	 * @method	setValue
	 * @param	{String}	value
	 */
	setValue: function(value) {
		this.getValueField().value = value;
	},



	/**
	 * Get hidden value field
	 *
	 * @method	getValueField
	 * @return	{Element}
	 */
	getValueField: function() {
		return this.element.up('div').down('input[type=hidden]');
	}

});