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
 * File logger
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuLoggerFile {

	/**
	 * File pointer
	 *
	 * @var	String
	 */
	private static $file = null;



	/**
	 * Write log message in the logfile
	 *
	 * @param	String		$message
	 * @param	Integer		$level
	 * @param	Mixed		$data
	 * @param	Array		$callerInfo
	 */
	public static function log($message, $level, $data, $info, $requestKey) {
			// If file is not opened yet
		if( is_null(self::$file) ) {
				// Get path from config
			$logFilePath	= TodoyuFileManager::pathAbsolute(Todoyu::$CONFIG['LOG']['MODES']['FILE']['file']);

				// If path is defined as string
			if( is_string($logFilePath) ) {
					// If file doesn't exist, create it
				if( ! is_file($logFilePath) ) {
					TodoyuFileManager::makeDirDeep(dirname($logFilePath));
					touch($logFilePath);
				}

					// Open file
				self::$file = fopen($logFilePath, 'a');
			}
		}

			// If the file is open, write log in it
		if( ! is_null(self::$file) ) {
			$logMessage = $requestKey . ' :: ' . date('Y-m-d H:i:s') . ' - L:' . $level . ' [' . $info['fileshort'] . ':' . $info['line'] . '] :: ' . $message . "\n";

			fwrite(self::$file, $logMessage);
		}
	}

}

?>