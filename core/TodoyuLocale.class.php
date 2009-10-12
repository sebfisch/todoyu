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
 * Locale language management for todoyu
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuLocale {

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
	 * Registered module files
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

			// Set php locale
		setlocale(LC_ALL, $locale);
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
	 * @param	String		$labelKey		Key to label. First part is the module
	 * @param	String		$locale			Force locale. If not set, us defined locale
	 * @return	String		Translated label
	 */
	public static function getLabel($labelKey, $locale = null) {
		$locale	= is_null($locale) ? self::$locale : $locale ;

			// Split path parts into module and label index
		$keyParts	= explode('.', $labelKey, 2);
		$moduleKey	=  substr($keyParts[0], 0, 4) == 'LLL:' ? substr($keyParts[0], 4) : $keyParts[0];
		$labelIndex	= $keyParts[1];

		$label	= self::getCachedLabel($moduleKey, $labelIndex, $locale);

		if ($label == '') {
			$label	= 'Label not found: \'' .$labelKey . '\'';
			Todoyu::log($label, LOG_LEVEL_NOTICE);
		}

		return $label;
	}



	/**
	 * Checks if requested lable exists
	 *
	 * @param	String	$labelKey
	 * @param	String	$locale
	 * @return	String
	 */
	public static function labelExists($labelKey, $locale = null)	{
		$locale = is_null($locale)	? self::$locale : $locale ;

		$keyParts	= explode('.', $labelKey, 2);
		$moduleKey	= substr($keyParts[0], 0, 4) === 'LLL:' ? substr($keyParts[0], 4) : $keyParts[0];
		$labelIndex	= $keyParts[1];

		$label	= self::getCachedLabel($moduleKey, $labelIndex, $locale);

		return $label == '' ? false : true;
	}



	/**
	 * Get all module labels (of a file)
	 *
	 * @param	String		$moduleKey
	 * @param	String		$locale
	 * @return	Array
	 */
	public static function getModuleLabels($moduleKey, $locale = null) {
		$locale	= is_null($locale) ? self::$locale : $locale ;

		self::loadModuleLabels($moduleKey, $locale);

		return self::getModuleCache($moduleKey, $locale);
	}



	/**
	 * Register a module file, so translations can be accessed over this key
	 * The $moduleKey has to be unique in the system, else, it will override other translations
	 *
	 * @param	String		$moduleKey			Module key used as prefix of the labels
	 * @param	String		$absPathToFile		Absolute path to the locallang XML file
	 */
	public static function register($identifier, $absPathToFile) {
		$absPathToFile = TodoyuDiv::pathAbsolute($absPathToFile);

		if( !is_file($absPathToFile) ) {
			TodoyuDebug::printHtml($absPathToFile, 'Locale file not found!', null, true);
		}

		self::$files[$identifier] = $absPathToFile;
	}




	/**
	 * Add translation of a module to the internal cache
	 *
	 * @param	String		$moduleKey			Key of the module
	 * @param 	String		$locale				Locale of the translation
	 * @param	Array		$locallangArray		Translated labels
	 */
	private static function setModuleCache($moduleKey, $locale, array $locallangArray) {
		self::$cache[$moduleKey][$locale] = $locallangArray;
	}



	/**
	 * Get translated labels from internal cache if available
	 *
	 * @param	String		$moduleKey
	 * @param	String		$locale
	 * @return	Array
	 */
	private static function getModuleCache($moduleKey, $locale) {
		return !empty(self::$cache[$moduleKey][$locale]) ? self::$cache[$moduleKey][$locale] : array();
	}



	/**
	 * Get a label from internal cache. If the label is not available, load it
	 *
	 * @param	String		$moduleKey		Module key
	 * @param	String		$index			Index of the label in the file
	 * @param	String		$locale			Locale to load the label
	 * @return	String		The label with the key $index for $locale
	 */
	private static function getCachedLabel($moduleKey, $index, $locale = 'en') {
		if( is_null(self::$cache[$moduleKey][$locale][$index]) ) {
			self::loadModuleLabels($moduleKey, $locale);
		}

		return self::$cache[$moduleKey][$locale][$index];
	}



	/**
	 * Get path of the file which is registered for a module key
	 *
	 * @param	String		$moduleKey
	 * @return	String		Abs. path to file
	 */
	private static function getModulePath($moduleKey) {
		return self::$files[$moduleKey];
	}



	/**
	 * Load labels of a module for $locale
	 * Load translated labels into internal cache.
	 * If necessary create a new uptodate cache file.
	 * The following files are checked (by their modification times)
	 * - Registered locallang file (english and the locale)
	 * - External locallang file in english
	 * - External locallang file in locale
	 *
	 * @param	String		$moduleKey		Module key
	 * @param	String		$locale			Requested locale
	 */
	private static function loadModuleLabels($moduleKey, $locale = null) {
		$locale = is_null($locale) ? self::getLocale() : $locale;

		if( empty(self::$cache[$moduleKey][$locale]) ) {
				// Get file paths (files don't need to exist!)
			$origFile	= self::getModulePath($moduleKey);
			$cacheFile	= self::getCacheFileName($moduleKey, $locale);
			$extFile	= self::getExternalFileName($moduleKey, $locale);
			$extFileEn	= self::getExternalFileName($moduleKey, 'en');

				// Get file modification times
			$mTimeOrig	= intval(@filemtime($origFile));
			$mTimeCache	= intval(@filemtime($cacheFile));
			$mTimeExt	= intval(@filemtime($extFile));
			$mTimeExtEn	= intval(@filemtime($extFileEn));

				// If a file is newer than the cache, regenerate the cache from locallang XML files
			if( $mTimeCache < $mTimeOrig || $mTimeCache < $mTimeExt || $mTimeCache < $mTimeExtEn ) {
				$labelsOrig	= self::readLocallangFile($origFile);
				$labelsExt	= self::readLocallangFile($extFile);
				$labelsExtEn= self::readLocallangFile($extFileEn);

					// Load english labels with custom changes from ext file and override it with current locale
				$baseLabelsEn	= self::mergeLabelArray($labelsOrig['en'], $labelsExtEn['en']);
				$baseLabels		= self::mergeLabelArray($baseLabelsEn, $labelsOrig[$locale]);
				$finalLabels	= self::mergeLabelArray($baseLabels, $labelsExt[$locale]);

				self::cacheStore($moduleKey, $finalLabels, $locale);
				self::setModuleCache($moduleKey, $locale, $finalLabels);
			} else {
				$cachedLabels = self::cacheLoad($cacheFile);
				self::setModuleCache($moduleKey, $locale, $cachedLabels);
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
	private static function cacheStore($moduleKey, array $locallangArray, $locale) {
		$cacheData	= serialize($locallangArray);
		$cacheFile	= self::getCacheFileName($moduleKey, $locale);

		TodoyuDiv::makeDirDeep(dirname($cacheFile));

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
	private static function getCacheFileName($moduleKey, $locale) {
		return $GLOBALS['CONFIG']['LOCALE']['cacheDir'] . '/' . $moduleKey . '-' . $locale . '.' . $GLOBALS['CONFIG']['LOCALE']['cacheExt'];
	}



	/**
	 * Make the external file name. The external file doesn't need to exist
	 *
	 * @param	String		$moduleKey
	 * @param	String		$locale
	 * @return	String
	 */
	private static function getExternalFileName($moduleKey, $locale) {
		$absPath	= self::$files[$moduleKey];
		$intPath	= str_replace(PATH . '/', '', $absPath);
		$filename	= str_replace('/', '-', $intPath);

		return $GLOBALS['CONFIG']['LOCALE']['l10nDir'] . '/' . $locale . '/' . $filename;
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



	/**
	 * Register localized labels required for JavaScript usage
	 *
	 * Registered labels are rendered inline in page, to be accessible from JS via Locale['labelName'];
	 *
	 * @param	Array	$labels
	 */
	public static function registerJSlabels($labels) {

		$CONFIG['JS-LOCALE'] = array_merge($GLOBALS['CONFIG']['JS-LOCALE'], $labels);
	}

}



?>