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

Todoyu.Ui = {
	
	bodyClickObservers: [],

	/**
	 * Update element
	 *
	 * @param	{String}	container
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	update: function(container, url, options) {
		options = this._getDefaultOptions(options);

		if( Todoyu.exists(container) ) {
			return new Ajax.Updater(container, url, options);
		} else {
			Todoyu.log('You tried to update "' + container + '" which is not part of the DOM! (No request sent)');
		}
	},



	/**
	 * Replace element
	 *
	 * @param	{String}	container
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	replace: function(container, url, options) {
		options = this._getDefaultOptions(options);

		if( Todoyu.exists(container) ) {
			return new Ajax.Replacer(container, url, options);
		} else {
			Todoyu.log('You tried to replace "' + container + '" which is not part of the DOM!');
		}
	},



	/**
	 * Append to element
	 *
	 * @param	{String}	container
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	append: function(container, url, options) {
		options = this._getDefaultOptions(options);
		options.insertion = 'after';

		return this.update(container, url, options);
	},



	/**
	 * Insert after element
	 *
	 * @param	{String}	container
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	insert: function(container, url, options)	{
		options = this._getDefaultOptions(options);
		options.insertion = 'bottom';

		return this.update(container, url, options);
	},



	/**
	 * Set default options
	 *
	 * @param	{Object}	options
	 * @return	Object
	 */
	_getDefaultOptions: function(options) {
		if( Object.isUndefined(options) ) {
			options = {};
		}

		if( Object.isUndefined(options.evalScripts) ) {
			options.evalScripts = true;
		}

		if( Object.isUndefined(options.parameters) ) {
			options.parameters = {};
		}

		if( Object.isUndefined(options.parameters.area) ) {
			options.parameters.area = Todoyu.getArea();
		}

		return options;
	},



	/**
	 * Hide element
	 *
	 * @param	{String}	idElement
	 */
	hide: function(idElement) {
		if( Todoyu.exists(idElement) ) {
			$(idElement).hide();
		}
	},



	/**
	 * Show element
	 *
	 * @param	{String}	idElement
	 */
	show: function(idElement) {
		if( Todoyu.exists(idElement) ) {
			$(idElement).show();
		}
	},



	/**
	 * Toggle element visibility
	 *
	 * @param	{String}	idElement
	 */
	toggle: function(idElement) {
		if( Todoyu.exists(idElement) ) {
			$(idElement).toggle();
		}
	},



	/**
	 * Update toggler icon
	 *
	 * @param	{String}	elementPrefix
	 * @param	{String}	idElement
	 */
	updateToggleIcon: function(elementPrefix, idElement) {
		if( $(elementPrefix + idElement + '-details').visible() ) {
			$(elementPrefix + idElement + '-toggler').addClassName('expanded');
		} else {
			$(elementPrefix + idElement + '-toggler').removeClassName('expanded');
		}
	},



	/**
	 * Update element content
	 *
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	updateContent: function(url, options) {
		return this.update('content', url, options);
	},



	/**
	 * Update content with new html
	 *
	 * @param	{String}		content
	 */
	setContent: function(content) {
		$('content').update(content);
	},



	/**
	 * Update content tabs div
	 *
	 * @param	{String}		tabs
	 */
	setContentTabs: function(tabs) {
		$('content-tabs').update(tabs);
	},



	/**
	 * Update content body div
	 *
	 * @param	{String}		body
	 */
	setContentBody: function(body) {
		$('content-body').update(body);
	},



	/**
	 * Update content body with request
	 *
	 * @param	{String}		url
	 * @param	{Hash}		options
	 */
	updateContentBody: function(url, options) {
		return this.update('content-body', url, options);
	},



	/**
	 * Update (left column) panel
	 *
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	updatePanel: function(url, options) {
		return this.update('leftCol', url, options);
	},



	/**
	 * Update context menu
	 *
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	updateContextMenu: function(url, options) {
		return this.update('contextmenu', url, options);
	},



	/**
	 * Update page
	 *
	 * @param	{String}	ext
	 * @param	{String}	controller
	 */
	updatePage: function(ext, controller) {
		var url = {ext: ext};

		if( Object.isString(controller) ) {
			if( !controller.empty ) 	{
				url.controller = controller;
			}
		}

		location.href = '?' + Object.toQueryString(url);
	},



	/**
	 * AJAX loader
	 *
	 * @param	{Boolean}		showLoader
	 */
	ajaxLoader: function(showLoader) {
		Todoyu.Headlet.show(showLoader);	
	},



	/**
	 * Set cursor of link
	 *
	 * @param	{Boolean}	wait
	 */
	setLinkCursor: function(wait) {
		$$('a').each(function(a) {
			a.setStyle({
				'cursor': wait ? 'wait' : 'pointer'
			});
		});

		$(document.body).setStyle({
			'cursor': wait ? 'wait' : 'auto'
		});
	},



	/**
	 * Add hover effect to element
	 *
	 * @param	{String}	idElement
	 */
	addHoverEffect: function(idElement) {
		var elt = $(idElement);
		Event.observe(idElement, 'mouseover', this.hoverEffect.bindAsEventListener(this, true, elt));
		Event.observe(idElement, 'mouseout', this.hoverEffect.bindAsEventListener(this, false, elt));
	},



	/**
	 * Hover effect handler (handles both mouseOver/ Out)
	 *
	 * @param	{Object}		event
	 * @param	{Boolean}		over
	 * @param	{Element}		element
	 */
	hoverEffect: function(event, over, element) {
		if( over ) {
			element.addClassName('hover');
		} else {
			element.removeClassName('hover');
		}
	},



	/**
	 * Fix element anchor position
	 */
	fixAnchorPosition: function() {
		if( location.hash !== '') {
			var name	= location.hash.substr(1);

			this.scrollToAnchor(name);
		}
	},



	/**
	 * @todo	comment
	 *
	 * @param	{String}	name
	 */
	scrollToAnchor: function(name) {
		var element = document.getElementsByName(name)[0];

		if(element) {
			this.scrollToElement(element);
		}
	},



	/**
	 * Scroll to given element
	 *
	 * @param	{Element}		element
	 */
	scrollToElement: function(element) {
		element = $(element);
		element.scrollTo();
				
		if( Todoyu.exists('header') ) {
			var headerHeight = $('header').getHeight();
			window.setTimeout(window.scrollBy, 10, 0, -headerHeight);
		}
	},



	/**
	 * Scroll to top of the page
	 */
	scrollToTop: function() {
		Effect.ScrollTo('header', {
			'duration': 0.3
		});
	},



	/**
	 * Collapse / expand element
	 *
	 * @param	{Integer}	idElement
	 * @param	{Element}	toggle
	 */
	collapseExpandElement: function(idElement, toggle) {
		var options = {
			'duration': 0.3
		};

		var content = $(idElement);

		if( content.visible() ) {
			Effect.SlideUp( content, options );
		} else {
			Effect.SlideDown( content, options );
		}

		toggle.toggleClassName('expand');
	},



	/**
	 * Evoke twinkeling effect upon given element
	 *
	 * @param	{Element}		element
	 */
	twinkle: function(element) {
		Todoyu.Ui.hide( element );
		Effect.Appear( element );
	},



	/**
	 * Check whether given element is currently visible
	 *
	 * @param	{Element}	element
	 * @return	{Boolean}
	 */
	isVisible: function(element) {
		if( Todoyu.exists(element) ) {
			return $(element).visible();
		} else {
			return false;
		}
	},



	/**
	 * Show time picker
	 *
	 * @param	{Integer}		idElement
	 * @param	{Array}		config
	 * @return	TimePicker
	 */
	showTimePicker: function(idElement, config) {
		config = $H({
			'rangeHour':	[0,23],
			'rangeMinute':	[0,55]
		}).merge(config).toObject();

		return new TimePicker(idElement, config);
	},



	/**
	 * Show duration picker
	 *
	 * @param	{String}		idElement
	 * @return	TimePicker
	 */
	showDurationPicker: function(idElement, config) {
		config = config || {};
		config = $H({
			'rangeHour':	[0,99],
			'rangeMinute':	[0,55]
		}).merge(config).toObject();

		return new TimePicker(idElement);
	},



	/**
	 * Set document title (shown in browser window title bar)
	 * 
	 * @param	{String}		title
	 */
	setTitle: function(title) {
		document.title = title + ' - todoyu';
	},



	/**
	 * Get document title, without the " - todoyu" postfix (shown in browser window title bar)
	 * 
	 * @return String
	 */	
	getTitle: function(strip) {
		if( strip === false ) {
			return document.title;
		} else {
			return document.title.replace(/ - todoyu/, '');
		}
	},



	/**
	 * Creates a js filereference and appends it to head
	 *
	 * @param	{String}		filename
	 * @todo	NOT USED...
	 */
	loadJSFile: function(filename)	{
		var fileref=document.createElement( 'script' );
		fileref.setAttribute( "type" , "text/javascript" );
		fileref.setAttribute( "src" , filename );

		Todoyu.Ui.appendAssetToHead(fileref);
	},



	/**
	 * Creates a CSS filereference and appends it to head
	 *
	 * @param	{String}		filename
	 * @todo	NOT USED...
	 */
	loadCSSFile: function(filename)	{
		var fileref=document.createElement( "link" );
		fileref.setAttribute( "rel" , "stylesheet" );
		fileref.setAttribute( "type" , "text/css" );
		fileref.setAttribute( "href" , filename );

		Todoyu.Ui.appendAssetToHead(fileref);
	},



	/**
	 * Appends given filereference to html head
	 *
	 * @param	fileref
	 */
	appendAssetToHead: function(fileref)	{
		if (typeof fileref!="undefined")	{
			 document.getElementsByTagName( "head" )[0].appendChild( fileref );
		}
	},
		
	
	
	/**
	 * Observe body for click events
	 */
	observeBody: function() {
		$(document.body).observe('click', this.onBodyClick.bindAsEventListener(this));
	},



	/**
	 * Handler when clicked on the body
	 * 
	 * @param	{Event}	event
	 */
	onBodyClick: function(event) {
		this.bodyClickObservers.each(function(event, func){
			func(event);
		}.bind(this, event));
	},



	/**
	 * Add an observer for the body
	 * 
	 * @param	{Function}	func
	 */
	addBodyClickObserver: function(func) {
		this.bodyClickObservers.push(func);
	},



	/**
	 * Stop event bubbling
	 * Useful when handling onclick-events of nested elements
	 * whose parents have onclick handlers which should be not fired than
	 * 
	 * @param	{Event}	event
	 */
	stopEventBubbling: function(event) {
		if( window.event ){
			event.returnValue = false;
			event.cancelBubble = true;
		} else{
			event.preventDefault();
			event.stopPropagation();
		}	
	}

};