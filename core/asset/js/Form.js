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
	 * @param	{String}  formID
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
			parameters: {
				action:		'addSubform',
				'form': 	formName,
				'field':	fieldName,
				'record':	idRecord,
				'index': 	index
			},
			onComplete: this.onRecordAdded.bind(this, container, idRecord, formName, fieldName, index)
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
	 * @param	{String}	method
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
	 * Open popup for create wizard
	 *
	 * @method	openCreateWizard
	 * @param	{String}			fieldName
	 * @param	{Object}			config
	 * @return	{Todoyu.Popup}
	 */
	openCreateWizard: function(fieldName, config) {
		var url		= Todoyu.getUrl(config.ext,	config.controller);
		var options	= {
			parameters: {
				action:	config.action,
				record:	config.record,
				field:	fieldName
			}
		};
		var idPopup	= 'popup-' + fieldName;

		var title	= config.title ? config.title : 'Form Wizard';
		var width	= config.width ? config.width : 662;

		return Todoyu.Popups.open(idPopup, title, width, url, options);
	},



	/**
	 * Live-validation and correction of numeric value input field. Removes/corrects illegal/ambiguous characters
	 *
	 * @method	assistNumericInput
	 * @param	{Element}			field
	 * @param	{Boolean}			allowFloat
	 */
	assistNumericInput: function(field, allowFloat) {
		allowFloat		= allowFloat || false;
		var value		= $F(field);
		var allowedChars= '0123456789.-';

		if(allowFloat) {
			value	= value.replace(',', '.');

			if( value.indexOf('.') !== value.lastIndexOf('.') ) {
				value = value.substring(0, value.lastIndexOf('.'));
			}
		}

		if( ! Todoyu.Validate.isOnlyAllowedChars(value, allowedChars) ) {
				// Filter-out any illegal characters
			var whitelist	= (allowFloat) ? /([0-9]|\.|\-)/g : /([0-9])/g;
			var illegalChars	= value.replace(whitelist, '');

			for( var i = 0; i <= illegalChars.length; i++ ) {
				value	= value.replace(illegalChars[i], '');
			}
		}

		$(field).value = value;
	},



	/**
	 * Add an iFrame to the document body
	 *
	 * @method	addIFrame
	 * @param	{String}	key			Identifier
	 * @return	{Element}				IFrame element
	 */
	addIFrame: function(key) {
		var idIFrame= 'upload-iframe-' + key;

		if( ! Todoyu.exists(idIFrame) ) {
			var iFrame	= new Element('iframe', {
				name:		'upload-iframe-' + key,
				id:			'upload-iframe-' + key,
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
	 * Open URL in new iFrame with given key
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
	 * Submit a form to an iFrame
	 *
	 * @method	submitToIFrame
	 * @param	{Element|String}	form
	 * @param	{String}			iFrameName
	 */
	submitToIFrame: function(form, iFrameName) {
		var iFrame	= this.addIFrame(iFrameName);

		$(form).writeAttribute('target', iFrame.name);

		$(form).submit();
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
	 * @param	{String}	value
	 * @param	{String}	baseID
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
	},



	/**
	 * Enable save and cancel buttons in form
	 *
	 * @method	enableSaveButtons
	 * @param	{Element}	form
	 */
	enableSaveButtons: function(form) {
		$(form).down('fieldset.buttons').select('button').each(function(button){
			Form.Element.enable(button);
		});
	},



	/**
	 * Set selected options of a select element
	 *
	 * @method	selectOptions
	 * @param	{Element}	element
	 * @param	{Array}		selection
	 */
	selectOptions: function(element, selection) {
		element		= $(element);
		selection	= selection.constructor === Array ? selection : [selection];

		element.selectedIndex = -1;

		$A(element.options).each(function(selection, option){
			if( selection.include(option.value) ) {
				option.selected = true;
			}
		}.bind(this, selection));
	},



	/**
	 * Get selected item pairs from a multi select
	 *
	 * @method	getSelectedItems
	 * @param	{Element}	element
	 * @return	{Object}	Format: value:text
	 */
	getSelectedItems: function(element) {
		var values	= $F(element);
		var options	= {};
		var items	= {};

		$(element).select('option').each(function(option){
			options[option.value] = option.innerHTML;
		});

		values.each(function(value){
			items[value] = options[value];
		});

		return items;
	},



	/**
	 * Custom handler for keyUp event inside textarea field - auto resize field by content
	 *
	 * @method	onKeyupInTextArea
	 * @param	{String}			idElement	ID of textarea field element
	 */
	onKeyupInTextArea: function(idElement) {
		var element			= $(idElement);
		var minHeight		= element.rows * 18;
		var elementHeight	= element.getHeight();
		var amountLines		= Todoyu.Helper.countLines($F(idElement));

			// Grow if necessary
		if( elementHeight < (element.scrollHeight + 4) ) {
			element.style.overflow	= "hidden";

			new Effect.Morph(idElement, {
				style:			'height:' + (element.getHeight() + 18) + 'px',
				duration:		0.1,
				afterFinish:	function(event) {
					if( event.element.getHeight() < (event.element.scrollHeight + 4) ) {
						Todoyu.Form.onKeyupInTextArea(event.element.id);
					}
					event.element.scrollTop	= event.element.scrollHeight;
				}
			});
			// Shrink if necessary
		} else if( elementHeight > minHeight && (elementHeight / 18) > amountLines ) {
			new Effect.Morph(idElement, {
				style:		'height:' + (element.getHeight() - 18) + 'px',
				duration:	0.1
			});
		}
	}

};