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

Todoyu.Form = {

	subFormIndex: 100,

	/**
	 * Initialize form display: expand invalid foreign records, focus first field
	 *
	 * @param   String  formID
	 */
	onFormDisplay: function(formID) {
		if( Todoyu.exists(formID) ) {
			this.expandInvalidForeignRecords(formID);
			this.focusFirstFormField(formID);
		}
	},



	/**
	 * @todo    comment
	 */
	getNextIndex: function() {
		return this.subFormIndex++;
	},



	/**
	 * Toggle sub form
	 *
	 * @param	{Integer}		idRecord
	 * @param	{String}		fieldName
	 * @param	{Integer}		index
	 */
	toggleRecordForm: function(idRecord, fieldName, index)	{
		var baseName	= 'foreignrecord-' + idRecord + '-' + fieldName + '-' + index;
		var formHtml	= baseName + '-formhtml';
		var trigger		= baseName + '-trigger';
		
		if( Todoyu.exists(trigger) ) {
			$(formHtml).toggle();
			
			$(trigger).down('span')[$(formHtml).visible() ? 'addClassName' : 'removeClassName']('expanded');
		}
		
		/*
		var idForm = 'foreignrecord-' + idRecord + '-' + fieldName + '-' + index + '-formhtml';

		if( Todoyu.exists(idForm) ) {
			$(idForm).toggle();
		}
		*/
	},



	/**
	 * Remove sub form
	 *
	 * @param	{Integer}		idRecord
	 * @param	{String}		fieldName
	 * @param	{Integer}		index
	 */
	removeRecord: function(idRecord, fieldName, index) {
		var idElement	= 'foreignrecord-' + idRecord + '-' + fieldName + '-' + index;

		$(idElement).remove();
	},



	/**
	 * Add a new record
	 *
	 * @param	{Integer}		idRecord
	 * @param	{String}		formName
	 * @param	{String}		fieldName
	 * @param	{String}		updateExt
	 * @param	{String}		updateController
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
	 * @param	{Integer}		idRecord
	 * @param	{String}		formName
	 * @param	{String}		fieldName
	 * @param	{String}		index
	 * @param	{String}		response
	 */
	onRecordAdded: function(container, idRecord, formName, fieldName, index, response) {
		$(container).insert({'top':response.responseText});

		this.toggleRecordForm(idRecord, fieldName, index);
		this.focusFirstRecordField(idRecord, fieldName, index);
	},



	/**
	 * Focus first record field
	 *
	 * @param	{Integer}		idRecord
	 * @param	{String}		fieldName
	 * @param	{Integer}		index
	 */
	focusFirstRecordField: function(idRecord, fieldName, index) {
		var formHTML= $('foreignrecord-' + idRecord + '-' + fieldName + '-' + index + '-formhtml');
		var field	= formHTML.select('input[type!=hidden]', 'select', 'textarea').first();
		
		if( field )  {
			field.focus();
		}
	},
	
	
	
	/**
	 * Focus first form field
	 * 
	 * @param	{String}	formID
	 */
	focusFirstFormField: function(formID)	{
		if( $(formID) )	{
			var field = $(formID).select('input[type!=hidden]', 'select', 'textarea').first();
			
			if( field )	{
				field.focus();
			}
		}
	},



	/**
	 * @todo    comment
	 *
	 * @param	{Array}	fieldNames
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
	 * Show formHTML of invalid form elements in foreign records
	 * 
	 * @param	{String}		formID
	 */
	expandInvalidForeignRecords: function(formID) {			
		$(formID).select('div.error').each(function(errorField){
			var formHTML = $(errorField).up('div.databaseRelationFormhtml');
			if( formHTML ) {
				formHTML.show();
			}
		});
	},



	/**
	 * Expand all foreign records in a form
	 *
	 * @param	{Integer}		idRecord
	 * @param	{Integer}		idField
	 * @param	{String}		extension
	 * @param	{String}		controller
	 * @param	{String}		action
	 * @param	{Integer}		height
	 * @param	{Integer}		width
	 * @param	{String}		title
	 * @return	{String}
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
	},



	/**
	 * Add an iFrame to the document body
	 *
	 * @param	{String}		key			Identifier
	 * @return	{Element}		IFrame element
	 */
	addIFrame: function(key) {
		var idIFrame= 'upload-iframe-' + key;

		if( ! Todoyu.exists(idIFrame) ) {
			var iFrame	= new Element('iframe', {
				'name':		'upload-iframe-' + key,
				'id':		'upload-iframe-' + key,
				'class':	'upload-iframe'
			});

			iFrame.hide();

			$(document.body).insert(iFrame);
		}

		return $(idIFrame)
	},



	/**
	 * Get a hidden iFrame
	 *
	 * @param	{String}		key
	 */
	getIFrame: function(key) {
		return $('upload-iframe-' + key);
	},



	/**
	 * Remove a hidden iFrame
	 *
	 * @param	{String}		key
	 */
	removeIFrame: function(key) {
		var iFrame	= this.getIFrame(key);

		if( iFrame ) {
			iFrame.remove();
		}
	}

};