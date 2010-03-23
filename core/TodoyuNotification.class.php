<?php
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

/**
 * Send notification to browser
 * Currently, the number of notes per response is limited to one (1)!
 *
 * @package		Todoyu
 * @subpackage	Core
 * @see 		core/assets/js/Notification.js
 */
class TodoyuNotification {

	/**
	 * Send notification over HTTP header
	 *
	 * @param	String		$type
	 * @param	String		$message
	 * @param	Integer		$countdown
	 */
	private static function notify($type, $message, $countdown = 3) {
		$info	= array(
			'type'		=> $type,
			'message'	=> TodoyuDiv::getLabel($message),
			'countdown'	=> $countdown
		);

		TodoyuHeader::sendTodoyuHeader('note', json_encode($info));
	}



	/**
	 * Send success notification
	 *
	 * @param	String		$message
	 */
	public static function notifySuccess($message) {
		self::notify('success', $message);
	}



	/**
	 * Send error notification
	 *
	 * @param	String		$message
	 */
	public static function notifyError($message) {
		self::notify('error', $message);
	}



	/**
	 * Send info notification
	 *
	 * @param	String		$message
	 */
	public static function notifyInfo($message) {
		self::notify('info', $message);
	}

}

?>