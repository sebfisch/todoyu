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

Todoyu.Tabs = {

	bindCache: {},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	list
	 *	@param	unknown_type	handlerFunction
	 */
	create: function(list, handlerFunction) {
		list = $(list);

		this.bindCache[list.id] = {
			'click': 	this._clickHandler.bindAsEventListener(this, handlerFunction),
			'mouseover':this._hoverHandler.bindAsEventListener(this, true),
			'mouseout': this._hoverHandler.bindAsEventListener(this, false)
		};

		Event.observe(list, 'click', this.bindCache[list.id].click);
		Event.observe(list, 'mouseover', this.bindCache[list.id].mouseover);
		Event.observe(list, 'mouseout', this.bindCache[list.id].mouseout);
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	list
	 *	@param	unknown_type	handlerFunction
	 */
	destroy: function(list, handlerFunction) {
		list = $(list);
		Event.stopObserving(list, 'click', this.bindCache[list.id].click);
		Event.stopObserving(list, 'mouseover', this.bindCache[list.id].mouseover);
		Event.stopObserving(list, 'mouseout', this.bindCache[list.id].mouseout);

		delete this.bindCache[list.id];
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	idList
	 */
	isTabList: function(idList) {
		return Object.isUndefined(this.bindCache[idList]) === false;
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	e
	 *	@param	unknown_type	handlerFunction
	 */
	_clickHandler: function(e, handlerFunction) {
		e.stop();

		var element	= Event.findElement(e, 'li');

		if( Object.isUndefined(element) ) {
			return;
		}

		var classes = element.className.split(' ');

		classes.each(function(item){
			if( item.substr(0,7) === 'tabkey-' ) {
				handlerFunction(e, item.substr(7));
				return;
			}
		});

		var list = Event.findElement(e, 'ul');

		this.setActive(list, element);
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	list
	 *	@param	unknown_type	element
	 */
	setActive: function(list, element) {
		$(list).select('li').invoke('removeClassName', 'active');
		$(element).addClassName('active');
	},
	
	
	getActive: function(list) {
		//console.log($(list).select('li.active'));
		return $(list).select('li.active').first().getAttribute('id').split('-').last();
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	idTab
	 *	@param	unknown_type	tabClass
	 *	@param	unknown_type	tabLabel
	 *	@param	unknown_type	active
	 */
	build: function(idTab, tabClass, tabLabel, active) {
		/* this is not the new html code ! ==> SHOULD WORK NOW */
		var tab = new Element('li', {
			'id': idTab,
			'class': tabClass
		});
		var p = new Element('p', {
			'id': idTab + '-label',
			'class': 'label'
		});
		var lt = new Element('span', {
			'class': 'lt'
		});
		var icon = new Element('span', {
			'class': 'icon'
		});
		var labeltext = new Element('span', {
			'class': 'labeltext'
		}).update(tabLabel);

		tab.insert(p);
		p.insert(lt);
		p.insert(icon);
		p.insert(labeltext);

		if( active === true ) {
			tab.addClassName('active');
			p.addClassName('active');
		}

		return tab;
	},



	/**
	 *	Enter Description here...
	 *
	 *	@param	unknown_type	e
	 *	@param	unknown_type	over
	 */
	_hoverHandler: function(e, over) {
		var li = Event.findElement(e, 'li');

		if( Object.isUndefined(li) ) {
			return;
		}

		if( over ) {
			li.addClassName('hover');
		} else {
			li.removeClassName('hover');
		}
	}

};