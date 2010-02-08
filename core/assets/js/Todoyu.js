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
 * Todoyu main container. All other JS containers are (sub-)nodes
 * of this container
 */
var Todoyu = {

	name: 		'Todoyu',

	copyright: 	'Snowflake Productions, Zürich Switzerland',

	/**
	 * Container for extensions
	 */
	Ext: 		{},


	/**
	 * Initialize todoyu object
	 */
	init: function() {
		this.AjaxResponders.init();
		this.Ui.fixAnchorPosition();
	},



	/**
	 * Build request url with extension and controller
	 *
	 *	@param	String		ext
	 *	@param	String		controller
	 */
	getUrl: function(ext, controller) {
		var url = 'index.php?ext='+ext;

		if(controller)	{
			url = url + '&controller='+controller;
		}

		return url;
	},



	/**
	 * Redirect to an onther page
	 *
	 *	@param	String	ext
	 *	@param	String	controller
	 *	@param	Hash	params
	 *	@param	String	hash
	 */
	goTo: function(ext, controller, params, hash) {
		var url =  this.getUrl(ext, controller);

		if( typeof params === 'object' ) {
			url += '&' + Object.toQueryString(params);
		}

		if( Object.isString(hash) ) {
			url += '#' + hash;
		}

		document.location.href = url;
	},



	/**
	 * Send AJAX request
	 *
	 *	@param	String		url
	 *	@param	Hash		options
	 */
	send: function(url, options) {
		options = Todoyu.Ui._getDefaultOptions(options);

		return new Ajax.Request(url, options);
	},



	/**
	 * Check if an element exists
	 *
	 *	@param	DomElement,String		element		Element or its ID
	 */
	exists: function(element) {
		if( typeof element === 'object' ) {
			element = element.id;
		}

		return document.getElementById(element) !== null;
	},



	/**
	 * Get current area
	 */
	getArea: function() {
		return document.body.id.split('-').last();
	},


	/**
	 * Show notification
	 * 
	 * @param	String		type		('info', 'error', 'success')
	 * @param	String		message
	 * @param	Integer		countdown
	 */
	notify: function(type, message, countdown) {
		Todoyu.Notification.add(type, message, countdown);
	},



	/**
	 * Show error notification
	 * 
	 * @param	String		message
	 * @param	Integer		countdown
	 */
	notifyError: function(message, countdown) {
		this.notify('error', message, countdown);
	},



	/**
	 * Show info notification
	 * 
	 * @param	String		message
	 * @param	Integer		countdown
	 */
	notifyInfo: function(message, countdown) {
		this.notify('info', message, countdown);
	},



	/**
	 * Show success notification
	 * 
	 * @param	String		message
	 * @param	Integer		countdown
	 */
	notifySuccess: function(message, countdown) {
		this.notify('success', message, countdown);
	},



	callUserFunction: function(functionName /*, args */) {
		var args	= Array.prototype.splice.call(arguments, 1);
		var func	= this.getFunctionFromString(functionName);
		
		return func.apply(func, args);
	},



	getFunctionFromString: function(functionName) {
		var namespaces 	= functionName.split(".");
		var func 		= namespaces.pop();
		var context		= window;
		
		for(var i = 0; i < namespaces.length; i++) {
			context = context[namespaces[i]];
			
			if( context === undefined ) {
				alert("Function: " + functionName + " not found!");
			}
		}
		return context[func];
	},



	log: function(element) {
		if( typeof(console) !== 'undefined' ) {
			console.log(element);
		}
	}

};