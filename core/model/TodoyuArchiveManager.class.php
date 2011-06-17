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

	private static $excludeItems	= array();

	private static $defaultExcludePatterns = array(
		'.svn'
	);

	/**
	 * Extract an archive to a folder
	 *
	 * @param	String			$pathArchive
	 * @param	String			$targetFolder
	 * @throws	TodoyuException
	 * @return	Boolean
	 */
	public static function extractTo($pathArchive, $targetFolder) {
		@set_time_limit(120);

		$pathArchive	= TodoyuFileManager::pathAbsolute($pathArchive);
		$targetFolder	= TodoyuFileManager::pathAbsolute($targetFolder);

		if( ! is_file($pathArchive) ) {
			throw new TodoyuException('Archive not found for extraction: ' . $pathArchive);
		}

		TodoyuFileManager::makeDirDeep($targetFolder);

		try {
			if( TodoyuServer::isPhp53() ) {
				return self::extractToPhp53($pathArchive, $targetFolder);
			} else {
				return self::extractToPhp52($pathArchive, $targetFolder);
			}
		} catch(TodoyuException $e) {
			TodoyuLogger::logFatal('Cannot extract archive: ' . $e->getMessage());

			return false;
		}
	}


	private static function extractToPhp52($pathArchive, $targetFolder) {
			// Extract files
		$archive	= new PclZip($pathArchive);
		$result		= $archive->extract(PCLZIP_OPT_PATH, $targetFolder);

		if( $result == 0 ) {
			throw new TodoyuException($archive->errorInfo(true));
		}

		return true;
	}


	private static function extractToPhp53($pathArchive, $targetFolder) {
		$archive	= new ZipArchive();
		$archive->open($pathArchive);

		$result	= $archive->extractTo($targetFolder);

		if( $result === false ) {
			throw new TodoyuException('Unknown error');
		}

		$archive->close();

		return true;
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

		if( true || TodoyuServer::isPhp53() ) {
			return self::createArchiveFromFolderPhp53($pathFolder, $exclude);
		} else {
//			return self::createArchiveFromFolderPhp52($pathFolder, $exclude);
		}
	}



	/**
	 * @static
	 * @param  $pathFolder
	 * @param array $exclude
	 * @return String
	 */
	private static function createArchiveFromFolderPhp52($pathFolder, array $exclude = array()) {
		$pathArchive	= TodoyuFileManager::getTempFile('zip');

		self::setExcludeItems($pathFolder, $exclude);

		$archive	= new PclZip($pathArchive);
		$archive->create($pathFolder, PCLZIP_OPT_REMOVE_PATH, $pathFolder, PCLZIP_CB_PRE_ADD, 'TodoyuArchiveManagerCreateArchiveFromFolderPhp52Callback');

		return $pathArchive;
	}



	/**
	 * Create archive from a folder (php 5.3 style)
	 *
	 * @param	String		$pathFolder
	 * @param	Array		$exclude
	 * @return	String
	 */
	private static function createArchiveFromFolderPhp53($pathFolder, array $exclude = array()) {
		$pathArchive	= TodoyuFileManager::getTempFile('zip', false);

			// Prevent empty archive (which will not be created)
		$elements	= TodoyuFileManager::getFolderContents($pathFolder);

		if( sizeof($elements) === 0 ) {
			self::createEmptyArchive($pathArchive);
		} else {
				// Prepare exclude paths
			foreach($exclude as $index => $path) {
				$exclude[$index] = TodoyuFileManager::pathAbsolute($path);
			}

				// Create archive
			$archive = new ZipArchive();
			$archive->open($pathArchive, ZipArchive::CREATE);

			self::addFolderToArchive($archive, $pathFolder, $pathFolder, true, $exclude);

			$archive->close();
		}

		return $pathArchive;
	}



	/**
	 * Create an empty archive
	 *
	 * @param	String		$pathArchive
	 */
	private static function createEmptyArchive($pathArchive) {
		$archive	= new PclZip($pathArchive);
		$archive->create('');
	}



	/**
	 * Set exclude items
	 * Make absolute paths with slashes as separators (even when OS separator is backslash)
	 *
	 * @param	String		$basePath
	 * @param	Array		$items
	 */
	private static function setExcludeItems($basePath, array $items) {
		foreach($items as $index => $item) {
			$items[$index] = str_replace('\\', '/', TodoyuFileManager::pathAbsolute($basePath . '/' . $item));
		}

		self::$excludeItems = $items;
	}


	public static function createArchiveFromFolderPhp52Callback($event, array $header) {
		$file	= $header['filename'];

			// Check for default exclude patterns
		foreach(self::$defaultExcludePatterns as $excludePattern) {
			if( stristr($file, $excludePattern) ) {
				return 0;
			}
		}

			// Check for exclude items
		foreach(self::$excludeItems as $excludeItem) {
			if( strncasecmp($file, $excludeItem, strlen($excludeItem)) === 0 ) {
				return 0;
			}
		}

		return 1;
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

//		TodoyuDebug::printInFireBug($archive, 'ar2');

			// Add files
		foreach($files as $file) {
			$filePath	= $pathToFolder . DIR_SEP . $file;

			if( ! in_array($filePath, $exclude) ) {
				$relPath	= str_replace($baseFolder . DIR_SEP, '', $filePath);
				$relPath	= str_replace('\\', '/', $relPath);
//				TodoyuDebug::printInFireBug($archive, 'ar3');
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


/**
 * @param  $event
 * @param  $header
 * @return int
 */
function TodoyuArchiveManagerCreateArchiveFromFolderPhp52Callback($event, $header) {
	return TodoyuArchiveManager::createArchiveFromFolderPhp52Callback($event, $header);
}

?>