/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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
 * User Interface
 *
 * @namespace	Todoyu.Ui
 */
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

		this.closeRTE(container);

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

		this.closeRTE(container);

		if( Todoyu.exists(container) ) {
			return new Todoyu.Ajax.Replacer(container, url, options);
		} else {
			Todoyu.log('You tried to replace "' + container + '" which is not part of the DOM!');
		}
	},



	/**
	 * Prepend before element
	 *
	 * @param	{String}	container
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	prepend: function(container, url, options) {
		options = this._getDefaultOptions(options);
		options.insertion = 'before';

		return this.update(container, url, options);
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
	insert: function(container, url, options) {
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
	 * @param	{String|Element}	idElement
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
	 * Update content with new HTML
	 *
	 * @param	{String}		content
	 */
	setContent: function(content) {
		this.closeRTE('content');

		$('content').update(content);
	},



	/**
	 * Update content tabs DIV
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
		this.closeRTE('content-body');

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
			var headerHeight	= $('header').getHeight();
			var scrollOffset	= element.cumulativeScrollOffset().top;
			var elementOffset	= element.cumulativeOffset().top;

			var scrollBy	= headerHeight;

			if( scrollOffset > headerHeight ) {
				if( scrollOffset !== elementOffset ) {
					scrollBy	= scrollOffset - (elementOffset - headerHeight);
				}

				this.scrollBy.delay(0.1, 0, -scrollBy);
			}
		}
	},



	/**
	 * Scroll window content by given values
	 *
	 * @param	{Number}	x
	 * @param	{Number}	y
	 */
	scrollBy: function(x, y) {
		//alert('scroll: ' + y);
		window.scrollBy(x, y);
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
	 * @param	{Number}	idElement
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
	 * @param	{Number}		idElement
	 * @param	{Array}		config
	 * @return	Todoyu.TimePicker
	 */
	showTimePicker: function(idElement, config) {
		config = $H({
			'rangeHour':	[0,23],
			'rangeMinute':	[0,55]
		}).merge(config).toObject();

		return new Todoyu.TimePicker(idElement, config);
	},



	/**
	 * Show duration picker
	 *
	 * @param	{String}		idElement
	 * @return	Todoyu.TimePicker
	 */
	showDurationPicker: function(idElement, config) {
		config = config || {};
		config = $H({
			'rangeHour':	[0,99],
			'rangeMinute':	[0,55]
		}).merge(config).toObject();

		return new Todoyu.TimePicker(idElement);
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
	},



	/**
	 * Simulate sending a keystroke to RTE to initialize it, so that focus is given also for special keys like [BACKSPACE], [CTRL]+[A], etc.
	 * Neccessary if there are more than one instance of tiny_mce open
	 *
	 * @param	{Element}	textControlElement
	 */
	initRTEfocus: function(textControlElement) {
		var textControl = tinyMCE.get(textControlElement);

		if ( textControl ) {
				// get content
			var tempContent = textControl.getContent();

				// return content
			textControl.setContent(tempContent);
		}
	},



	/**
	 * Save all RTEs in the document
	 * Sometimes, double instances exist. Prevents saving if missing instances of an editor
	 * Use this function instead of tinyMCE.triggerSave();
	 */
	saveRTE: function() {
		window.tinyMCE.editors.each(function(editor){
			if( editor ) {
				editor.save();
			}
		});
	},



	/**
	 * Removes tinyMCE controls and save the editor
	 * Prevents "ghost" objects which will break the save process
	 *
	 * @param	{Element}	area		Area to look for tinyMCE instances (Can be a form, the whole window or the element itself)
	 */
	closeRTE: function(area) {
		this.saveRTE();

		if( ! Todoyu.exists(area) ) {
			area = document.body;
		}

			// Remove controls for all editors in the range
		$(area).select('textarea.RTE').each(function(textarea){
			tinyMCE.execCommand('mceRemoveControl', false, textarea.id);
		});
	},



	/**
	 * Center an element on the screen
	 *
	 * @param	{Element|String}	element
	 */
	centerElement: function(element) {
		element			= $(element);
		var elementDim	= element.getDimensions();
		var screenDim	= document.viewport.getDimensions();

		var left	= parseInt((screenDim.width-elementDim.width)/2);
		var top		= parseInt((screenDim.height-elementDim.height)/2);

		element.setStyle({
			'top': top + 'px',
			'left':left + 'px'
		});

		return element;
	},



	/**
	 * Set selected options of a select element
	 *
	 * @param	{Element}	element
	 * @param	{Array}		selection
	 */
	selectOptions: function(element, selection) {
		element		= $(element);
		selection	= selection.constructor === Array ? selection : [selection];

		element.selectedIndex = -1;

		$A(element.options).each(function(selection, option){
			if( selection.include(option.value) ) {
				option.selected = true;
			}
		}.bind(this, selection));
	}

};