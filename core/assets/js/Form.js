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
	 * Toggle sub form
	 *
	 * @param	Integer		idRecord
	 * @param	String		fieldName
	 * @param	Integer		index
	 */
	toggleRecordForm: function(idRecord, fieldName, index)	{
		var idForm = 'foreignrecord-' + idRecord + '-' + fieldName + '-' + index + '-formhtml';

		if( Todoyu.exists(idForm) ) {
			$(idForm).toggle();
		}
	},



	/**
	 * Remove sub form
	 *
	 * @param	Integer		idRecord
	 * @param	String		fieldName
	 * @param	Integer		index
	 */
	removeRecord: function(idRecord, fieldName, index) {
		var idElement	= 'foreignrecord-' + idRecord + '-' + fieldName + '-' + index;

		$(idElement).remove();
	},



	/**
	 * Add a new record
	 *
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
				'action':		'addSubform',
				'form': 	formName,
				'field':	fieldName,
				'record':	idRecord,
				'index': 	index
			},
			'onComplete': this.onRecordAdded.bind(this, container, idRecord, formName, fieldName, index)
		};

		Todoyu.send(url, options);
	},



	/**
	 * Callback when new record added
	 *
	 * @param	Integer		idRecord
	 * @param	String		formName
	 * @param	String		fieldName
	 * @param	String		index
	 * @param	String		response
	 */
	onRecordAdded: function(container, idRecord, formName, fieldName, index, response) {
		$(container).insert({'top':response.responseText});

		this.toggleRecordForm(idRecord, fieldName, index);
		this.focusFirstRecordField(idRecord, fieldName, index);
	},



	/**
	 * Focus first record field
	 *
	 * @param	Integer		idRecord
	 * @param	String		fieldName
	 * @param	Integer		index
	 */
	focusFirstRecordField: function(idRecord, fieldName, index) {
		var formHTML= $('foreignrecord-' + idRecord + '-' + fieldName + '-' + index + '-formhtml');
		var field	= formHTML.select('input[type!=hidden]', 'select', 'textarea').first();

		if( field )  {
			field.focus();
		}
	},



	/**
	 *
	 * @param	Array	fieldNames
	 */
	expandForeignRecords: function(fieldNames) {
		fieldNames = fieldNames || [];

		fieldNames.each(function(fieldName){
			var parentField = $$('form div.fieldname' + fieldName.capitalize()).first();
			if ( parentField ) {
				var subForms	= parentField.select('div.databaseRelation div.databaseRelationFormhtml');

				subForms.invoke('show');
			}
		});
	},



	/**
	 * Expand all foreign records in a form
	 *
	 * @param	Integer		idRecord
	 * @param	Integer		idField
	 * @param	String		extension
	 * @param	String		controller
	 * @param	String		action
	 * @param	Integer		height
	 * @param	Integer		width
	 * @param	String		title
	 * @return	String
	 */
	openWizard: function(idRecord, idField, extension, controller, action, height, width, title)	{
		var url		= Todoyu.getUrl(extension,	controller);
		var options	= {
			'parameters': {
				'action':	action,
				'idRecord':	idRecord,
				'idField':	idField
			}
		};
		var idPopup	= 'popup-' + idField;
		
		title	= title ? title : 'Form Wizard';
		width	= width > 0 ? width : 480;
		height	= height > 0 ? height : 300;

		return Todoyu.Popup.openWindow(idPopup, title, width, url, options);
	}

};