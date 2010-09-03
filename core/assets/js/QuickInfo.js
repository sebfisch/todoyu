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
	 * Timout callback for delayed hiding of quickinfo
	 */
	delayedHide: null,

	/**
	 * Delay for hiding. Allows to get move the mouse over the quickinfo, to prevent hiding
	 */
	delayedHideTime: 0.4,

	/**
	 * Active element (DOM element)
	 */
	active: null,



	/**
	 * Init quickinfo
	 */
	init: function() {
			// Insert HTML element into document
		this.insertQuickInfoElement(this.popupID);
			// Observe document for clicks to close the quickinfo
		document.body.observe('click', this.hide.bindAsEventListener(this));
	},



	/**
	 * Install quickinfo on elements which match the selector
	 *
	 * @param	{String}	name
	 * @param	{String}	selector
	 * @param	{Function}	callback
	 */
	install: function(name, selector, callback) {
		this.uninstall(selector);

		$$(selector).each(function(name, callback, element){
			element.observe('mouseover', this.onMouseOver.bindAsEventListener(this, name, callback, element));
			element.observe('mouseout', this.onMouseOut.bindAsEventListener(this, name, callback, element));
		}.bind(this, name, callback));
	},



	/**
	 * Handler when an observer element is hovered
	 *
	 * @param	{Event}		event
	 * @param	{String}	name
	 * @param	{Function}	callback
	 * @param	{Element}	element
	 */
	onMouseOver: function(event, name, callback, element) {
			// Hide active element if another one should be displayed
		if( this.active !== null && this.active !== element) {
			this.hide();
		}

			// Clear delayed timeout for hide
		clearTimeout(this.delayedHide);

		if( ! this.isVisible() ) {
			this.show(event, name, callback, element);
		}

        Todoyu.Hook.exec('core.quickinfo.mouseover', event, name, element);
	},



	/**
	 * Handler when an observed element is left with the mouse
	 *
	 * @param	{Event}		event
	 * @param	{String}	name
	 * @param	{Function}	callback
	 * @param	{Element}	element
	 */
	onMouseOut: function(event, name, callback, element) {
		if( this.isVisible() ) {
				// Delayed hide
			this.delayedHide = this.hide.bind(this).delay(this.delayedHideTime);
		}

        Todoyu.Hook.exec('core.quickinfo.mouseout', event, name, element);
	},



	/**
	 * Uninstall quickinfo from elements which match the selector
	 *
	 * @param	{String}	selector
	 */
	uninstall: function(selector) {
		$$(selector).each(function(element) {
			element.stopObserving('mouseover');
			element.stopObserving('mouseout');
		});
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

				// Observe quickinfo for mouse events
			$(this.popupID).observe('mouseover', this.onInfoOver.bindAsEventListener(this));
			$(this.popupID).observe('mouseout', this.onInfoOut.bindAsEventListener(this));
		}
	},



	/**
	 * Handler when moving the mouse on the quickinfo
	 * Cancel delayed hiding
	 *
	 * @param	{Event}		event
	 */
	onInfoOver: function(event) {
		clearTimeout(this.delayedHide);
	},



	/**
	 * Handler when moving the mouse off the quickinfo
	 * Start delayed hiding
	 *
	 * @param	{Event}		event
	 */
	onInfoOut: function(event) {
		this.delayedHide = this.hide.bind(this).delay(this.delayedHideTime);
	},



	/**
	 * Update quick info element style to given position and set it visible
	 *
	 * @param	{Event}			event
	 * @param	{String}		name
	 * @param	{Function}		callback
	 * @param	{String}		observedElement
	 */
	show: function(event, name, callback, observedElement) {
		event.stop();

//		var elementID	=observedElement.id;
//		var context		= elementID.split('_')[0];

		var elementKey	= callback(observedElement, event);

		var cacheID= name + elementKey;
		this.hidden	= false;

		if( this.loading === true ) {
			return false;
		}

		this.loading = true;

		if( this.isCached(cacheID) ) {
				// Show cached
			this.display(name, elementKey, event.pointerX(), event.pointerY(), observedElement);
			this.loading = false;
		} else {
				// Have it be loaded and shown after
			this.loadQuickInfo(name, elementKey, callback, event, observedElement);
		}
	},



	/**
	 * Display quickinfo which is in cache
	 *
	 * @param	{String}	name
	 * @param	{String}	elementKey
	 * @param	{Number}	pointerX
	 * @param	{Number}	pointerY
	 * @param	{Element}	observedElement
	 */
	display: function(name, elementKey, pointerX, pointerY, observedElement) {
		this.active	= observedElement;

		this.updatePopup(this.getFromCache(name + elementKey));

		this.showPopUp(pointerX, pointerY);
	},



	/**
	 * Set cache time for a type
	 *
	 * @param	{String}		type
	 * @param	{Number}		time		Cache time in seconds
	 */
	setCacheTime: function(type, time) {
		this.customCacheTime[type] = time;
	},



	/**
	 * Get cache time for an element type. Gets time until cache is valid
	 *
	 * @param	{String}		type
	 */
	getCacheTime: function(type) {
		return (new Date()).getTime() + (this.customCacheTime[type] !== undefined ? parseInt(this.customCacheTime[type], 10) : this.defaultCacheTime)*1000;
	},



	/**
	 * Show quickinfo tooltip
	 *
	 * @param	{Number}		x
	 * @param	{Number}		y
	 */
	showPopUp: function(x, y) {
			// Check hide-flag (prevent lapse due to running request while mouseOut happened)
		if( ! this.hidden ) {
			x += 8;
			y -= 12;

			var popupWidth	= $(this.popupID).getDimensions().width;

			if( x + 20 > window.innerWidth - popupWidth) {
				x = x - popupWidth - 20;
			}

			$(this.popupID).setStyle({
				'top':	y + 'px',
				'left':	x + 'px'
			}).show();
		}
	},



	/**
	 * Hide quick-info tooltip
	 */
	hide: function() {
		if( $(this.popupID) ) {
			$(this.popupID).hide();

				// hide-flag: comprehend overlapping of mouseOut and running show request
			this.hidden	= true;
			this.active	= null;
		}
	},



	/**
	 * Evoke loading of quickinfo tooltip content
	 *
	 * @param	{String}	name			'event' / 'holiday' / 'person', etc.
	 * @param	{String}	elementKey
	 * @param	{Function}	callback
	 * @param	{Event}		event
	 * @param	{Element}	observedElement
	 */
	loadQuickInfo: function(name, elementKey, callback, event, observedElement) {
		var url		= Todoyu.getUrl('core', 'quickinfo');
		var options	= {
			'parameters': {
				'action':		'get',
				'quickinfo':	name,
				'element':		elementKey
			},
			'onComplete': this.onQuickInfoLoaded.bind(this, name, elementKey, event, observedElement)
		};

		Todoyu.send(url, options);
	},



	/**
	 * Show quickinfo after loaded by ajax. Add to cache with custom cache time
	 *
	 * @param	{String}			name			Type of element
	 * @param	{String}			elementKey		Key of element (mostly element ID)
	 * @param	{Event}				event			Hover event
	 * @param	{Ajax.Response}		response		Ajax response
	 * @param	{Ajax.Response}	response
	 */
	onQuickInfoLoaded: function(name, elementKey, event, observedElement, response) {
		var cacheKey= name + elementKey;
		var content	= this.buildQuickInfo(response.responseJSON);
		var time	= this.getCacheTime(name);

		this.addToCache(cacheKey, content, time);

		this.loading= false;

		if( ! this.hidden ) {
			this.display(name, elementKey, event.pointerX(), event.pointerY(), observedElement);
		}
	},



	/**
	 * Render quick info tooltip HTML from JSON data
	 *
	 * @param	{Object}		json
	 * @return	{String}
	 */
	buildQuickInfo: function(json) {
		if( this.template === null ) {
			this.template = new Template('<dt class="#{key}Icon">&nbsp;</dt><dd class="#{key}Label">#{label}&nbsp;</dd>');
		}

		var content	= '';
		json.each(function(item){
				// Ensure maximum word length not to break layout: add wordwrap. But only if string doesn't contain html
			if( item.label.indexOf('<') === -1 ) {
				item.label	= Todoyu.Helper.wordwrap(item.label, 16, ' ', true);
			}

				// Add template row with item
			content += this.template.evaluate(item);
		}.bind(this));

		return '<dl>' + content.replace("\n", '<br />') + '</dl>';
	},



	/**
	 * Update popUp content
	 *
	 * @param	{String}	content
	 */
	updatePopup: function(content) {
		$(this.popupID).update(content);
	},



	/**
	 * Check whether the quickinfo is currently visible
	 */
	isVisible: function() {
		return $(this.popupID).visible();
	},



	/**
	 * Add quickInfo content to cache
	 *
	 * @param	{String}		cacheID		ID of the cached element
	 * @param	{String}		content		cached content
	 * @param	{Number}		time		cache time
	 */
	addToCache: function(cacheID, content, time) {
		this.cache[cacheID] = {
			time:	time,
			content:content
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
		return typeof(this.cache[cacheID]) === 'object' && this.cache[cacheID].time > (new Date()).getTime();
	}

};