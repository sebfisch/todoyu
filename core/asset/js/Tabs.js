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
	 * Max width of all tab
	 */
	maxWidth: 672,

	/**
	 * Max width of a tab (ignored if there is more space available
	 */
	maxTabWidth: 224,


	/**
	 * Create tabs based on the <ul><li>
	 *
	 * @method	create
	 * @param	{String}	name
	 * @param	{Function}	handlerFunction
	 */
	create: function(name, handlerFunction) {
		var list = this.getList(name);

		this.handler[list.id] = {
			click: 		list.on('click',	'li', this._clickHandler.bind(this, handlerFunction)),
			mouseover: 	list.on('mouseover','li', this._hoverHandler.bind(this, true)),
			mouseout: 	list.on('mouseout', 'li', this._hoverHandler.bind(this, false))
		};

		this.activateFirstIfNonActive(list);
		this.cropTabs(list);
	},



	/**
	 * List element (ul)
	 *
	 * @param	{String}	name
	 * @return	{Element}
	 */
	getList: function(name) {
		return $(name + '-tabs');
	},



	/**
	 * Get tab element
	 *
	 * @param	{String}	listName
	 * @param	{String}	tabName
	 */
	getTab: function(listName, tabName) {
		return this.getList(listName).down('.item.tabkey-' + tabName);
	},

	

	/**
	 * Crop all labels in a tab row to make sure they fit in a row
	 *
	 * @param	{Element}	list
	 */
	cropTabs: function(list) {
		list = $(list);
		this.resetUncroppedLabels(list);

		var postFix				= '..';
		var totalWidthSmallTabs	= 0;
		var allTabs				= $(list).select('.item');
		var padding				= allTabs.size() * 3;
		var tooLongTabs			= allTabs.findAll(function(tab){
			return parseInt(tab.getWidth()) > this.maxTabWidth;
		}, this);
		var numTooLongTabs		= tooLongTabs.size();

			// Calc total width
		var totalWidth = allTabs.collect(function(tab){
			return parseInt(tab.getWidth(), 10);
		}).sum() + padding;

			// Stop here, if tabs are not too width
		if( totalWidth <= this.maxWidth ) {
			return false;
		}

			// Count width of small tabs and number of too long tabs
		$(list).select('.item').each(function(tab){
			var width = parseInt(tab.getWidth(), 10);
			if( width <= this.maxTabWidth ) {
				totalWidthSmallTabs += width;
			}
		}, this);

			// Divide available with by too long tabs
		var shareWidth	= this.maxWidth - totalWidthSmallTabs - padding;
		var cropToWidth	= Math.floor(shareWidth/numTooLongTabs);

		var tabWidth, tabLabel, shortenLabel, postFixLength = postFix.length;

			// Remove chars as long the total width is larger than the maxWidth
		while( totalWidth > this.maxWidth ) {
			tooLongTabs.each(function(tab, index){
				tabWidth	= parseInt(tab.getWidth(), 10);

				console.log('crop');

					// Is tab longer than the crop width
				if( tabWidth > cropToWidth ) {
					tabLabel = tab.down('span.labeltext').innerHTML;
						// Remove postFix if added
					if( tabLabel.substr(-postFix.length) == postFix ) {
						tabLabel = tabLabel.substr(0, tabLabel.length-postFixLength);
					}
						// Shorten label
					shortenLabel = tabLabel.substr(0, tabLabel.length-1).strip();
						// Update element with label and postfix
					tab.down('span.labeltext').update(shortenLabel + postFix);
				}
			}, this);

			totalWidth = allTabs.collect(function(tab){
				return parseInt(tab.getWidth(), 10);
			}).sum() + padding;
		}
	},



	/**
	 * Update labels with full length label to start cropping from start
	 *
	 * @param	{Element}	list
	 */
	resetUncroppedLabels: function(list) {
		$(list).select('.item').each(function(tab){
			tab.down('span.labeltext').update(tab.title);
		});
	},



	/**
	 * Activate first tab if no tab is selected
	 *
	 * @method	activateFirstIfNonActive
	 * @param	{Element}	list
	 */
	activateFirstIfNonActive: function(list) {
		if( list.down('li.active') === undefined ) {
			this.setActiveByElement(list.down('li'));
		}
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
	 * @param	{Function}		handlerFunction
	 * @param	{Event}			event
	 * @param	{Element}		element
	 */
	_clickHandler: function(handlerFunction, event, element) {
		event.stop();

			// Get tabkey identifier
		var tabKeyClass = element.getClassNames().detect(function(className){
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
	 * @param	{String}	listName
	 * @param	{String}	tabName
	 */
	setActive: function(listName, tabName) {
		var list	= this.getList(listName);

		if( list ) {
			var tab = list.down('li.tabkey-' + tabName);

			if( tab ) {
				list.select('li').invoke('removeClassName', 'active');
				tab.addClassName('active');
				return;
			}
		}

		Todoyu.log('Tab with name "' + tabName + '" not found in list ' + listName + '!');
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
	 * Set first tab active
	 *
	 * @param	{String|Element}	list
	 */
	setFirstActive: function(list) {
		var first	= this.getFirstTab(list);

		if( first ) {
			this.setActiveByElement(first);
		}
	},



	/**
	 * Get currently active tab in a list
	 *
	 * @method	getActive
	 * @param	{String}		list		List element or its ID
	 * @return	{Element}
	 */
	getActive: function(list) {
		return $(list + '-tabs').down('li.active');
	},



	/**
	 * Get key of the active tab of the list
	 *
	 * @method	getActiveKey
	 * @param	{String}		list		List or its ID
	 * @return	{String|Boolean}
	 */
	getActiveKey: function(list) {
		var active = this.getActive(list);

		if( ! active ) {
			this.setFirstActive(list);
			active = this.getActive(list);
		}

		if( active ) {
			return active.id.split('-').last();
		} else {
			return false;
		}
	},



	/**
	 * Set the label text of a tab
	 *
	 * @method	setLabel
	 * @param	{String}	listName
	 * @param	{String}	tabName
	 * @param	{String}	label
	 */
	setLabel: function(listName, tabName, label) {
		label	= label.strip();
		var tab	= this.getTab(listName, tabName);

		if( tab ) {
			tab.down('.labeltext').update(label);
			tab.title = label;
		}

		this.cropTabs(this.getList(listName));
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
			'class': 'item bcg05 tabkey-' + name + ' ' + name + ' ' + tabClass
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
	 * Add a new tab to a tab group
	 *
	 * @method	addTab
	 * @param	{String}	listName
	 * @param	{String}	tabName
	 * @param	{String}	tabClass
	 * @param	{String}	tabLabel
	 * @param	{Boolean}	isActive
	 * @param	{Boolean}	insertAsFirst
	 */
	addTab: function(listName, tabName, tabClass, tabLabel, isActive, insertAsFirst) {
		var tab	= this.build(listName, tabName, tabClass, tabLabel, isActive);
		var list= this.getList(listName);
		tab.title = tabLabel;

		if( insertAsFirst ) {
			list.insert({
				top: tab
			});
		} else {
			list.insert({
				bottom: tab
			});
		}

		if( isActive ) {
			this.setActive(listName, tabName);
		}

		this.cropTabs(list);
	},



	/**
	 * Enter Description here...
	 *
	 * @private
	 * @method	_hoverHandler
	 * @param	{Boolean}	over
	 * @param	{Event}		event
	 * @param	{Element}	element
	 */
	_hoverHandler: function(over, event, element) {
		if( over ) {
			element.addClassName('hover');
		} else {
			element.removeClassName('hover');
		}
	},



	/**
	 * Move tab to first position (on the left)
	 *
	 * @method	moveAsFirst
	 * @param	{String}	list
	 * @param	{String}	idTab
	 */
	moveAsFirst: function(list, idTab) {
			// Get tab which will be in front
		var tab	= $(list + '-tab-' + idTab);
			// Remove it from the DOM
		tab.remove();

			// Add the tab as first element
		$(list + '-tabs').insert({
			'top':	tab
		});

		this.highlight(list, idTab);
	},



	/**
	 * Highlight a tab
	 *
	 * @method	highlight
	 * @param	{String}	list
	 * @param	{String}	idTab
	 */
	highlight: function(list, idTab) {
		// Highlighting is currently disabled (find a nice style to highlight)
	},



	/**
	 * Get tab IDs in the tab group
	 *
	 * @method	getTabNames
	 * @param	{String}	list
	 */
	getTabNames: function(list) {
		return $(list + '-tabs').select('li.item').collect(function(tab){
			return tab.id.split('-').last();
		});
	},



	/**
	 * Check if a tab with the ID is in the tab group
	 *
	 * @method	hasTab
	 * @param	{String}	list
	 * @param	{String}	idTab
	 */
	hasTab: function(list, idTab) {
		return Todoyu.exists(list + '-tab-' + idTab);
	},



	/**
	 * Remove surplus tabs
	 *
	 * @method	removeSurplus
	 * @param	{String}	name
	 * @param	{Number}	max		Maximal amount of tabs
	 * @return	{Array}		List of removed tab IDs
	 */
	removeSurplus: function(name, max) {
		var tabIDs = [];
		var idTab, surplusTab;
		var list	= this.getList(name);

		while( (surplusTab = list.down('li', max)) !== undefined ) {
			//var x	= $(name + '-tabs').down('li', max);
			idTab	= this.removeLast(name);
			tabIDs.push(surplusTab);
		}

		this.cropTabs(list);

		return tabIDs;
	},



	/**
	 * Remove last tab
	 *
	 * @method	removeLast
	 * @param	{String}	name
	 * @return	{String}	ID of the remove tab
	 */
	removeLast: function(name) {
		var list	= this.getList(name);
		var last	= list.select('li').last();
		var idTab	= last.id.split('-').last();

		last.remove();

		this.cropTabs(list);

		return idTab;
	},



	/**
	 * Get amount of tabs in list
	 *
	 * @method	getNumTabs
	 * @param	{String}	list
	 * @return	{Number}
	 */
	getNumTabs: function(list) {
		return $(list + '-tabs').select('li').size();
	},



	/**
	 * Get tab with given index in given list
	 *
	 * @method	getTab
	 * @param	{String}	list
	 * @param	{Number}	index
	 * @return	{Element}
	 */
	getTabByIndex: function(list, index) {
		return $(list + '-tabs').down('li', index || 0);
	},



	/**
	 * Get first tab of given list
	 *
	 * @method	getFirstTab
	 * @param	{String}	list
	 * @return	{Element}
	 */
	getFirstTab: function(list) {
		return this.getTabByIndex(list, 0);
	},



	/**
	 * Get last tab of given list
	 *
	 * @method	getLastTab
	 * @param	{String}	list
	 * @return	{Element}
	 */
	getLastTab: function(list) {
		return this.getTabByIndex(list, this.getNumTabs(list)-1);
	},



	/**
	 * Get all tab elements of given list
	 *
	 * @method	getAllTabs
	 * @param	{String}	list
	 * @return	{Array}
	 */
	getAllTabs: function(list) {
		return $(list + '-tabs').select('li.item');
	}

};