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

Todoyu.QuickInfo = {

	/**
	 * ID of the quickinfo popup
	 */
	popupID:	'quickinfo',

	/**
	 * Content cache with time info
	 */
	cache:		{},

	/**
	 * Default cache time for an element (seconds)
	 */
	defaultCacheTime:	60,

	/**
	 * Custom cache time per element
	 * @see		this.setCacheTime()
	 */
	customCacheTime: {},

	/**
	 * Template object to convert JSON into HTML
	 */
	template:	null,

	/**
	 * Flag if loading is in progress (prevents multiple loading requests)
	 */
	loading:	false,

	/**
	 * Flag if quickinfo is currently hidden
	 */
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
	 * Set cache time for a type
	 *
	 * @param	{String}		extension
	 * @param	{String}		type
	 * @param	{Integer}		time		Cache time in seconds
	 */
	setCacheTime: function(extension, type, time) {
		this.customCacheTime[extension + type] = time;
	},



	/**
	 * Get cache time for an element type. Gets time until cache is valid
	 *
	 * @param	{String}		extension
	 * @param	{String}		type
	 */
	getCacheTime: function(extension, type) {
		return (new Date()).getTime() + (this.customCacheTime[extension + type] !== undefined ? parseInt(this.customCacheTime[extension + type]) : this.defaultCacheTime)*1000;
	},



	/**
	 * Show quickinf tooltip
	 * 
	 * @param	{Integer}		x
	 * @param	{Integer}		y
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
	 * @param	{String}		extension
	 * @param	{String}		type			'event', 'holiday', 'person' etc.
	 * @param	{String}		key
	 * @param	{Integer}		mouseX
	 * @param	{Integer}		mouseY
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
	 * @param	{Boolean}		isHidden
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
	 * @param	{String}	extension		todoyu extension to provide the controller
	 * @param	{String}	type			'event' / 'holiday' / 'person', etc.
	 * @param	{String}	key
	 * @param	{Integer}	mouseX
	 * @param	{Integer}	mouseY
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
	 * Show quickinfo after loaded by ajax. Add to cache with custom cache time
	 *
	 * @param	{String}			extension		Extension providing the element
	 * @param	{String}			type			Type of element
	 * @param	{String}			key				Key of element (mostly element ID)
	 * @param	{Integer}			x				Mouse Pointer Position
	 * @param	{Integer}			y				Mouse Pointer Position
	 * @param	{Ajax.Response}	response
	 */
	onQuickInfoLoaded: function(extension, type, key, x, y, response) {
		var cacheKey= type + key;
		var content	= this.buildQuickInfo(response.responseJSON);
		var time	= this.getCacheTime(extension, type);

		this.addToCache(cacheKey, content, time);

		this.loading = false;

		if ( ! this.hidden ) {
			this.show(extension, type, key, x, y);
		}
	},



	/**
	 * Render quick info tooltip HTML from JSON data
	 *
	 * @param	{JSON}		json
	 * @return	{String}
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
	 * @param	{String}	content
	 */
	updatePopup: function(content) {
		$(this.popupID).update(content);
	},



	/**
	 * Add quickinfo content to cache
	 *
	 * @param	{String}		cacheID		ID of the cached element
	 * @param	{String}		content		cached content
	 * @param	{Integer}		time		cache time
	 */
	addToCache: function(cacheID, content, time) {
		this.cache[cacheID] = {
			'time': 	time,
			'content': 	content
		};
	},



	/**
	 * Get quickinfo content from cache
	 *
	 * @param	{String}		cacheID
	 * @return	{String}		Or false
	 */
	getFromCache: function(cacheID) {
		return this.isCached(cacheID) ? this.cache[cacheID].content : false;
	},



	/**
	 * Remove item of given ID from cache
	 *
	 * @param	{String}	cacheID
	 */
	removeFromCache: function(cacheID) {
		if( this.cache[cacheID] ) {
			delete this.cache[cacheID];
		}
	},



	/**
	 * Check whether item with given ID is cached
	 *
	 * @return	{Boolean}
	 */
	isCached: function(cacheID) {
		var o =typeof(this.cache[cacheID]);



		return typeof(this.cache[cacheID]) === 'object' && this.cache[cacheID].time > (new Date()).getTime();
	}

};