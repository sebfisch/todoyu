<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2,
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * File management functions
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuFileManager {

	/**
	 * Get absolute path
	 *
	 * @param	String	$path
	 * @return	String
	 */
	public static function pathAbsolute($path) {
		$path	= trim($path);
		
			// If no absolute path
		if( substr($path, 0, 1) !== '/' && substr($path, 1, 2) !== ':\\' ) {
			$path = PATH . DIRECTORY_SEPARATOR . $path;
		}
		
			// Remove slash at the end
		if( substr($path, -1, 1) === DIRECTORY_SEPARATOR ) {
			$path = substr($path, 0, -1);
		}
		
		return $path;
	}



	/**
	 * Get web path of a file
	 *
	 * @param	String		$absolutePath
	 * @return 	String
	 */
	public static function pathWeb($absolutePath) {
		return str_replace('\\', '/', str_replace(PATH . DIRECTORY_SEPARATOR, '', self::pathAbsolute($absolutePath)));
	}
	


	/**
	 * Get folder contents
	 *
	 * @param	String	$pathToFolder
	 * @param	Boolean	$showHidden
	 * @return	Array
	 */
	public static function getFolderContent($pathToFolder, $showHidden = false) {
		$pathToFolder	= self::pathAbsolute($pathToFolder);
		$elements		= scandir($pathToFolder);
		$items			= array();

		if( $showHidden === false ) {
			foreach($elements as $element) {
				if( substr($element, 0, 1) !== '.' ) {
					$items[] = $element;
				}
			}
		} else {
			$items = array_slice($elements, 2);
		}

		return $items;
	}



	/**
	 * Get listing of files in given folder
	 *
	 * @param	String	$pathToFolder
	 * @param	Boolean	$showHidden
	 * @return	Array
	 */
	public static function getFilesInFolder($pathToFolder, $showHidden = false) {
		$pathToFolder	= self::pathAbsolute($pathToFolder);
		$elements		= self::getFolderContent($pathToFolder, $showHidden);
		$files			= array();

		foreach($elements as $element) {
			if( is_file($pathToFolder . '/' . $element) ) {
				$files[] = $element;
			}
		}

		return $files;
	}



	/**
	 * Get sub folders in given path
	 *
	 * @param	String	$pathToFolder
	 * @param	Boolean	$showHidden
	 * @return	Array
	 */
	public static function getFoldersInFolder($pathToFolder, $showHidden = false) {
		$pathToFolder	= self::pathAbsolute($pathToFolder);
		$elements		= self::getFolderContent($pathToFolder, $showHidden);
		$folders		= array();

		foreach($elements as $element) {
			if( is_dir($pathToFolder . '/' . $element) ) {
				$folders[] = $element;
			}
		}

		return $folders;
	}
	
	
	
	/**
	 * Delete all files inside given folder
	 *
	 * @param	String	$pathToFolder
	 */
	public static function deleteFolderContent($pathToFolder) {
		$pathToFolder = self::pathAbsolute($pathToFolder);
		$folders	= self::getFoldersInFolder($pathToFolder, true);
		$files		= self::getFilesInFolder($pathToFolder);

		foreach($folders as $folder) {
			self::deleteFolderContent($pathToFolder . '/' . $folder);
			rmdir($pathToFolder . '/' . $folder);
		}

		foreach($files as $file) {
			unlink($pathToFolder . '/' . $file);
		}
	}
	
	
	
	/**
	 * Replace all not allowed characters of a filename by "_" or another character
	 *
	 * @param	String		$dirtyFilename		Filename (not path!)
	 * @return	String
	 */

	public static function makeCleanFilename($dirtyFilename, $replaceBy = '_') {
		$pattern	= '|[^A-Za-z0-9\.-_\[\]()]|';
		
		return preg_replace($pattern, $replaceBy, $dirtyFilename);
	}
	
	
	
	/**
	 * Create multiple subdirectories to create a path structure in the filesystem
	 * The path will be a directory (don't give a file path as parameter!)
	 *
	 * @param	String		$directoryPath		Directory path to create
	 */
	public static function makeDirDeep($directoryPath) {
		$directoryPath	= self::pathAbsolute($directoryPath);
		
			// Prevent directory creation outside of the base path	
		if( substr($directoryPath, 0, 1) === '/' && ! stristr($directoryPath, PATH) ) {
			die('Directory creation outside of the PATH is not allowed');
		}
		
			// If PATH is not already a part of the directory path, prepend it (assume its a relative path)
		if( ! stristr($directoryPath, PATH) ) {
			$directoryPath = PATH . DIRECTORY_SEPARATOR . $directoryPath;
		}
		
			// Check if directory already exists
		if( is_dir($directoryPath) ) {
			return true;
		}
		
			// Remove base PATH, we only create the subfolders. Split the parts
		$directoryPath	= str_replace(PATH, '', $directoryPath);
		$pathParts		= array_slice(explode(DIRECTORY_SEPARATOR, $directoryPath), 1);
		$basePath		= PATH;
		
			// Create each level of the subfolder
		foreach( $pathParts as $pathPart ) {
			$currentPath = $basePath . DIRECTORY_SEPARATOR . $pathPart;

			if( ! is_dir($currentPath) ) {
				mkdir($currentPath);
				chmod($currentPath, 0775);
			}

			$basePath = $currentPath;
		}
	}

}


?>