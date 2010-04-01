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

var TodoyuInstaller = {

	/**
	 * Disable given text box if selected value == 0
	 *
	 * @param	Element	selector
	 */
	disableTextBox: function(selector)	{
		textbox = document.getElementById('database_new');

		if( selector.options[selector.selectedIndex].value === '0' )	{
			textbox.disabled = false;
		} else {
			textbox.disabled = true;
		}
	},



	/**
	 * Check database selection / declaration of new database to be created
	 */
	checkDbSelect: function() {
		var newDbName	= $F('database_new');

		if ( newDbName !== '' ) {
				// New DB name specified? deactivate selector
			$('database').selectedIndex	= 0;
			$('database').disabled	= true;

				// Make sure there's no existing DB with that name
			$('error-newnameTaken').hide();
			$('submit').show();
			$$('#database option').each(function(dbOption){
				if ( dbOption.value == newDbName) {
					$('error-newnameTaken').show();
					$('submit').hide();
				}
			});
		} else {
			$('database').disabled	= false;
		}
	},



	/**
	 * Toggle display of SQL review
	 *
	 * @param	String	togglerID
	 * @param	String	sqlElementID
	 */
	toggle: function(togglerID, sqlElementID) {
		var toggler	= $(togglerID);

		if ( ! toggler.hasClassName('expanded') ) {
				// Collapse toggler
			toggler.innerHTML	= 'Hide SQL';
			toggler.addClassName('expanded');
			$(sqlElementID).show();
		} else {
				// Expand toggler
			toggler.innerHTML	= 'View SQL';
			toggler.removeClassName('expanded');
			$(sqlElementID).hide();
		}
	},



	/**
	 * Ensure password and it's repetition are identical
	 */
	validatePasswordRepetition: function() {
		var areIdentic	= ( $F('password') == $F('password_confirm') );
		var longEnough	= $F('password').length >= 5;
		var submitButton	= $$('button')[0];

		if ( areIdentic && longEnough ) {
			$('passwordLabel').removeClassName('redLabel');
			$('passwordConfirmLabel').removeClassName('redLabel');
			submitButton.show();
		} else {
			$('passwordLabel').addClassName('redLabel');
			$('passwordConfirmLabel').addClassName('redLabel');
			submitButton.hide();
		}
	},



	/**
	 *	Skip data import action (by altering action to next one)
	 */
	skipDataImport: function() {
		document.getElementById('action').value = 'config';
	}

};