/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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

/**
 * @module	Core
 */

/**
 * General form helper functions
 *
 * @class		Form
 * @namespace	Todoyu
 */
Todoyu.Form = {

	/**
	 * Index counter for sub forms. Starts at 100 to prevent colisions
	 * @property	subFormIndex
	 * @type		Number
	 */
	subFormIndex: 100,


	/**
	 * Initialize form display: expand invalid foreign records, focus first field
	 *
	 * @method	onFormDisplay
	 * @param   {String}  formID
	 */
	onFormDisplay: function(formID) {
		if( Todoyu.exists(formID) ) {
			this.expandInvalidForeignRecords(formID);
//			this.focusFirstFormField(formID);
		}
	},



	/**
	 * Get the next index for a sub form to prevent name collisions
	 *
	 * @method	getNextIndex
	 * @return	{Number}
	 */
	getNextIndex: function() {
		return this.subFormIndex++;
	},



	/**
	 * Toggle sub form
	 *
	 * @method	toggleRecordForm
	 * @param	{Number}		idRecord
	 * @param	{String}		fieldName
	 * @param	{Number}		index
	 */
	toggleRecordForm: function(idRecord, fieldName, index) {
		var baseName	= 'foreignrecord-' + idRecord + '-' + fieldName + '-' + index;
		var formHtml	= baseName + '-formhtml';
		var trigger		= baseName + '-trigger';

		if( Todoyu.exists(trigger) ) {
			$(formHtml).toggle();

			$(trigger).down('span')[$(formHtml).visible() ? 'addClassName' : 'removeClassName']('expanded');
		}
	},



	/**
	 * Remove sub form
	 *
	 * @method	removeRecord
	 * @param	{Number}		idRecord
	 * @param	{String}		fieldName
	 * @param	{Number}		index
	 */
	removeRecord: function(idRecord, fieldName, index) {
		var idElement	= 'foreignrecord-' + idRecord + '-' + fieldName + '-' + index;

		$(idElement).remove();
	},



	/**
	 * Add a new record
	 *
	 * @method	addRecord
	 * @param	{Number}		idRecord
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
	 * @method	onRecordAdded
	 * @param	{Number}		idRecord
	 * @param	{String}		formName
	 * @param	{String}		fieldName
	 * @param	{String}		index
	 * @param	{Ajax.Response}	response
	 */
	onRecordAdded: function(container, idRecord, formName, fieldName, index, response) {
		$(container).insert({'top':response.responseText});

		this.toggleRecordForm(idRecord, fieldName, index);
		this.focusFirstRecordField(idRecord, fieldName, index);
	},



	/**
	 * Focus first record field
	 *
	 * @method	focusFirstRecordField
	 * @param	{Number}		idRecord
	 * @param	{String}		fieldName
	 * @param	{Number}		index
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
	 * @method	focusFirstFormField
	 * @param	{String}	formID
	 */
	focusFirstFormField: function(formID) {
		if( $(formID) ) {
			var field = $(formID).select('input[type!=hidden]', 'select', 'textarea').first();

			if( field ) {
				if( field.visible() ) {
					field.focus();
				}
			}
		}
	},



	/**
	 * Expand / collapse foreign record fields
	 *
	 * @method	toggleForeignRecords
	 * @param	{Array}		fieldNames
	 */
	toggleForeignRecords: function(fieldNames) {
		fieldNames = fieldNames || [];

		var method	= this.isAnyFieldHidden(fieldNames) ? 'show' : 'hide';

		this.invokeForeignRecords(fieldNames, method);
	},



	/**
	 * Check whether any of the given fields is currently hidden
	 *
	 * @method	isAnyFieldHidden
	 * @param	{Array}	fieldNames
	 * @return	{Boolean}
	 */
	isAnyFieldHidden: function(fieldNames) {
		if( fieldNames.length > 0 ) {
			var fieldName = fieldNames[0];
			var parentField = $$('form div.fieldname' + fieldName.replace(/_/g,'').capitalize()).first();
			if( parentField !== undefined ) {
				var subForms	= parentField.select('div.databaseRelation div.databaseRelationFormhtml');

				var anyHidden	= subForms.any(function(item){
					return item.style.display == 'none';
				}.bind(this));
			}
		}

		return anyHidden;
	},



	/**
	 * Expand fields of foreign records
	 *
	 * @method	expandForeignRecords
	 * @param	{Array}		fieldNames
	 */
	expandForeignRecords: function(fieldNames) {
		this.invokeForeignRecords(fieldNames, 'show');
	},



	/**
	 * Invoke given method (e.g. 'show', 'hide') on all fields inside the parent of the given field names
	 *
	 * @method	invokeForeignRecords
	 * @param	{Array}		fieldNames
	 */
	invokeForeignRecords: function(fieldNames, method) {
		fieldNames	= fieldNames || [];
		method		= method || 'show';

		fieldNames.each(function(fieldName){
			var parentField = $$('form div.fieldname' + fieldName.replace(/_/g,'').capitalize()).first();
			if( parentField !== undefined ) {
				var subForms	= parentField.select('div.databaseRelation div.databaseRelationFormhtml');

				subForms.invoke(method);
			}
		});
	},



	/**
	 * Show formHTML of invalid form elements in foreign records
	 *
	 * @method	expandInvalidForeignRecords
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
	 * @method	openWizard
	 * @param	{Number}		idRecord
	 * @param	{Number}		idField
	 * @param	{String}		extension
	 * @param	{String}		controller
	 * @param	{String}		action
	 * @param	{Number}		height
	 * @param	{Number}		width
	 * @param	{String}		title
	 * @return	{String}
	 */
	openWizard: function(idRecord, idField, extension, controller, action, height, width, title) {
		var url		= Todoyu.getUrl(extension,	controller);
		var options	= {
			'parameters': {
				'action':	action,
				'idRecord':	idRecord,
				'idField':	idField
			}
		};
		var idPopup	= 'popup-' + idField;

		title	= ( title ) ? title : 'Form Wizard';
		width	= ( width > 0 ) ? width : 480;
		height	= ( height > 0 ) ? height : 300;

		return Todoyu.Popup.openWindow(idPopup, title, width, url, options);
	},



	/**
	 * Live-validation and correction of float value input field: on keyUp event replace ',' by '.'
	 *
	 * @method	assistFloatInput
	 * @param	{Element}	field
	 */
	assistFloatInput: function(field) {
		var val	= $F(field).replace(',', '.');

		$(field).value = val;
	},



	/**
	 * Add an iFrame to the document body
	 *
	 * @method	addIFrame
	 * @param	{String}		key			Identifier
	 * @return	{Element}					IFrame element
	 */
	addIFrame: function(key) {
		var idIFrame= 'upload-iframe-' + key;

		if( ! Todoyu.exists(idIFrame) ) {
			var iFrame	= new Element('iframe', {
				'name':		'upload-iframe-' + key,
				'id':		'upload-iframe-' + key,
				'class':	'uploadIframe'
			});

			iFrame.hide();
			$(document.body).insert(iFrame);
		}

		return $(idIFrame)
	},



	/**
	 * Get a hidden iFrame
	 *
	 * @method	getIFrame
	 * @param	{String}		key
	 */
	getIFrame: function(key) {
		return $('upload-iframe-' + key);
	},



	/**
	 * Open an iframe URL
	 *
	 * @method	openIFrame
	 * @param	{String}	key
	 * @param	{String}	url
	 */
	openIFrame: function(key, url) {
		this.addIFrame(key);
		this.getIFrame(key).contentWindow.location.href = url;
	},



	/**
	 * Remove a hidden iFrame
	 *
	 * @method	removeIFrame
	 * @param	{String}	key
	 */
	removeIFrame: function(key) {
		var iFrame	= this.getIFrame(key);

		if( iFrame ) {
			iFrame.remove();
		}
	},



	/**
	 * Sets the value of the chosen icon to the hidden field
	 *
	 * @method	setIconSelectorValue
	 */
	setIconSelectorValue: function(value, baseID) {
		$(baseID).value = value;

		var selectedOld = $(baseID + '-selector').select('.selected').first();

		if(selectedOld) {
			selectedOld.toggleClassName('selected');
		}

		$(baseID + '-listItem-' + value).toggleClassName('selected');
	},



	/**
	 * Disable save and cancel buttons in form
	 *
	 * @method	disableSaveButtons
	 * @param	{Element}	form
	 */
	disableSaveButtons: function(form) {
		$(form).down('fieldset.buttons').select('button').each(function(button){
			Form.Element.disable(button);
		});
	}

};