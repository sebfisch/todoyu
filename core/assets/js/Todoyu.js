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

var Todoyu = {

	name: 		'Todoyu',

	copyright: 	'Snowflake Productions, Zürich Switzerland',

	Ext: 		{},

	Ui:			{},


	/**
	 *	Enter Description here...
	 *
	 */
	init: function() {
		this.AjaxResponders.register();
		this.Ui.fixAnchorPosition();
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	extKey
	 *	@param	unknown_type	version
	 *	@param	unknown_type	build
	 */
	register: function(extKey, version, build) {
		this.extReg[extKey] = {
			'version':	version,
			'build':	build
		};
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	extKey
	 */
	isInstalled: function(extKey) {
		return typeof this.extReg[extKey] !== 'undefined';
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	ext
	 *	@param	unknown_type	controller
	 */
	getUrl: function(ext, controller) {
		var url = '?ext='+ext;

		if(controller)	{
			url = url + '&controller='+controller;
		}

		return url;
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	ext
	 *	@param	unknown_type	controller
	 *	@param	unknown_type	params
	 *	@param	unknown_type	hash
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
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	url
	 *	@param	unknown_type	options
	 */
	send: function(url, options) {
		options = Todoyu.Ui._setDefaultOptions(options);

		return new Ajax.Request(url, options);
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	element
	 */
	exists: function(element) {
		if (typeof element === 'object') {
			element = element.id;
		}

		return document.getElementById(element) !== null;
	},



	/**
	 *	Get current viewed area (get-param 'ext' during last page load)
	 *
	 */
	getArea: function() {
		var queryParams = document.location.href.toQueryParams();

		return (queryParams.ext);
	},
	
	notify: function(type, message, countdown) {
		Todoyu.Notification.add(type, message, countdown);
	},
	
	notifyError: function(message, countdown) {
		this.notify('error', message, countdown);
	},
	
	notifyInfo: function(message, countdown) {
		this.notify('info', message, countdown);
	},
	
	notifySuccess: function(message, countdown) {
		this.notify('success', message, countdown);
	},
	
	callUserFunction: function(functionName, context /*, args */) {
		var args = Array.prototype.slice.call(arguments).splice(2);
		var namespaces = functionName.split(".");
		var func = namespaces.pop();
		for(var i = 0; i < namespaces.length; i++) {
			context = context[namespaces[i]];
		}
		return context[func].apply(this, args);
	}	

};