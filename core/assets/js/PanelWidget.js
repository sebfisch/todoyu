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

Todoyu.PanelWidget = {

	observerCallbacks: {},

	/**
	 * Toggle panel widget expanded/ collapsed, evoke storing of expand status
	 *
	 * @param	{String}			ext			e.g. 'project' / 'calendar' etc.
	 * @param	{String}			widgetName
	 * @param	{Integer}			idArea
	 */
	toggle: function(widget) {
		var h1		= $('panelwidget-' + widget + '-h1');
		var content	= $('panelwidget-' + widget + '-content');
		var toggle	= $('panelwidget-' + widget + '-toggle');
		var options = {
			'duration': 0.3
		};

		this.saveToggleStatus(widget, !content.visible());

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
	 * @param	{String}			ext			e.g. 'project' / 'calendar' etc.
	 * @param	{String}			widgetName
	 * @param	{Boolean}			expand
	 * @param	{Integer}			idArea
	 */
	saveToggleStatus: function(widget, expanded) {
		var url		= Todoyu.getUrl('core', 'panelwidget');
		var options	= {
			'parameters': {
				'action': 	'expanded',
				'widget':	widget,
				'expanded':	expanded ? 1 : 0
			}
		};

		Todoyu.send(url, options);
	},



	/**
	 * Install observer on given widget, firing given callback function 
	 *
	 * @param	{String}			widget
	 * @param	{String}			callbackFunction
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
	 * @param	{String}		widget
	 * @param	{String}		callbackFunction
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
	 * @param	{String}	widget
	 * @param	{Array}	params
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
	 * @param	{String}		extKey
	 * @param	{String}		widgetName
	 * @return	{Boolean}
	 */
	isLoaded: function(extKey, widgetName) {
		return typeof(Todoyu.Ext[extKey].PanelWidget[widgetName]) === 'object';
	}

};