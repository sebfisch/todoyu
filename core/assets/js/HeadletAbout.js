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
	 * Handler for button clicks
	 *
	 * @param	{Event}		event
	 */
	onButtonClick: function(event) {
		this.showWindow();
	},



	/**
	 * Check whether window is already created
	 *
	 * @return	Boolean
	 */
	hasWindow: function() {
		return Todoyu.exists(this.idWindow);
	},



	/**
	 * Create a window element in the DOM
	 */
	createWindow: function() {
		var window = new Element('div', {
			'id':	this.idWindow
		}).setStyle({
			'display': 'none'
		});

		document.body.appendChild(window);
	},



	/**
	 * Load window content over AJAX
	 */
	loadWindow: function() {
		var url		= Todoyu.getUrl('core', 'about');
		var options	= {
			'parameters': {
				'action':	'window'
			},
			'onComplete': this.onWindowLoaded.bind(this)
		};
		var target	= this.idWindow;

		Todoyu.Ui.update(target, url, options);
	},



	/**
	 * Handler when window content is loaded
	 *
	 * @param	{Ajax.Response}		response
	 */
	onWindowLoaded: function(response) {
		$(this.idWindow).down('.close').observe('click', this.hideWindow.bindAsEventListener(this));
		this.displayWindow(true);
		this.initEE();
	},



	/**
	 * Show window. Load if necessary
	 */
	showWindow: function() {
		if( this.hasWindow() ) {
			this.displayWindow(true);
		} else {
			this.createWindow();
			this.loadWindow();
		}
	},



	/**
	 * Hide window
	 */
	hideWindow: function() {
		this.displayWindow(false);

		if( this.nameEffect !== null ) {
			this.nameEffect.options.afterFinish = null;
			this.nameEffect.cancel();
		}
	},



	/**
	 * Display the window on the screen with an animation.
	 * Hide it if show is false
	 *
	 * @param	{Boolean}		show
	 */
	displayWindow: function(show) {
		var window		= $(this.idWindow);
		var screenDim	= document.viewport.getDimensions();
		var windowDim	= window.getDimensions();

		var left	= parseInt((screenDim.width-windowDim.width)/2);
		var topHide	= -windowDim.height - 30;
		var top;

		if( show ) {
			window.setStyle({
				'left': left + 'px',
				'top': topHide + 'px',
				'display': 'block'
			});

			top	= parseInt((screenDim.height-windowDim.height)/2);
			top	= top < 0 ? 0 : top;
		} else {
			top	= topHide;
		}

			// Move in/out
		new Effect.Move(this.idWindow, {
			y: top,
			x: left,
			'mode': 'absolute',
			'duration': 0.5,
			'afterFinish': show ? this.startNameScrolling.bind(this, true, true) : null
		});
	},



	/**
	 * Start scrolling the names in the 'thank you' box
	 *
	 * @param	{Boolean}	up		Scroll up
	 * @param	{Boolean}	first	Is the first scrolling, reset positions for start
	 */
	startNameScrolling: function(up, first) {
		var box	= $('headlet-about-window').down('div.names');
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


	}
	
};