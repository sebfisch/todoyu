/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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

	idWindow: 'headlet-about-window',

	/**
	 * 
	 */
	onButtonClick: function(event) {
		this.showWindow();
	},



	hasWindow: function() {
		return Todoyu.exists(this.idWindow);
	},



	createWindow: function() {
		var window = new Element('div', {
			'id':	this.idWindow
		}).setStyle({
			'display': 'none'
		});

		document.body.appendChild(window);
	},



	loadWindow: function() {
		var url		= Todoyu.getUrl('core', 'about');
		var options	= {
			'parameters': {
				'action':	'window'
			},
			'onComplete': this.onWindowLoaded.bind(this)
		}
		var target	= this.idWindow;

		Todoyu.Ui.update(target, url, options);
	},



	onWindowLoaded: function(response) {
		$(this.idWindow).down('.close').observe('click', this.hideWindow.bindAsEventListener(this));
		this.displayWindow(true);
	},


	
	showWindow: function() {
		if( this.hasWindow() ) {
			this.displayWindow(true);
		} else {
			this.createWindow();
			this.loadWindow();
		}
	},



	hideWindow: function() {
		this.displayWindow(false);
	},



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
			'duration': 0.5
		});
	}
	
};