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
 * @module	Core
 */

/**
 * Headlet
 *
 * @namespace	Todoyu
 */
Todoyu.Headlets = {

	/**
	 * List of headlet JS objects (to call the handlers)
	 * @property	headlets
	 * @type		Object
	 */
	headlets: {},

	/**
	 *
	 * @property	openStatusTimeout
	 * @type		Function
	 */
	openStatusTimeout: null,

	/**
	 * Currently opened headlet
	 * @property	openHeadlet
	 * @type		String
	 */
	openHeadlet: null,



	/**
	 * Initialize headlet management (observation)
	 *
	 * @method	init
	 */
	init: function() {
			// Close headlets when clicked outside of the headlets (on body)
		Todoyu.Ui.addBodyClickObserver(this.onBodyClick.bind(this));
	},



	/**
	 * Add a headlet object
	 *
	 * @method	add
	 * @param	{String}	name
	 * @param	{Class}		headletClass
	 */
	add: function(name, headletClass) {
		this.headlets[name] = new headletClass(name);
	},



	/**
	 * Get headlet
	 *
	 * @method	getHeadlet
	 * @param	{String}	name
	 * @return	{Todoyu.Headlet}
	 */
	getHeadlet: function(name) {
		return this.headlets[name.toLowerCase()];
	},



	/**
	 * Check whether a headlet with this name exists
	 *
	 * @method	isHeadlet
	 * @param	{String}	name
	 * @return	{Boolean}
	 */
	isHeadlet: function(name) {
		return this.getHeadlet(name) !== undefined;
	},



	/**
	 * Handler when clicked on body, fired by Todoyu.Ui.onBodyClick()
	 * If clicked outside the headlets, hide all content boxes
	 *
	 * @method	onBodyClick
	 * @param	{Event}		event
	 */
	onBodyClick: function(event) {
		if( this.areHeadletsOpen() ) {
			$H(this.headlets).each(function(pair) {
				pair.value.onBodyClick();
			}, this);
		}
	},



	/**
	 * Save open status of a headlet
	 * Setup a timeout for the save function
	 *
	 * @method	saveOpenStatus
	 */
	saveOpenStatus: function() {
		var headletName		= false;
			// Find open headlet
		var openOverlay	= this.getOpenHeadlet();

			// Extract headlet name
		if( openOverlay !== undefined ) {
			headletName	= openOverlay.id.split('-').first();
		}

		if( this.openHeadlet === false && headletName === false ) {
			return ;
		}

		this.openHeadlet = headletName;

			// Clear current timeout
		window.clearTimeout(this.openStatusTimeout);
			// Start new timeout
		this.openStatusTimeout = this.submitOpenStatus.bind(this, headletName).delay(1);
	},



	/**
	 * Get currently open headlet
	 *
	 * @method	getOpenHeadlet
	 * @return	{Element|undefined}
	 */
	getOpenHeadlet: function() {
		return $('headlets').select('li.overlay > ul').detect(function(overlay){
			return overlay.visible();
		});
	},



	/**
	 * Check whether any headlet is currently opened
	 *
	 * @method	areHeadletsOpen
	 * @return	{Boolean}
	 */
	areHeadletsOpen: function() {
		return this.getOpenHeadlet() !== undefined;
	},



	/**
	 * Submit the currently open headlet
	 * False means, no headlet is open at the moment
	 *
	 * @method	submitOpenStatus
	 * @param	{String|Boolean}	headletName
	 */
	submitOpenStatus: function(headletName) {
		var url		= Todoyu.getUrl('core', 'headlet');
		var options	= {
			'parameters': {
				'action': 	'open',
				'headlet':	headletName === false ? '' : headletName
			}
		};

		Todoyu.send(url, options);
	}

};