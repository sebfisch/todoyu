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

Todoyu.Wizard = {

	wizards: {},

	open: function(wizardName, onLoadCallback) {
		var url		= Todoyu.getUrl('core', 'wizard');
		var options	= {
			parameters: {
				action: 'load',
				wizard: wizardName
			},
			onComplete: this.onOpened.bind(this, wizardName)
		};

		this.wizards[wizardName] = {
			popup: Todoyu.Popup.openWindow('wizard' + wizardName, 'Wizard', 900, url, options),
			callback: onLoadCallback || Prototype.emptyFunction
		};
	},

	onOpened: function(wizardName, response) {
		this.wizards[wizardName].popup.setTitle('test');
		this.wizards[wizardName].callback(wizardName, response);
	},

	back: function(wizardName) {
		this.setDirection('back');
		this.submit(wizardName);
	},

	next: function(wizardName) {
		this.setDirection('next');
		this.submit(wizardName);
	},

	submit: function(wizardName) {
		$('wizard').down('form').request({
			onComplete: this.onSubmitted.bind(this, wizardName)
		});
	},

	onSubmitted: function(wizardName, response) {
		$('wizard').replace(response.responseText);
		this.wizards[wizardName].callback(wizardName, response);
	},

	setDirection: function(direction) {
		$('wizard-direction').value = direction;
	},

	getStepName: function() {
		return $F('wizard-step');
	},

	getWizardName: function() {
		return $F('wizard-wizard');
	}

};