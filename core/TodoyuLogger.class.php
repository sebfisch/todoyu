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
 * Log information in various systems
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuLogger {


	/**
	 * Log instance. Singleton
	 *
	 * @var	Log
	 */
	private static $instance = null;

	/**
	 * Logging modes. Modes need to be implemented in this log class
	 *
	 * @var	Array
	 */
	private $modes	= array();

	/**
	 * Log level. Only messages with minimum this level are logged
	 * Levels are defined as constants LOG_LEVEL_*
	 *
	 * Levels:
	 * 	0: Debug Message
	 *  1: Notice
	 * 	2: Logical Error
	 * 	3: Security Error
	 *  4: Fatal Error
	 *
	 * @var	Integer
	 */
	private $level	= 0;

	/**
	 * Unique key for current request. So we can group the log messages by request
	 *
	 * @var	String
	 */
	private $requestKey;


	/**
	 * Ignore this files when detecting file where logging was executed
	 *
	 * @var	Array
	 */
	private $ignoreFiles = array(
		'TodoyuLogger.class.php',
		'TodoyuErrorHandler.class.php',
		'TodoyuDebug.class.php',
		'Todoyu.class.php'
	);



	/**
	 * Get the only instance. Singleton
	 *
	 * @param	Array		$modes		Active modes
	 * @param	Integer		$level		Log level limit
	 * @return	Log
	 */
	public static function getInstance(array $modes, $level = 0) {
		if( is_null(self::$instance) ) {
			self::$instance = new self($modes, $level);
		}

		return self::$instance;
	}



	/**
	 * Called by getInstance for Singleton Pattern
	 *
	 * @param	Array		$modes
	 * @param	Integer		$level
	 */
	private function __construct(array $modes, $level = 0) {
		$this->modes	= $modes;
		$this->level	= intval($level);

		$this->requestKey =   substr(md5(microtime(true) . session_id()), 0, 10);
	}


	/**
	 * Cleanup actions
	 *
	 */
	public function __destruct() {

	}



	/**
	 * Log a message. The message will be processed by all log modes
	 *
	 * @param	String		$message		Log message
	 * @param	Integer		$level			Log level of current message
	 * @param	Mixed		$data			An additional data container (for debugging)
	 */
	public function log($message, $level = 0, $data = null) {
		$backtrace	= debug_backtrace();
		$info		= $backtrace[0];
		$level		= intval($level);

			// Find file in backtrace which is not on ignore list
		foreach($backtrace as $btElement) {
			if( ! in_array(basename($btElement['file']), $this->ignoreFiles) ) {
				$info = $btElement;
				break;
			}
		}

		$info['fileshort'] 	= TodoyuFileManager::pathWeb($info['file']);

		if( $level >= $this->level ) {
			foreach($this->modes as $mode) {
				$funcRef = Todoyu::$CONFIG['LOG']['MODES'][$mode]['funcRef'];

				TodoyuFunction::callUserFunction($funcRef, $message, $level, $data, $info, $this->requestKey);
			}
		}
	}

}


?>