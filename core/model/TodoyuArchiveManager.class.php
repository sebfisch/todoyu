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
 * Manage zip archives
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuArchiveManager {

	/**
	 * Extract an archive to a folder
	 *
	 * @param	String			$zipFile
	 * @param	String			$targetFolder
	 * @param	String|Array	$entries
	 * @throws	TodoyuException
	 */
	public static function extract($zipFile, $targetFolder, $entries = null) {
		@set_time_limit(60);

		$zipFile	= TodoyuFileManager::pathAbsolute($zipFile);

		if( ! is_file($zipFile) ) {
			throw new TodoyuException('Archive not found for extraction: ' . $zipFile);
		}
		if( ! is_string($targetFolder) ) {
			throw new TodoyuException('Invalid target folder to extract ' . $zipFile . ' to');
		}

		TodoyuFileManager::makeDirDeep($targetFolder);

		$zip	= new ZipArchive();
		$zip->open($zipFile);

			// Workaround because null is not a valid default parameter
			// @see http://pecl.php.net/bugs/bug.php?id=14962
		if( is_null($entries) ) {
			$zip->extractTo($targetFolder);
		} else {
			$zip->extractTo($targetFolder, $entries);
		}

		$zip->close();
	}
}

?>