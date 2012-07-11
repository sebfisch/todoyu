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
 * Context menu
 *
 * @class		ContextMenu
 * @namespace	Todoyu
 */
Todoyu.ContextMenu = {

	/**
	 * Callback for body click events
	 */
	bodyClickObserver: null,

	/**
	 * Currently visible sub menu (for the delay workaround)
	 */
	visibleSubmenu: null,

	/**
	 * Timeout callback for sub menu (for the delay workaround)
	 */
	hideSubmenuDelay: null,



	/**
	 * Attach contextmenu to a group of elements
	 * Automatically prevents double context menus by removing registered ones before adding the new one
	 *
	 * @method	attach
	 * @param	{String}		name		Name of the context menu type (php callbacks are registered for this type)
	 * @param	{String}		selector	CSS selector expression
	 * @param	{Function}		callback	Callback function to find element if on the observed DomElement
	 */
	attach: function(name, selector, callback) {
		this.detach(selector);

		$$(selector).each(function(element){
			element.on('contextmenu', this.load.bind(this, name, callback, element));
		}, this);
	},



	/**
	 * Detach context menu from all elements which match to the selector
	 *
	 * @method	detach
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
	 * @private
	 * @method	load
	 * @param	{String}		type				Name of the context menu type
	 * @param	{Function}		callback			Callback function to parse ID from element
	 * @param	{Element}		observedElement		Observed element
	 * @param	{Event}			event				Click event object
	 * @return	{Boolean}
	 */
	load: function(type, callback, observedElement, event) {
			// Stop click event to prevent browsers context menu
		event.stop();

		var elementKey	= callback(observedElement, event);

		var url		= Todoyu.getUrl('core', 'contextmenu');
		var options	= {
			parameters: {
				action:			'get',
				contextmenu:	type,
				element:		elementKey
			}
		};

		this.showMenu(url, options, event, type, elementKey);

		return false;
	},



	/**
	 * Request, render and display context menu
	 *
	 * @private
	 * @method	showMenu
	 * @param	{String}	url
	 * @param	{Array}		options
	 * @param	{Event}		event
	 * @param	{String}	type
	 * @param	{String}	elementKey
	 */
	showMenu: function(url, options, event, type, elementKey) {
			// Wrap to onComplete function to call renderMenu right before the defined onComplete function
		options.onComplete = (options.onComplete || Prototype.emptyFunction).wrap(function(proceed, transport, json) {
				// Build menu HTML from json
			this.buildMenuFromJSON(transport.responseJSON);
				// Set menu dimensions based on the event location and the items
			this.setMenuDimensions(event, type, elementKey);
				// Call defined onComplete function
			proceed(transport, json, type, elementKey);
		 }.bind(this));

		Todoyu.send(url, options);
	},



	/**
	 * Render item context menu from given JSON (using JS template)
	 *
	 * @private
	 * @method	buildMenuFromJSON
	 * @param	{Object}		menuJSON
	 */
	buildMenuFromJSON: function(menuJSON) {
		var menu = this.Template.render(menuJSON);

		this.updateMenuContainer(menu);

		Todoyu.QuickInfo.disable();
	},



	/**
	 * Set menu dimensions (display position) and show the menu
	 *
	 * @private
	 * @method	setMenuDimensions
	 * @param	{Event}		event
	 * @param	{String}	type
	 * @param	{String}	elementKey
	 */
	setMenuDimensions: function(event, type, elementKey) {
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
			position:	'absolute',
			display:	'block',
			left:		left + 'px',
			top:		top + 'px'
		});

			// Observe outside clicks
		this.bodyClickObserver = document.body.on('click', this.hide.bind(this));
			// Observe context-menu-clicks on context menu
		menu.on('contextmenu', this.preventContextMenu.bind(this));

		Todoyu.Hook.exec('core.contextmenu', type, elementKey, left, top);
	},



	/**
	 * Update context menu container with given HTML
	 *
	 * @method	updateMenuContainer
	 * @param	{String}		menuHTML
	 */
	updateMenuContainer: function(menuHTML) {
		$('contextmenu').update(menuHTML);
	},



	/**
	 * Prevent showing of context menu: stop event.
	 *
	 * @method	preventContextMenu
	 * @param	{Event}			event
	 * @return	{Boolean}
	 */
	preventContextMenu: function(event) {
		event.stop();
		return false;
	},



	/**
	 * Hide context menu
	 *
	 * @method	hide
	 */
	hide: function() {
		if( $('contextmenu') && this.bodyClickObserver ) {
				// Stop body click observer
			this.bodyClickObserver.stop();

			$('contextmenu').hide();

			Todoyu.QuickInfo.enable();
		}
	},



	/**
	 * Show or hide given item's sub menu (at calculated position)
	 * Note: The whole hide callback stuff is only necessary for firefox (5) on linux
	 *
	 * @method	submenu
	 * @param	{String}		key			Key of the item, a submenu should be displayed
	 * @param	{Boolean}		show		Show or hide?
	 * @param	{Boolean}		noDelay		Don't delay hide (was delayed on the first execution)
	 * @return	{Boolean}
	 */
	submenu: function(key, show, noDelay) {
		var ctxMenuID	= 'contextmenu';
		var itemID		= 'contextmenu-' + key;
		var submenuID	= itemID + '-submenu';

		if( ! Todoyu.exists(submenuID) ) {
			return false;
		}

			// Already show another submenu, hide last visible
		if( show === true && this.visibleSubmenu !== key && this.visibleSubmenu !== undefined ) {
			this.submenu(this.visibleSubmenu, false, true);
		}

			// Hide request for current submenu. Prevent closing while navigating in the submenu, so start delayed call
		if( show === false && noDelay !== true && key === this.visibleSubmenu ) {
			clearTimeout(this.hideSubmenuDelay);
			this.hideSubmenuDelay = this.submenu.bind(this, key, false, true).delay(0.1);
			return true;
		}

			// Show submenu, cancel delayed hide callback
		if( show === true && key === this.visibleSubmenu ) {
			clearTimeout(this.hideSubmenuDelay);
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

				// Set current visible context menu
			this.visibleSubmenu = key;
		} else {
				// Cancel hide callback
			if( this.visibleSubmenu === key ) {
				clearTimeout(this.hideSubmenuDelay);
			}
			this.visibleSubmenu = null;
			submenu.hide();
		}

		return true;
	}

};