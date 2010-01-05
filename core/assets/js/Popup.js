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
 *	Todoyu popup
 *
 *	@see http://prototype-window.xilinus.com/documentation.html
 */


Todoyu.Popup = {

	/**
	 * Popup object reference
	 */
	popup: {},
	
	last: null,

	timeoutID:	null,



	/**
	 * Get popup reference
	 *
	 *	@param	String		idPopup
	 */
	getPopup: function(idPopup) {
		return this.popup[idPopup];
	},



	/**
	 * @todo	comment
	 */
	getLastPopup: function() {
		return this.last;
	},



	/**
	 *	Get ID
	 *
	 */
	getIDXXXX: function() {
		return this.popup.getId();
	},



	/**
	 * Open new popup window
	 *
	 *	@param	unknown_type	idPopup
	 *	@param	unknown_type	titleTxt
	 *	@param	unknown_type	winWidth
	 *	@param	unknown_type	winHeight
	 *	@param	unknown_type	posTop
	 *	@param	unknown_type	posLeft
	 *	@param	unknown_type	contentUrl
	 *	@param	unknown_type	requestOptions
	 */
	openWindow: function(idPopup, title, winWidth, winHeight, contentUrl, requestOptions) {
			// Construct popup
		this.popup[idPopup] = new Window({
			id:					idPopup,
			className:			"dialog",
			title:				title,
			resizable:			true,
			closable:			true,
			minimizable:		true,
			maximizable:		true,
			draggable:			false,

			minWidth:			100,
			minHeight:			20,
			width:				winWidth,
			height:				winHeight,

			zIndex:				2000,
			destroyOnClose:		true,
			recenterAuto:		false,

			'hideEffect': 		Element.hide,
			'showEffect': 		Element.show,
			//showEffectOptions:	{},
			//hideEffectOptions:	{},
			effectOptions:		null,
			parent:				document.getElementsByTagName("body").item(0)
		});
		
			// Show popup and activate content overlay
		this.getPopup(idPopup).showCenter(true);

			// Wrap onComplete with own onComplete to handle popup
		requestOptions = requestOptions || {};
		if( typeof requestOptions.onComplete !== 'function' ) {
			requestOptions.onComplete = Prototype.emptyFunction
		}
		requestOptions.onComplete.wrap(function(idPopup, callOriginal, response){
			this.onContentLoaded.bind(this, idPopup);
			callOriginal(response);
		}.bind(this, idPopup));

		this.getPopup(idPopup).setAjaxContent(contentUrl, requestOptions, false, false);
		
			// Save last opened popup
		this.last = this.getPopup(idPopup);		
		
		return this.getPopup(idPopup);
	},



	/**
	 * 	Evoked after window content has been rendered.
	 *	Installing observers:
	 *	1. upon clicking:	(delayed) popup windwow updates its seize to fit its content
	 *	2. upon closing:	the cick observer (1) is stopped
	 *
	 *	@param	String	idPopup
	 * 	@param	Object	response
	 */
	onContentLoaded: function(idPopup, response) {
		//this.getPopup(idPopup).updateHeight();
		
		
		//this.getContentElement(idPopup).observe('mouseup', this.onMouseUp.bindAsEventListener(this, idPopup));

		//this.getPopup(idPopup).setCloseCallback(this.onWindowClose.bindAsEventListener(this, idPopup));
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	Object	event
	 *	@param	String	idPopup
	 */
	onWindowClose: function(event, idPopup) {
		//this.getContentElement(idPopup).stopObserving('mouseup');
		//this.clearTimeout();

		//return true;
	},



	/**
	 * Enter Description here...
	 *
	 *	@param	Object	event
	 *	@param	String	idPopup
	 */
	onMouseUp: function(event, idPopup) {
		//this.timeoutID = this.updateHeight.bind(this).delay(0.3, idPopup, true);
	},



	/**
	 * Enter Description here...
	 *
	 *	@param	String	idPopup
	 */
	getContentElement: function(idPopup) {
		return $(idPopup + '_content');
	},



	/**
	 * Update seize of popup to fit its content without scrollbar
	 *
	 *	@param	String	idPopup
	 *	@param	Boolean clearTimeout
	 */
	updateHeight: function(idPopup, clearTimeout) {
		this.getPopup(idPopup).updateHeight();
/*
		if( clearTimeout ) {
			this.clearTimeout();
		}
		*/
	},



	/**
	 * Clear timeout (if set)
	 */
	clearTimeout: function() {
		/*
		if( this.timeoutID !== null ) {
			window.clearTimeout(this.timeoutID);
			this.timeoutID = null;
		}
		*/
	},



	/**
	 * Update popup content
	 *
	 *	@param	String	contentUrl
	 *	@param	Object	requestOptions
	 */
	updateContent: function(idPopup, contentUrl, requestOptions) {		
		this.getPopup(idPopup).setAjaxContent(contentUrl, requestOptions, false, false);
	},



	/**
	 * Set content of given popup
	 *
	 *	@param	String	idPopup
	 *	@param	String	content
	 */	
	setContent: function(idPopup, content) {
		this.getPopup(idPopup).setHTMLContent(content);		
	},



	/**
	 * Refresh popup
	 *
	 *	@param	String	idPopup
	 */
	refresh: function(idPopup) {
		this.getPopup(idPopup).refresh();
	},



	/**
	 * Close popup
	 *
	 *	@param	idPopup
	 */
	close: function(idPopup) {
		this.getPopup(idPopup).close();
	}

};