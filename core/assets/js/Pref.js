/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSC License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

Todoyu.Pref = {

	count: 0,

	temp: [],



	/**
	 * Save preference (AJAX)
	 *
	 *	@param	unknown_type	ext
	 *	@param	unknown_type	action
	 *	@param	unknown_type	value
	 *	@param	unknown_type	idItem
	 *	@param	unknown_type	onComplete
	 */
	save: function(ext, action, value, idItem, onComplete) {
		var url		= Todoyu.getUrl(ext, 'preference');
		var options	= {
			'parameters': {
				'action':	action,
				'value':	value,
				'item':		idItem,
				'area':		Todoyu.getArea()
			}
		};

		if( ! Object.isUndefined(onComplete) ) {
			options.onComplete = onComplete;
		}

		Todoyu.send(url, options);
	},



	/**
	 * Get preference (AJAX)
	 *
	 *	@param	String	ext
	 *	@param	String	preference
	 */
	get: function(ext, preference) {
		var currentCount = this.count++;
		this.temp[currentCount] = null;

		var url		= Todoyu.getUrl(ext, 'preference');
		var options	= {
			'parameters': {
				'action':		'get',
				'preference':	preference
			},
			'asynchronous':		false,
			'onComplete':		function(count, response) {
									this.temp[count] = response.responseText;
								}.bind(this, currentCount)
		};

		Todoyu.send(url, options);

		return this.temp[currentCount];
	}

};