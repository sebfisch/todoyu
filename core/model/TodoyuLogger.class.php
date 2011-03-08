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
 * Log information in various systems
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuLogger {

	/**
	 * Log levels
	 */
	const LEVEL_DEBUG	= 0;
	const LEVEL_NOTICE	= 1;
	const LEVEL_ERROR	= 2;
	const LEVEL_SECURITY= 3;
	const LEVEL_FATAL	= 4;


	/**
	 * Log instance. Singleton
	 *
	 * @var	Log
	 */
	private static $instance = null;

	/**
	 * Classnames of registered loggers
	 *
	 * @var	Array
	 */
	private $loggerNames	= array();

	/**
	 * Logger instances of the classes registered in $this->loggerNames
	 */
	private $loggerInstances = null;

	/**
	 * Log level. Only messages with minimum this level are logged
	 * Levels are defined as constants TodoyuLogger::LEVEL_*
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
	private $fileIgnorePattern = array(
		'TodoyuLogger.class.php',
		'TodoyuErrorHandler.class.php',
		'TodoyuDebug.class.php',
		'Todoyu.class.php'
	);



	/**
	 * Get the logger instance. Singleton
	 *
	 * @param	Integer		$level		Log level limit
	 * @return	Log
	 */
	public static function getInstance($level = 0) {
		if( is_null(self::$instance) ) {
			self::$instance = new self($level);
		}

		return self::$instance;
	}



	/**
	 * Called by getInstance for Singleton Pattern
	 *
	 * @param	Integer		$level
	 */
	private function __construct($level = 0) {
		$this->setLevel($level);

		$this->requestKey = substr(md5(microtime(true) . session_id()), 0, 10);
	}



	/**
	 * Add a logger class. Class is just provided as string and will be
	 * instantiated on the first use of the log
	 *
	 * @param	String		$className
	 * @param	Array		$config
	 */
	public function addLogger($className, array $config = array()) {
		$this->loggerNames[] = array(
			'class'	=> $className,
			'config'=> $config
		);
	}



	/**
	 * Change to log level
	 *
	 * @param	Integer		$level
	 */
	public function setLevel($level) {
		$this->level = intval($level);
	}



	/**
	 * Add a pattern which will be ignored while looking for the error
	 * position in the backtrace
	 *
	 * @param	String		$pattern
	 */
	public function addFileIgnorePattern($pattern) {
		$this->fileIgnorePattern[] = $pattern;
	}



	/**
	 * Get instances of all loggers
	 * The logger objects are not created until they are used
	 *
	 * @return	Array
	 */
	private function getLoggerInstances() {
		if( is_null($this->loggerInstances) ) {
			foreach($this->loggerNames as $logger) {
				$className	= $logger['class'];
				$this->loggerInstances[] = new $className($logger['config']);
			}
		}

		return $this->loggerInstances;
	}



	/**
	 * Log a message. The message will be processed by all loggers
	 *
	 * @param	String		$message		Log message
	 * @param	Integer		$level			Log level of current message
	 * @param	Mixed		$data			An additional data container (for debugging)
	 */
	public function log($message, $level = 0, $data = null) {
		$backtrace	= debug_backtrace(false);
		$info		= $backtrace[0];
		$level		= intval($level);

		if( $level >= $this->level ) {
				// Find file in backtrace which is not on ignore list
			foreach($backtrace as $btElement) {
				if( ! in_array(basename($btElement['file']), $this->fileIgnorePattern) ) {
					$info = $btElement;
					break;
				}
			}

			$info['fileshort'] 	= TodoyuFileManager::pathWeb($info['file']);

			$loggers	= $this->getLoggerInstances();

			foreach($loggers as $logger) {
				/**
				 * @var	TodoyuLoggerIf	$logger
				 */
				$logger->log($message, $level, $data, $info, $this->requestKey);
			}
		}
	}

}

?>