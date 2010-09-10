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

/**
 * Context menu
 *
 * @namespace	Todoyu.ContextMenu
 */
Todoyu.ContextMenu = {

	/**
	 * Attach contextmenu to a group of elements
	 * Automatically prevents double context menus by removing registered ones before adding the new one
	 *
	 * @param	{String}		name		Name of the contextmenu type (php callbacks are registered for this type)
	 * @param	{String}		selector	CSS selector expression
	 * @param	{Function}		callback	Callback function to find element if on the observed DomElement
	 */
	attach: function(name, selector, callback) {
		this.detach(selector);

		var elements	= $$(selector);

		elements.each(function(name, callback, element){
			element.observe('contextmenu', this.load.bindAsEventListener(this, name, callback, element));
		}.bind(this, name, callback));
	},



	/**
	 * Detach context menu from all elements which match to the selector
	 *
	 * @param	{String}	selector		CSS Selector
	 */
	detach: function(selector) {
		var elements	= $$(selector);

		elements.each(function(element) {
			element.stopObserving('contextmenu');
		});
	},



	/**
	 * Load context menu items (JSON) over AJAX
	 *
	 * @param	{Event}			event				Click event object
	 * @param	{String}		name				Name of the contextmenu type
	 * @param	{Function}		callback			Callback function to parse ID from element
	 * @param	{Element}		observedElement		Observed element
	 */
	load: function(event, name, callback, observedElement) {
			// Stop click event to prevent browsers context menu
		event.stop();

		var url		= Todoyu.getUrl('core', 'contextmenu');
		var options	= {
			'parameters': {
				'action':		'get',
				'contextmenu':	name,
				'element':		callback(observedElement, event)
			}
		};

		this.showMenu(url, options, event);

		return false;
	},



	/**
	 * Request, render and display context menu
	 *
	 * @param	{String}		url
	 * @param	{Array}			options
	 * @param	{Object}		event
	 */
	showMenu: function(url, options, event) {
			// Wrap to onComplete function to call renderMenu right before the defined onComplete function
		options.onComplete = (options.onComplete || Prototype.emptyFunction).wrap(function(event, proceed, transport, json) {
				// Build menu HTML from json
			this.buildMenuFromJSON(transport.responseJSON);
				// Set menu dimensions based on the event location and the items
			this.setMenuDimensions(event);
				// Call defined onComplete function
			proceed(transport, json);
		 }.bind(this, event));

		Todoyu.send(url, options);
	},



	/**
	 * Render item context menu from given JSON (using JS template)  
	 *
	 * @param	{Object}		menuJSON
	 */
	buildMenuFromJSON: function(menuJSON) {
		var menu = this.Template.render(menuJSON);

		this.updateMenuContainer(menu);
	},



	/**
	 * Set menu dimensions (display position) and show the menu
	 * 
	 * @param	{Event}		event
	 */
	setMenuDimensions: function(event) {
			// Fetch menu dimension data
		var menu		= $('contextmenu');
		var left		= event.pointerX();
		var top			= event.pointerY();
		var menuHeight	= parseInt(menu.clientHeight, 10);
		var menuWidth	= parseInt(menu.clientWidth, 10);
		var screenHeight= document.viewport.getHeight();
		var screenWidth	= document.viewport.getWidth();

			// Bugfix for FF2
		if( (top === 0 || top - window.scrollY === 0) && event.screenX ) {
			top		= event.screenY + window.scrollY;
			left	= event.screenX + window.scrollX;
		}

			// Render menu on top of the pointer if clicked to near of the page bottom
		if( (event.clientY + menuHeight) > screenHeight ) {
			top = top - menuHeight;
		}

			// Render menu on the left of the pointer if clicked to near of the right page border
		if( (event.clientX + menuWidth) > screenWidth ) {
			left = left - menuWidth;
		}

			// Set position of the menu
		menu.setStyle({
			'position':		'absolute',
			'display':		'block',
			'left':			left + 'px',
			'top':			top + 'px'
		});

			// Observe outside clicks
		Event.observe(document.body, 'click', Todoyu.ContextMenu.hide);
			// Observe context-menu-clicks on contextmenu
		Event.observe(menu, 'contextmenu', Todoyu.ContextMenu.preventContextMenu);
	},



	/**
	 * Update context menu container with given HTML
	 *
	 * @param	{String}		menuHTML
	 */
	updateMenuContainer: function(menuHTML) {
		$('contextmenu').update(menuHTML);
	},



	/**
	 * Prevent showing of context menu: stop event.
	 *
	 * @param	{Object}		event
	 */
	preventContextMenu: function(event) {
		event.stop();
		return false;
	},



	/**
	 * Hide context menu
	 */
	hide: function() {
			// Hide context menu
		$('contextmenu').hide();

			// Stop observing body for click events (outside of the context menu)
		Event.stopObserving(document.body, 'click', this.hide);
	},



	/**
	 * Show or hide given item's sub menu (at calculated position)   
	 *
	 * @param	{String}		key
	 * @param	{Boolean}		show
	 */
	submenu: function(key, show) {
		var ctxMenuID	= 'contextmenu';
		var itemID		= 'contextmenu-' + key;
		var submenuID	= itemID + '-submenu';

		if( ! Todoyu.exists(submenuID) ) {
			return false;
		}

		var submenu	= $(submenuID);

		if( show ) {
			var item	= $(itemID);
			var ctxMenu	= $(ctxMenuID);

			var itemWidth	= item.getWidth();
			var posCtxMenu	= ctxMenu.viewportOffset();
			var posItem		= item.viewportOffset();

			var left	= itemWidth - 5;
			var top		= posItem.top - posCtxMenu.top + 5;

			submenu.setStyle({
				'display':	'block',
				'left':		left + 'px',
				'top':		top + 'px'
			});
		} else {
			submenu.hide();
		}
	}

};