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

/**
 *	Todoyu autocompleter (extended prototype autocompleter)
 */
 
Todoyu.Autocompleter = Class.create(Ajax.Autocompleter, {

	/**
	 * Hanlde completion of autocompleter suggestion retrieval
	 * 
	 * @param	Object	response
	 */
	onComplete: function(response) {
			// If a custom onComplete defined
		if( this.options.onCompleteCustom ) {
			var funResult = Todoyu.callUserFunction(this.options.onCompleteCustom, window, response, this);

				// If the custom function returns an object, override response
			if( typeof(funResult) === 'object' ) {
				response = funResult;
			}
		}
		
		if( response.getTodoyuHeader('acElements') == 0 ) {
			this.onEmptyResult(response);
		}
		
			// Call default ac handler
		this.updateChoices(response.responseText);
	},



	/**
	 * Hanlde receival of empty result (no suggestion found)
	 * 
	 * @param	Object	response
	 */
	onEmptyResult: function(response) {
		new Effect.Highlight(this.element, {
			'startcolor':	'#ff0000',
			'endcolor':		'#ffffff',
			'duration':		2.0
		});

		if( ! this.options.onCompleteCustom ) {
			Todoyu.notifyInfo('[LLL:form.ac.noResults]');
		}
	}
});