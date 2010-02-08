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
 * Manage stylesheets and javascripts which are added to the page
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuPageAssetManager {

	/**
	 * Javascripts to add to the page
	 *
	 * @var	Array
	 */
	private static $javascripts = array();

	/**
	 * Stylesheets to add to the page
	 *
	 * @var	Array
	 */
	private static $stylesheets = array();



	/**
	 * Add a javascript file to the page (it will be processed as configured)
	 *
	 * @param	String		$pathToFile			Path to original file
	 * @param	Integer		$position			Position in filelist
	 * @param	Boolean		$compress			Compress content?
	 * @param	Boolean		$merge				Include file into merge file?
	 * @param	Boolean		$localize			Parse locale labels
	 */
	public static function addJavascript($pathToFile, $position = 100, $compress = true, $merge = true, $localize = true) {
		$absPathToFile	= TodoyuFileManager::pathAbsolute($pathToFile);
		$position		= intval($position) == 0 ? 100 : intval($position);
		$compress		= $compress === false ? false : true ;
		$merge			= $merge 	=== false ? false : true ;
		$localize		= $localize === false ? false : true ;

			// Break if file not found
		if( $absPathToFile === false || ! is_file($absPathToFile) ) {
			TodoyuDebug::printHtml($pathToFile, 'File not found');
			return false;
		}

			// Add file, if not already in list
		if( ! array_key_exists($absPathToFile, self::$javascripts) ) {
			self::$javascripts[$absPathToFile] = array(
				'file'		=> $absPathToFile,
				'position'	=> $position,
				'compress'	=> $compress,
				'merge'		=> $merge,
				'localize'	=> $localize,
				'lib'		=> strstr($absPathToFile, PATH_LIB) !== false
			);
		}
	}



	/**
	 * Add a stylesheet file to the page (it will be processed as configured)
	 *
	 * @param	String		$pathToFile			Path to original file
	 * @param	String		$media				Media type
	 * @param	Integer		$position			Position in filelist
	 * @param	Boolean		$compress			Compress content?
	 * @param	Boolean		$merge				Include file into merge file?
	 */
	public static function addStylesheet($pathToFile, $media = 'all', $position = 100, $compress = true, $merge = true) {
		$pathToFile	= TodoyuFileManager::pathAbsolute($pathToFile);
		$position	= intval($position) == 0 ? 100 : intval($position);
		$compress	= $compress === false ? false : true ;
		$merge		= $merge 	=== false ? false : true ;

		if( ! is_file($pathToFile) ) {
			TodoyuDebug::printHtml($pathToFile, 'File not found');
		}

		if( ! array_key_exists($pathToFile, self::$stylesheets) ) {
			self::$stylesheets[$pathToFile] = array(
				'file'		=> $pathToFile,
				'position'	=> $position,
				'compress'	=> $compress,
				'merge'		=> $merge,
				'media'		=> $media
			);
		}
	}



	/**
	 * Add processed assets (JS + CSS) to the page
	 *
	 */
	public static function addAssetsToPage() {
			// Make cache folders
		TodoyuFileManager::makeDirDeep(PATH_CACHE . '/js');
		TodoyuFileManager::makeDirDeep(PATH_CACHE . '/css');

			// Add javascripts
		$jsFiles	= self::getJavascripts();

			// Add all JS files
		foreach($jsFiles as $jsFile) {
			TodoyuPage::add('jsFiles', array(
				'file' => $jsFile
			));
		}

			// Add stylesheets
		$cssFiles	= self::getStylesheets();

			// Add all CSS files
		foreach($cssFiles as $cssFile) {
			TodoyuPage::add('cssFiles', array(
				'file' 	=> $cssFile['file'],
				'media' => $cssFile['media']
			));
		}
	}








	### GLOBAL METHODS ###


	/**
	 * Build a unique mergefile name
	 * The md5 hash is based on the content of the configuration and the
	 * modification times of all included files
	 *
	 * @param	Array		$fileConfigs
	 * @param	String		$fileExt
	 * @return	String
	 */
	private static function buildMergefileName(array $fileConfigs, $fileExt) {
		$files		= TodoyuArray::getColumn($fileConfigs, 'file');
		$md5hash	= md5(implode('', $files));

		return $md5hash . '.' . $fileExt;
	}










	### JAVASCRIPT ###


	/**
	 * Get all JavaScripts which have to be included in the page
	 *
	 * @return	Array
	 */
	private static function getJavascripts() {
		$files	= array();
		$single	= array();

			// Arrays of core / 3rd party merge file configs
		$merge		= array();
		$libsMerge	= array();

		$javascripts= TodoyuArray::sortByLabel(self::$javascripts, 'position');
		$doMerging	= $GLOBALS['CONFIG']['CACHE']['JS']['merge'] === true;

		foreach( $javascripts as $fileConfig ) {
			if( $doMerging && $fileConfig['merge'] ) {
					// If file is a thirdparty library, add to a seperate merge file
				if( $fileConfig['lib'] === true ) {
					$libsMerge[]	= $fileConfig;
				} else {
					$merge[]	= $fileConfig;
				}
			} else {
				$single[]	= $fileConfig;
			}
		}

			// Add single files to list
		if( sizeof($single) ) {
			$files = self::getSingleJavascriptFiles($single);
		}

			// Add library merge file to list
		if( sizeof($libsMerge) > 0 ) {
			$files[] = self::getMergedJavascriptFile($libsMerge);
		}

			// Add merge-file to list
		if( sizeof($merge) > 0 ) {
			$files[] = self::getMergedJavascriptFile($merge);
		}

		return $files;
	}


	/**
	 * Get the javascript merge file
	 *
	 * @param	Array		$fileConfigs		Configs for all files which have merging enabled
	 * @return	String		Web path to merge file in cache
	 */
	private static function getMergedJavascriptFile(array $fileConfigs) {
		$mergeFileName	= self::buildMergefileName($fileConfigs, 'js');
		$mergeFilePath	= PATH_CACHE . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $mergeFileName;

			// If merge file doesn't exist yet, create it
		if( ! is_file($mergeFilePath) ) {
			$mergeCode	= '';

			$doLocalize	= $GLOBALS['CONFIG']['CACHE']['JS']['localize'];
			$doCompress	= $GLOBALS['CONFIG']['CACHE']['JS']['compress'];

			foreach($fileConfigs as $fileConfig) {
				$fileCode	= file_get_contents($fileConfig['file']);

				if( $doLocalize && $fileConfig['localize'] ) {
					$fileCode = self::localizeJavascript($fileCode);
				}
				if( $doCompress && $fileConfig['compress'] ) {
					$fileCode = self::compressJavascript($fileCode);
				}

					// If not compressed, add file information at the top of the code
				if( $doCompress === false ) {
					$fileCode = "\n\n/* " . TodoyuFileManager::pathWeb($fileConfig['file']) . "\n" . str_repeat('=', 50) . "*/\n" . $fileCode;
				}

				$mergeCode .= $fileCode;
			}

			TodoyuFileManager::makeDirDeep( dirname($mergeFilePath) );
			file_put_contents($mergeFilePath, $mergeCode);
		}

		return TodoyuFileManager::pathWeb($mergeFilePath);
	}



	/**
	 * Get paths to javascript files which are not merged (but possibly compressed and localized)
	 *
	 * @param	Array		$fileConfigs
	 * @return	Array
	 */
	private static function getSingleJavascriptFiles(array $fileConfigs) {
		$fileConfigs= TodoyuArray::sortByLabel($fileConfigs);
		$files		= array();
		$doLocalize	= $GLOBALS['CONFIG']['CACHE']['JS']['localize'] === true;
		$doCompress	= $GLOBALS['CONFIG']['CACHE']['JS']['compress'] === true;

		foreach($fileConfigs as $fileConfig) {
			if( ($doLocalize && $fileConfig['localize']) || ($doCompress && $fileConfig['compress']) ) {

				$localized	= $doLocalize && $fileConfig['localize'];
				$compressed	= $doCompress && $fileConfig['compress'];

					// Get file path
				$filePath	= self::getSingleJavascriptPath($fileConfig['file'], $compressed, $localized);

				if( ! is_file($filePath) ) {
					$fileCode	= file_get_contents($fileConfig['file']);

					if( $localized ) {
						$fileCode 	= self::localizeJavascript($fileCode);
						$localized	= true;
					}
					if( $compressed ) {
						$fileCode	= self::compressJavascript($fileCode);
						$compressed	= true;
					}

						// Save content in this file
					TodoyuFileManager::makeDirDeep( dirname($filePath) );
					file_put_contents($filePath, $fileCode);
				}
			} else {
				$filePath	= $fileConfig['file'];
			}

			$files[] = TodoyuFileManager::pathWeb($filePath);
		}

		return $files;
	}



	/**
	 * Path to single javascript file
	 *
	 * @param	String		$pathToFile
	 * @param	Boolean		$compressed
	 * @param	Boolean		$localized
	 * @return	String
	 */
	private static function getSingleJavascriptPath($pathToFile, $compressed = false, $localized = false) {
		$pathToFile	= TodoyuFileManager::pathAbsolute($pathToFile);
		$dirHash	= TodoyuDiv::md5short(dirname($pathToFile));
		$pathInfo	= pathinfo($pathToFile);

		$postfix	= ($compressed ? '-min' : '') . ($localized ? '-' . TodoyuLanguage::getLanguage() : '');

		$storagePath= PATH_CACHE . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $dirHash . '.' . $pathInfo['filename'] . $postfix . '.' . $pathInfo['extension'];

		return $storagePath;
	}



	/**
	 * Compress javascript code
	 *
	 * @param	String		$javascriptCode
	 * @return	String
	 */
	private static function compressJavascript($javascriptCode) {
		try {
			return JSMin::minify($javascriptCode);
		} catch(JSMinException $e) {
			ob_end_clean();
//			TodoyuDebug::printHtml($e->getTrace(), $e->getMessage());
			TodoyuDebug::printHtml($javascriptCode, 'JSMin Error: ' . $e->getMessage());
			exit();
		}
	}



	/**
	 * Localize a javascript
	 *
	 * @param	String	$javascriptCode
	 * @return	String
	 */
	private static function localizeJavascript($javascriptCode) {
		return preg_replace_callback($GLOBALS['CONFIG']['CACHE']['JS']['localePattern'], array('TodoyuPageAssetManager', 'localizeJavascriptCallback'), $javascriptCode);
	}



	/**
	 * Callback for javascrip localization
	 *
	 * @param	Array		$match		Regex matching data
	 * @return	String
	 */
	private static function localizeJavascriptCallback(array $match) {
		return str_replace('\'', '\\\'', TodoyuLanguage::getLabel($match[1]));
	}











	### STYLESHEETS ###


	/**
	 * Get stylesheets which have to be included in the page
	 * The files are merged and compressed as configured in the asset array
	 *
	 * @return	Array
	 */
	private static function getStylesheets() {
		$files	= array();
		$merge	= array();
		$single	= array();

		$stylesheets= TodoyuArray::sortByLabel(self::$stylesheets, 'position');
		$doMerging	= $GLOBALS['CONFIG']['CACHE']['CSS']['merge'];

		foreach( $stylesheets as $fileConfig ) {
			if( $doMerging && $fileConfig['merge'] ) {
				$merge[]	= $fileConfig;
			} else {
				$single[]	= $fileConfig;
			}
		}

			// Process non-merge files
		if( sizeof($single) ) {
			$files = self::getSingleCssFiles($single);
		}

			// Process merge files
		if( sizeof($merge) > 0 ) {
			$files = array_merge($files, self::getMergedCssFiles($merge));
		}

		return $files;
	}



	/**
	 * Get single CSS files (non-merge)
	 *
	 * @param	Array		$fileConfigs
	 * @return	Array		List of file paths with media type attribute
	 */
	private static function getSingleCssFiles(array $fileConfigs) {
		$fileConfigs= TodoyuArray::sortByLabel($fileConfigs, 'position');
		$files		= array();
		$doCompress	= $GLOBALS['CONFIG']['CACHE']['CSS']['compress'];

			// Make sure CSS cache folder exists
		if( $doCompress ) {
			TodoyuFileManager::makeDirDeep( PATH_CACHE . DIRECTORY_SEPARATOR . 'css');
		}

			// Collect file paths and create compressed version if configured
		foreach($fileConfigs as $fileConfig) {
			if( $doCompress && $fileConfig['compress'] ) {
					// Get file path (absolute)
				$filePath	= self::getSingleStylesheetPath($fileConfig['file'], true);

					// If file doesn't exist, create a compressed version
				if( ! is_file($filePath) ) {
						// Get content
					$fileCode	= file_get_contents($fileConfig['file']);
						// Compress
					$fileCode	= self::compressStylesheet($fileCode);
						// Rewrite external media paths (url())
					$fileCode	= self::rewriteRelativPaths($fileCode, $fileConfig['file'], $filePath);
						// Save content in this file
					file_put_contents($filePath, $fileCode);
				}
			} else {
					// No compression, get normal path
				$filePath	= $fileConfig['file'];
			}

			$files[] = array(
				'file'	=> TodoyuFileManager::pathWeb($filePath),
				'media'	=> $fileConfig['media']
			);
		}

		return $files;
	}



	/**
	 * Get path to cached CSS file
	 *
	 * @param	String		$pathToFile			Path to uncached file
	 * @param	Boolean		$compressed			Compress content with cssMin?
	 * @return	String							Absolute path for the cache file
	 */
	private static function getSingleStylesheetPath($pathToFile, $compressed = false) {
		$pathToFile	= TodoyuFileManager::pathAbsolute($pathToFile);
		$dirHash	= TodoyuDiv::md5short(dirname($pathToFile));
		$pathInfo	= pathinfo($pathToFile);

		$postfix	= $compressed ? '-min' : '' ;

		$storagePath= PATH_CACHE . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR. $dirHash . '.' . $pathInfo['filename'] . $postfix . '.' . $pathInfo['extension'];

		return $storagePath;
	}



	/**
	 * Get merged CSS files (one for each media type)
	 *
	 * @param	Array		$fileConfigs
	 * @return	Array
	 */
	private static function getMergedCssFiles(array $fileConfigs) {
		$files		= array();
		$media		= array();
		$doCompress	= $GLOBALS['CONFIG']['CACHE']['CSS']['compress'];

			// Split in to the different media types
		foreach($fileConfigs as $fileConfig) {
			$media[$fileConfig['media']][] = $fileConfig;
		}

			// Process all files of a media type
		foreach($media as $mediaType => $mediaFileConfigs) {
			$mergeFileName	= self::buildMergefileName($mediaFileConfigs, 'css');
			$mergeFilePath	= PATH_CACHE . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR .$mergeFileName;

				// If merge file doesn't exist yet, create it
			if( ! is_file($mergeFilePath) ) {
				$mergeCode	= '';

				foreach($mediaFileConfigs as $fileConfig) {
					if( is_file($fileConfig['file']) )	{
							// Load file content
						$fileCode	= file_get_contents($fileConfig['file']);
							// Rewrite external media paths (url())
						$fileCode	= self::rewriteRelativPaths($fileCode, $fileConfig['file'], $mergeFilePath);

						if( $doCompress && $fileConfig['compress'] ) {
							$fileCode = self::compressStylesheet($fileCode);
						}

							// If not compressed, add file information at the top of the code
						if( $doCompress === false ) {
							$fileCode = "\n\n/* " . TodoyuFileManager::pathWeb($fileConfig['file']) . "\n" . str_repeat('=', 50) . "*/\n" . $fileCode;
						}

						$mergeCode .= $fileCode;
					}
				}

					// Write content into file
				TodoyuFileManager::makeDirDeep( dirname($mergeFilePath) );
				file_put_contents($mergeFilePath, $mergeCode);
			}

			$files[] = array(
				'file'	=> TodoyuFileManager::pathWeb($mergeFilePath),
				'media'	=> $mediaType
			);
		}

		return $files;
	}



	/**
	 * Compress CSS code
	 *
	 * @param	String		$cssCode
	 * @return	String
	 */
	private static function compressStylesheet($cssCode) {
		return cssmin::minify($cssCode);
	}



	/**
	 *  Rewrite relative CSS paths in files
	 *
	 * @param	String		$cssCode
	 * @param	String		$pathToUncompressedFile
	 * @return 	String
	 */
	private static function rewriteRelativPaths($cssCode, $pathToUncompressedFile) {
			// Remove quotes in url() elements
		$pattern	= '|url\([\'"]{1}([^\'")]+?)[\'"]{1}\)|';
		$replace	= 'url($1)';
		$cssCode	= preg_replace($pattern, $replace, $cssCode);

			// Rewrite paths
		$search		= 'url(';
		$replace 	= 'url(../../' . dirname( TodoyuFileManager::pathWeb($pathToUncompressedFile) ) . '/';
		$cssCode	= str_replace($search, $replace, $cssCode);

			// Make a real path
		$search		= '|url\((.*?)\)|';
		$cssCode	= preg_replace_callback($search, array('TodoyuPageAssetManager','callbackRealpath'), $cssCode);

		return $cssCode;
	}



	/**
	 * Callback for make a nicer and shorter path in the CSS file url attributes
	 *
	 * @param	Array		$match		Matching data array
	 * @return	String
	 */
	private static function callbackRealpath(array $match) {
		$realpath	= realpath(PATH_CACHE . '/css/' . $match[1]);

		if( $realpath !== false ) {
			$realpath = '../../' . TodoyuFileManager::pathWeb($realpath);
		} else {
			$realpath = $match[1];
		}

		return 'url(' . $realpath . ')';
	}



	/**
 	 * Add IE custom scripts to the browser (if its an IE)
	 *
	 */
	function addInternetExplorerAssets() {
		if( TodoyuBrowserInfo::isIE() ) {
			self::addJavascript('core/assets/js/IE.js', 1000);
			self::addStylesheet('core/assets/css/ie.css', 'all', 1000);
		}
	}

}


?>
