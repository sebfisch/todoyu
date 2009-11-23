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

/**
 * Todoyu specific Ajax.Responders to prototype ajax handling
 * This responders are called for every ajax request of prototype
 */
Todoyu.AjaxResponders = {
	
	/**
	 *	Register the used ajax responders
	 *
	 */
	register: function() {
		Ajax.Responders.register({
			'onCreate': this.onCreate.bind(this),
			'onComplete': this.onComplete.bind(this)
		});
	},



	/**
	 * Extend the prototype 'respondToReadyState' handler
	 * Delete the onComplete handler if no access flag is set in header
	 * 
	 * @param	Ajax.Request	request
	 */
	onCreate: function(request) {
		Todoyu.Ui.ajaxLoader(true);
		Todoyu.Ui.setLinkCursor(true);

		var oldRespondToReadyState = request.respondToReadyState;
		request.respondToReadyState = function(readyState) {
			var state	= Ajax.Request.Events[readyState];
			var response= new Ajax.Response(this);
					
				// Only process if request completed and has access error	
			if( state == 'Complete' && response.hasNoAccess() )	{
					// Delete onComplete handler to prevent processing an empty respone			
				delete response.request.options.onComplete;
				var missingRight = response.getTodoyuHeader('noAccess-right');
				Todoyu.notifyError('[LLL:core.noAccess.errorMessage] (' + missingRight + ')');
			}
			oldRespondToReadyState.call(response.request, readyState);
		};
	},
	


	/**
	 *	Handler when a request is completed
	 *
	 *	@param	Ajax.Response		response
	 */
	onComplete: function(response) {
		this.goToHash(response);
				
		if( Ajax.activeRequestCount < 1 ) {
			Todoyu.Ui.ajaxLoader(false);
			Todoyu.Ui.setLinkCursor(false);
		}
	},



	/**
	 *	Check if a hash header has been sent and scroll to it
	 *
	 *	@param	Ajax.Response		response
	 */
	goToHash: function(response) {
		var hash = response.getHeader('Todoyu-Hash'); // Do not use getTodoyuHeader(), it fails...

		if( hash !== null && Todoyu.exists(hash) ) {
			$(hash).scrollToElement();
		}
	}
};