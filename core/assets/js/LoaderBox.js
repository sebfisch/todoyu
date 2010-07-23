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
 * Loader box
 * Shows a message and a spinner to show that an action is in progress
 */
Todoyu.LoaderBox = {

	/**
	 * Id of the loader box
	 */
	idBox: 'loader-box',

	/**
	 * Loader box element
	 */
	box: null,

	/**
	 * Id of the screen blocker
	 */
	idScreenBlock: 'loader-box-screen-block',

	/**
	 * Screen blocker element
	 */
	screenBlock: null,



	/**
	 * Show the loader box with a message
	 *
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
	 * Build the loader box with its subelements
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
			}).update('[LLL:core.loaderBox.title]'));

			this.box.insert(new Element('img', {
				'class': 	'spinner',
				'src': 		'core/assets/img/ajax-loader-large.gif'
			}));
			this.box.insert(new Element('div', {
				'class': 'message'
			}));
		}
	},



	/**
	 * Update the message in the loader box
	 *
	 * @param	{String}	message
	 */
	_updateMessage: function(message) {
		this.box.down('div.message').update(message);
	},



	/**
	 * Center the loader box on the screen
	 */
	_center: function() {
		Todoyu.Ui.centerElement(this.box);
	},



	/**
	 * Show the screen blocker
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