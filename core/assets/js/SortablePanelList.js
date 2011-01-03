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
Todoyu.SortablePanelList = {

	/**
	 * Initialize sorting and toggle
	 *
	 * @param	{Element|String}	list
	 * @param	{Function}			callbackToggle
	 * @param	{Function}			callbackSort
	 */
	init: function(list, callbackToggle, callbackSort) {
		if( ! Todoyu.exists(list) ) {
			throw {
				name: 'List element not found',
				message: list
			};
		}
		this._initToggle($(list), callbackToggle);
		this._initSortable($(list), callbackSort);
	},



	/**
	 * Add toggle functions
	 *
	 * @param	{Element}	list
	 * @param	{Function}	callback
	 */
	_initToggle: function(list, callback) {
		list.select('li.groupTitle').each(function(callback, groupItem){
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
	 * @param	{Element}	list
	 * @param	{Function}	callback
	 */
	_initSortable: function(list, callback) {
			// Define options for all sortables
		var options	= {
			'handle':	'handle',
			'onUpdate':	this._onSort.bind(this)
		};

			// Make each list sortable
		list.select('.sortable').each(function(element) {
				// Create a sortable
			Sortable.create(element, {
				handle: 'handle',
				onUpdate: this._onSort.bind(this, callback)
			});
		}, this);

			// Add hover effect to handles
		list.select('.handle').each(Todoyu.Ui.addHoverEffect, Todoyu.Ui);
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
	}

};