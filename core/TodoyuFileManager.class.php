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

			// Replace directory separator according to current system settings
		$path = str_replace(array('\\', '/'), DIR_SEP, $path);

			// If no absolute path
		if( substr($path, 0, 1) !== '/' && substr($path, 0, strlen(PATH)) !== PATH && substr($path, 1, 1) !== ':' ) {
			$path = PATH . DIR_SEP . $path;
		}

			// Remove slash at the end
		if( substr($path, -1, 1) === DIR_SEP ) {
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
		return str_replace('\\', '/', str_replace(PATH . DIR_SEP, '', self::pathAbsolute($absolutePath)));
	}



	/**
	 * Delete all files inside given folder
	 *
	 * @param	String	$pathToFolder
	 */
	public static function deleteFolderContents($folderPath, $deleteHidden = false) {
		$folderPath 	= self::pathAbsolute($folderPath);
		$folders		= self::getFoldersInFolder($folderPath, $deleteHidden);
		$files			= self::getFilesInFolder($folderPath);

			// Delete folders with contents
		foreach($folders as $foldername) {
			$pathFolder	= $folderPath . DIR_SEP . $foldername;

			if( is_dir($pathFolder) ) {
				self::deleteFolderContents($pathFolder, $deleteHidden);

					// Check if there are still elements in the folder
				$elementsInFolder	= self::getFolderContents($pathFolder, true);

					// Only delete the folder if empty
				if( sizeof($elementsInFolder) === 0 ) {
					self::deleteFolder($pathFolder);
				}
			}
		}

			// Delete files in folder
		foreach($files as $filename) {
			$pathFile	= $folderPath . DIR_SEP . $filename;

			if( is_file($pathFile) ) {
				unlink($pathFile);
			}
		}
	}



	/**
	 * Delete given directory
	 *
	 * @param	String		$pathFolder
	 * @return	Boolean
	 */
	public static function deleteFolder($pathFolder) {
			// Prevent deleting whole todoyu if an empty variable is given
		if( empty($pathFolder) || $pathFolder === PATH ) {
			return false;
		}

		$pathFolder	= self::pathAbsolute($pathFolder);

		if( is_dir($pathFolder) ) {
			self::deleteFolderContents($pathFolder, true);

			$result	= rmdir($pathFolder);
			if ( $result === false ) {
				Todoyu::log('Folder deletion failed: ' . $pathFolder, TodoyuLogger::LEVEL_NOTICE);
			}
		} else {
			$result	= false;
		}

		return $result;
	}



	/**
	 * Replace all not allowed characters of a filename by "_" or another character
	 *
	 * @param	String		$dirtyFilename		Filename (not path!)
	 * @return	String
	 */
	public static function makeCleanFilename($dirtyFilename, $replaceBy = '_') {
		$pattern	= '|[^A-Za-z0-9\.\-_\[\]()]|';

		return preg_replace($pattern, $replaceBy, $dirtyFilename);
	}



	/**
	 * Create multiple sub directories to create a path structure in the file system
	 * The path will be a directory (don't give a file path as parameter!)
	 *
	 * @param	String		$directoryPath		Directory path to create
	 * @param	Integer		$mode				Access rights mode
	 * @return	Boolean
	 */
	public static function makeDirDeep($directoryPath, $mode = null) {
		$directoryPath	= self::pathAbsolute($directoryPath);

			// Check if directory already exists
		if( is_dir($directoryPath) ) {
			return true;
		}

			// Remove base PATH, we only create the sub folders. Split the parts
		$directoryPath	= str_replace(PATH, '', $directoryPath);
		$pathParts		= array_slice(explode(DIR_SEP, $directoryPath), 1);
		$basePath		= PATH;
		$chmod			= is_null($mode) ? Todoyu::$CONFIG['CHMOD']['folder'] : $mode;

			// Create each level of the sub folder
		foreach( $pathParts as $pathPart ) {
			$currentPath = $basePath . DIR_SEP . $pathPart;

			if( ! is_dir($currentPath) ) {
				mkdir($currentPath);
				chmod($currentPath, $chmod);
			}

			$basePath = $currentPath;
		}

		return true;
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
	 * Move a file to the folder structure
	 *
	 * @param	String		$path
	 * @param	String		$sourceFile
	 * @param	String		$uploadFileName
	 * @return	Boolean
	 */
	public static function addFileToStorage($path, $sourceFile, $uploadFileName, $prependWithTimestamp = true) {
		$fileName	= ( $prependWithTimestamp === true ? NOW . '_' . $fileName : '') . self::makeCleanFilename($uploadFileName);
		$filePath	= $path . DIR_SEP . $fileName;

		$fileMoved	= move_uploaded_file($sourceFile, $filePath);

		return $fileMoved ? $filePath : false;
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

		return @chmod($pathToFile, Todoyu::$CONFIG['CHMOD']['file']);
	}



	/**
	 * Set default file access
	 *
	 * @param	String		$pathToFolder
	 * @return	Boolean
	 */
	public static function setDefaultFolderAccess($pathToFolder) {
		$pathToFolder	= self::pathAbsolute($pathToFolder);

		return @chmod($pathToFolder, Todoyu::$CONFIG['CHMOD']['folder']);
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
			Todoyu::log('Can\'t open file! File: ' . $file, TodoyuLogger::LEVEL_ERROR);
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
	 * Read a file from hard disk and send it to the browser (with echo)
	 * Reads file in small parts (1024 B)
	 *
	 * @param	String		$absoluteFilePath
	 * @param	String		$mimeType			Mime type of the file
	 * @param	String		$filename			Name of the downloaded file shown in the browser
	 * @return	Boolean		File was allowed to download and sent to browser
	 */
	public static function sendFile($absoluteFilePath, $mimeType = null, $filename = null) {
		$pathFile	= realpath($absoluteFilePath);

		if( $pathFile !== false ) {
			if( is_readable($pathFile) ) {
				if( self::isFileInAllowedDownloadPath($pathFile) ) {
						// Send download headers
					$filesize	= filesize($pathFile);
					$filename	= is_null($filename) ? basename($pathFile) : $filename;
					$filemodtime= filemtime($pathFile);
					TodoyuHeader::sendDownloadHeaders($mimeType, $filename, $filesize, $filemodtime);

						// Send file data
					$status = readfile($pathFile);

					if( $status === false ) {
						Todoyu::log('Reading the file failed for a unknown reason: ' . $pathFile, TodoyuLogger::LEVEL_ERROR, $pathFile);
					}

					return $status !== false && $status > 0;
				} else {
					Todoyu::log('Tried to download a file from a not allowed path: ' . $pathFile, TodoyuLogger::LEVEL_SECURITY, $pathFile);
				}
			} else {
				Todoyu::log('sendFile() failed because file was not readable: ' . $pathFile, TodoyuLogger::LEVEL_ERROR, $pathFile);
			}
		} else {
			Todoyu::log('sendFile() failed because file was not found: ' . $pathFile, TodoyuLogger::LEVEL_ERROR, $pathFile);
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
		$pathInfo	= pathinfo($filename);
		$dir		= ( $pathInfo['dirname'] == '.' ) ? '' : $pathInfo['dirname'] . DIR_SEP;

		return $dir . $pathInfo['filename'] . $append . '.' . $pathInfo['extension'];
	}



	/**
	 * Get folder contents
	 *
	 * @param	String		$pathFolder
	 * @param	Boolean		$showHidden
	 * @return	Array
	 */
	public static function getFolderContents($pathFolder, $showHidden = false) {
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
		$elements	= self::getFolderContents($pathFolder, $showHidden);
		$files		= array();

		foreach($elements as $element) {
			if( is_file($pathFolder . DIR_SEP . $element) ) {
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
		$elements		= self::getFolderContents($pathToFolder, $showHidden);
		$folders		= array();

		foreach($elements as $element) {
			if( is_dir($pathToFolder . DIR_SEP . $element) ) {
				$folders[] = $element;
			}
		}

		return $folders;
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
			Todoyu::log('Can\'t open file! File: ' . $file, TodoyuLogger::LEVEL_ERROR);
			return '';
		}
	}



	/**
	 * Get file extension
	 *
	 * @param	String	$filename
	 * @return	String					file extension (without dot)
	 */
	public static function getFileExtension($filename) {
		return pathinfo($filename, PATHINFO_EXTENSION);
	}

}

?>