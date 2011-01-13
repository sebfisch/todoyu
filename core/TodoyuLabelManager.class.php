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
 * Language management for todoyu
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuLabelManager {

	/**
	 * @var	String		Current locale key
	 */
	private static $locale = 'en_GB';

	/**
	 * @var	Array		Locallang labels cache
	 */
	public static $cache = array();

	/**
	 * @var	Array		Registered file references
	 */
	private static $files = array();



	/**
	 * Set locale. All request for labels without a locale will use this locale.
	 * Default locale is en_GB (british english)
	 *
	 * @param	String		$language
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
	 * @param	String		$locale			Force language. If not set, us defined language
	 * @return	String		Translated label
	 */
	public static function getLabel($labelKey, $locale = null) {
		return self::getLabelInternal($labelKey, $locale);
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
	 * @param	String		$fullLabelKey
	 * @param	String		$locale
	 * @return	String		Or NULL
	 */
	private static function getLabelInternal($fullLabelKey, $locale = null) {
		$locale	= is_null($locale) ? self::$locale : $locale ;

		if( ! is_string($fullLabelKey) || $fullLabelKey === '' ) {
			if( Todoyu::$CONFIG['LOCALE']['logEmptyKeys'] ) {
				Todoyu::log('Tried to read a label, but no key was provided', TodoyuLogger::LEVEL_ERROR);
			}
			return '';
		}

			// Split path parts into fileKey and label index
		$fullLabelKey	= substr($fullLabelKey, 0, 4) == 'LLL:' ? substr($fullLabelKey, 4) : $fullLabelKey;
		$keyParts		= explode('.', $fullLabelKey, 2);
		$fileKey		= $keyParts[0];
		$labelKey		= $keyParts[1];

			// Return key if not valid key (in debug mode, else empty string)
		if( is_null($labelKey) ) {
			if( Todoyu::$CONFIG['DEBUG'] ) {
				return $fullLabelKey;
			} else {
				return '';
			}
		}

		$label	= self::getCachedLabel($fileKey, $labelKey, $locale);

		if( is_null($label) ) {
			if( Todoyu::$CONFIG['DEBUG'] ) {
				return $fullLabelKey;
			} else {
				return '';
			}
		} else {
			return $label;
		}
	}



	/**
	 * Checks if requested label exists
	 *
	 * @param	String	$labelKey
	 * @param	String	$locale
	 * @return	String
	 */
	public static function labelExists($labelKey, $locale = null) {
		$label	= self::getLabelInternal($labelKey, $locale);

		return !is_null($label);
	}



	/**
	 * Register a file, so translations can be accessed over this key
	 * The $fileKey has to be unique in the system, else, it will override other translations
	 *
	 * @param	String		$fileKey			Filekey used as prefix of the labels
	 * @param	String		$absPathToFile		Absolute path to the locallang XML file
	 */
	public static function register($identifier, $extKey, $file) {
		$baseDir	= TodoyuExtensions::getExtPath($extKey, 'locale');

		self::registerFile($identifier, $file, $baseDir);
	}



	/**
	 * Register a label file for the core
	 *
	 * @param	String		$identifier
	 * @param	String		$filename
	 */
	public static function registerCore($identifier, $filename) {
		$baseDir	= TodoyuFileManager::pathAbsolute(PATH_CORE . '/locale');

		self::registerFile($identifier, $filename, $baseDir);
	}



	/**
	 * Register a label file
	 *
	 * @param	String		$identifier			Prefix to get labels from the file
	 * @param	String		$filename			Filename (exists in all/some locale folders)
	 * @param	String		$dir				Absolute path to the base folder which contains the locale folders
	 */
	public static function registerFile($identifier, $filename, $dir) {
		$defaultLocale	= Todoyu::$CONFIG['LOCALE']['default'];
		$pathTestFile	= TodoyuFileManager::pathAbsolute($dir . '/' . $defaultLocale . '/' . $filename);

		if( ! is_file($pathTestFile) ) {
			Todoyu::log('Language file for ' . $defaultLocale . ' not found in "' . $dir . '"!');
			TodoyuDebug::printHtml($pathTestFile, 'Language file for ' . $defaultLocale . ' not found in "' . $dir . '"!');
			//TodoyuDebug::printBacktrace();
		}

		self::$files[$identifier] = array(
			'dir'	=> $dir,
			'file'	=> $filename
		);
	}






	/**
	 * Get a label from internal cache. If the label is not available, load it
	 *
	 * @param	String		$fileKey		Filekey
	 * @param	String		$labelKey		Index of the label in the file
	 * @param	String		$locale			Locale to load the label
	 * @return	String		The label with the key $index for $language
	 */
	private static function getCachedLabel($fileKey, $labelKey, $locale = null) {
		$locale	= is_null($locale) ? self::$locale : $locale ;

		if( ! is_string(self::$cache[$fileKey][$locale][$labelKey]) ) {
			self::$cache[$fileKey][$locale] = self::getFileLabels($fileKey, $locale);
		}

		return self::$cache[$fileKey][$locale][$labelKey];
	}



	/**
	 * Get path of the file which is registered for a file key
	 *
	 * @param	String		$identifier
	 * @return	String		Abs. path to file
	 */
	private static function getFilePath($identifier, $locale) {
		$path	= $locale . '/' . self::$files[$identifier]['file'];

		return TodoyuFileManager::pathAbsolute(self::$files[$identifier]['dir'] . '/' . $path);
	}



	/**
	 * Get all fallback locales of a locale
	 *
	 * @param	String		$locale
	 * @return	Array
	 */
	public static function getFallbackLocales($locale) {
		$fallbacks	= TodoyuArray::assure(Todoyu::$CONFIG['LOCALE']['fallback']);
		$fallback	= array();
		$tmpLocale	= $locale;
		$counter	= 0;

			// Dig down the fallback languages. The counter prevents endless loops for bad configuration
		while( $counter < 10 ) {
			if( array_key_exists($tmpLocale, $fallbacks) ) {
					// If fallback defined, add it and check again
				$fallback[] = $fallbacks[$tmpLocale];
				$tmpLocale	= $fallbacks[$tmpLocale];
			} else {
					// If no fallback defined for locale, add default locale and stop searching
				$fallback[] = Todoyu::$CONFIG['LOCALE']['default'];
				break;
			}
		}

		return array_reverse(array_unique($fallback));
	}



	/**
	 * Get labels for an identifier for a locale
	 *
	 * @param	String		$identifier
	 * @param	String		$locale
	 * @return	Array
	 */
	private static function getFileLabels($identifier, $locale = null) {
		$locale		= is_null($locale) ? self::$locale : $locale;
		$locales	= self::getFallbackLocales($locale);
		$cacheFile	= self::getCacheFileName($identifier, $locale);

		if( is_file($cacheFile) ) {
			return self::readCachedLabelFile($cacheFile);
		}

		$locales[] = $locale;

		$labels	= array();

		foreach($locales as $fallbackLocale) {
			$pathFile	= self::getFilePath($identifier, $fallbackLocale);

			if( is_file($pathFile) ) {
				$localeLabels	= self::readXmlFile($pathFile);

				$labels	= array_merge($labels, $localeLabels);
			}
		}

			// Only write a cache file when labels are found
		if( sizeof($labels) > 0 ) {
			self::writeCachedLabelFile($cacheFile, $labels);
		}

		return $labels;
	}



	/**
	 * Read a locallang XML file using a XML parser.
	 * Transforms the parser result in an usful array
	 * Structure [de][INDEX] = Label
	 *
	 * @param	String		$absPathToLocallangFile		Absolute path to locallang file
	 * @return	Array
	 */
	private static function readXmlFile($absPathToLocallangFile) {
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

		return self::extractLabelsFromXmlResult($values);
	}



	/**
	 * Transform the output of the XML parser to an useful array
	 *
	 * @param	Array		$xmlValueArray
	 * @return	Array
	 */
	private static function extractLabelsFromXmlResult(array $xmlValueArray) {
		$labels 	= array();

		foreach($xmlValueArray as $xmlTag) {
			switch($xmlTag['type']) {

				case 'open':
				case 'close':
					// Nothing to do
					break;

				case 'complete':
					$index = $xmlTag['attributes']['index'];
					$labels[$index] = $xmlTag['value'];
					break;
			}
		}

		return $labels;
	}



	/**
	 * Save locallang array to cache
	 *
	 * @param	String		$pathFile
	 * @param	Array		$labels
	 * @return	Boolean
	 */
	private static function writeCachedLabelFile($pathFile, array $labels) {
		$cacheData	= serialize($labels);

		TodoyuFileManager::makeDirDeep(dirname($pathFile));

		return file_put_contents($pathFile, $cacheData) !== false;
	}



	/**
	 * Load cache file and get back locallang array
	 *
	 * @param	String		$cacheFile
	 * @return	Array
	 */
	private static function readCachedLabelFile($cacheFile) {
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
		return TodoyuFileManager::pathAbsolute(Todoyu::$CONFIG['LOCALE']['labelCacheDir'] . DIR_SEP . $locale . DIR_SEP . $fileKey . '.labels');
	}

}

?>