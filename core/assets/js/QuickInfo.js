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

Todoyu.QuickInfo = {

	popupID:	'quickinfo',

	cache:		{},

	template:	null,

	loading:	false,

	hidden:		false,



	/**
	 * Init quickinfo
	 */
	init: function() {
		this.insertQuickInfoElement(this.popupID);
	},



	/**
	 * Insert quick info elements container
	 */
	insertQuickInfoElement: function() {
		if( ! Todoyu.exists( this.popupID) ) {
			var quickInfo  = new Element('div', {
				'id':	this.popupID
			}).hide();

			$(document.body).insert(quickInfo);
		}
	},



	/**
	 * Show quickinf tooltip
	 * 
	 * @param	Integer		x
	 * @param	Integer		y
	 */
	showPopUp: function(x, y) {
			// Check hide-flag (prevent lapse due to running request while mouseOut happened)
		if ( ! this.hidden ) {
			x += 8;
			y -= 12;

			var popupWidth	= $(this.popupID).getDimensions().width;

			if ( x + 20 > window.innerWidth - popupWidth) {
				x = x - popupWidth - 20;
			}

			$(this.popupID).setStyle({
				'top':	y + 'px',
				'left':	x + 'px'
			}).show();
		}
	},



	/**
	 * Update quick info element style to given position and set it visible
	 *
	 * @param	String		extension
	 * @param	String		type			'event', 'holiday', 'person' etc.
	 * @param	String		key
	 * @param	Integer		mouseX
	 * @param	Integer		mouseY
	 */
	show: function(extension, type, key, mouseX, mouseY) {
		var cacheID= type + key;
		this.hidden	= false;

		if( this.loading === true ) {
			return false;
		}

		this.loading = true;

		if( this.isCached(cacheID) ) {
				// Show cached
			this.updatePopup(this.getFromCache(cacheID));
			this.showPopUp(mouseX, mouseY);
			this.loading = false;
		} else {
				// Have it be loaded and shown after
			this.loadQuickInfo(extension, type, key, mouseX, mouseY);
		}
	},



	/**
	 * Hide quick-info tooltip
	 *
	 * @param	Boolean		isHidden
	 * @return	Boolean
	 */
	hide: function() {
		if ( $(this.popupID) ) {
			$(this.popupID).hide();

				// hide-flag: comprehend overlapping of mouseOut and running show request
			this.hidden	= true;
		}
	},



	/**
	 * Evoke loading of quickinfo tooltip content
	 *
	 * @param	String	extension		todoyu extension to provide the controller
	 * @param	String	type			'event' / 'holiday' / 'person', etc.
	 * @param	String	key
	 * @param	Integer	mouseX
	 * @param	Integer	mouseY
	 */
	loadQuickInfo: function(extension, type, key, mouseX, mouseY) {
		var	url		= Todoyu.getUrl(extension, 'quickinfo');
		var options	= {
			'parameters': {
				'action':	type,
				'key':		key
			},
			'onComplete': this.onQuickInfoLoaded.bind(this, extension, type, key, mouseX, mouseY)
		};

		Todoyu.send(url, options);
	},



	/**
	 * Evoked upon quickinfo having been loaded, shows the quickinfo tooltip
	 *
	 * @param	String	type
	 * @param	String	key
	 * @param	Integer	x
	 * @param	Integer	y
	 * @param	Object	response
	 */
	onQuickInfoLoaded: function(extension, type, key, x, y, response) {
		this.loading = false;
		var quickInfo= this.buildQuickInfo(response.responseJSON);

		this.addToCache(type + key, quickInfo);

		if ( ! this.hidden ) {
			this.show(extension, type, key, x, y);
		}
	},



	/**
	 * Render quick info tooltip HTML from JSON data
	 *
	 * @param	JSON		json
	 * @return	String
	 */
	buildQuickInfo: function(json) {
		if( this.template === null ) {
			this.template = new Template('<dt class="#{key}Icon">&nbsp;</dt><dd class="#{key}Label">#{label}&nbsp;</dd>');
		}

		var content	= '';
		json.each(function(item){
				// Ensure maxiumum word length not to break layout: add wordwrap
			item.label	= Todoyu.Helper.wordwrap(item.label, 16, ' ', true);
				
				// Add template row with item
			content += this.template.evaluate(item);
		}.bind(this));

		return '<dl>' + content.replace("\n", '<br />') + '</dl>';
	},



	/**
	 * Update popup content
	 *
	 * @param	String	content
	 */
	updatePopup: function(content) {
		$(this.popupID).update(content);
	},



	/**
	 * Add quickinfo content to cache
	 *
	 * @param	String		cacheID
	 * @param	String		content
	 */
	addToCache: function(cacheID, content) {
		this.cache[cacheID] = content;
	},



	/**
	 * Get quickinfo from cache
	 *
	 * @param	String	cacheID
	 * @return	String
	 */
	getFromCache: function(cacheID) {
		return this.cache[cacheID];
	},



	/**
	 * Remove item of given ID from cache
	 *
	 * @param	String	cacheID
	 */
	removeFromCache: function(cacheID) {
		if( this.cache[cacheID] ) {
			delete this.cache[cacheID];
		}
	},



	/**
	 * Check whether item with given ID is cached
	 *
	 * @return	Boolean
	 */
	isCached: function(cacheID) {
		return typeof(this.cache[cacheID]) === 'string';
	}

};