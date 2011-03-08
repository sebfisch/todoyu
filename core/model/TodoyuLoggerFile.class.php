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
 * File logger
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuLoggerFile implements TodoyuLoggerIf {

	/**
	 * File pointer
	 *
	 * @var	String
	 */
	private $filePointer = null;


	/**
	 * Log file pattern
	 */
	private $pattern	= "%s :: %s - L:%d [%s:%d] :: %s\n";


	public function __construct(array $config) {
		$pathFile	= TodoyuFileManager::pathAbsolute($config['file']);

			// Make folder and file if not exists
		if( ! is_file($pathFile) ) {
			TodoyuFileManager::makeDirDeep(dirname($pathFile));
			touch($pathFile);
		}

			// Change pattern if given
		if( isset($config['pattern']) ) {
			$this->pattern = $config['pattern'];
		}

			// Open file
		$this->filePointer = fopen($pathFile, 'a');
	}



	/**
	 * Close file if opened
	 *
	 */
	public function __destruct() {
		if( ! is_null($this->filePointer) ) {
			fclose($this->filePointer);
		}
	}



	/**
	 * Write log message in the log file
	 *
	 * @param	String		$message
	 * @param	Integer		$level
	 * @param	Mixed		$data
	 * @param	Array		$callerInfo
	 */
	public function log($message, $level, $data, $info, $requestKey) {
		$logLine	= sprintf($this->pattern, $requestKey, date('Y-m-d H:i:s'), $level, $info['fileshort'], $info['line'], $message);

		fwrite($this->filePointer, $logLine);
	}

}

?>