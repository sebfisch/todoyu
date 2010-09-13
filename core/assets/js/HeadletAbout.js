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
 * Headlet: About
 * Options about splash screen
 *
 * @package		Todoyu
 * @subpackage	Core
 */
Todoyu.Headlet.About = {

	eeVisible: {},

	/**
	 * Window HTML ID
	 */
	idWindow: 'headlet-about-window',

	/**
	 * Instance of the current effect
	 *
	 * @param	{Effect.Move}
	 */
	nameEffect: null,

	/**
	 * @var	Todoyu.OverflowWindow
	 */
	win: null,

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

	init: function() {
		this.winConfig.onUpdate = this.onUpdate.bind(this);
		this.winConfig.onHide	= this.onHide.bind(this);
		this.winConfig.onDisplay= this.onDisplay.bind(this);
	},


	/**
	 * Handler for button clicks
	 *
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
	 * @param	{Ajax.Response}		response
	 */
	onUpdate: function(response) {

	},



	/**
	 * Handler when window is hiding
	 */
	onHide: function() {
		if( this.nameEffect !== null ) {
			this.nameEffect.options.afterFinish = null;
			this.nameEffect.cancel();
		}
	},



	/**
	 * Handler when window is displayed
	 */
	onDisplay: function() {
		this.startNameScrolling(true, true);
		this.initEE();
	},



	/**
	 * Start scrolling the names in the 'thank you' box
	 *
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
	 * @param	{Event}		event
	 */
	EE: function(event, coderName) {
		var li = event.findElement('li');
		this.eeVisible[coderName] = true;

		li.addClassName('coder');

		if( $H(this.eeVisible).all(function(pair){ return pair.value === true; })) {
			$('headlet-about-window').insert({
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

	hide: function() {
		if( this.win ) {
			this.win.hide();
		}
	}

};