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
	 *	Toggle panel widget expanded/ collapsed, evoke storing of expand status
	 *
	 *	@param	unknown_type	ext
	 *	@param	unknown_type	widgetName
	 *	@param	unknown_type	idArea
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
	 *	Save widget expand status
	 *
	 *	@param	unknown_type	ext
	 *	@param	unknown_type	widgetName
	 *	@param	unknown_type	expand
	 *	@param	unknown_type	idArea
	 */
	saveToggleStatus: function(ext, widgetName, expand, idArea) {
		var cmd	= 'pwidget';
		var pref= expand ? 'expand' : 'collapse';

		Todoyu.Pref.save(ext, cmd, pref, widgetName, idArea);
	},



	/**
	 *	Please enter Description here...
	 *
	 *	@param	String	widget
	 *	@param	unknown_type	callbackFunction
	 */
	observe: function(widget, callbackFunction) {
		if( Object.isUndefined(this.observerCallbacks[widget]) ) {
			this.observerCallbacks[widget] = [];
		}

		this.observerCallbacks[widget].push(callbackFunction);
	},



	/**
	 *	Please enter Description here...
	 *
	 *	@param	String	widget
	 *	@param	unknown_type	callbackFunction
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
	 *	Please enter Description here...
	 *
	 *	@param	String	widget
	 *	@param	unknown_type	callbackFunction
	 */
	inform: function(widget, params) {
		if( ! Object.isUndefined(this.observerCallbacks[widget]) ) {
			this.observerCallbacks[widget].each(function(callbackFunction) {
				callbackFunction(widget, params);
			});
		}
	},



	/**
	 *	Please enter Description here...
	 *
	 *	@param	unknown_type	extKey
	 *	@param	unknown_type	widgetName
	 */
	isLoaded: function(extKey, widgetName) {
		return typeof(Todoyu.Ext[extKey].PanelWidget[widgetName]) === 'object';
	}

};