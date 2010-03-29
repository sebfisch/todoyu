<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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
 * File management functions
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuFileManager {

	/**
	 * Remove absolute site path from a path
	 *
	 * @param	String		$path
	 * @return	String
	 */
	public static function removeSitePath($path) {
		return str_replace(PATH, '', $path);
	}



	/**
	 * Get absolute path
	 *
	 * @param	String	$path
	 * @return	String
	 */
	public static function pathAbsolute($path) {
		$path	= trim($path);

			// Replace directory seperatory with current system settings
		$path = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $path);

			// If no absolute path
		if( substr($path, 0, strlen(PATH)) !== PATH ) {
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
	 * @param	String		$pathFolder
	 * @param	Boolean		$showHidden
	 * @return	Array
	 */
	public static function getFolderContent($pathFolder, $showHidden = false) {
		$pathFolder	= self::pathAbsolute($pathFolder);
		$items			= array();

		if( is_dir($pathFolder) ) {
			$elements		= scandir($pathFolder);

			foreach($elements as $element) {
				if( $element === '.' || $element === '..' ) {
						// Ignore parent and self references
					continue;
				}
					// Also get hidden folders (starting with a dot)?
				if( substr($element, 0, 1) !== '.' || $showHidden ) {
					$items[] = $element;
				}
			}
		}

		return $items;
	}



	/**
	 * Get listing of files inside given folder
	 *
	 * @param	String		$pathFolder
	 * @param	Boolean		$showHidden
	 * @param	String		$filters			strings needed to be contained in files looking for
	 * @return	Array
	 */
	public static function getFilesInFolder($pathFolder, $showHidden = false, $filters = array()) {
		$pathFolder	= self::pathAbsolute($pathFolder);
		$elements	= self::getFolderContent($pathFolder, $showHidden);
		$files		= array();

		foreach($elements as $element) {
			if( is_file($pathFolder . DIRECTORY_SEPARATOR . $element) ) {
					// No filters defined: add file to results array
				if ( sizeof($filters) === 0) {
					$files[] = $element;
				} else {
						// Check string filters
					foreach($filters as $filterString) {
						if ( strpos($element, $filterString) !== false ) {
							$files[] = $element;
							break;
						}
					}
				}
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
			if( is_dir($pathToFolder . DIRECTORY_SEPARATOR . $element) ) {
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
	public static function deleteFolderContent($folderPath, $deleteHidden = false) {
		$folderPath 	= self::pathAbsolute($folderPath);
		$folders		= self::getFoldersInFolder($folderPath, $deleteHidden);
		$files			= self::getFilesInFolder($folderPath);

			// Delete folders with contents
		foreach($folders as $foldername) {
			$pathFolder	= $folderPath . DIRECTORY_SEPARATOR . $foldername;

			if( is_dir($pathFolder) ) {
				self::deleteFolderContent($pathFolder);
				rmdir($pathFolder);
			}
		}

			// Delete files in folder
		foreach($files as $filename) {
			$pathFile	= $folderPath . DIRECTORY_SEPARATOR . $filename;

			if( is_file($pathFile) ) {
				unlink($pathFile);
			}
		}
	}



	/**
	 * Delete folder including all files and folders contained in it
	 *
	 * @param	String		$pathFolder
	 * @return	Boolean
	 */
	public static function deleteFolder($pathFolder) {
		$pathFolder	= self::pathAbsolute($pathFolder);

		if( is_dir($pathFolder) ) {
			self::deleteFolderContent($pathFolder, true);

			return rmdir($pathFolder);
		} else {
			return false;
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
	 * @param	Integer		$mode				Access rights mode
	 */
	public static function makeDirDeep($directoryPath, $mode = null) {
		$directoryPath	= self::pathAbsolute($directoryPath);

			// Check if directory already exists
		if( is_dir($directoryPath) ) {
			return true;
		}

			// Remove base PATH, we only create the subfolders. Split the parts
		$directoryPath	= str_replace(PATH, '', $directoryPath);
		$pathParts		= array_slice(explode(DIRECTORY_SEPARATOR, $directoryPath), 1);
		$basePath		= PATH;
		$chmod			= is_null($mode) ? Todoyu::$CONFIG['CHMOD']['folder'] : $mode;

			// Create each level of the subfolder
		foreach( $pathParts as $pathPart ) {
			$currentPath = $basePath . DIRECTORY_SEPARATOR . $pathPart;

			if( ! is_dir($currentPath) ) {
				mkdir($currentPath);
				chmod($currentPath, $chmod);
			}

			$basePath = $currentPath;
		}
	}



	/**
	 * Check if file exists. Also relative path from PATH
	 *
	 * @param	String		$path
	 * @return	Boolean
	 */
	public static function isFile($path) {
		$path	= self::pathAbsolute($path);

		return is_file($path);
	}



	/**
	 * Set modification timestamp of file
	 *
	 * @param	String		$filePath
	 * @param	Integer		$timestamp
	 */
	public static function setFileMtime($filePath, $timestamp = NOW) {
		touch($filePath, $timestamp);
	}



	/**
	 * Save file content based on a template
	 *
	 * @param	String		$savePath		Path where the file is saved
	 * @param	String		$tmpl			Path to the template file
	 * @param	Array		$data			Template data
	 * @param	Boolean		$wrapAsPhp		Wrap content with PHP start and end tags
	 */
	public static function saveTemplatedFile($savePath, $tmpl, array $data = array(), $wrapAsPhp = true) {
		$savePath	= self::pathAbsolute($savePath);

			// Render file content
		$content= render($tmpl, $data);

		if( $wrapAsPhp ) {
				// Add php start and end tag
			$content= TodoyuString::wrap($content, '<?php|?>');
		}

		return file_put_contents($savePath, $content) !== false;
	}



	/**
	 * Set default access rights to folder or file
	 *
	 * @param	String		$path
	 * @return	Boolean
	 */
	public static function setDefaultAccessRights($path) {
		$path	= self::pathAbsolute($path);

		if( is_file($path) ) {
			return self::setDefaultFileAccess($path);
		} elseif( is_dir($path) ) {
			return self::setDefaultFolderAccess($path);
		}
	}



	/**
	 * Set default file access
	 *
	 * @param	String		$pathToFile
	 * @return	Boolean
	 */
	public static function setDefaultFileAccess($pathToFile) {
		$pathToFile	= self::pathAbsolute($pathToFile);

		return chmod($pathToFile, Todoyu::$CONFIG['CHMOD']['file']);
	}



	/**
	 * Set default file access
	 *
	 * @param	String		$pathToFolder
	 * @return	Boolean
	 */
	public static function setDefaultFolderAccess($pathToFolder) {
		$pathToFolder	= self::pathAbsolute($pathToFolder);

		return chmod($pathToFolder, Todoyu::$CONFIG['CHMOD']['folder']);
	}



	/**
	 * Get file content
	 *
	 * @param	String		$path
	 * @return	String
	 */
	public static function getFileContent($path) {
		$path	= self::pathAbsolute($path);

		if( is_file($path) && is_readable($path) ) {
			return file_get_contents($path);
		} else {
			Todoyu::log('Can\'t open file! File: ' . $file, LOG_LEVEL_ERROR);
			return '';
		}
	}



	/**
	 * Save content in file
	 *
	 * @param	String		$path
	 * @param	String		$content
	 */
	public static function saveFileContent($path, $content) {
		$path	= self::pathAbsolute($path);

		if( is_file($path) && is_writable($path) ) {
			file_put_contents($path, $content);
		} else {
			Todoyu::log('Can\'t open file! File: ' . $file, LOG_LEVEL_ERROR);
		}
	}



	/**
	 * Check if a file is in allowed download paths
	 * By default, no download path is allowed (except PATH_FILES)
	 * You can allow paths in Todoyu::$CONFIG['sendFile']['allow'] or disallow paths in Todoyu::$CONFIG['sendFile']['disallow']
	 * Disallow tasks precedence before allow
	 *
	 * @param	String		$absoluteFilePath		Absolute path to file
	 * @return	Boolean
	 */
	public static function isFileInAllowedDownloadPath($absoluteFilePath) {
		$absoluteFilePath	= realpath($absoluteFilePath);
		$disallowedPaths	= Todoyu::$CONFIG['sendFile']['disallow'];
		$allowedPaths		= Todoyu::$CONFIG['sendFile']['allow'];

		// If file exists
		if( $absoluteFilePath !== false ) {

			// Check if file is in an explicitly disallowed path
			if( is_array($disallowedPaths) ) {
				foreach($disallowedPaths as $disallowedPath) {
					if( strpos($absoluteFilePath, $disallowedPath) !== false ) {

						return false;
					}
				}
			}
			// Check if file is in an allowed path
			if( is_array($allowedPaths) ) {
				foreach($allowedPaths as $allowedPath) {
					if( strpos($absoluteFilePath, $allowedPath) !== false ) {
						return true;
					}
				}
			}
		}

			// If file not found, or no allowing config available, disallow download
		return false;
	}



	/**
	 * Read a file from harddisk and send it to the browser (with echo)
	 * Reads file in small parts (1024 B)
	 *
	 * @param	String		$absoluteFilePath
	 * @return	Boolean		File was allowed to download and sent to browser
	 */
	public static function sendFile($absoluteFilePath) {
		$absoluteFilePath	= realpath($absoluteFilePath);

		if( $absoluteFilePath !== false ) {
			if( is_readable($absoluteFilePath) ) {
				if( self::isFileInAllowedDownloadPath($absoluteFilePath) ) {
					$fp	= fopen($absoluteFilePath, 'rb');

					while($data = fread($fp, 1024)) {
						echo $data;
					}

					fclose($fp);

					return true;
				} else {
					Todoyu::log('Tried to download a file from a not allowed path', LOG_LEVEL_SECURITY, $absoluteFilePath);
				}
			}
		}

		return false;
	}



	/**
	 * Append string to filename, preserving path delimiter and file extension
	 *
	 * @param	String	$filename
	 * @param	String	$append
	 * @return	String
	 */
	public static function appendToFilename($filename, $append) {
		$pathinfo	= pathinfo($filename);
		$dir		= ( $pathinfo['dirname'] == '.' ) ? '' : $pathinfo['dirname'] . DIRECTORY_SEPARATOR;

		return $dir . $pathinfo['filename'] . $append . '.' . $pathinfo['extension'];
	}

}

?>