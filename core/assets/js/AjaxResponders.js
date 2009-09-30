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

Todoyu.AjaxResponders = {



	/**
	 *	Please enter Description here...
	 *
	 */
	register: function() {
		Ajax.Responders.register({
			'onCreate': this.onCreate.bind(this),
			'onComplete': this.onComplete.bind(this)
		});
	},



	/**
	 *	Please enter Description here...
	 *
	 */
	onCreate: function() {
		Todoyu.Ui.ajaxLoader(true);
		Todoyu.Ui.setLinkCursor(true);
	},



	/**
	 *	Please enter Description here...
	 *
	 *	@param	unknown_type	response
	 */
	onComplete: function(response) {
		this.goToHash(response);

		if( Ajax.activeRequestCount === 0 ) {
			Todoyu.Ui.ajaxLoader(false);
			Todoyu.Ui.setLinkCursor(false);
		}
	},



	/**
	 *	Please enter Description here...
	 *
	 *	@param	unknown_type	response
	 */
	goToHash: function(response) {
		var hash = this.getHashHeader(response);
		
		if( hash !== null && Todoyu.exists(hash) ) {
			$(hash).scrollToElement();
		}
	},



	/**
	 *	Please enter Description here...
	 *
	 *	@param	unknown_type	response
	 */
	getHashHeader: function(response) {
		return response.transport.getResponseHeader('Todoyu-Hash');
	}
};