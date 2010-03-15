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

/**
 * Quickcreate headlet
 * 
 * @package		Todoyu
 * @subpackage	Core
 */
Todoyu.Headlet.QuickCreate = {

	/**
	 * Popup reference
	 */
	popup:	null,



	/**
	 * Initialize quick create headlet
	 */
	init: function() {

	},
	
	
	
	/**
	 * Handler: When clicked on button
	 * 
	 * @param	Event		event
	 */
	onButtonClick: function(event) {
		this.headlet.showContent('quickcreate');
	},
	
	
	/**
	 * Handler: When clicked on menu entry
	 * 
	 * @param	Event		event
	 */
	onMenuClick: function(event) {
		var idParts	= event.findElement('a').id.split('-');
		var ext		= idParts[3];
		var type	= idParts[4];
		
		this.openTypePopup(ext, type);
		this.headlet.hideContent('quickcreate');
	},


	/**
	 * Open creator wizard popup
	 * 
	 * @param	String		ext
	 * @param	String		mode
	 */
	openTypePopup: function(ext, type) {
		if ( ! $('quickcreate') ) {
			var ctrl 	= 'Quickcreate' + Todoyu.Helper.ucwords(type);
			var url		= Todoyu.getUrl(ext, ctrl);
			var options	= {
				'parameters': {
					'action':	'popup'
				},
				'onComplete': this.onPopupOpened.bind(this, ext, type)
			};
			var idPopup	= 'quickcreate';
			var title	= '[LLL:core.create]' + ': ' + this.getTypeLabel(ext, type);
			var width	= 700;

			this.popup = Todoyu.Popup.openWindow(idPopup, title, width, url, options);
		}
	},



	/**
	 * Handler after popup opened: call mode's onPopupOpened-handler
	 * 
	 * @param	String	ext
	 */
	onPopupOpened: function(ext, type) {
		$('quickcreate').addClassName(type);

		var quickCreateObject	= 'Todoyu.Ext.' + ext + '.QuickCreate' + Todoyu.Helper.ucwords(type);
		
		Todoyu.callUserFunction(quickCreateObject + '.onPopupOpened');
	},
	
	
	
	/**
	 * Get label of a type from menu entry
	 * 
	 * @param	String		ext
	 * @param	String		type
	 */
	getTypeLabel: function(ext, type) {
		return $('headlet-quickcreate-item-' + ext + '-' + type).innerHTML;
	},



	/**
	 * Close wizard popup
	 */
	closePopup: function() {
		Todoyu.Popup.close('quickcreate');
	},



	/**
	 * Update quick create popup content
	 *
	 * @param	String		content
	 */
	updatePopupContent: function(content) {
		$('quickcreate_content').update(content);
	}

};