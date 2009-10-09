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

Todoyu.Ui = {

	/**
	 *	Update element
	 *
	 *	@param	unknown_type	container
	 *	@param	unknown_type	url
	 *	@param	unknown_type	options
	 */
	update: function(container, url, options) {
		options = this._setDefaultOptions(options);

		if( ! Todoyu.exists(container) ) {
			console.log('You tried to update "' + container + '" which is not part of the DOM!');
		}

		return new Ajax.Updater(container, url, options);
	},



	/**
	 *	Replace element
	 *
	 *	@param	unknown_type	container
	 *	@param	unknown_type	url
	 *	@param	unknown_type	options
	 */
	replace: function(container, url, options) {
		options = this._setDefaultOptions(options);

		if( ! Todoyu.exists(container) ) {
			console.log('You tried to replace "' + container + '" which is not part of the DOM!');
		}

		return new Ajax.Replacer(container, url, options);
	},



	/**
	 *	Append to element
	 *
	 *	@param	unknown_type	container
	 *	@param	unknown_type	url
	 *	@param	unknown_type	options
	 */
	append: function(container, url, options) {
		options = this._setDefaultOptions(options);
		options.insertion = 'after';

		return this.update(container, url, options);
	},



	/**
	 *	Insert after element
	 *
	 *	@param	unknown_type	container
	 *	@param	unknown_type	url
	 *	@param	unknown_type	options
	 */
	insert: function(container, url, options)	{
		options = this._setDefaultOptions(options);
		options.insertion = 'bottom';

		return this.update(container, url, options);
	},



	/**
	 *	Set default options
	 *
	 *	@param	unknown_type	options
	 */
	_setDefaultOptions: function(options) {
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
	 *	Hide element
	 *
	 *	@param	unknown_type	idElement
	 */
	hide: function(idElement) {
		if( Todoyu.exists(idElement) ) {
			$(idElement).hide();
		} else {
			console.log('not: ' + idElement);
		}
	},



	/**
	 *	Show element
	 *
	 *	@param	unknown_type	idElement
	 */
	show: function(idElement) {
		if( Todoyu.exists(idElement) ) {
			$(idElement).show();
		}
	},



	/**
	 *	Toggle element visibility
	 *
	 *	@param	unknown_type	idElement
	 */
	toggle: function(idElement) {
		if( Todoyu.exists(idElement) ) {
			$(idElement).toggle();
		}
	},



	/**
	 *	Update toggler icon
	 *
	 *	@param	unknown_type	elementPrefix
	 *	@param	unknown_type	idElement
	 */
	updateToggleIcon: function(elementPrefix, idElement) {
		if( $(elementPrefix + idElement + '-details').visible() ) {
			$(elementPrefix + idElement + '-toggler').addClassName('expanded');
		} else {
			$(elementPrefix + idElement + '-toggler').removeClassName('expanded');
		}
	},



	/**
	 *	Update element content
	 *
	 *	@param	unknown_type	url
	 *	@param	unknown_type	options
	 */
	updateContent: function(url, options) {
		return this.update('content', url, options);
	},



	/**
	 *	Update panel
	 *
	 *	@param	unknown_type	url
	 *	@param	unknown_type	options
	 */
	updatePanel: function(url, options) {
		return this.update('leftCol', url, options);
	},



	/**
	 *	Update context menu
	 *
	 *	@param	unknown_type	url
	 *	@param	unknown_type	options
	 */
	updateContextMenu: function(url, options) {
		return this.update('contextmenu', url, options);
	},



	/**
	 *	Update page
	 *
	 *	@param	String	ext
	 *	@param	unknown_type	controller
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
	 *	AJAX loader
	 *
	 *	@param	Boolean	show
	 */
	ajaxLoader: function(show) {
		if(show) {
			Effect.Appear($('ajax-loader').down(), {
				'duration': 0.2,
				'from': 0.3,
				'to': 1.0,
				'transition': Effect.Transitions.spring
			});
		} else {
			Effect.Fade.delay(0.5, $('ajax-loader').down(), {
				'duration': 0.5
			});
		}
	},



	/**
	 *	Set cursor of link
	 *
	 *	@param	Boolean	wait
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
	 *	Add hover effect to element
	 *
	 *	@param	unknown_type	idElement
	 */
	addHoverEffect: function(idElement) {
		var elt = $(idElement);
		Event.observe(idElement, 'mouseover', this.hoverEffect.bindAsEventListener(this, true, elt));
		Event.observe(idElement, 'mouseout', this.hoverEffect.bindAsEventListener(this, false, elt));
	},



	/**
	 *	Hover effect handler (handles both mouseOver/ Out)
	 *
	 *	@param	unknown_type	event
	 *	@param	unknown_type	over
	 *	@param	unknown_type	element
	 */
	hoverEffect: function(event, over, element) {
		if( over ) {
			element.addClassName('hover');
		} else {
			element.removeClassName('hover');
		}
	},



	/**
	 *	Fix element anchor position
	 *
	 */
	fixAnchorPosition: function() {

		if( location.hash !== '') {

			var name	= location.hash.substr(1);
			var element	= $(document.getElementsByName(name)[0]);

			if(element) {
				$(element).scrollToElement();
			}
		}
	},



	/**
	 *	Scroll to given element
	 *
	 *	@param	unknown_type	element
	 */
	scrollToElement: function(element) {
		$(element).scrollTo();
		window.scrollBy(0, -72);
	},



	/**
	 *	Collapse / expand element
	 *
	 *	@param	unknown_type	idElement
	 *	@param	unknown_type	toggle
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
	 *	Evoke twinkeling effect upon given element
	 *
	 *	@param	unknown_type	element
	 */
	twinkle: function(element) {
		Todoyu.Ui.hide( element );
		Effect.Appear( element );
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	element
	 */
	isVisible: function(element) {
		if( Todoyu.exists(element) ) {
			return $(element).visible();
		} else {
			return false;
		}
	},
	
	showDurationPicker: function(idElement) {
		return new DurationPicker(idElement);
	}

};