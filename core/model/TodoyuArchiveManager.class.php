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

require_once( PATH_LIB . '/php/pclzip/pclzip.lib.php' );

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

			// Extract files
		$zip	= new PclZip($zipFile);
		$result	= $zip->extract(PCLZIP_OPT_PATH, $targetFolder);

		if( $result == 0 ) {
			TodoyuLogger::logFatal('Cannot extract archive. ' . $zip->errorInfo(true));
			return false;
		} else {
			return true;
		}
	}



	/**
	 * Create an archive from a folder
	 *
	 * @param	String			$pathFolder
	 * @param	Array			$exclude
	 * @return	String
	 */
	public static function createArchiveFromFolder($pathFolder, array $exclude = array()) {
		$pathFolder		= TodoyuFileManager::pathAbsolute($pathFolder);
		$randomFile		= md5(uniqid($pathFolder, microtime(true))) . '.zip';
		$tempArchivePath= TodoyuFileManager::pathAbsolute('cache/temp/' . $randomFile);

			// Create temp folder with content
		$tempFolder	= TodoyuFileManager::makeRandomCacheDir('archive');
		TodoyuFileManager::copyRecursive($pathFolder, $tempFolder, $exclude);

//			// Remove excluded
//		foreach($exclude as $excludeElement) {
//			$excludeElement		= TodoyuFileManager::pathAbsolute($excludeElement);
//			$excludeElementRel	= str_replace(PATH, '', $excludeElement);
//			$excludeElement		= TodoyuFileManager::pathAbsolute($tempFolder . $excludeElementRel);
//
//			if( is_file($excludeElement) ) {
//				TodoyuFileManager::deleteFile($excludeElement);
//			} elseif( is_dir($excludeElement) ) {
//				TodoyuFileManager::deleteFolder($excludeElement);
//			}
//		}

			// Create temp dir
		TodoyuFileManager::makeDirDeep(dirname($tempArchivePath));

		$archive	= new PclZip($tempArchivePath);
		$archive->create($tempFolder, PCLZIP_OPT_REMOVE_PATH, $tempFolder);

		TodoyuFileManager::deleteFolder($tempFolder);

		return $tempArchivePath;
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