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
 * Core config for error logging
 *
 * @package		Todoyu
 * @subpackage	Core
 */

	/**
	 * Log settings: level of incidents to logged, function references and related paths
	 *
	 * There are 5 levels of logging:
	 *		0) TodoyuLogger::LEVEL_DEBUG		logs all message levels
	 *		1) TodoyuLogger::LEVEL_NOTICE		logs notices and more serious levels
	 *		2) TodoyuLogger::LEVEL_ERROR		logs errors and more serious levels
	 *		3) TodoyuLogger::LEVEL_SECURITY	logs security critical incidents and more serious levels
	 *		4) TodoyuLogger::LEVEL_FATAL		logs fatal errors
	 */

Todoyu::$CONFIG['LOG_LEVEL'] = TodoyuLogger::LEVEL_DEBUG;

	// File Logger
Todoyu::logger()->addLogger('TodoyuLoggerFile', array(
	'file'	=> PATH_CACHE . '/log/todoyu.log'
));

	// FirePhp Logger
Todoyu::logger()->addLogger('TodoyuLoggerFirePhp');

	// Database Logger
//Todoyu::logger()->addLogger('TodoyuLoggerDb', array(
//	'table'	=> 'system_errorlog'
//));

?>