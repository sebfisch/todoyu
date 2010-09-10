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
 * Notification
 *
 * @namespace	Todoyu.Notification
 */

Todoyu.Notification = {

	SUCCESS:	'success',

	ERROR:		'error',

	INFO:		'info',



	/**
	 * Default countdown if non set
	 */
	defaultCountdown: 3,

	/**
	 * Template object
	 */
	template: null,

	/**
	 * Current id for note, incremented
	 */
	id: 1,



	/**
	 * Add new notification
	 * 
	 * @param	{String}		type
	 * @param	{String}		message
	 * @param	{Number}		countdown		Seconds for automatic closing. 0 = sticky (no close)
	 */
	notify: function(type, message, countdown) {
		this.loadTemplate();

		countdown	= countdown === undefined ? this.defaultCountdown : Todoyu.Helper.intval(countdown);
		var id		= this.id++;

		var data	= {
			'id':			id,
			'type':			type,
			'message':		message,
			'countdown':	countdown === 0 ? '' : countdown
		};

		var note	= this.template.evaluate(data);

		this.appendNote(id, note);

			// Only start countdown if not sticky
		if( countdown !== 0 ) {
			this.countDown.bind(this).delay(1, id);
		}
	},



	/**
	 * Init template
	 */
	loadTemplate: function() {
		if( this.template === null ) {
			this.template = new Template('<div class="note #{type}" id="notification-note-#{id}"><table width="100%"><tr><td class="icon">&nbsp;</td><td class="message">#{message}</td><td class="countdown" align="center">#{countdown}</td></tr></table></div>');
		}
	},



	/**
	 * Remove note
	 */
	remove: function(id) {
		$('ntification-note-' + id).remove();
	},



	/**
	 * Shortcut to show info notification
	 * 
	 * @param	{String}		message
	 * @param	{Number}		countdown		Seconds for automatic closing. 0 = sticky (no close)
	 */
	notifyInfo: function(message, countdown) {
		this.notify(this.INFO, message, countdown);
	},



	/**
	 * Shortcut to show error notification
	 * 
	 * @param	{String}		message
	 * @param	{Number}		countdown		Seconds for automatic closing. 0 = sticky (no close)
	 */
	notifyError: function(message, countdown) {
		this.notify(this.ERROR, message, countdown);
	},



	/**
	 * Shortcut to show success notification
	 * 
	 * @param	{String}		message
	 * @param	{Number}		countdown		Seconds for automatic closing. 0 = sticky (no close)
	 */
	notifySuccess: function(message, countdown) {
		this.notify(this.SUCCESS, message, countdown);
	},



	/**
	 * Close when clicking in the close button
	 * 
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
	 *	Fade-out all notifications
	 */
	fadeAllNotes: function() {
		$$('.note').each(function(note){
			Effect.Fade(note.id, {'duration': 0.3});
		}.bind(this));
	},



	/**
	 * Close first (topmost) of the currently displayed notifications
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
	 * @param	{Number}		idNote
	 * @param	{String}		code
	 */
	appendNote: function(idNote, code) {
		$('notes').insert({
			'top':code
		});

		var htmlID	= 'notification-note-' + idNote;

			// Observe mouse over of note
		$(htmlID).observe('mouseover', this.onMouseOver.bindAsEventListener(this, idNote));
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
	 * @param	{Event}		event
	 * @param	{Number}	idNote
	 */
	onMouseOver: function(event, idNote) {
		this.closeNote(idNote);
	},



	/**
	 * 
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
	 * @param	{Ajax.Response}		response
	 */
	checkNoteHeader: function(response) {
		if( response.hasTodoyuHeader('note') ) {
			var info	= response.getTodoyuHeader('note').evalJSON();

			this.notify(info.type, info.message);
		}
	}

};