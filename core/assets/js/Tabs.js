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
 * @module	Core
 */

/**
 * Tabs handling
 *
 * @class		Tabs
 * @namespace	Todoyu
 */
Todoyu.Tabs = {

	/**
	 * Event handlers for tabs
	 * @property	handler
	 * @type		Object
	 */
	handler: {},



	/**
	 * Create tabs based on the <ul><li>
	 *
	 * @method	create
	 * @param	{String}	name
	 * @param	{Function}	handlerFunction
	 */
	create: function(name, handlerFunction) {
		var list = $(name + '-tabs');

		this.handler[list.id] = {
			click: 		list.on('click',	'li', this._clickHandler.bind(this, handlerFunction)),
			mouseover: 	list.on('mouseover','li', this._hoverHandler.bindAsEventListener(this, true)),
			mouseout: 	list.on('mouseout', 'li', this._hoverHandler.bindAsEventListener(this, false))
		};
	},



	/**
	 * Remove event listeners from a tab group
	 *
	 * @method	destroy
	 * @param	{String}	list
	 */
	destroy: function(list) {
		list = $(list);

		this.handler[list.id].click.stop();
		this.handler[list.id].mouseover.stop();
		this.handler[list.id].mouseout.stop();

		delete this.handler[list.id];
	},



	/**
	 * Handler for click events on a tab
	 *
	 * @private
	 * @method	_clickHandler
	 * @param	{Event}			event
	 * @param	{Function}		handlerFunction
	 */
	_clickHandler: function(handlerFunction, event, element) {
		event.stop();

			// Get tabkey identifier
		var tabKeyClass = element.classNames().detect(function(className){
			return className.startsWith('tabkey-');
		}).split('-').last();

			// Call handler function
		handlerFunction(event, tabKeyClass);

		this.setActiveByElement(element);
	},



	/**
	 * Set a tab active in a group of tabs
	 *
	 * @method	setActive
	 * @param	{String}	listname
	 * @param	{String}	tab
	 */
	setActive: function(listname, tab) {
		var tabID	= listname + '-tabs';

		if( Todoyu.exists(tabID) ) {
			$(listname + '-tabs').select('li').invoke('removeClassName', 'active');
			$(listname + '-tabs').down('li.tabkey-' + tab).addClassName('active');
		} else {
			Todoyu.log('Tab with name "' + listname + '" not found!');
		}
	},



	/**
	 * Activate tab element
	 *
	 * @method	setActiveByElement
	 * @param	{Element}	tabElement
	 */
	setActiveByElement: function(tabElement) {
		var idParts	= $(tabElement).id.split('-tab-');
		this.setActive(idParts.first(), idParts.last());
	},



	/**
	 * Get currently active tab in a list
	 *
	 * @method	getActive
	 * @param	{String}		list		List element or its ID
	 * @return	{Element}
	 */
	getActive: function(list) {
		return $(list).down('li.active');
	},



	/**
	 * Get key of the active tab of the list
	 *
	 * @method	getActiveKey
	 * @param	{String}		list		List or its ID
	 * @return	{Element}
	 */
	getActiveKey: function(list) {
		var active = this.getActive(list);

		if( active ) {
			return active.id.split('-').last();
		} else {
			return null;
		}
	},



	/**
	 * Set the label text of a tab
	 *
	 * @method	setLabel
	 * @param	{String}	listname
	 * @param	{String}	tab
	 * @param	{String}	label
	 */
	setLabel: function(listname, tab, label) {
		$(listname + '-tab-' + tab).down('span.labeltext').update(label);
	},



	/**
	 * Remove a tab from a tab group
	 *
	 * @method	removeTab
	 * @param	{String}	listname
	 * @param	{String}	tab
	 */
	removeTab: function(listname, tab) {
		var tabElement = $(listname + '-tab-' + tab);

		if( tabElement ) {
			tabElement.remove();
		}
	},



	/**
	 * Build a tab
	 *
	 * @method	build
	 * @param	{String}	listname
	 * @param	{String}	name
	 * @param	{String}	tabClass
	 * @param	{String}	tabLabel
	 * @param	{Boolean}	active
	 */
	build: function(listname, name, tabClass, tabLabel, active) {
		var tab = new Element('li', {
			'id': listname + '-tab-' + name,
			'class': tabClass
		});
		var p = new Element('p', {
			'id': listname + '-tab-' + name + '-label',
			'class': 'label'
		});
		var lt = new Element('span', {
			'class': 'lt'
		});
		var icon = new Element('span', {
			'class': 'icon'
		});
		var labeltext = new Element('span', {
			'class': 'labeltext'
		}).update(tabLabel);

		tab.insert(p);
		p.insert(lt);
		p.insert(icon);
		p.insert(labeltext);

		if( active === true ) {
			tab.addClassName('active');
			p.addClassName('active');
		}

		return tab;
	},



	/**
	 * Enter Description here...
	 *
	 * @private
	 * @method	_hoverHandler
	 * @param	{Event}		event
	 * @param	{Boolean}	over
	 */
	_hoverHandler: function(event, over, element, x, y, z) {
		var li = event.findElement('li');

		if( Object.isUndefined(li) ) {
			return;
		}

		if( over ) {
			li.addClassName('hover');
		} else {
			li.removeClassName('hover');
		}
	}

};