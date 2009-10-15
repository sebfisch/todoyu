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

	subFormIndex: 100,

	getNextIndex: function() {
		return this.subFormIndex++;
	},

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
	toggleRecordForm: function(idRecord, fieldName, index)	{
		var idForm = 'foreignrecord-' + idRecord + '-' + fieldName + '-' + index + '-formhtml';

		if( Todoyu.exists(idForm) ) {
			$(idForm).toggle();
		}
	},


	/**
	 *	Remove sub form
	 *
	 *	@param	unknown_type	removeLink
	 */
	removeRecord: function(idRecord, fieldName, index) {
		var idElement	= 'foreignrecord-' + idRecord + '-' + fieldName + '-' + index;

		$(idElement).remove();

		/*
		Effect.BlindUp(idElement);

		$(idElement).remove.delay(1);


		, {
			'scaleMode': 'contents',
			'afterFinish': function(){
				console.log('remove');
				//$(idElement).remove()
			}
		});
		*/

	},


	/**
	 * Add a new record
	 * @param	Integer		idRecord
	 * @param	String		formName
	 * @param	Stirng		fieldName
	 * @param	String		updateExt
	 * @param	String		updateController
	 */
	addRecord: function(idRecord, formName, fieldName, updateExt, updateController) {
		var container	= $('foreignrecords-' + idRecord + '-' + fieldName);
		var index		= this.getNextIndex();

		var url 	= Todoyu.getUrl(updateExt, updateController);
		var options = {
			'parameters': {
				'cmd':		'addSubform',
				'form': 	formName,
				'field':	fieldName,
				'record':	idRecord,
				'index': 	index
			},
			'onComplete': this.onRecordAdded.bind(this, idRecord, formName, fieldName, index)
		};

		Todoyu.Ui.insert(container, url, options);
	},



	/**
	 * Callback when new record added
	 * @param	Integer		idRecord
	 * @param	String		formName
	 * @param	String		fieldName
	 * @param	String		index
	 * @param	String		response
	 */
	onRecordAdded: function(idRecord, formName, fieldName, index, response) {
		this.toggleRecordForm(idRecord, fieldName, index);
		this.focusFirstRecordField(idRecord, fieldName, index);
	},



	/**
	 * Focus first record field
	 * @param	Integer		idRecord
	 * @param	String		fieldName
	 * @param	Integer		index
	 */
	focusFirstRecordField: function(idRecord, fieldName, index) {
		var field	= $('foreignrecord-' + idRecord + '-' + fieldName + '-' + index + '-formhtml').select('input', 'select', 'textarea').first();

		if( field )  {
			field.focus();
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

	},



	/**
	 *	Expand all foreign records in a form
	 *
	 *	@param	unknown_type	buttonID
	 */
	openWizard: function(idRecord, idField, extension, controller, command, height, width, title)	{
		var url		= Todoyu.getUrl(extension,	controller);
		var options	= {
			'parameters': {
				'cmd': command,
				'idRecord': idRecord,
				'idField': idField
			}
		};
		var idPopup	= 'popup-'+idField;
		var title	= title ? title : 'Form Wizard';
		var width	= width > 0 ? width : 480;
		var height	= height > 0 ? height: 300;

		Todoyu.Popup.openWindow(idPopup, title, width, height, 0, 0, url, options);
	}

};