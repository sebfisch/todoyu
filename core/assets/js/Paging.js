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

Todoyu.Paging = {
	
	config: {},
	
	init: function(name, update, size, offset, total) {
		var url	= update.split('/');
		
		this.config[name] = {
			'name':		name,
			'size':		size,
			'offset':	offset,
			'total':	total,
			'url': {
				'ext':			url[0],
				'controller': 	url[1],
				'action':		url[2]
			}
		};
	},
	
	update: function(name, offset) {
		var url		= Todoyu.getUrl(this.config[name].url.ext, this.config[name].url.controller);
		var options	= {
			'parameters': {
				'action': 	this.config[name].url.action,
				'name':		name,
				'offset':	offset
			},
			'onComplete': this.onUpdated.bind(this, name, offset)
		};
		var target	= 'paging-' + name;
		
		Todoyu.Ui.replace(target, url, options);
	},
	
	onUpdated: function(name, offset, response) {
		
	},
	
	foreward: function(name) {
		var newOffset = this.config[name].offset + this.config[name].size;
		if( newOffset < this.config[name].total ) {
			this.update(name, newOffset);
		}
	},
	
	backward: function(name) {
		var newOffset = this.config[name].offset - this.config[name].size;
		if( newOffset >= 0 ) {
			this.update(name, newOffset);
		}
	}
	
};