<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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

$installedExtensions	= TodoyuExtensions::getInstalledExtKeys();

	// First add all include paths
foreach($installedExtensions as $extKey) {
	TodoyuExtensions::addExtAutoloadPaths($extKey);
}

	// Load all ext.php files to init the extensions
foreach($installedExtensions as $extKey) {
	$extDir	= TodoyuExtensions::getExtPath($extKey);
	$extFile= $extDir . '/ext.php';

	if( is_file($extFile) ) {
		require_once($extFile);
	}
}

?>