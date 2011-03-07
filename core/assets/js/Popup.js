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
 * Todoyu popup
 *
 * @namespace	Todoyu
 * @class
 * @see			http://prototype-window.xilinus.com/documentation.html
 */
Todoyu.Popup = Class.create(Window, {

	/**
	 * Default todoyu options for window
	 * @var	{Object}
	 */
	todoyuOptions: {
		className:			'dialog',
		resizable:			true,
		closable:			true,
		minimizable:		false,
		maximizable:		false,
		draggable:			false,
		zIndex:				2000,
		recenterAuto:		false,
		hideEffect:			Element.hide,
		showEffect:			Element.show,
		effectOptions:		null,
		destroyOnClose:		true
	},


	/**
	 * Constructor
	 * Handle contentUrl, content and element parameter
	 *
	 * @constructor
	 * @param	{Window.initialize}	$super
	 * @param	{Object}			options
	 */
	initialize: function($super, options) {
		options = Object.extend(this.todoyuOptions, options);

		$super(options);

		if( this.options.contentUrl ) {
			this.setAjaxContent(this.options.contentUrl, this.options.requestOptions||{}, false, false);
		} else if( this.options.content ) {
			this.setHTMLContent(this.options.content, true);
		} else if( this.options.element ) {
			this.insertElement(this.options.element);
		}

		Todoyu.Popups.setPopup(this.options.id, this);
		this.installObserver();
	},



	/**
	 * Get ID of the popup
	 *
	 * @method	getPopupID
	 * @return	{String}
	 */
	getPopupID: function() {
		return this.options.id;
	},



	/**
	 * Install observers
	 *
	 * @method	installObserver
	 */
	installObserver: function() {
		Windows.addObserver({
			onDestroy: this.onDestroy.bind(this)
		});
	},



	/**
	 * Insert a content element from DOM
	 *
	 * @method	insertElement
	 * @param	{Element}	element
	 */
	insertElement: function(element) {
		this.setContent(element, true, true);
	},



	/**
	 * Set html content. Evaluate scripts
	 *
	 * @method	setHTMLContent
	 * @param	{Window.setHTMLContent}	$super
	 * @param	{String}				html
	 * @param	{Boolean}	evalScripts
	 */
	setHTMLContent: function($super, html, evalScripts) {
		$super(html);

		if( evalScripts !== false ) {
			html.evalScripts();
		}
	},



	/**
	 * Destroy handler
	 *
	 * @method	onDestroy
	 * @param	{String}		eventName
	 * @param	{Todoyu.Popup}	popup
	 */
	onDestroy: function(eventName, popup) {
		Todoyu.Popups.onDestroy(popup);
	}
});