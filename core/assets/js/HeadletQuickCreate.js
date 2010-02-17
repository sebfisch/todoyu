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
		this.openPopup(ext, mode);
	},



	/**
	 * Open creator wizard popup
	 * 
	 * @param	String		ext
	 * @param	String		mode
	 */
	openPopup: function(ext, mode) {
		if ( ! $('quickcreate') ) {
			var controller = 'quickcreate' + mode;
			var url		= Todoyu.getUrl(ext, controller);
			var options	= {
				'parameters': {
					'action':	'popup',
				},
				'onComplete': this.onPopupOpened.bind(this, mode)
			};
			var idPopup	= 'quickcreate';
			var title	= '[LLL:core.create]' + ': ' + this.Mode.getLabel(mode);
			var width	= 700;
			var height	= 360;

			this.popup = Todoyu.Popup.openWindow(idPopup, title, width, url, options);
		}
	},



	/**
	 * Handler after popup opened: call mode's onPopupOpened-handler
	 * 
	 * @param	String	ext
	 */
	onPopupOpened: function(mode) {
		$('quickcreate').addClassName(mode);

		var modeClass	= 'Todoyu.Headlet.QuickCreate.' + Todoyu.Helper.ucwords(mode);
		Todoyu.callUserFunction(modeClass + '.onPopupOpened');
	},



	/**
	 * Close wizard popup
	 */
	closePopup: function() {
		Todoyu.Popup.close('quickcreate');
	},



	/**
	 * Update quick create form
	 *
	 * @param	Integer	idTask
	 * @param	String	formHTML
	 */
	updateFormDiv: function(formHTML) {
		$('quickcreate_content').update(formHTML);
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

			var classnames	= $(liElement).getAttribute('class');
			if ( classnames.indexOf('grouplabel') === -1 ) {
				var ext	= liElement.readAttribute('ext');
				var mode= liElement.readAttribute('mode');

				this.hide();
				Todoyu.Headlet.QuickCreate.add(ext, mode);
			}
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
		},



		/**
		 * Get label of given mode
		 * 
		 * @return	String
		 */
		getLabel: function(mode) {
			return $$('#headletquickcreate-modes li.createmode-' + mode)[0].innerHTML;
		}

	}

};