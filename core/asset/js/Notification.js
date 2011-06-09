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
 * Notification
 *
 * @class		Notification
 * @namespace	Todoyu
 */

Todoyu.Notification = {

	/**
	 * @property	SUCCESS
	 * @type		String
	 */
	SUCCESS:	'success',

	/**
	 * @property	ERROR
	 * @type		String
	 */
	ERROR:		'error',

	/**
	 * @property	INFO
	 * @type		String
	 */
	INFO:		'info',

	/**
	 * Close delay
	 * @property	closeDelay
	 * @type		Number
	 */
	closeDelay: 3,

	/**
	 * Close delay for sticky messages
	 *
	 * @property	closeDelaySticky
	 * @type		Number
	 */
	closeDelaySticky: 20,

	/**
	 * Template object
	 * @property	template
	 * @type		Template
	 */
	template: null,

	/**
	 * Current id for note, incremented
	 * @property	id
	 * @type		Number
	 */
	id: 1,



	/**
	 * Add new notification
	 *
	 * @method	notify
	 * @param	{String}		type
	 * @param	{String}		message
	 * @param	{Boolean}		sticky			Don't hide note (error is by default sticky)
	 */
	notify: function(type, message, sticky) {
		this.loadTemplate();

		var id		= this.id++;

		var data	= {
			'id':			id,
			'type':			type,
			'message':		message
		};

		var note	= this.template.evaluate(data);
		var delay	= sticky ? this.closeDelaySticky : this.closeDelay;

		this.appendNote(id, note);
		this.closeNote.bind(this, id).delay(delay);
	},



	/**
	 * Init notification HTML template
	 *
	 * @method	loadTemplate
	 */
	loadTemplate: function() {
		if( this.template === null ) {
			this.template = new Template(
				'<div class="note #{type}" id="notification-note-#{id}">'
				+	'<table width="100%"><tr>'
				+		'<td class="icon">&nbsp;</td>'
				+		'<td class="message">#{message}</td>'
				+		'<td class="countdown" align="center">#{countdown}</td>'
				+	'</tr></table></div>'
			);
		}
	},



	/**
	 * Remove notification from DOM
	 *
	 * @method	remove
	 */
	remove: function(id) {
		$('ntification-note-' + id).remove();
	},



	/**
	 * Shortcut to show info notification
	 *
	 * @method	notifyInfo
	 * @param	{String}		message
	 * @param	{Boolean}		sticky
	 */
	notifyInfo: function(message, sticky) {
		this.notify(this.INFO, message, sticky);
	},



	/**
	 * Shortcut to show error notification
	 *
	 * @method	notifyError
	 * @param	{String}		message
	 */
	notifyError: function(message) {
		this.notify(this.ERROR, message, true);
	},



	/**
	 * Shortcut to show success notification
	 *
	 * @method	notifySuccess
	 * @param	{String}		message
	 * @param	{Boolean}		sticky
	 */
	notifySuccess: function(message, sticky) {
		this.notify(this.SUCCESS, message, sticky);
	},



	/**
	 * Close when clicking in the close button
	 *
	 * @method	close
	 * @param	{Element}		closeButton
	 */
	close: function(closeButton) {
		var idNote = $(closeButton).up('div.note').id.split('-').last();

		this.closeNote(idNote);
	},



	/**
	 * Close note by ID
	 * @todo	watch out for a bugfix of scriptaculous' malfunctioning 'afterFinish' callback
	 *
	 * @method	closeNote
	 * @param	{Number}		id
	 */
	closeNote: function(id) {
		var duration	= 0.3;

		new Effect.Move('notification-note-' + id, {
			'y':		-80,
			'mode':		'absolute'
		});

		this.onNoteClosed.bind(this).delay(duration + 0.1, id);
	},



	/**
	 * Fade-out all notifications
	 *
	 * @method	fadeAllNotes
	 */
	fadeAllNotes: function() {
		$$('.note').each(function(note){
			Effect.Fade(note.id, {'duration': 0.3});
		}.bind(this));
	},



	/**
	 * Close first (topmost) of the currently displayed notifications
	 *
	 * @method	closeFirstNote
	 */
	closeFirstNote: function() {
		var notes = $('notes').select('div.note');

		if( notes.size() > 0 ) {
			var idNote = notes.first().id.split('-').last();
			this.closeNote(idNote);
		}
	},



	/**
	 * Handler being evoked when a note is closed (fade-out finished)
	 *
	 * @method	onNoteClosed
	 * @param	{Number}		id
	 */
	onNoteClosed: function(id) {
		if( $('notification-note-' + id) ) {
			$('notification-note-' + id).remove();
		}
	},



	/**
	 * Append new note
	 *
	 * @method	appendNote
	 * @param	{Number}		idNote
	 * @param	{String}		code
	 */
	appendNote: function(idNote, code) {
		$('notes').insert({
			'top':code
		});

		var htmlID	= 'notification-note-' + idNote;

			// Observe mouse over of note
		$(htmlID).on('mouseover', this.onMouseOver.bindAsEventListener(this, idNote));
			// Hide the note before appearing
		$(htmlID).hide();
			// Appear with effect
		$(htmlID).appear({
			'duration': 0.5
		});
	},



	/**
	 * Handler for note mouse over
	 *
	 * @method	onMouseOver
	 * @param	{Event}		event
	 * @param	{Number}	idNote
	 */
	onMouseOver: function(event, idNote) {
		this.closeNote(idNote);
	},



	/**
	 * @method	countDown
	 * @param	{Number}		id
	 */
	countDown: function(id) {
		if( $('notification-note-' + id) ) {
			var countBox= $('notification-note-' + id).down('.countdown');
			var current	= parseInt(countBox.innerHTML, 10);

			if( current === 0 ) {
				this.closeNote(id);
			} else {
				countBox.update(current-1);
				this.countDown.bind(this).delay(1, id);
			}
		}
	},



	/**
	 * Check whether the todoyu specific 'note' HTTP header was received
	 *
	 * @method	checkNoteHeader
	 * @param	{Ajax.Response}		response
	 */
	checkNoteHeader: function(response) {
		if( response.hasTodoyuHeader('note') ) {
			var info	= response.getTodoyuHeader('note').evalJSON();

			this.notify(info.type, info.message);
		}
	}

};