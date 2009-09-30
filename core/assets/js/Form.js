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

Todoyu.Form = {

	/**
	 *	Get serialized data of form containing given element
	 *
	 *	@param	unknown_type	idElement
	 */
	getContainingFormData: function( idElement ) {
		var idForm		= $(idElement).form.id;
		if (idForm !== '' && $(idForm)) {
			var formData	= Form.serialize(idForm);
		} else {
			var formData	= '';
		}

		return formData;
	},


	/**
	 *	Toggle sub form
	 *
	 *	@param	unknown_type	trigger
	 */
	toggleSubform: function(trigger)	{
		var triggerID = trigger.id;

		subFormID = triggerID.replace(/trigger/, 'formhtml');

		if(trigger.hasClassName('closed'))	{
			if($(subFormID))	{
				$(subFormID).style.display = 'block';
			}

			trigger.removeClassName('closed');
			trigger.addClassName('open');
		} else {
			if($(subFormID))	{
				$(subFormID).style.display = 'none';
			}

			trigger.removeClassName('open');
			trigger.addClassName('closed');
		}
	},


	/**
	 *	Remove sub form
	 *
	 *	@param	unknown_type	removeLink
	 */
	removeSubRecord: function(removeLink)	{
		removeLink.up('.databaseRelation').remove();
	},



	/**
	 *	Add sub form
	 *
	 *	@param	unknown_type	form
	 *	@param	unknown_type	formname
	 *	@param	unknown_type	field
	 *	@param	unknown_type	ext
	 *	@param	unknown_type	controller
	 */
	addSubform: function(form, formname, field, ext, controller)	{
		if(ext && controller)	{
			var container = $('foreign-records-'+field);

			var index = container.select('.databaseRelation').length;

			var cmd = 'addSubform';

			var url = Todoyu.getUrl(ext, controller);

			var options = {
				'parameters': {
					'form':		form,
					'field':	field,
					'cmd':		cmd,
					'formname': formname,
					'indexOfForeignRecord': index
				}
			};

			Todoyu.Ui.insert(container.id, url, options);
		} else {
			alert('no extension and/or controller defined');
		}
	},



	/**
	 *	Expand all foreign records in a form
	 *
	 *	@param	unknown_type	buttonID
	 */
	expandAllForeignRecords: function(buttonID) {
		var recordID    		= buttonID.split('-field-expandall')[0].split('-')[1];
		var parentRecordName	= buttonID.replace( '-' + recordID + '-field-expandall', '');

			// Hit (toggle) all closed triggers
		$$('.formtrigger.closed').each(function(trigger) {
			var triggerID	= trigger.id;
			if (triggerID.indexOf('foreign-record-') != -1) {
				Todoyu.Form.toggleSubform( trigger );
			}
		});

	}



};