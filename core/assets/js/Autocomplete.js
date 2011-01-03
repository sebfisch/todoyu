/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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
 * @module	Core
 */

/**
 * Autocompleter
 *
 * @class		Autocomplete
 * @namespace	Todoyu
 */
Todoyu.Autocomplete = {
	/**
	 * Configuration for autocompleter object
	 * @property	config
	 * @type		Object
	 */
	config: {
		paramName: 'input',
		minChars: 2
	},

	/**
	 * Autocompleter references
	 * @property	AC
	 * @type		Object
	 */
	AC: {},



	/**
	 * Initialize autocompleter
	 *
	 * @method	install
	 * @param	{Number}		idElement		ID of the element whichs value will be set by autocomplete
	 * @param	{Object}		config			Custom config
	 */
	install: function(idElement, config) {
		var inputField		= idElement + '-fulltext';
		var suggestDiv		= idElement + '-suggest';

			// setup request
		var url		= Todoyu.getUrl('core', 'autocomplete');
		var options = {
			paramName:	config.paramName || this.config.paramName,
			minChars:	config.minChars || this.config.minChars,
			callback:	this.beforeRequestCallback.bind(this),
			parameters:	'&action=update&autocompleter=' + config.acName + '&element=' + idElement
		};

		if( config.options ) {
			options = $H(options).update(config.options).toObject();
		}
		
			// Create autocompleter
		this.AC[idElement] = new Todoyu.Autocompleter(inputField, suggestDiv, url, options);
	},



	/**
	 * Callback which builds the request url
	 *
	 * @method	beforeRequestCallback
	 * @param	{Number}		idElement
	 * @param	{String}		acParam
	 * @return	{String}
	 */
	beforeRequestCallback: function(idElement, acParam) {
		var form	= $(idElement).up('form');
		var name	= form.readAttribute('name');
		var data	= form.serialize();

		return acParam + '&form=' + name + '&' + data;
	},

	getAC: function(name) {
		return this.AC[name];
	}

};