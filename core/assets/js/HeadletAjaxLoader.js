/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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
 * Headlet: Ajax Loader
 * Icon which indicated AJAX loading activity in head area
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
	 * Show AJAX loading icon
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
	 * Hide AJAX loading icon
	 */
	hide: function() {
		Effect.Fade.delay(0.2, this.buttonID, {
			'duration': 0.3
		});
	},
	
	
	
	/**
	 * 
	 */
	onButtonClick: function(event) {
		Todoyu.log('ajax loader clicked');
	},



	/**
	 * 
	 */
	onContentClick: function(event) {
		Todoyu.log('ajax loader content clicked');
	},



	/**
	 * 
	 */
	onMouseOver: function(event) {
		Todoyu.log('over ajax');
	},



	/**
	 * 
	 */
	onMouseOut: function(event) {
		Todoyu.log('out ajax');
	}
	
};