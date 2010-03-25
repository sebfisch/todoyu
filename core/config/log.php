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
	 * Log settings: level of incidents to logged, function references and related paths
	 *
	 * There are 5 levels of logging:
	 *		0) LOG_LEVEL_DEBUG:		logs all message levels
	 *		1) LOG_LEVEL_NOTICE		logs notices and more serious levels
	 *		2) LOG_LEVEL_ERROR		logs errors and more serious levels
	 *		3) LOG_LEVEL_SECURITY	logs security critical incidents and more serious levels
	 *		4) LOG_LEVEL_FATAL		logs fatal errors
	 */
Todoyu::$CONFIG['LOG'] 	= array(
	'active'	=> array(
		'FILE',
		'DB',
		'FIREBUG'
	),
	'level'		=> LOG_LEVEL_DEBUG,
	'MODES'		=> array(
		'DB'	=> array(
			'funcRef'	=> 'TodoyuLoggerDb::log',
			'table'		=> 'system_errorlog'
		),
		'FILE'		=> array(
			'funcRef'	=> 'TodoyuLoggerFile::log',
			'file'		=> PATH_CACHE . '/log/todoyu.log'
		),
		'FIREBUG'	=> array(
			'funcRef'	=> 'TodoyuLoggerFirePhp::log'
		)
	)
);

?>