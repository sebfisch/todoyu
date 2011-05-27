/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
 * @module	Core
 */

/**
 * User Interface
 *
 * @class		Ui
 * @namespace	Todoyu
 */
Todoyu.Ui = {

	/**
	 * @property	bodyClickObservers
	 * @type		Array
	 */
	bodyClickObservers: [],

	/**
	 * Update element
	 *
	 * @method	update
	 * @param	{String}	container
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	update: function(container, url, options) {
		options = Todoyu.Ajax.getDefaultOptions(options);

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
	 * @method	replace
	 * @param	{String}	container
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	replace: function(container, url, options) {
		options = Todoyu.Ajax.getDefaultOptions(options);

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
	 * @method	prepend
	 * @param	{String}	container
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	prepend: function(container, url, options) {
		options = Todoyu.Ajax.getDefaultOptions(options);
		options.insertion = 'before';

		return this.update(container, url, options);
	},



	/**
	 * Append to element
	 *
	 * @method	append
	 * @param	{String}	container
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	append: function(container, url, options) {
		options = Todoyu.Ajax.getDefaultOptions(options);
		options.insertion = 'after';

		return this.update(container, url, options);
	},



	/**
	 * Insert after element
	 *
	 * @method	insert
	 * @param	{String}	container
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	insert: function(container, url, options) {
		options = Todoyu.Ajax.getDefaultOptions(options);
		options.insertion = 'bottom';

		return this.update(container, url, options);
	},



	/**
	 * Hide element
	 *
	 * @method	hide
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
	 * @method	show
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
	 * @method	toggle
	 * @param	{String}	idElement
	 */
	toggle: function(idElement) {
		if( Todoyu.exists(idElement) ) {
			$(idElement).toggle();
		}
	},



	/**
	 * Update toggle (expand/collapse) icon
	 *
	 * @method	updateToggleIcon
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
	 * @method	updateContent
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	updateContent: function(url, options) {
		return this.update('content', url, options);
	},



	/**
	 * Update content with new HTML
	 *
	 * @method	setContent
	 * @param	{String}		content
	 */
	setContent: function(content) {
		this.closeRTE('content');

		$('content').update(content);
	},



	/**
	 * Update content tabs DIV
	 *
	 * @method	setContentTabs
	 * @param	{String}		tabs
	 */
	setContentTabs: function(tabs) {
		$('content-tabs').update(tabs);
	},



	/**
	 * Update content body div
	 *
	 * @method	setContentBody
	 * @param	{String}		body
	 */
	setContentBody: function(body) {
		this.closeRTE('content-body');

		$('content-body').update(body);
	},



	/**
	 * Update content body with request
	 *
	 * @method	updateContentBody
	 * @param	{String}		url
	 * @param	{Hash}		options
	 */
	updateContentBody: function(url, options) {
		return this.update('content-body', url, options);
	},



	/**
	 * Update (left column) panel
	 *
	 * @method	updatePanel
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	updatePanel: function(url, options) {
		return this.update('leftCol', url, options);
	},



	/**
	 * Update context menu
	 *
	 * @method	updateContextMenu
	 * @param	{String}	url
	 * @param	{Object}	options
	 */
	updateContextMenu: function(url, options) {
		return this.update('contextmenu', url, options);
	},



	/**
	 * Update page
	 *
	 * @method	updatePage
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
	 * Disable screen by adding todoyu overlay
	 */
	disableScreen: function() {
		WindowUtilities.disableScreen('todoyu', 'overlay_modal', 0.7, '', document.body);
	},



	/**
	 * Enable screen by removing todoyu overlay
	 */
	enableScreen: function() {
		$('overlay_modal').remove();
	},



	/**
	 * Set cursor of link
	 *
	 * @method	setLinkCursor
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
	 * @method	addHoverEffect
	 * @param	{Element|String}	element
	 */
	addHoverEffect: function(element) {
		$(element).observe('mouseover', this.hoverEffect.bindAsEventListener(this, true, $(element)));
		$(element).observe('mouseout', this.hoverEffect.bindAsEventListener(this, false, $(element)));
	},



	/**
	 * Hover effect handler (handles both mouseOver/ Out)
	 *
	 * @method	hoverEffect
	 * @param	{Event}			event
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
	 * Set favIcon from file at given path
	 *
	 * @method	setFavIcon
	 * @param	{String}	hrefIcon
	 */
	setFavIcon: function(hrefIcon) {
		var link	= document.createElement('link');
		link.type	= 'image/x-icon';
		link.rel	= 'shortcut icon';

		link.href	= hrefIcon

		$$('head')[0].appendChild(link);
	},



	/**
	 * Set favIcon back to original one
	 *
	 * @method	resetFavIcon
	 */
	resetFavIcon: function() {
		this.setFavIcon('favicon.ico');
	},



	/**
	 * Fix element anchor position
	 *
	 * @method	fixAnchorPosition
	 */
	fixAnchorPosition: function() {
		if( location.hash !== '') {
			var name	= location.hash.substr(1);

			this.scrollToAnchor(name);
		}
	},



	/**
	 * Scroll view to the anchor in the document
	 *
	 * @method	scrollToAnchor
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
	 * @method	scrollToElement
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

				this.scrollBy.defer(0, -scrollBy);
			}
		}
	},



	/**
	 * Scroll window content by given values
	 *
	 * @method	scrollBy
	 * @param	{Number}	x
	 * @param	{Number}	y
	 */
	scrollBy: function(x, y) {
		//alert('scroll: ' + y);
		window.scrollBy(x, y);
	},



	/**
	 * Scroll to top of the page
	 *
	 * @method	scrollToTop
	 */
	scrollToTop: function() {
		Effect.ScrollTo('header', {
			'duration': 0.3
		});
	},



	/**
	 * Collapse / expand element
	 *
	 * @method	collapseExpandElement
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
	 * @method	twinkle
	 * @param	{Element}		element
	 */
	twinkle: function(element) {
		Todoyu.Ui.hide( element );
		Effect.Appear( element );
	},



	/**
	 * Check whether given element is currently visible
	 *
	 * @method	isVisible
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
	 * @method	showTimePicker
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
	 * @method	showDurationPicker
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
	 * @method	setTitle
	 * @param	{String}		title
	 */
	setTitle: function(title) {
		document.title = title + ' - todoyu';
	},



	/**
	 * Get document title, without the " - todoyu" postfix (shown in browser window title bar)
	 *
	 * @method	getTitle
	 * @return	{String}
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
	 *
	 * @method	observeBody
	 */
	observeBody: function() {
		$(document.body).observe('click', this.onBodyClick.bindAsEventListener(this));
	},



	/**
	 * Handler when clicked on the body
	 *
	 * @method	onBodyClick
	 * @param	{Event}		event
	 */
	onBodyClick: function(event) {
		this.bodyClickObservers.each(function(event, func){
			func(event);
		}.bind(this, event));
	},



	/**
	 * Add an observer for the body
	 *
	 * @method	addBodyClickObserver
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
	 * @method	addBodyClickObserver
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
	 * @method	initRTEfocus
	 * @param	{Element}	textControlElement
	 */
	initRTEfocus: function(textControlElement) {
		var textControl = tinyMCE.get(textControlElement);

		if( textControl ) {
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
	 *
	 * @method	saveRTE
	 */
	saveRTE: function() {
		window.tinyMCE.editors.each(function(editor, index){
			if( editor && Todoyu.exists(editor.editorId) ) {
				editor.save();
				return;
			}

				// Delete item if element does not exist
			delete window.tinyMCE.editors[index];
		});
	},



	/**
	 * Removes tinyMCE controls and save the editor
	 * Prevents "ghost" objects which will break the save process
	 *
	 * @method	closeRTE
	 * @param	{Element}	area		Area to look for tinyMCE instances (Can be a form, the whole window or the element itself)
	 */
	closeRTE: function(area) {
		this.saveRTE();

		if( area === null || ! Todoyu.exists(area) ) {
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
	 * @method	centerElement
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
	 * Build a button element
	 *
	 * @param	{String}	id
	 * @param	{String}	className
	 * @param	{String}	label
	 * @param	{Function}	onClick
	 */
	buildButton: function(id, className, label, onClick) {
		var button	= new Element('button', {
			title:	label,
			'class':'button ' + className,
			type:	'button',
			id:		id
		});
		button.insert(new Element('span', {
			'class': 'icon'
		}));
		button.insert(new Element('span', {
			'class': 'label'
		}).update(label));
		button.insert(new Element('span', {
			'class': 'rgt'
		}));

		if( onClick ) {
			button.on('click', 'button', onClick);
		}

		return button;
	}

};