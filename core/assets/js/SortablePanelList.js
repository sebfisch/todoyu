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
 * Sortable panel list with group toggle
 */
Todoyu.SortablePanelList = Class.create({

	/**
	 * List
	 */
	list: null,

	/**
	 * Flag if only one item in the whole list can be active
	 */
	onlyOneActive: true,



	/**
	 * Initialize sorting and toggle
	 *
	 * @param	{Element|String}	list
	 * @param	{Function}			callbackToggle
	 * @param	{Function}			callbackSort
	 * @param	{Boolean}			onlyOneActive
	 */
	initialize: function(list, callbackToggle, callbackSort, onlyOneActive) {
		if( ! Todoyu.exists(list) ) {
			throw {
				name: 'List element not found',
				message: list
			};
		}

		this.list			= $(list);
		this.onlyOneActive	= onlyOneActive || false;

		this._initToggle(callbackToggle);
		this._initSortable(callbackSort);
		this._initActivator();
	},



	/**
	 * Add toggle functions
	 *
	 * @param	{Function}	callback
	 */
	_initToggle: function(callback) {
		this.list.select('li.groupTitle').each(function(callback, groupItem){
			var groupName = Todoyu.Helper.getClassKey(groupItem, 'groupName');
			if( groupName ) {
				groupItem.on('click', 'li', this._toggle.bind(this, groupItem, groupName, callback));
			}
		}.bind(this, callback));
	},



	/**
	 * Toggle handler
	 *
	 * @param	{Element}	groupItem
	 * @param	{String}	groupKey
	 * @param	{Function}	callback
	 * @param	{Event}		event
	 */
	_toggle: function(groupItem, groupKey, callback, event) {
		var groupList = groupItem.next('li');

		$(groupList).toggle();

		if( typeof(callback) === 'function' ) {
			callback(groupKey, $(groupList).visible());
		}
	},



	/**
	 * Add sortable function
	 *
	 * @param	{Function}	callback
	 */
	_initSortable: function(callback) {
			// Make each list sortable
		this.list.select('.sortable').each(function(element) {
				// Create a sortable
			Sortable.create(element, {
				handle: 'handle',
				onUpdate: this._onSort.bind(this, callback)
			});
		}, this);

			// Add hover effect to handles
		this.list.select('.handle').each(Todoyu.Ui.addHoverEffect, Todoyu.Ui);
	},



	/**
	 * Sorting handler
	 *
	 * @param	{Function}	callback
	 * @param	{Element}	listItem
	 */
	_onSort: function(callback, listItem) {
		var group	= listItem.id.split('-').last();
		var items	= Sortable.sequence(listItem);

		callback(group, items);
	},



	/**
	 * Initialize handler to activate list items (with current class)
	 */
	_initActivator: function() {
		this.list.select('li.itemList > ul').each(function(itemList){
			itemList.on('click', 'a', this._activate.bind(this));
		}, this);
	},



	/**
	 * Activate the list item
	 *
	 * @param	{Event}		event
	 * @param	{Element}	linkItem
	 */
	_activate: function(event, linkItem) {
		var listItem= linkItem.up('li.listItem');
		var up		= this.onlyOneActive ? 1 : 0;

		listItem.up('ul', up).select('li.current').invoke('removeClassName', 'current');
		listItem.addClassName('current');
	}

});