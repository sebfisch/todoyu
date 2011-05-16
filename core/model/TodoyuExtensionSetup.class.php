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
 * [Enter Class Description]
 *
 * @package		Todoyu
 * @subpackage	[Subpackage]
 */
class TodoyuExtensionSetup {


	protected static function processDbUpdateFiles($extKey, $previousVersion) {
		$path	= TodoyuExtensions::getExtPath($extKey, 'config/db/update');
		$files	= TodoyuFileManager::getVersionFiles($path, 'sql', $previousVersion);

		foreach($files as $file) {
			TodoyuSQLManager::executeQueriesFromFile($path . '/' . $file);
		}
	}


	public static function afterOtherExtensionUninstall($extKey) {


	}


	public static function afterOtherExtensionInstall($extKey) {


	}


	public static function beforeDbInstall($extKey) {

	}


	public static function beforeDbUpdate($extKey, $previousVersion) {
		self::processDbUpdateFiles($extKey, $previousVersion);
	}

	public static function beforeUpdate($extKey, $previousVersion) {

	}

	public static function afterInstall($extKey) {

	}

	public static function beforeUninstall($extKey) {

	}



	public static function afterUpdate($extKey, $previousVersion) {

	}


}

?>