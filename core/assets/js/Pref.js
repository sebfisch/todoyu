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

Todoyu.Pref = {

	count: 0,

	temp: [],



	/**
	 *	Save user preference (AJAX)
	 *
	 *	@param	unknown_type	ext
	 *	@param	unknown_type	cmd
	 *	@param	unknown_type	value
	 *	@param	unknown_type	idItem
	 *	@param	unknown_type	onComplete
	 */
	save: function(ext, cmd, value, idItem, onComplete) {
		var url		= Todoyu.getUrl(ext, 'preference');
		var options	= {
			'parameters': {
				'cmd':		cmd,
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
	 *	Get user preference (AJAX)
	 *
	 *	@param	unknown_type	ext
	 *	@param	unknown_type	preference
	 */
	get: function(ext, preference) {
		var currentCount = this.count++;
		this.temp[currentCount] = null;

		var url		= Todoyu.getUrl(ext, 'preference');
		var options	= {
			'parameters': {
				'cmd':			'get',
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