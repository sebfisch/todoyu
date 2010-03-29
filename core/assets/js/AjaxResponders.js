/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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
 * Todoyu specific Ajax.Responders to prototype ajax handling
 * This responders are called for every ajax request of prototype
 */
Todoyu.AjaxResponders = {

	/**
	 * Hooks called when request is completed
	 */
	completeHooks: [],



	/**
	 * Register the used ajax responders
	 */
	init: function() {
		Ajax.Responders.register({
			'onCreate':		this.onCreate.bind(this),
			'onComplete':	this.onComplete.bind(this)
			//'onException':	this.onException.bind(this)
		});

		this.addOnCompleteHook(Todoyu.Ajax.checkNoAccessHeader.bind(Todoyu.Ajax));
		this.addOnCompleteHook(Todoyu.Ajax.checkPhpErrorHeader.bind(Todoyu.Ajax));
		this.addOnCompleteHook(Todoyu.Notification.checkNoteHeader.bind(Todoyu.Notification));
	},



	/**
	 * Add a new hook function which will be called when request is completed
	 *
	 * @param	Function	hook
	 */
	addOnCompleteHook: function(hook) {
		this.completeHooks.push(hook);
	},



	/**
	 * Call all registered hook functions
	 * They receive the response object as only parameter
	 *
	 * @param	Ajax.Response	response
	 */
	callOnCompleteHooks: function(response) {
		this.completeHooks.each(function(response, func){
			func(response);
		}.bind(this, response));
	},



	/**
	 * Extend the prototype 'respondToReadyState' handler
	 * Delete the onComplete handler if no access flag is set in header
	 *
	 * @param	Ajax.Request	request
	 */
	onCreate: function(request) {
		Todoyu.Headlet.AjaxLoader.show();
		Todoyu.Ui.setLinkCursor(true);

		var oldRespondToReadyState = request.respondToReadyState;
		request.respondToReadyState = function(readyState) {
			var state	= Ajax.Request.Events[readyState];
			var response= new Ajax.Response(this);

				// Call onComplete hooks
			if( state == 'Complete' ) {
				Todoyu.AjaxResponders.callOnCompleteHooks(response);
			}

			oldRespondToReadyState.call(response.request, readyState);
		};
	},



	/**
	 * Handler when a request is completed
	 *
	 * @param	Ajax.Response		response
	 */
	onComplete: function(response) {
			// Check for hash header and scroll to element
		this.scrollToElement(response);

			// If no more requests are running, stop spinner
		if( Ajax.activeRequestCount < 1 ) {
			Todoyu.Headlet.AjaxLoader.hide();
			Todoyu.Ui.setLinkCursor(false);
		}
	},



	/**
	 * Handler when connection to server fails
	 *
	 * @param	Ajax.Response	response
	 * @param	Exception		exception
	 */
	onException: function(response, exception) {
		//Todoyu.notifyError('[LLL:core.ajax.requestFailed]', 0);
		alert('[LLL:core.ajax.requestFailed]');
	},



	/**
	 * Check whether hash header has been sent and scroll to it
	 *
	 * @param	Ajax.Response		response
	 */
	scrollToElement: function(response) {
		var hash = response.getHeader('Todoyu-Hash'); // Do not use getTodoyuHeader(), it fails...

		if( hash !== null && Todoyu.exists(hash) ) {
			$(hash).scrollToElement();
		}
	}
};