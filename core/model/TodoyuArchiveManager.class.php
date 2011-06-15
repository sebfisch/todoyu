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
	 * @return	Boolean
	 */
	public static function extractTo($zipFile, $targetFolder, $entries = null) {
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
			$success	= $zip->extractTo($targetFolder);
		} else {
			$success	= $zip->extractTo($targetFolder, $entries);
		}

		$zip->close();

		return $success;
	}



	/**
	 * Create an archive from a folder
	 *
	 * @param	String			$pathFolder
	 * @param	String|Boolean	$baseFolder
	 * @param	Boolean			$recursive
	 * @param	Array			$exclude
	 * @return	String
	 */
	public static function createArchiveFromFolder($pathFolder, $baseFolder = false, $recursive = true, array $exclude = array()) {
		if( $baseFolder === false ) {
			$baseFolder = $pathFolder;
		}

		$pathFolder	= TodoyuFileManager::pathAbsolute($pathFolder);
		$baseFolder	= TodoyuFileManager::pathAbsolute($baseFolder);
		$randomFile	= md5(uniqid($pathFolder, microtime(true))) . '.zip';
		$tempPath	= TodoyuFileManager::pathAbsolute('cache/temp/' . $randomFile);
		$archive	= new ZipArchive();


			// Prepare exclude paths
		foreach($exclude as $index => $path) {
			$exclude[$index] = TodoyuFileManager::pathAbsolute($path);
		}

			// Create temp dir
		TodoyuFileManager::makeDirDeep(dirname($tempPath));

		$archive->open($tempPath, ZipArchive::CREATE);

			// Prevent empty archive (which will not be created)
		$elements	= TodoyuFileManager::getFolderContents($pathFolder);

		if( sizeof($elements) === 0 ) {
			$archive->addFromString('todoyu-this-archive-is-empty', '');
		} else {
			self::addFolderToArchive($archive, $pathFolder, $baseFolder, $recursive, $exclude);
		}

		$archive->close();

		return $tempPath;
	}



	/**
	 * Add a folder (and sub elements) to an archive
	 *
	 * @param	ZipArchive		&$archive
	 * @param	String			$pathToFolder		Path to folder which elements should be added
	 * @param	String			$baseFolder			Base folder defined to root for the archive. Base path will be removed from internal archive path
	 * @param	Boolean			$recursive			Add also all sub folders and files
	 * @param	Array			$exclude
	 */
	private static function addFolderToArchive(ZipArchive &$archive, $pathToFolder, $baseFolder, $recursive = true, array $exclude = array()) {
		$files		= TodoyuFileManager::getFilesInFolder($pathToFolder);

			// Add files
		foreach($files as $file) {
			$filePath	= $pathToFolder . DIR_SEP . $file;

			if( ! in_array($filePath, $exclude) ) {
				$relPath	= str_replace($baseFolder . DIR_SEP, '', $filePath);
				$relPath	= str_replace('\\', '/', $relPath);

				$archive->addFile($filePath, $relPath);
			}
		}

			// Add folders if recursive is enabled
		if( $recursive ) {
			$folders	= TodoyuFileManager::getFoldersInFolder($pathToFolder);
				// Add folders
			foreach($folders as $folder) {
				$folderPath	= $pathToFolder . DIR_SEP . $folder;

				if( ! in_array($folderPath, $exclude) ) {
					$relPath	= str_replace($baseFolder . DIR_SEP, '', $folderPath);

					$archive->addEmptyDir($relPath);

					self::addFolderToArchive($archive, $folderPath, $baseFolder, true, $exclude);
				}
			}
		}
	}
}

?>