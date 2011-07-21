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
 * @module
 */

/**
 * Search list panel widget
 *
 * @class
 * @namespace	Todoyu
 */
Todoyu.PanelWidgetSearchList = Class.create({
	/**
	 * Config
	 * @var	{Object}
	 */
	config: {},

	/**
	 * Search input text field
	 * @var	{Element}	input
	 */
	input: null,

	/**
	 * Timeout for a search request
	 * Allows typing without firing too much requests
	 * @var	{Function}	timeoutSearch
	 */
	timeoutSearch: null,

	/**
	 * Keys which are ignored on entering
	 * No request will be fired
	 */
	ignoreKeyInputs: [
		Event.KEY_RETURN,
		32 // Space
	],

	/**
	 * Last submitted search text.
	 * Prevent needless updates
	 */
	lastText: null,



	/**
	 * Constructor
	 *
	 * @constructor
	 * @method	initialize
	 * @param	{Object}	config
	 */
	initialize: function(config) {
		this.config	= config;
		this.input	= $(this.config.id + '-field-search');
		this.list	= $('panelwidget-' + this.config.id + '-list');

		this.lastText	= this.getSearchText();

		this.initObservers();
	},



	/**
	 * Initialize search input and list observers
	 *
	 * @method	initObservers
	 */
	initObservers: function() {
		this.input.on('keyup', this.onSearchKeyUp.bind(this));
		this.list.on('click', 'li', this.onItemClick.bind(this));
	},



	/**
	 * Start a new timeout for update
	 *
	 * @method	startNewTimeout
	 */
	startNewTimeout: function() {
		clearTimeout(this.timeoutSearch);
		this.timeoutSearch = this.update.bind(this).delay(0.3);
	},



	/**
	 * Handler for keyup in search field
	 *
	 * @method	onSearchKeyUp
	 * @param	{Event}		event
	 */
	onSearchKeyUp: function(event) {
		event.stop();

		if( ! this.ignoreKeyInputs.include(event.keyCode) ) {
			if( this.lastText !== this.getSearchText() ) {
				this.startNewTimeout();
			} else {
				//console.log('Text has not changed');
			}
		} else {
			//console.log('Ignored key');
		}
	},



	/**
	 * Handler for click in list item
	 * Overwrite this handler to handle click events
	 *
	 * @method	onItemClick
	 * @param	{Event}		event
	 * @param	{Element}	item
	 */
	onItemClick: function(event, item) {
		// Override
	},



	/**
	 * Refresh project list panelWidget
	 *
	 * @method	update
	 */
	update: function() {
		var url		= Todoyu.getUrl(this.config.ext, this.config.controller);
		var options	= {
			parameters: {
				action:	this.config.action,
				search:	this.getSearchText()
			},
			onComplete:	this.onListUpdated.bind(this)
		};

		this.lastText = this.getSearchText();

		Todoyu.Ui.update(this.list, url, options);
	},



	/**
	 * Handler to be evoked after refresh of project list panelWidget
	 *
	 * @method	onListUpdated
	 * @param	{Ajax.Response}  response
	 */
	onListUpdated: function(response) {
		this.onUpdated();
	},



	/**
	 * Handler when list was updated
	 *
	 * @method	onUpdated
	 */
	onUpdated: function() {
		// Override
	},



	/**
	 * Get full-text input field value
	 *
	 * @method	getSearchText
	 * @return	{String}
	 */
	getSearchText: function() {
		return $F(this.input).strip();
	}

});