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

Todoyu.FieldList = Class.create({

	field: null,

	initialize: function(field) {
		this.field = $(field);
	},



	/**
	 * Add a new id to the value field
	 *
	 * @param	{Number}	item
	 */
	add: function(item) {
		var items	= this.getItems();
		items.push(item);

		this.setItems(items);
	},



	/**
	 * Remove item from list
	 *
	 * @param	{Number}	item
	 */
	remove: function(item) {
		this.setItems(this.getItems().without(item));
	},



	/**
	 * Get selected items
	 *
	 */
	getItems: function() {
		var value	= $F(this.field);

		return value === '' ? [] : value.split(',');
	},



	/**
	 * Set selected items
	 *
	 * @param	{Array}		items
	 */
	setItems: function(items) {
		this.field.value = items.without(0).uniq().join(',');
	},



	/**
	 * Check if the list already contains the item
	 *
	 * @param	{Number}	item
	 */
	contains: function(item) {
		return this.getItems().include(item);
	}

});