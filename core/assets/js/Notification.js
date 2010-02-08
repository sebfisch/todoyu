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

Todoyu.Notification = {
	
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
	 *	@param	String		type
	 *	@param	String		message
	 *	@param	Integer		countdown		Seconds for automatic closing. 0 = sticky (no close)
	 */
	add: function(type, message, countdown) {
		this.init();

		countdown	= Object.isUndefined(countdown) ? this.defaultCountdown : countdown;
		var id		= this.id++;

		var data	= {
			'id':			id,
			'type':			type,
			'message':		message,
			'countdown':	countdown == 0 ? '' : countdown		
		};

		var note	= this.template.evaluate(data);

		this.appendNote(id, note);

			// Only start countdown if not sticky
		if( countdown != 0 ) {
			this.countDown.bind(this).delay(1, id);
		}
	},



	/**
	 * Remove note
	 */
	remove: function(id) {
		$('ntification-note-' + id).remove();
	},



	/**
	 * @todo	comment
	 */
	addInfo: function(message, countdown) {
		this.add('info', message, countdown);
	},



	/**
	 * @todo	comment
	 */
	addError: function(message, countdown) {
		this.add('error', message, countdown);
	},



	/**
	 * @todo	comment
	 */
	addSuccess: function(message, countdown) {
		this.add('success', message, countdown);
	},



	/**
	 * Close when clicking in the close button
	 * 
	 *	@param	DomElement		closeButton
	 */
	close: function(closeButton) {
		var idNote = $(closeButton).up('div.note').id.split('-').last();

		this.closeNote(idNote);
	},



	/**
	 * Close note by ID
	 * @todo	watch out for a bugfix of scriptaculous' malfunctioning 'afterFinish' callback
	 * 
	 *	@param	Integer		id
	 */
	closeNote: function(id) {
		var duration	= 0.3;

		new Effect.Move('notification-note-' + id, {
			'y':		-80,
			'mode':		'absolute'
		});

		this.onNoteClosed.bind(this).delay(duration + 0.1, id);
	},



	fadeAllNotes: function() {
		$$('.note').each(function(note){
			Effect.Fade(note.id, {'duration': 0.3});	
		}.bind(this));
	},



	closeFirstNote: function() {
		var notes= $$('.note');
		if ( notes.length > 0 ) {
		var openNotificationID= $$('.note')[0].id.replace('notification-note-','');
			this.closeNote( openNotificationID );
		}
	},



	/**
	 * Handler being evoked when a note is closed (fade-out finished)
	 * 
	 * @param	Integer		id
	 */
	onNoteClosed: function(id) {
		$('notification-note-' + id).remove();
	},



	/**
	 * Init template
	 */
	init: function() {
		if( this.template === null ) {
			this.template = new Template('<div class="note #{type}" id="notification-note-#{id}"><table width="100%"><tr><td class="icon">&nbsp;</td><td class="message">#{message}</td><td class="countdown" align="center">#{countdown}</td><td class="close" onclick="Todoyu.Notification.close(this)">&nbsp;</td></tr></table></div>');
		}
	},



	/**
	 * Append new note
	 * 
	 *	@param	Integer		id
	 *	@param	String		code
	 */
	appendNote: function(id, code) {
		$('notes').insert({'top':code});
		
		var id	= $$('.note').last().id;

		$(id).hide();
		$(id).appear({'duration': 0.5});
	},



	/**
	 * 
	 *	@param	Integer		id
	 */
	countDown: function(id) {
		var countBox= $('notification-note-' + id).down('.countdown');	
		var current	= parseInt(countBox.innerHTML, 10);

		if( current === 0 ) {
			this.closeNote(id);
		} else {
			countBox.update(current-1);
			this.countDown.bind(this).delay(1, id);
		}
	},



	idElement:	'notification',

	/**
	 * Show notification
	 *
	 *	@param	String		notificationHTML
	 *	@param	String		elementToAddAfter
	 */
	notify:	function(notificationHTML, elementToAddAfter) {
		var notification = 'notification';

		if( Todoyu.exists(this.idElement) ) {
			$(this.idElement).remove();
		}

		$(elementToAddAfter).insert({'after': notificationHTML});
		Effect.Appear(this.idElement);
	},



	/**
	 *	Hide notification
	 */
	hideNotification: function() {
		Effect.Fade(this.idElement);
	},



	/**
	 * Get amount of notes currently being shown
	 * 
	 * @return	Integer 
	 */
	getAmountOpenNotes: function() {
		return $$('.note').size();
	}

};