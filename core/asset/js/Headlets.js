/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012, snowflake productions GmbH, Switzerland
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

		if( this.areHeadletsVisible() ) {
			this.openHeadlet = this.getOpenHeadlet();
		}
	},



	/**
	 * Check whether headlets are present on the page
	 */
	areHeadletsVisible: function() {
		return Todoyu.exists('headlets');
	},



	/**
	 * Add a headlet object
	 *
	 * @method	add
	 * @param	{String}	name
	 * @param	{Class}		headletClass
	 */
	add: function(name, headletClass) {
		Todoyu.R[name] = this.headlets[name] = new headletClass(name);
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
			// Find open headlet
		var openOverlay	= this.getOpenHeadlet();

		if( this.openHeadlet === openOverlay ) {
			return ;
		} else {
			this.openHeadlet = openOverlay;
		}

			// Clear current timeout
		window.clearTimeout(this.openStatusTimeout);
			// Start new timeout
		this.openStatusTimeout = this.submitOpenStatus.bind(this).delay(1);
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
	 */
	submitOpenStatus: function() {
		var openHeadlet	= this.getOpenHeadlet();
		var headletKey	= openHeadlet ? openHeadlet.id.split('-').first() : '';

		var url		= Todoyu.getUrl('core', 'headlet');
		var options	= {
			parameters: {
				action: 'open',
				headlet:headletKey
			}
		};

		Todoyu.send(url, options);
	}

};