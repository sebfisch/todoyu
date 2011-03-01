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
 * Headlet: About
 * Options about splash screen
 *
 * @package		Todoyu
 * @subpackage	Core
 */
Todoyu.CoreHeadlets.About = Class.create(Todoyu.Headlet, {

	/**
	 * ID of the about popup
	 * @property	popupID
	 * @type		String
	 */
	popupID:	'popup-about',

	/**
	 * @property	popupWidth
	 * @type		Number
	 * @private
	 */
	popupWidth:	560,

	/**
	 * @property	eeVisible
	 * @type		Object
	 * @private
	 */
	eeVisible: {},

	/**
	 * Instance of the current effect
	 *
	 * @property	nameEffect
	 * @type		Effect.Move
	 * @private
	 */
	nameEffect: null,


	/**
	 * Handle headlet icon click
	 *
	 * @method	onButtonClick
	 * @param	{Event}		event
	 */
	onButtonClick: function(event) {
		this.openPopup();
	},



	/**
	 * Open about popUp and load content
	 */
	openPopup: function() {
		var url		= Todoyu.getUrl('core', 'about');
		var options	= {
			'parameters': {
				'action':	'popup'
			},
			'onComplete':	this.onPopupLoaded.bind(this)
		};

		Todoyu.Popup.openWindow(this.popupID, '[LLL:headlet-about.title]', this.popupWidth, url, options);
	},



	/**
	 * Handler when PopUp is loaded: Call hook to inform other extensions
	 *
	 * @param	{Ajax.Response}		response
	 */
	onPopupLoaded: function(response) {
		Todoyu.Hook.exec('core.about.popupLoaded', response);
		this.onDisplay();

			// Deactivate resizability
		$(this.popupID + '_sizer').remove();
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
	 * Start scrolling the names in the 'thank you' box. Scrolling loops while popup is available in DOM
	 *
	 * @method	startNameScrolling
	 * @param	{Boolean}	up		Scroll up
	 * @param	{Boolean}	first	Is the first scrolling, reset positions for start
	 */
	startNameScrolling: function(up, first) {
		if ( Todoyu.exists($(this.popupID)) ) {
			var box	= $(this.popupID).down('div.names');
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
				x:				0,
				y:				newY,
				mode:			'relative',
				duration:		list.select('li').size() * 0.5,
				transition:		Effect.Transitions.linear,
				afterFinish:	this.startNameScrolling.bind(this, !up)
			});
		}
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
			$(this.popupID).insert({
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
	 * Check whether names of team members are shown
	 *
	 * @return	{Boolean}
	 */
	isTeamShown: function() {
		return $('about-team').style.display !== 'none';
	},



	/**
	 * Toggle display of team names / third party library credits
	 */
	toggleCredits: function() {
		if( this.isTeamShown() ) {
			$('about-team').hide();
			$('about-libs').show();
			this.setCreditsButtonText('Special thanks go to');
			this.setCreditsDedicationText('Third party products credits:');
		} else {
			$('about-team').show();
			$('about-libs').hide();
			this.setCreditsButtonText('Third party products credits');
			this.setCreditsDedicationText('Special thanks go to:');
		}
	},



	/**
	 * Change label of credits dedication
	 *
	 * @param	{String}	label
	 */
	setCreditsDedicationText: function(label) {
		$('about-credits-dedication').innerHTML = label;
	},



	/**
	 * Change label of toggle button
	 *
	 * @param	{String}	label
	 */
	setCreditsButtonText: function(label) {
		$('about-toggle-credits').down('span.label').innerHTML = label;
	}

});