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
 * Handle multi-select filters
 */
Todoyu.SelectMulti = Class.create({

	/**
	 * Item list for display
	 *
	 * @var	{Todoyu.ItemList}	itemList
	 */
	itemList: null,

	/**
	 * Field list for IDs
	 *
	 * @var	{Todoyu.FieldList}	fieldList
	 */
	fieldList: null,

	/**
	 * Select field
	 *
	 * @var	{Element}	field
	 */
	field: null,

	/**
	 * Event handler callbacks
	 *
	 * @var	{Object}	callbacks
	 */
	callbacks: {},



	/**
	 * Initialize multi select object
	 *
	 * @param	{Element|String}	field
	 * @param	{Function}			callbackAdd
	 * @param	{Function}			callbackRemove
	 */
	initialize: function(field, callbackAdd, callbackRemove) {
		this.field = $(field);

		this.callbacks = {
			onAdd: callbackAdd,
			onRemove: callbackRemove
		};

		this.field.on('mouseup', 'select', this.onSelect.bind(this));
		this.field.stopObserving('change');

		var idItemList		= $(field).id + '-itemlist';
		var idFieldList		= $(field).id + '-value';

		this.itemList	= new Todoyu.ItemList(idItemList, {
			onRemove: this.onItemListRemove.bind(this)
		});
		this.fieldList	= new Todoyu.FieldList(idFieldList);

	},



	/**
	 * Handler when an item was selected
	 *
	 * @param	{Event}		event
	 * @param	{Element}	field
	 */
	onSelect: function(event, field) {
		var items	= Todoyu.Form.getSelectedItems(field);

		$H(items).each(function(pair){
			this.itemList.add(pair.key, pair.value);
			this.fieldList.add(pair.key);
		}, this);

		this.callbacks.onAdd.call(this, this, items);
	},



	/**
	 * Handler when an item was removed from the list
	 *
	 * @param	{Element}		listElement
	 * @param	{String|Number}	idItem
	 */
	onItemListRemove: function(listElement, idItem) {
		//this.itemList.remove(idItem);
		this.fieldList.remove(idItem);

		this.callbacks.onRemove.call(this, this, idItem);
	}

});