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
 * Template class to render the context menu JSON object into
 * html with prototype template
 *
 */
Todoyu.ContextMenu.Template = {

	/**
	 * Template objects
	 */
	template: {
		item:		null,
		submenu:	null
	},



	/**
	 * HTML patterns for the templates
	 */
	html: {
		item:		'<li class="#{key}" id="contextmenu-#{key}" onmouseover="Todoyu.ContextMenu.submenu(\'#{key}\', true)" onmouseout="Todoyu.ContextMenu.submenu(\'#{key}\', false)"><a onclick="#{jsAction}" href="javascript:void(0)" class="#{class}">#{label}</a>#{submenu}</li>',
		submenu:	'<ul class="context-submenu" id="contextmenu-#{parentKey}-submenu">#{submenu}</ul>'
	},



		// The render functions appends each item to this variable to build the menu
	code: '',



	/**
	 * Render a json object into the context menu html code
	 *
	 * @param	Object	json
	 */
	render: function(json) {
		this.init();

			// Render each menu item
		json.each(function(item){
				// If the item has a submenu, replace the key with the rendered code
			if( typeof item.submenu === 'object' ) {
				item.submenu = this.renderSubmenu(item);
			}
				// Append rendered item
			this.code += this.template.item.evaluate(item);
		}.bind(this));

		return this.code;
	},



	/**
	 * Render submenu of an item
	 *
	 * @param	Object		parentItem
	 */
	renderSubmenu: function(parentItem) {
		var items = '';

			// Transform the submenu object into a hash for iterating over it
		$H(parentItem.submenu).each(function(pair){
			items += this.template.item.evaluate(pair.value);
		}.bind(this));

		return this.template.submenu.evaluate({
			'parentKey':	parentItem.key,
			'submenu':		items
		});
	},



	/**
	 * Initialize template objects and clean code variable
	 */
	init: function() {
		this.code = '';

		if( this.template.item === null ) {
			this.template.item 		= new Template(this.html.item);
			this.template.submenu 	= new Template(this.html.submenu);
		}
	}

};