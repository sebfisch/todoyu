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
 * Loader box
 * Shows a message and a spinner to show that an action is in progress
 *
 * @class		LoaderBox
 * @namespace	Todoyu
 */
Todoyu.LoaderBox = {

	/**
	 * Id of the loader box
	 * @property	idBox
	 * @type		String
	 */
	idBox: 'loader-box',

	/**
	 * Loader box element
	 * @property	box
	 * @type		Element
	 */
	box: null,

	/**
	 * Id of the screen blocker
	 * @property	idScreenBlock
	 * @type		String
	 */
	idScreenBlock: 'loader-box-screen-block',

	/**
	 * Screen blocker element
	 * @property	screenBlock
	 * @type		Element
	 */
	screenBlock: null,



	/**
	 * Show the loader box with a message
	 *
	 * @method	show
	 * @param	{String}	message
	 * @param	{Boolean}	blockScreen
	 */
	show: function(message, blockScreen) {
		this._build();
		this._updateMessage(message);
		this._center();

		if( blockScreen ) {
			this._showScreenBlock();
		}

		this.box.show();
	},



	/**
	 * Hide the loader box
	 *
	 * @method	hide
	 */
	hide: function() {
		if( this.box ) {
			this.box.hide();
		}
		if( this.screenBlock ) {
			this.screenBlock.hide();
		}
	},



	/**
	 * Build the loader box with its sub elements
	 *
	 * @private
	 * @method	_build
	 */
	_build: function() {
		if( ! Todoyu.exists(this.idBox) ) {
			document.body.insert(new Element('div',{
				'id':	this.idBox,
				'style':'display:none'
			}));

			this.box = $(this.idBox);

			this.box.insert(new Element('div', {
				'class': 'title'
			}).update('[LLL:core.global.loaderBox.title]'));

			this.box.insert(new Element('img', {
				'class': 	'spinner',
				'src': 		'core/asset/img/ajax-loader-large.gif'
			}));
			this.box.insert(new Element('div', {
				'class': 'message'
			}));
		}
	},



	/**
	 * Update the message in the loader box
	 *
	 * @method	_updateMessage
	 * @param	{String}	message
	 */
	_updateMessage: function(message) {
		this.box.down('div.message').update(message);
	},



	/**
	 * Center the loader box on the screen
	 *
	 * @method	_center
	 */
	_center: function() {
		Todoyu.Ui.centerElement(this.box);
	},



	/**
	 * Show the screen blocker
	 *
	 * @method	_showScreenBlock
	 */
	_showScreenBlock: function() {
		if( ! Todoyu.exists(this.idScreenBlock) ) {
			document.body.insert(new Element('div', {
				'id':	this.idScreenBlock
			}));

			this.screenBlock = $(this.idScreenBlock);
		}

		this.screenBlock.show();
	}

};