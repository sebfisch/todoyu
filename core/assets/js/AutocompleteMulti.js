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
 * Multi autocomplete selector
 * Adds selected options to a filterItemList
 */
Todoyu.AutocompleteMulti = {

	itemLists: {},

	/**
	 * Custom selector handler for multi AC
	 *
	 * @param	{String}	name
	 * @param	{Element}	field
	 * @param	{Element}	selectedListElement
	 */
	onSelect: function(name, field, selectedListElement) {
			// Reset visible field
		field.value = '';

			// Get selected item data
		var	idItem	= selectedListElement.id;
		var label	= selectedListElement.innerHTML;

			// Only add each item once
		if( this.containsItem(field, idItem) ) {
			return false;
		}

			// Add id to value list
		//this.addItem(field, idItem);

			// Add label to list
		this.getItemList(field.id).add(idItem, label);

//		this.ext.refreshReport();
	},

	getItemList: function(idField) {
		if( this.itemLists[idField] === undefined ) {
			this.itemLists[idField] = new Todoyu.ItemList(idField, {
				onRemove: this.onRemove.bind(this, $(idField)),
				onAdd: this.onAdd.bind(this, $(idField))
			});
		}

		return this.itemLists[idField];
	},


	onRemove: function(field, list, idItem) {
		this.removeItem(field, idItem);

//		this.ext.refreshReport();
	},

	onAdd: function(field, list, idItem, label) {

	},



	/**
	 * Add a new id to the value field
	 *
	 * @param	{Element}	field
	 * @param	{Number}	item
	 */
	addItem: Todoyu.FieldList.add.bind(Todoyu.FieldList),



	/**
	 * Remove item from list
	 *
	 * @param	{Element}	field
	 * @param	{Number}	item
	 */
	removeItem: Todoyu.FieldList.remove.bind(Todoyu.FieldList),



	/**
	 * Get selected items
	 *
	 * @param	{Element}		field
	 */
	getItems: Todoyu.FieldList.getList.bind(Todoyu.FieldList),



	/**
	 * Set selected items
	 *
	 * @param	{Element}	field
	 * @param	{Array}		items
	 */
	setItems: Todoyu.FieldList.setList.bind(Todoyu.FieldList),



	/**
	 * Check if the list already contains the item
	 *
	 * @param	{Element}	field
	 * @param	{Number}	item
	 */
	containsItem: Todoyu.FieldList.contains.bind(Todoyu.FieldList)

};