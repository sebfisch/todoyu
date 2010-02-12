/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2,
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

Todoyu.Headlet.QuickCreate = {

	/**
	 * Enter description here...
	 */
	init: function() {
//		this.createField = $('headletquickcreate-query');
		this.Mode.init();
	},


/* ---------------------------------------------------------
	Todoyu.Headlet.Quickcreate.Mode
------------------------------------------------------------ */

	/**
	 * Enter description here...
	 */
	Mode: {

		mode: 0,

		button: null,

		/**
		 * Enter description here...
		 *
		 * @param Integer idFilterset
		 */
		init: function() {
			this.button = $('headletquickcreate-mode-btn');

			this.installObserver();
		},



		/**
		 * Enter description here...
		 */
		installObserver: function() {
			this.button.observe('click', this.show.bindAsEventListener(this));
		},



		/**
		 * Enter description here...
		 *
		 * @param	String	mode
		 */
		show: function(event) {
			var btnOffset	= this.button.cumulativeOffset();
			var btnHeight	= this.button.getHeight();

			var top			= btnOffset.top + btnHeight;
			var left		= btnOffset.left;

			$('headletquickcreate-modes').setStyle({
				'display':	'block',
				'left':		left + 'px',
				'top':		top + 1 + 'px'
			});

			$('headletquickcreate-modes').observe('click', this.onSelect.bindAsEventListener(this));
			Event.observe.delay(0.1, document.body, 'click', this.onBodyClick.bindAsEventListener(this));
		},



		/**
		 * Enter description here...
		 *
		 * @param	String	mode
		 */
		setMode: function(mode) {
			$('headletquickcreate-mode').value = mode;
			$('headletquickcreate-mode-icon').writeAttribute('class', 'createmode-' + mode);
		},



		/**
		 * Enter description here...
		 *
		 * @return	String
		 */
		getMode: function() {
			return $F('headletquickcreate-mode');
		},



		/**
		 * Enter description here...
		 *
		 * @param	Object	event
		 */
		onSelect: function(event) {
			var mode = event.findElement('li').readAttribute('mode');

			this.setMode(mode);
			this.hide();
		},


		/**
		 * Enter description here...
		 */
		onBodyClick: function(event) {
			this.hide();
			$(document.body).stopObserving('click');
		},



		/**
		 * Enter description here...
		 */
		hide: function() {
			$('headletquickcreate-modes').hide();
		}

	}

};