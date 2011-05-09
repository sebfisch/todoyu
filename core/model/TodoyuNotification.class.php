<?php
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
 * Send notification to browser
 * Currently, the number of notes per response is limited to one (1)!
 *
 * @package		Todoyu
 * @subpackage	Core
 * @see 		core/asset/js/Notification.js
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
			'message'	=> Todoyu::Label($message),
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