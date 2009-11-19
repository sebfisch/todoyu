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
	 * @param	String		type
	 * @param	String		message
	 * @param	Integer		countdown		Seconds for automatic closing. 0 = sticky (no close)
	 */
	add: function(type, message, countdown) {
		this.init();
		
		countdown	= Object.isUndefined(countdown) ? this.defaultCountdown : countdown;
		var id		= this.id++;
		
		var data	= {
			'id': id,
			'type': type,
			'message': message,
			'countdown': countdown == 0 ? '' : countdown		
		};
		
		var note	= this.template.evaluate(data);
		
		this.appendNote(id, note);
		
			// Only start countdown if not sticky
		if( countdown != 0 ) {
			this.countDown.bind(this).delay(1, id);
		}		
	},
	
	addInfo: function(message, countdown) {
		this.add('info', message, countdown);
	},
	
	addError: function(message, countdown) {
		this.add('error', message, countdown);
	},
	
	addSuccess: function(message, countdown) {
		this.add('success', message, countdown);
	},
	
	/**
	 * Close when clicking in the close button
	 * @param	DomElement		closeButton
	 */
	close: function(closeButton) {
		var idNote = $(closeButton).up('div.note').id.split('-').last();
		
		this.closeNote(idNote);
	},
	
	
	
	/**
	 * Close note by ID
	 * @param	Integer		id
	 */
	closeNote: function(id) {
		$('notification-note-' + id).fade({
			'duration': 0.7
		});
	},
	
	
	
	/**
	 * Init template
	 */
	init: function() {
		if( this.template === null ) {
			//this.template = new Template( '<div class="note #{type}" id="notification-note-#{id}"><div class="icon"></div><div class="message">#{message}</div><div class="countdown">#{countdown}</div><div class="close" onclick="Todoyu.Notification.close(this)"></div></div><br clear="all" />');
			this.template = new Template( '<div class="note #{type}" id="notification-note-#{id}"><table><tr><td class="icon">&nbsp;</td><td class="message">#{message}</td><td class="countdown" align="center">#{countdown}</td><td class="close" onclick="Todoyu.Notification.close(this)">&nbsp;</td></tr></table></div>');
		}
	},
	
	
	
	/**
	 * Append new note
	 * @param	Integer		id
	 * @param	String		code
	 */
	appendNote: function(id, code) {
		$('notes').insert({'top':code});
	},
	
	
	
	/**
	 * 
	 * @param	Integer		id
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
	

	
	
	

	idElement: 'notification',

	/**
	 *	Show notification
	 *
	 *	@param	unknown_type	notificationHTML
	 *	@param	unknown_type	elementToAddAfter
	 */
	notify:	function(notificationHTML, elementToAddAfter) {
		var notification = 'notification';

		if( Todoyu.exists(this.idElement) ) {
			$(this.idElement).remove();
		}

		$(elementToAddAfter).insert({'after': notificationHTML});

		Effect.Appear(this.idElement);

		//this.hideNotification.bind(this).delay(0.85);
	},



	/**
	 *	Hide notification
	 *
	 */
	hideNotification: function() {
		Effect.Fade(this.idElement);
	}

};