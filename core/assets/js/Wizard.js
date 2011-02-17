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

Todoyu.Wizard = {

	/**
	 * Active wizard info
	 */
	wizard: null,



	/**
	 * Open a wizard
	 *
	 * @param	{String}	wizardName
	 * @param	{Function}	onLoadCallback
	 */
	open: function(wizardName, onLoadCallback) {
		var url		= Todoyu.getUrl('core', 'wizard');
		var options	= {
			parameters: {
				action: 'load',
				wizard: wizardName
			},
			onComplete: this.onOpened.bind(this)
		};

		this.wizard = {
			name: wizardName,
			popup: Todoyu.Popup.openWindow('wizard' + wizardName, 'Wizard', 900, url, options),
			callback: onLoadCallback || Prototype.emptyFunction
		};
	},


	/**
	 * Handler when wizard was opened
	 *
	 * @param 	{Ajax.Response}	response
	 */
	onOpened: function(response) {
		this.onLoaded(response);
	},


	/**
	 * Go one step back in wizard
	 */
	back: function() {
		this.submit('back');
	},



	/**
	 * Go to next step in wizard
	 */
	next: function() {
		this.submit('next');
	},



	/**
	 * Submit the wizard form. Set direction if provided
	 */
	submit: function(direction, callback) {
		if( typeof direction === 'string' ) {
			this.setDirection(direction);
		}

		callback	= callback || Prototype.emptyFunction;

		$('wizard').down('form').request({
			onComplete: this.onSubmitted.bind(this, callback)
		});
	},



	/**
	 * Handler when form was submitted
	 *
	 * @param	{Ajax.Response}	response
	 */
	onSubmitted: function(callback, response) {
		$('wizard').replace(response.responseText);
		this.onLoaded(response);
		callback(response);
	},



	/**
	 * Handler when wizard was loaded (opened or submitted)
	 *
	* @param	{Ajax.Response}	response
	 */
	onLoaded: function(response) {
		this.wizard.popup.setTitle(response.getTodoyuHeader('label'));
		this.wizard.callback(response, this.wizard);
	},



	/**
	 * Set direction for next step
	 *
	 * @param	{String}	direction
	 */
	setDirection: function(direction) {
		$('wizard-direction').value = direction;
	},



	/**
	 * Get current step
	 */
	getStepName: function() {
		return $F('wizard-step');
	},



	/**
	 * Get name of the wizard
	 */
	getWizardName: function() {
		return this.wizard.name;
	},



	/**
	 * Get form element of the wizard
	 */
	getForm: function() {
		return $('wizard-form');
	},



	/**
	 * Set no save mode. Wizard just goes to requested direction without validation or saving data
	 *
	 * @param	{Boolean}	value
	 */
	setNoSave: function(value) {
		$('wizard-nosave').value = value === false ? 0 : 1;
	}

};