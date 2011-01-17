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

	// Define constant, so we can prevent direct script call
define('TODOYU', true);

	// Directory separator shorthand
if( ! defined('DIR_SEP') ) {
	define('DIR_SEP', DIRECTORY_SEPARATOR);
}

	// Paths
define( 'PATH', 			dirname(dirname(dirname(__FILE__))) );
define( 'PATH_WEB',			defined('PATH_WEB_OVERRIDE') ? PATH_WEB_OVERRIDE : dirname($_SERVER['SCRIPT_NAME']) );
define( 'PATH_CACHE',		PATH . DIR_SEP . 'cache' );
define( 'PATH_CORE',		PATH . DIR_SEP . 'core' );
define( 'PATH_EXT',			PATH . DIR_SEP . 'ext' );
define( 'PATH_CONFIG',		PATH_CORE . DIR_SEP . 'config' );
define( 'PATH_LOCALCONF',	PATH . DIR_SEP . 'config' );
define( 'PATH_LIB',			PATH . DIR_SEP . 'lib' );
define( 'PATH_PEAR',		PATH_LIB . DIR_SEP . 'php' . DIR_SEP . 'PEAR' );
define( 'PATH_TEMP',		PATH_CACHE . DIR_SEP . 'temp' );
define( 'PATH_FILES',		PATH . DIR_SEP . 'files' );


	// Constants
define( 'NOW', time() );

/**
 * Public URL of the server (use to build absolute links)
 */
define('SERVER_URL', ($_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);

/**
 * Public URL of the todoyu installation (server path and web path)
 */
define('TODOYU_URL', SERVER_URL . PATH_WEB);

?>