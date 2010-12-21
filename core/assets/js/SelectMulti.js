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
	 * List of itemLists
	 */
	itemList: null,

	fieldList: null,

	/**
	 *
	 * var	{Element}	field
	 */
	field: null,

	callbacks: {},


	/**
	 *
	 * @param	{Element|String}	field
	 * @param callbackAdd
	 * @param callbackRemove
	 */
	initialize: function(field, callbackAdd, callbackRemove) {
		this.field = $(field);

		this.callbacks = {
			onAdd: callbackAdd,
			onRemove: callbackRemove
		};

		this.field.on('click', 'select', this.onSelect.bind(this));
		this.field.stopObserving('change');

		var idItemList		= $(field).id + '-itemlist';
		var idFieldList		= $(field).id + '-value';

		this.itemList	= new Todoyu.ItemList(idItemList, {
			onRemove: this.onItemListRemove.bind(this)
		});
		this.fieldList	= new Todoyu.FieldList(idFieldList);

	},

	onSelect: function(event, field) {
		var idItem	= $F(field);
		var label	= field.options[field.selectedIndex].text;

		this.itemList.add(idItem, label);
		this.fieldList.add(idItem);

		this.callbacks.onAdd.call(this, this, idItem, label);
	},


	onItemListRemove: function(field, idItem) {
		this.removeItem(field, idItem);

		this.ext.refreshReport();
	}

});