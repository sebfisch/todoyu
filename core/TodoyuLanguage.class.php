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
 * Language management for todoyu
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuLanguage {

	/**
	 * Current locale key. Default is english
	 *
	 * @var	String
	 */
	private static $locale = 'en';

	/**
	 * Locallang labels cache
	 *
	 * @var	Array
	 */
	public static $cache = array();

	/**
	 * Registered file references
	 *
	 * @var	Array
	 */
	private static $files = array();



	/**
	 * Set locale. All request for labels without a locale will use this locale.
	 * Default locale is en (english)
	 *
	 * @param	String		$locale
	 */
	public static function setLocale($locale) {
		self::$locale = $locale;
	}



	/**
	 * Get current locale
	 *
	 * @return	String
	 */
	public static function getLocale() {
		return self::$locale;
	}



	/**
	 * Get translated label
	 *
	 * @param	String		$labelKey		Key to label. First part is the fileKey
	 * @param	String		$locale			Force locale. If not set, us defined locale
	 * @return	String		Translated label
	 */
	public static function getLabel($labelKey, $locale = null) {
		$label	= self::getLabelInternal($labelKey, $locale);

		if( $label === '' && $GLOBALS['CONFIG']['DEBUG'] ) {
			Todoyu::log($label, LOG_LEVEL_NOTICE);
			$label	= 'Label not found: #' .$labelKey . '#';
		}

		return $label;
	}



	/**
	 * Get label which will be parsed with wildcards like printf()
	 *
	 * @param	String		$labelKey
	 * @param	Array		$wildcards
	 * @param	String		$locale
	 * @return	String
	 */
	public static function getFormatLabel($labelKey, array $wildcards = array(), $locale = null) {
		$label	= self::getLabel($labelKey, $locale);

		return vsprintf($label, $wildcards);
	}



	/**
	 * Get label if it exists. If not existing, get empty string
	 *
	 * @param	String		$labelKey
	 * @param	String		$locale
	 * @return	String
	 */
	public static function getLabelIfExists($labelKey, $locale = null) {
		return trim(self::getLabelInternal($labelKey, $locale));
	}



	/**
	 * Get label or null if not existing
	 *
	 * @param	String		$labelKey
	 * @param	String		$locale
	 * @return	String		Or NULL
	 */
	private static function getLabelInternal($labelKey, $locale = null) {
		$locale	= is_null($locale) ? self::$locale : $locale ;

			// Split path parts into fileKey and label index
		$keyParts	= explode('.', $labelKey, 2);
		$fileKey	=  substr($keyParts[0], 0, 4) == 'LLL:' ? substr($keyParts[0], 4) : $keyParts[0];
		$labelIndex	= $keyParts[1];

		return self::getCachedLabel($fileKey, $labelIndex, $locale);
	}



	/**
	 * Checks if requested lable exists
	 *
	 * @param	String	$labelKey
	 * @param	String	$locale
	 * @return	String
	 */
	public static function labelExists($labelKey, $locale = null)	{
		$label	= self::getLabelInternal($labelKey, $locale);

		return !is_null($label);
	}



	/**
	 * Get all file labels
	 *
	 * @param	String		$fileKey
	 * @param	String		$locale
	 * @return	Array
	 */
	public static function getFileLabels($fileKey, $locale = null) {
		$locale	= is_null($locale) ? self::$locale : $locale ;

		self::loadFileLabels($fileKey, $locale);

		return self::getFileCache($fileKey, $locale);
	}



	/**
	 * Register a file, so translations can be accessed over this key
	 * The $fileKey has to be unique in the system, else, it will override other translations
	 *
	 * @param	String		$fileKey			Filekey used as prefix of the labels
	 * @param	String		$absPathToFile		Absolute path to the locallang XML file
	 */
	public static function register($identifier, $absPathToFile) {
		$absPathToFile = TodoyuFileManager::pathAbsolute($absPathToFile);

		if( ! is_file($absPathToFile) ) {
			TodoyuDebug::printHtml($absPathToFile, 'Locale file not found!', null, true);
		}

		self::$files[$identifier] = $absPathToFile;
	}



	/**
	 * Add translation of a file to the internal cache
	 *
	 * @param	String		$fileKey			Key of the file
	 * @param 	String		$locale				Locale of the translation
	 * @param	Array		$locallangArray		Translated labels
	 */
	private static function setFileCache($fileKey, $locale, array $locallangArray) {
		self::$cache[$fileKey][$locale] = $locallangArray;
	}



	/**
	 * Get translated labels from internal cache if available
	 *
	 * @param	String		$fileKey
	 * @param	String		$locale
	 * @return	Array
	 */
	private static function getFileCache($fileKey, $locale) {
		return !empty(self::$cache[$fileKey][$locale]) ? self::$cache[$fileKey][$locale] : array();
	}



	/**
	 * Get a label from internal cache. If the label is not available, load it
	 *
	 * @param	String		$fileKey		Filekey
	 * @param	String		$index			Index of the label in the file
	 * @param	String		$locale			Locale to load the label
	 * @return	String		The label with the key $index for $locale
	 */
	private static function getCachedLabel($fileKey, $index, $locale = 'en') {
		if( is_null(self::$cache[$fileKey][$locale][$index]) ) {
			self::loadFileLabels($fileKey, $locale);
		}

		return self::$cache[$fileKey][$locale][$index];
	}



	/**
	 * Get path of the file which is registered for a file key
	 *
	 * @param	String		$fileKey
	 * @return	String		Abs. path to file
	 */
	private static function getFilePath($fileKey) {
		return self::$files[$fileKey];
	}



	/**
	 * Load labels of a file for $locale
	 * Load translated labels into internal cache.
	 * If necessary create a new uptodate cache file.
	 * The following files are checked (by their modification times)
	 * - Registered locallang file (english and the locale)
	 * - External locallang file in english
	 * - External locallang file in locale
	 *
	 * @param	String		$fileKey		Filekey
	 * @param	String		$locale			Requested locale
	 */
	private static function loadFileLabels($fileKey, $locale = null) {
		$locale = is_null($locale) ? self::getLocale() : $locale;

		if( empty(self::$cache[$fileKey][$locale]) ) {
				// Get file paths (files don't need to exist!)
			$origFile	= self::getFilePath($fileKey);
			$cacheFile	= self::getCacheFileName($fileKey, $locale);
			$extFile	= self::getExternalFileName($fileKey, $locale);
			$extFileEn	= self::getExternalFileName($fileKey, 'en');

//			TodoyuDebug::printInFirebug($extFile, '$extFile');

				// Get file modification times
			$mTimeOrig	= is_file($origFile) ? filemtime($origFile) : 0;
			$mTimeCache	= is_file($cacheFile) ? filemtime($cacheFile) : 0;
			$mTimeExt	= is_file($extFile) ? filemtime($extFile) : 0;
			$mTimeExtEn	= is_file($extFileEn) ? filemtime($extFileEn) : 0;

				// If a file is newer than the cache, regenerate the cache from locallang XML files
			if( $mTimeCache < $mTimeOrig || $mTimeCache < $mTimeExt || $mTimeCache < $mTimeExtEn ) {
				$labelsOrig	= self::readLocallangFile($origFile);
				$labelsExt	= self::readLocallangFile($extFile);
				$labelsExtEn= self::readLocallangFile($extFileEn);

					// Load english labels with custom changes from ext file and override it with current locale
				$baseLabelsEn	= self::mergeLabelArray($labelsOrig['en'], $labelsExtEn['en']);
				$baseLabels		= self::mergeLabelArray($baseLabelsEn, $labelsOrig[$locale]);
				$finalLabels	= self::mergeLabelArray($baseLabels, $labelsExt[$locale]);

				self::cacheStore($fileKey, $finalLabels, $locale);
				self::setFileCache($fileKey, $locale, $finalLabels);
			} else {
				$cachedLabels = self::cacheLoad($cacheFile);
				self::setFileCache($fileKey, $locale, $cachedLabels);
			}
		}
	}



	/**
	 * Read a locallang XML file using a XML parser.
	 * Transforms the parser result in an usful array
	 * Structure [de][INDEX] = Label
	 *
	 * @param	String		$absPathToLocallangFile		Absolute path to locallang file
	 * @return	Array
	 */
	private static function readLocallangFile($absPathToLocallangFile) {
		if( !is_file($absPathToLocallangFile) ) {
			return array();
		}

		$xmlString	= file_get_contents($absPathToLocallangFile);
		$parser		= xml_parser_create('UTF-8');

		$values	= $index = array();

		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    	xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, 'UTF-8');

		xml_parse_into_struct($parser, $xmlString, $values, $index);

		xml_parser_free($parser);

		return self::transformXmlToLocallang($values);
	}



	/**
	 * Read external locallang file.
	 * External files are overriding changes on the original files stored in /l10n/
	 * under to specific locale
	 *
	 * @param	String		$absPathToLocallangFile
	 * @param	String		$locale
	 * @return	Array
	 */
	private static function readExternalLocallangFile($absPathToLocallangFile, $locale) {
		$extFile	= self::getExternalFileName($absPathToLocallangFile, $locale);

		return self::readLocallangFile($extFile);
	}



	/**
	 * Transform the output of the XML parser to an useful array
	 *
	 * @param	Array		$xmlValueArray
	 * @return	Array
	 */
	private static function transformXmlToLocallang(array $xmlValueArray) {
		$locallangArray = array();
		$languageKey	= 'none';

		foreach($xmlValueArray as $xmlTag) {
			switch($xmlTag['type']) {

				case 'open':
					if( $xmlTag['tag'] === 'labels' ) {
						$languageKey = $xmlTag['attributes']['lang'];
						$locallangArray[$languageKey] = array();
					}
					break;


				case 'close':
					// Nothing to do
					break;


				case 'complete':
					$index = $xmlTag['attributes']['index'];
					$locallangArray[$languageKey][$index] = $xmlTag['value'];
					break;
			}
		}

		return $locallangArray;
	}



	/**
	 * Save locallang array to cache
	 *
	 * @param	String		$absPathToLocallangFile
	 * @param	Array		$locallangArray
	 * @return	Boolean
	 */
	private static function cacheStore($fileKey, array $locallangArray, $locale) {
		$cacheData	= serialize($locallangArray);
		$cacheFile	= self::getCacheFileName($fileKey, $locale);

		TodoyuFileManager::makeDirDeep(dirname($cacheFile));

		return file_put_contents($cacheFile, $cacheData) !== false;
	}



	/**
	 * Load cache file and get back locallang array
	 *
	 * @param	String		$cacheFile
	 * @return	Array
	 */
	private static function cacheLoad($cacheFile) {
		if( is_file($cacheFile) ) {
			$cacheData	= file_get_contents($cacheFile);
			$locallang	= unserialize($cacheData);
		} else {
			$locallang	= array();
		}

		return $locallang;
	}



	/**
	 * Make cache file name. Based on the path to the XML file and its modification time
	 *
	 * @param	String		$absPathToLocallangFile
	 * @return	String
	 */
	private static function getCacheFileName($fileKey, $locale) {
		return TodoyuFileManager::pathAbsolute($GLOBALS['CONFIG']['LANGUAGE']['cacheDir'] . DIRECTORY_SEPARATOR . $fileKey . '-' . $locale . '.' . $GLOBALS['CONFIG']['LANGUAGE']['cacheExt']);
	}



	/**
	 * Make the external file name. The external file doesn't need to exist
	 *
	 * @param	String		$fileKey
	 * @param	String		$locale
	 * @return	String
	 */
	private static function getExternalFileName($fileKey, $locale) {
		$absPath	= self::$files[$fileKey];
		$intPath	= str_replace(PATH . DIRECTORY_SEPARATOR, '', $absPath);
		$filename	= str_replace(DIRECTORY_SEPARATOR, '-', $intPath);

		return $GLOBALS['CONFIG']['LANGUAGE']['l10nDir'] . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . $filename;
	}



	/**
	 * Merge label arrays. The second array will override the identical indexes
	 * of the first array if they exist
	 *
	 * @param	Array		$baseLabels
	 * @param	Array		$overrideLabels
	 * @return	Array
	 */
	private static function mergeLabelArray($baseLabels, $overrideLabels) {
		if( !is_array($baseLabels) ) {
			$baseLabels = array();
		}
		if( !is_array($overrideLabels) ) {
			$overrideLabels = array();
		}

		foreach($baseLabels as $index => $label) {
			if( array_key_exists($index, $overrideLabels) ) {
				$baseLabels[$index] = $overrideLabels[$index];
			}
		}

		return $baseLabels;
	}

}



?>