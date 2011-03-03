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
 * Add extra assets for browsers below ie7
 * @todo	Use hook functions to allow others to hook in here
 */

	// Add form include path
Todoyu::addIncludePath( PATH_CORE . '/form' );
	// Add document include path
Todoyu::addIncludePath( PATH_CORE . '/document');

	// Init basic classes
if( Todoyu::$CONFIG['INIT'] ) {
	Todoyu::init();
}

	// Add all paths of installed extensions to autoload
TodoyuExtensions::addAllExtensionAutoloadPaths();

	// Custom config overrides
require_once( PATH_LOCALCONF . '/override.php');

?>