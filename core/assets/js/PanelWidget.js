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

Todoyu.PanelWidget = {

	observerCallbacks: {},

	/**
	 * Toggle panel widget expanded/ collapsed, evoke storing of expand status
	 *
	 * @param	String			ext			e.g. 'project' / 'calendar' etc.
	 * @param	String			widgetName
	 * @param	Integer			idArea
	 */
	toggle: function(ext, widgetName, idArea) {
		var h1	= $('panelwidget-' + widgetName + '-h1');
		var content	= $('panelwidget-' + widgetName + '-content');
		var toggle	= $('panelwidget-' + widgetName + '-toggle');

		var options = {
			'duration': 0.3
		};

		this.saveToggleStatus(ext, widgetName, !content.visible(), idArea);

		if( content.visible() ) {
			Effect.SlideUp(content, options);
		} else {
			Effect.SlideDown(content, options);
		}

		h1.toggleClassName('expand');
		toggle.toggleClassName('expand');
	},



	/**
	 * Save widget expand status
	 *
	 * @param	String			ext			e.g. 'project' / 'calendar' etc.
	 * @param	String			widgetName
	 * @param	Boolean			expand
	 * @param	Integer			idArea
	 */
	saveToggleStatus: function(ext, widgetName, expand, idArea) {
		var action	= 'pwidget';
		var pref= expand ? 'expand' : 'collapse';

		Todoyu.Pref.save(ext, action, pref, widgetName, idArea);
	},



	/**
	 * Install observer on given widget, firing given callback function 
	 *
	 * @param	String			widget
	 * @param	String			callbackFunction
	 */
	observe: function(widget, callbackFunction) {
		if( Object.isUndefined(this.observerCallbacks[widget]) ) {
			this.observerCallbacks[widget] = [];
		}

		this.observerCallbacks[widget].push(callbackFunction);
	},



	/**
	 * Stop observation of given widget
	 *
	 * @param	String		widget
	 * @param	String		callbackFunction
	 */
	stopObserving: function(widget, callbackFunction)	{
		if( ! Object.isUndefined(this.observerCallbacks[widget]) ) {
			if( ! callbackFunction )	{
				if(this.observerCallbacks[widget].length > 0)	{
						this.observerCallbacks[widget].clear();
				}
			}
		}
	},



	/**
	 * Fire registered callback functions of given widget
	 *
	 * @param	String	widget
	 * @param	Array	params
	 */
	fire: function(widget, params) {
		if( ! Object.isUndefined(this.observerCallbacks[widget]) ) {
			this.observerCallbacks[widget].each(function(widget, params, callbackFunction) {
				callbackFunction(widget, params);
			}.bind(this, widget, params));
		}
	},



	/**
	 * Check whether given panel widget is loaded
	 *
	 * @param	String		extKey
	 * @param	String		widgetName
	 * @return	Boolean
	 */
	isLoaded: function(extKey, widgetName) {
		return typeof(Todoyu.Ext[extKey].PanelWidget[widgetName]) === 'object';
	}

};