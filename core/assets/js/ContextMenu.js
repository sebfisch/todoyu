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

Todoyu.ContextMenu = {
	
	/**
	 * Attach contextmenu to all elements with the triggerClass
	 * 
	 *	@param	String		triggerClass
	 *	@param	Function	callbackFunction
	 */
	attachMenuToClass: function(triggerClass, callbackFunction) {
		var triggerAreas = $$('.' + triggerClass);

		triggerAreas.each(function(element) {
			Todoyu.ContextMenu.attachMenuToElement(element, callbackFunction);
		});
	},


	
	/**
	 * Attach contextmenu to the elements in the list
	 * 
	 *	@param	Array		elementIDs
	 *	@param	Function	callbackFunction
	 */
	attachMenuToIDs: function(elementIDs, callbackFunction) {
		elementIDs.each(function(element) {
			Todoyu.ContextMenu.attachMenuToElement($(element), callbackFunction);
		});
	},



	/**
	 * Attach menu to the list of elements
	 * 
	 *	@param	Array		elements
	 *	@param	Function	callbackFunction
	 */
	attachMenuToElements: function(elements, callbackFunction) {
		elements.each(function(element) {
			Todoyu.ContextMenu.attachMenuToElement(element, callbackFunction);
		});
	},



	/**
	 * Attach menu to an element
	 * 
	 *	@param	DomElement	element
	 *	@param	Function	callbackFunction
	 */
	attachMenuToElement: function(element, callbackFunction) {
		if( $(element) ) {
			$(element).observe('contextmenu', callbackFunction);
		}
	},


	
	/**
	 * Detach contextmenu which are triggered to the triggerClass
	 * 
	 *	@param	String		triggerClass
	 */
	detachAllMenus: function(triggerClass) {
		var triggerAreas = $$('.'+triggerClass);

		triggerAreas.each(function(element) {
			element.stopObserving('contextmenu');
		});
	},


	
	/**
	 * Detach contextmenu from a specific element
	 * 
	 *	@param	DomElement		element
	 */
	detachMenuFromElement: function(element) {
		if($(element))	{
			$(element).stopObserving('contextmenu');
		}
	},

	
	/**
	 * Set menu dimensions (display position) and show the menu
	 * 
	 *	@param	Event		event
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
			top 	= event.screenY + window.scrollY;
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
			'position':'absolute',
			'display': 'block',
			'left': left + 'px',
			'top': top + 'px'
		});

			// Observe outside clicks
		Event.observe(document.body, 'click', Todoyu.ContextMenu.hide);
			// Observe context-menu-clicks on contextmenu
		Event.observe(menu, 'contextmenu', Todoyu.ContextMenu.preventContextMenu);
	},



	/**
	 * Please enter Description here...
	 *
	 *	@param	unknown_type	menuJSON
	 */
	buildMenuFromJSON: function(menuJSON) {
		var menu = this.Template.render(menuJSON);

		this.updateMenuContainer(menu);
	},



	/**
	 * Please enter Description here...
	 *
	 *	@param	unknown_type	menuHTML
	 */
	updateMenuContainer: function(menuHTML) {
		$('contextmenu').update(menuHTML);
	},



	/**
	 * Please enter Description here...
	 *
	 *	@param	unknown_type	event
	 */
	preventContextMenu: function(event) {
		event.stop();
		return false;
	},



	/**
	 * Please enter Description here...
	 *
	 */
	hide: function() {
			// Hide contextmenu
		$('contextmenu').hide();

			// Stop observing body for click events (outside of the context menu)
		Event.stopObserving(document.body, 'click', Todoyu.ContextMenu.hide);
	},



	/**
	 * Please enter Description here...
	 *
	 *	@param	unknown_type	key
	 *	@param	unknown_type	show
	 */
	submenu: function(key, show) {
		var ctxMenuID	= 'contextmenu';
		var itemID 		= 'contextmenu-' + key;
		var submenuID	= itemID + '-submenu';

		if( ! Todoyu.exists(submenuID) ) {
			return false;
		}

		var submenu	= $(submenuID);

		if( show ) {
			var item 	= $(itemID);
			var ctxMenu	= $(ctxMenuID);

			var itemWidth	= item.getWidth();

			//submenu.makePositioned();
			var posCtxMenu	= ctxMenu.viewportOffset();
			var posItem 	= item.viewportOffset();


			var left	= itemWidth - 5;
			var top		= posItem.top - posCtxMenu.top + 5;

			submenu.setStyle({
				'display': 'block',
				'left': left + 'px',
				'top': top + 'px'
			});
		} else {
			submenu.hide();
		}
	},



	/**
	 * Please enter Description here...
	 *
	 *	@param	unknown_type	url
	 *	@param	unknown_type	options
	 *	@param	unknown_type	event
	 */
	showMenu: function(url, options, event) {
			// Stop click event to prevent browsers context menu
		event.stop();
				
			// Wrap to onComplete function to call renderMenu right before the defined onComplete function
		options.onComplete = (options.onComplete || Prototype.emptyFunction).wrap(function(event, proceed, transport, json) {
				// Build menu html from json
			this.buildMenuFromJSON(transport.responseJSON);
				// Set menu dimensions based on the event location and the items
			this.setMenuDimensions(event);
				// Call defined onComplete function
			proceed(transport, json);
		 }.bind(this, event));

		Todoyu.send(url, options);
	}
};