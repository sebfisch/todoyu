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

	popup:	null,



	/**
	 * Initialize quick create headlet
	 */
	init: function() {
		this.Mode.init();
	},



	/**
	 * Open creation wizard to add new record
	 * 
	 * @param	String		mode
	 */
	add: function(ext, mode) {
		this.openWizard(ext, mode);
	},



	/**
	 * Open creator wizard popup
	 * 
	 * @param	String		ext
	 * @param	String		mode
	 */
	openWizard: function(ext, mode) {
		var controller = 'quickcreate' + mode;
		
		var url		= Todoyu.getUrl(ext, controller);
		var options	= {
			'parameters': {
				'action':	'popup',
			},
			'onComplete': this.onWizardOpened.bind(this, mode)
		};
		var idPopup	= 'quickcreate';
		var title	= 'Create new';
		var width	= 600;
		var height	= 360;

		this.popup = Todoyu.Popup.openWindow(idPopup, title, width, height, url, options);	
	},



	/**
	 * Handler after popup opened: install change-observer
	 * 
	 * @param	Integer		time
	 */
	onWizardOpened: function(time) {
		$('quickevent-field-eventtype').observe('change', this.onEventTypeChange.bindAsEventListener(this, time));
	},



	/**
	 * Close wizard popup
	 */
	closeWizard: function() {
		this.popup.close();
	},



/* ---------------------------------------------------------
	Todoyu.Headlet.Quickcreate.Mode
------------------------------------------------------------ */

	Mode: {
		
		button: 	null,



		/**
		 * Init quick create headlet
		 */
		init: function() {
			this.button = $('headletquickcreate-mode-btn');

			this.installObserver();
		},



		/**
		 * Install observer
		 */
		installObserver: function() {
			this.button.observe('click', this.show.bindAsEventListener(this));
		},



		/**
		 * Show quick creation modes (record types) selector list
		 *
		 * @param	String		mode
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
		 * Evoked upon selecting of creation mode: hide modes list and open creator wizard
		 *
		 * @param	Object	event
		 */
		onSelect: function(event) {
			var liElement	= event.findElement('li');
			
			var ext	= liElement.readAttribute('ext');
			var mode= liElement.readAttribute('mode');

			this.hide();
			
			Todoyu.Headlet.QuickCreate.add(ext, mode);
		},



		/**
		 * Click outside widget after having opened it: hide modes selector
		 */
		onBodyClick: function(event) {
			this.hide();
			$(document.body).stopObserving('click');
		},



		/**
		 * Hide modes selector
		 */
		hide: function() {
			$('headletquickcreate-modes').hide();
		}

	}

};