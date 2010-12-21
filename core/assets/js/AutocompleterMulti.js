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
 * Autocompleter which adds all selected elements to a list
 * - Add element as item to a list
 * - Add selected it to a hidden form field
 */
Todoyu.AutocompleterMulti = Class.create(Todoyu.Autocompleter, {

	/**
	 * List of selected items (visible as <ul><li> list)
	 *
	 * @var	{Todoyu.ItemList}	itemList
	 */
	itemList: null,

	/**
	 * List of selected IDs (comma separated)
	 *
	 * @var	{Todoyu.FieldList}	fieldList
	 */
	fieldList: null,

	/**
	 * Callbacks for add and remove events
	 *
	 * @var	{Object}	callbacks
	 */
	callbacks: null,



	/**
	 * Initialize autocompleter with callbacks
	 *
	 * @param	{Ajax.Autocompleter}	$super
	 * @param	{String|Element}		field
	 * @param	{String|Element}		fieldSuggest
	 * @param	{String}				url
	 * @param	{Object}				acOptions
	 * @param	{Function}				callbackAdd
	 * @param	{Function}				callbackRemove
	 */
	initialize: function($super, field, fieldSuggest, url, acOptions, callbackAdd, callbackRemove) {
		acOptions	= acOptions || {};
		acOptions.afterUpdateElement = this.onElementSelected.bind(this);

		this.callbacks = {
			onAdd: 		callbackAdd 	|| Prototype.emptyFunction,
			onRemove: 	callbackRemove  || Prototype.emptyFunction
		};

		var idItemList		= $(field).id + '-itemlist';
		var idFieldList		= $(field).id + '-value';
		var itemListOptions	= {
			onRemove: this.onItemListRemove.bind(this)
		};

		this.itemList	= new Todoyu.ItemList(idItemList, {
			onRemove: this.onItemListRemove.bind(this)
		});
		this.fieldList	= new Todoyu.FieldList(idFieldList);

		$super(field, fieldSuggest, url, acOptions);
	},



	/**
	 * Callback when element was selected
	 *
	 * @param	{Element}	field
	 * @param	{Element}	selectedListElement
	 */
	onElementSelected: function(field, selectedListElement) {
			// Reset visible field
		field.value = '';

			// Get selected item data
		var	idItem	= selectedListElement.id;
		var label	= selectedListElement.innerHTML;

		this.itemList.add(idItem, label);
		this.fieldList.add(idItem);

		this.callbacks.onAdd.call(this, this, idItem, label);
	},



	/**
	 * Callback when an item was removed from the list
	 *
	 * @param	{Element}		listElement
	 * @param	{String|Number}	idItem
	 */
	onItemListRemove: function(listElement, idItem) {
		this.fieldList.remove(idItem);
		this.callbacks.onRemove.call(this, idItem);
	}

});