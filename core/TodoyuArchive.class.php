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

/**
 * [Enter Class Description]
 * 
 * @package		Todoyu
 * @subpackage	[Subpackage]
 */
class TodoyuArchive extends ZipArchive {

	public function addFolderRecursive($pathToFolder, $baseFolder = '', $recursive = true) {
		$pathToFolder	= TodoyuFileManager::pathAbsolute($pathToFolder);
		$files		= TodoyuFileManager::getFilesInFolder($pathToFolder);
		$baseFolder	= $baseFolder === '' ? $pathToFolder : TodoyuFileManager::pathAbsolute($baseFolder);

					// Add files
		foreach($files as $file) {
			$filePath	= $pathToFolder . DIR_SEP . $file;
			$relPath	= str_replace($baseFolder . DIR_SEP, '', $filePath);

			$this->addFile($filePath, $relPath);
		}

			// Add folders if recursive is enabled
		if( $recursive ) {
			$folders	= TodoyuFileManager::getFoldersInFolder($pathToFolder);
				// Add folders
			foreach($folders as $folder) {
				$folderPath	= $pathToFolder . DIR_SEP . $folder;
				$relPath	= str_replace($baseFolder . DIR_SEP, '', $folderPath);

				$this->addEmptyDir($relPath);

				$this->addFolderRecursive($folderPath, $baseFolder, $recursive);
			}
		}
	}

}

?>