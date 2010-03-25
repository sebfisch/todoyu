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

	// Define constant, so we can prevent direct script call
define('TODOYU', true);

	// Path
define( 'PATH', 			dirname(dirname(dirname(__FILE__))) );
define( 'PATH_WEB',			dirname($_SERVER['SCRIPT_NAME']) );
define( 'PATH_CACHE',		PATH . DIRECTORY_SEPARATOR . 'cache' );
define( 'PATH_CORE',		PATH . DIRECTORY_SEPARATOR . 'core' );
define( 'PATH_EXT',			PATH . DIRECTORY_SEPARATOR . 'ext' );
define( 'PATH_CONFIG',		PATH_CORE . DIRECTORY_SEPARATOR . 'config' );
define( 'PATH_LOCALCONF',	PATH . DIRECTORY_SEPARATOR . 'config' );
define( 'PATH_LIB',			PATH . DIRECTORY_SEPARATOR . 'lib' );
define( 'PATH_PEAR',		PATH_LIB . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'PEAR' );
define( 'PATH_TEMP',		PATH_CACHE . DIRECTORY_SEPARATOR . 'temp' );
define( 'PATH_FILES',		PATH . DIRECTORY_SEPARATOR . 'files' );


	// Constants
define( 'NOW', time() );


	// Log levels
define('LOG_LEVEL_DEBUG', 	0);
define('LOG_LEVEL_NOTICE', 	1);
define('LOG_LEVEL_ERROR', 	2);
define('LOG_LEVEL_SECURITY',3);
define('LOG_LEVEL_FATAL', 	4);

/**
 * Public URL of the server (use to build absolute links)
 */
define('SERVER_URL', 'http://' . $_SERVER['HTTP_HOST']);

/**
 * Public URL of the todoyu installation (server path and web path)
 */
define('TODOYU_URL', SERVER_URL . PATH_WEB);

?>