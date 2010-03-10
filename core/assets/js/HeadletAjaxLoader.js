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
 * Headlet: Ajax Loader
 * Icon which indicated ajax loading activity in head area
 * 
 * @package		Todoyu
 * @subpackage	Core
 */
Todoyu.Headlet.AjaxLoader = {
	
	/**
	 * ID of the element which is toggled visible
	 */
	buttonID: 'headlet-ajaxloader-button',
	
	/**
	 * Show ajax loading icon
	 */
	show: function() {
		Effect.Appear(this.buttonID, {
			'duration': 0.2,
			'from': 0.3,
			'to': 1.0,
			'transition': Effect.Transitions.spring
		});		
	},
	
	
	
	/**
	 * Hide ajax loading icon
	 */
	hide: function() {
		Effect.Fade.delay(0.5, this.buttonID, {
			'duration': 0.5
		});
	},
	
	onButtonClick: function(event) {
		console.log('ajax loader clicked');
	},
	
	onContentClick: function(event) {
		console.log('ajax loader content clicked');
	},
	
	onMouseOver: function(event) {
		console.log('over ajax');
	},
	
	onMouseOut: function(event) {
		console.log('out ajax');
	}
	
};