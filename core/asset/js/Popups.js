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
 * @module
 */

/**
 * Todoyu popups
 *
 * @namespace	Todoyu
 * @see http://prototype-window.xilinus.com/documentation.html
 */
Todoyu.Popups = {
	/**
	 * References to opened popoups
	 */
	popups: {},

	/**
	 * Stack of opened popup IDs
	 */
	stack: [],


	/**
	 * Set popup reference to access opened popup
	 * This is done by Todoyu.Popup() already
	 *
	 * @method	setPopup
	 * @param	{String}		idPopup
	 * @param	{Todoyu.Popup}	popup
	 */
	setPopup: function(idPopup, popup) {
		this.popups[idPopup] = popup;

		this.stack.push(idPopup);
	},



	/**
	 * Get opened popup
	 *
	 * @method	getPopup
	 * @param	{String}	idPopup
	 * @return	{Todoyu.Popup}
	 */
	getPopup: function(idPopup) {
		return this.popups[idPopup];
	},



	/**
	 * Close open popup
	 *
	 * @method	close
	 * @param	{String}	idPopup
	 */
	close: function(idPopup) {
		this.getPopup(idPopup).close();
	},



	/**
	 * Get last opened popup
	 *
	 * @method	getLast
	 * @return	{Todoyu.Popup}
	 */
	getLast: function() {
		return this.getPopup(this.stack.pop());
	},



	/**
	 * Close last opened popup
	 *
	 * @method	closeLast
	 */
	closeLast: function() {
		this.getLast().close();
	},



	/**
	 * Create a new popup with given options and show it centered
	 *
	 * @method	show
	 * @param	{Object}	options
	 * @return	{Todoyu.Popup}
	 */
	show: function(options) {
		var popup = new Todoyu.Popup(options);

		popup.onShow =   this.onShow(options.id);
		popup.showCenter(true, 100);

		return popup;
	},



	/**
	 * Handler called after popup shown
	 *
	 * @param	{String}	idPopup
	 */
	onShow: function(idPopup) {

	},



	/**
	 * Focus first field (of first form) inside given/ most recent popup
	 *
	 * @param	{String}	idPopup
	 */
	focusFirstField: function(idPopup) {
		idPopup	=	idPopup || this.getLast().element.id;

		var form	= $(idPopup).select('form')[0];
		if( form ) {
			form.down('textarea[type!="hidden"], select[type!="hidden"], input[type!="hidden"]').focus();
		}
	},



	/**
	 * Handler when popup is destroyed
	 * Close RTE references
	 *
	 * @method	onDestroy
	 * @param	{Todoyu.Popup}	popup
	 */
	onDestroy: function(popup) {
		Todoyu.Ui.closeRTE(popup.content);

		delete this.popups[popup.getPopupID()];

		this.stack = this.stack.without(popup.getPopupID());
	},



	/**
	 * Open new popup window
	 *
	 * @method	open
	 * @param	{String}		idPopup
	 * @param	{String}		title
	 * @param	{Number}		minWidth
	 * @param	{String}		contentUrl
	 * @param	{Object}		requestOptions
	 * @return	{Todoyu.Popup}
	 */
	open: function(idPopup, title, minWidth, contentUrl, requestOptions) {
		return this.show({
			id:				idPopup,
			title:			title,
			minWidth:		minWidth || 220,
			minHeight:		240,
			contentUrl:		contentUrl,
			requestOptions:	requestOptions || {}
		});

		return popup;
	},



	/**
	 * Open new popup window containing given element
	 *
	 * @method	openElement
	 * @param	{String}		idPopup
	 * @param	{String}		idContentElement
	 * @param	{String}		title
	 * @param	{Function}		closeCallback
	 * @return	{Todoyu.Popup}
	 */
	openElement: function(idPopup, idContentElement, title, closeCallback) {
		return this.show({
			id:				idPopup,
			element:		idContentElement,
			title:			title,
			closeCallback:	closeCallback
		});
	},



	/**
	 * Open new popup window containing given HTML content
	 *
	 * @method	openContent
	 * @param	{String}		idPopup
	 * @param	{String}		content
	 * @param	{String}		title
	 * @param	{Number}		minWidth
	 * @param	{Function}		closePopupCallback
	 * @return	{Todoyu.Popup}
	 */
	openContent: function(idPopup, content, title, minWidth, closePopupCallback) {
		return this.show({
			id:				idPopup,
			title:			title,
			minWidth:		minWidth,
			minHeight:		100,
			content:		content,
			closeCallback:	closePopupCallback
		});
	},



	/**
	 * Update popup content from response of AJAX request
	 *
	 * @method	updateContent
	 * @param	{String}	idPopup
	 * @param	{String}	contentUrl
	 * @param	{Object}	requestOptions
	 */
	updateContent: function(idPopup, contentUrl, requestOptions) {
		this.getPopup(idPopup).setAjaxContent(contentUrl, requestOptions, false, false);
	},



	/**
	 * Set content of given popup
	 *
	 * @method	setContent
	 * @param	{String}	idPopup
	 * @param	{String}	content
	 */
	setContent: function(idPopup, content) {
		this.getPopup(idPopup).setHTMLContent(content);
	}

};