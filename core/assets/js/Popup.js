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

	config: {
		'overlayShowEffectOptions': {
			'duration': 0.2
		},
		'overlayHideEffectOptions': {
			'duration': 0.1
		}
	},


	
	/**
	 * Get popup reference
	 * @param	String		idPopup
	 */
	getPopup: function(idPopup) {
		return this.popup[idPopup];
	},
	
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
	 *	Open new popup window
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

			// Set overlay show/ hide options
		Windows.overlayShowEffectOptions = this.config.overlayShowEffectOptions;
		Windows.overlayHideEffectOptions = this.config.overlayHideEffectOptions;

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

			showEffectOptions:	{},
			hideEffectOptions:	{},
			effectOptions:		null,
			parent:				document.getElementsByTagName("body").item(0)
		});
		
			// Show popup and activate content overlay
		this.getPopup(idPopup).showCenter(true);

			// Load & set inner content, install general click (== update seize) observer
		requestOptions = requestOptions || {};
		requestOptions.onComplete = this.onContentLoaded.bind(this, idPopup);

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
	 */
	onContentLoaded: function(idPopup, response) {
		//this.getPopup(idPopup).updateHeight();
		
		
		//this.getContentElement(idPopup).observe('mouseup', this.onMouseUp.bindAsEventListener(this, idPopup));

		//this.getPopup(idPopup).setCloseCallback(this.onWindowClose.bindAsEventListener(this, idPopup));
	},


	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	event
	 */
	onWindowClose: function(event, idPopup) {
		//this.getContentElement(idPopup).stopObserving('mouseup');
		//this.clearTimeout();

		//return true;
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	event
	 */
	onMouseUp: function(event, idPopup) {
		//this.timeoutID = this.updateHeight.bind(this).delay(0.3, idPopup, true);
	},



	/**
	 *	Enter Description here...
	 *
	 */
	getContentElement: function(idPopup) {
		return $(idPopup + '_content');
	},



	/**
	 *	Update seize of popup to fit its content without scrollbar
	 *
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
	 *	Clear timeout (if set)
	 *
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
	 *	Update popup content
	 *
	 *	@param	unknown_type	contentUrl
	 *	@param	unknown_type	requestOptions
	 */
	updateContent: function(idPopup, contentUrl, requestOptions) {		
		this.getPopup(idPopup).setAjaxContent(contentUrl, requestOptions, false, false);
	},
	
	setContent: function(idPopup, content) {
		this.getPopup(idPopup).setHTMLContent(content);		
	},


	/**
	 *	Refresh popup
	 *
	 */
	refresh: function(idPopup) {
		this.getPopup(idPopup).refresh();
	},


	/**
	 *	Close popup
	 *
	 */
	close: function(idPopup) {
		this.getPopup(idPopup).close();
	}

};