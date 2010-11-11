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
 * @module	Core
 */

/**
 * Headlet: About
 * Options about splash screen
 *
 * @package		Todoyu
 * @subpackage	Core
 */
Todoyu.Headlet.About = {

	/**
	 * @property	eeVisible
	 * @type		Object
	 * @private
	 */
	eeVisible: {},

	/**
	 * Window HTML ID
	 * @property	idWindow
	 * @type		String
	 * @private
	 */
	idWindow: 'overflow-window-about',

	/**
	 * Instance of the current effect
	 *
	 * @property	nameEffect
	 * @type		Effect.Move
	 * @private
	 */
	nameEffect: null,

	/**
	 * @property	win
	 * @type		Todoyu.OverflowWindow
	 */
	win: null,

	/**
	 * @property	winConfig
	 * @type		Object
	 */
	winConfig: {
		id: 'about',
		width: 350,
		url: Todoyu.getUrl('core', 'about'),
		options: {
			parameters: {
				action: 'window'
			}
		}
	},


	/**
	 * Initialize headlet
	 *
	 * @method	init
	 */
	init: function() {
		this.winConfig.onUpdate = this.onUpdate.bind(this);
		this.winConfig.onHide	= this.onHide.bind(this);
		this.winConfig.onDisplay= this.onDisplay.bind(this);
	},



	/**
	 * Handler for button clicks
	 *
	 * @method	onButtonClick
	 * @param	{Event}		event
	 */
	onButtonClick: function(event) {
		if( this.win === null ) {
			this.win = new Todoyu.OverflowWindow(this.winConfig);
		} else {
			this.win.show();
		}
	},



	/**
	 * Handler when window is updated
	 *
	 * @method	onUpdate
	 * @param	{Ajax.Response}		response
	 */
	onUpdate: function(response) {

	},



	/**
	 * Handler when window is hiding
	 *
	 * @method	onHide
	 */
	onHide: function() {
		if( this.nameEffect !== null ) {
			this.nameEffect.options.afterFinish = null;
			this.nameEffect.cancel();
		}
	},



	/**
	 * Handler when window is displayed
	 *
	 * @method	onDisplay
	 */
	onDisplay: function() {
		this.startNameScrolling(true, true);
		this.initEE();
	},



	/**
	 * Start scrolling the names in the 'thank you' box
	 *
	 * @method	startNameScrolling
	 * @param	{Boolean}	up		Scroll up
	 * @param	{Boolean}	first	Is the first scrolling, reset positions for start
	 */
	startNameScrolling: function(up, first) {
		var box	= this.win.div().down('div.names');
		var list= box.down('ul');
		var newY= -list.getHeight()+(box.getHeight()/2);

		if( up === false ) {
			newY	= -newY;
		}

		if( first === true ) {
			list.setStyle({
				top: '0px'
			});
		}

		this.nameEffect = new Effect.Move(list, {
			x: 0,
			y: newY,
			mode: 'relative',
			duration: list.select('li').size()*0.8,
			transition: Effect.Transitions.linear,
			afterFinish: this.startNameScrolling.bind(this, !up)
		});
	},



	/**
	 * Initialize EE
	 *
	 * @method	initEE
	 */
	initEE: function() {
		var names	= ['Erni', 'Stenschke', 'Karrer'];

		names.each(function(name){
			this.eeVisible[name] = false;
		}, this);

		$('scrollingnames').select('li').findAll(function(names, element){
				// Check if list item is coder name
			var isCoder = names.any(function(itemName, coderName){
				return itemName.indexOf(coderName) !== -1;
			}.bind(this, element.innerHTML));

			if( isCoder ) {
					//
				var coderName = names.detect(function(itemName, coderName){
					return itemName.indexOf(coderName) !== -1;
				}.bind(this, element.innerHTML));

				element.observe('click', this.EE.bindAsEventListener(this, coderName));
			}
		}.bind(this, names));
	},



	/**
	 * Show EE
	 *
	 * @method	EE
	 * @param	{Event}		event
	 */
	EE: function(event, coderName) {
		var li = event.findElement('li');
		this.eeVisible[coderName] = true;

		li.addClassName('coder');

		if( $H(this.eeVisible).all(function(pair){ return pair.value === true; })) {
			$H(this.eeVisible).each(function(pair){this.eeVisible[pair.key]=false; console.log(pair);}, this);
			if(Todoyu.exists('ee-img')) $('ee-img').remove();
			$(this.idWindow).insert({
				'bottom': new Element('div', {
					'id': 'ee-img'
				})
			});

			$('scrollingnames').select('li').invoke('removeClassName', 'coder');

			$('ee-img').observe('click', function(event){
				Effect.Puff(event.element());
			});
		}

	},



	/**
	 * Hide window
	 *
	 * @method	hide
	 */
	hide: function() {
		if( this.win ) {
			this.win.hide();
		}
	}

};