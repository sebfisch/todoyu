<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSC License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * FirePhp/FireBug Logger
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuLoggerFirePhp {

	/**
	 * Write log message in firebug
	 *
	 * @param	String		$message
	 * @param	Integer		$level
	 * @param	Mixed		$data
	 * @param	Array		$info
	 * @param	String		$requestKey
	 */
	public static function log($message, $level, $data, $info, $requestKey) {
		$title	= '[' . $info['fileshort'] . ':' . $info['line'] . ']';
		$text	= $message . '  [' . $level . ']';

		try {

			switch($level) {
				case LOG_LEVEL_FATAL:
				case LOG_LEVEL_ERROR:
					TodoyuDebug::firePhp()->error($text, $title);
					break;


				case LOG_LEVEL_SECURITY:
					TodoyuDebug::firePhp()->warn($text, $title);
					break;


				case LOG_LEVEL_NOTICE:
					TodoyuDebug::firePhp()->info($text, $title);
					break;


				case LOG_LEVEL_DEBUG:
				default:
					TodoyuDebug::firePhp()->log($text, $title);
					break;
			}

			if( ! empty($data) ) {
				TodoyuDebug::firePhp()->log($data, '#');
			}
		} catch (Exception $e) {
			echo '<u>PROBLEM WITH FIREBUG</u><br />' . $e->getMessage();
		}
	}

}

?>