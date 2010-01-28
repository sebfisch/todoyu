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

Ajax.Replacer = Class.create(Ajax.Request, {


	/**
	 * Initialize AJAX replacer
	 *
	 *	@param	unknown_type	$super
	 *	@param	unknown_type	container
	 *	@param	String	url
	 *	@param	Object	options
	 */
	initialize: function($super, container, url, options) {
		options = options || { };
		options.onComplete = (options.onComplete || Prototype.emptyFunction).wrap(function(proceed, transport, json) {
			$(container).replace(transport.responseText);
			proceed(transport, json);
		});
		$super(url, options);
	}
});




/*
Ajax.Replacer = Class.create(Ajax.Updater, {
	initialize: function($super, container, url, options) {
		options = options || { };
		options.onComplete = (options.onComplete || Prototype.emptyFunction).wrap(function(proceed, transport, json) {
			$(container).replace(transport.responseText);
			proceed(transport, json);
		})
		$super(container, url, options);
	}
})
*/