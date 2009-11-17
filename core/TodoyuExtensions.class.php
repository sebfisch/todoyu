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
 * Manage Todoyu extensions
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuExtensions {

	private static $extIDs = array();

	private static $extKeys = array();

	private static function saveInstalledExtensions() {
		$file	= PATH_CONFIG . '/extensions.php';
		$tmpl	= PATH_CORE . '/view/extensions.php.tmpl';
		$data	= array(
			'extensions'	=> $GLOBALS['CONFIG']['EXT']['installed']
		);

		TodoyuFileManager::saveTemplatedFile($file, $tmpl, $data);
	}



	/**
	 * Get extension keys of all installed extensions
	 *
	 * @return	Array
	 */
	public static function getInstalledExtKeys() {
		return $GLOBALS['CONFIG']['EXT']['installed'];
	}



	/**
	 * Get extension keys (folder names) of extensions which are located in
	 * the /ext folder, but not installed at the moment
	 *
	 * @return	Array
	 */
	public static function getNotInstalledExtKeys() {
		$extFolders		= TodoyuFileManager::getFoldersInFolder(PATH_EXT);
		$extInstalled	= TodoyuExtensions::getInstalledExtKeys();

		return array_diff($extFolders, $extInstalled);
	}



	/**
	 * Get extension ids and keys of all installed extensions
	 *
	 * @return	Array
	 */
	public static function getInstalledExtIDs() {
		$extKeys	= self::getInstalledExtKeys();
		$extIDs		= array();

		foreach($extKeys as $extKey) {
			$extIDs[$extKey] = constant('EXTID_' . strtoupper($extKey));
		}

		return $extIDs;
	}



	/**
	 * Check if an extension is installed
	 *
	 * @param	String		$extKey
	 * @return	Boolean
	 */
	public static function isInstalled($extKey) {
		$installed	= self::getInstalledExtKeys();

		return in_array($extKey, $installed);
	}




	public static function install($extKey) {
		$GLOBALS['CONFIG']['EXT']['installed'][] = $extKey;

		$GLOBALS['CONFIG']['EXT']['installed'] = array_unique($GLOBALS['CONFIG']['EXT']['installed']);

		self::saveInstalledExtensions();
	}


	public static function uninstall($extKey) {
		$installed	= array_flip($GLOBALS['CONFIG']['EXT']['installed']);

		unset($installed[$extKey]);

		$GLOBALS['CONFIG']['EXT']['installed']	= array_keys($installed);

		self::saveInstalledExtensions();
	}



	/**
	 * Get extID by extKey
	 *
	 * @param	String		$extKey
	 * @return	Integer
	 */
	public static function getExtID($extKey) {
		$name	= 'EXTID_' . strtoupper(trim($extKey));

		if( defined($name) ) {
			return constant($name);
		} else {
			return 0;
		}
	}



	/**
	 * Add an extension classpath to the global include path
	 * for autoloading classes
	 *
	 * @param	String		$includePath
	 */
	public static function addIncludePath($includePath) {
		if( !in_array($includePath, $GLOBALS['CONFIG']['AUTOLOAD']) ) {
			$GLOBALS['CONFIG']['AUTOLOAD'][] = $includePath;
		}
	}



	/**
	 * Check if file path is in the path of the extension
	 *
	 * @param	String		$extKey
	 * @param	String		$filePath
	 * @return	Boolean
	 */
	public static function isPathInExtDir($extKey, $filePath) {
		$path = realpath($filePath);

		if( $path === false ) {
			return false;
		}

			// Extensio path
		$extPath	= self::getExtPath($extKey);

			// Check if the extension path is the first part of the file path (position = 0)
		return strpos($path, $extPath) === 0;
	}



	/**
	 * Get full path of the extension (or FALSE if extension is not installed)
	 *
	 * @param	String		$extKey
	 * @return	String		Or FALSE
	 */
	public static function getExtPath($extKey) {
		return PATH_EXT . DIRECTORY_SEPARATOR . $extKey;
	}



	/**
	 * Get extension information
	 *
	 * @param	String		$extKey			Extension key
	 * @return	Array		Or false if not defined
	 */
	public static function getExtInfo($extKey) {
		self::loadConfig($extKey, 'extinfo');

		if( is_array($GLOBALS['CONFIG']['EXT'][$extKey]['info']) ) {
			return $GLOBALS['CONFIG']['EXT'][$extKey]['info'];
		} else {
			return false;
		}
	}



	/**
	 * Get list of all extensions info
	 *
	 * @return	Array
	 */
	public static function getAllExtInfo() {
		$extensions	= self::getInstalledExtKeys();
		$infos		= array();

		foreach($extensions as $ext) {
			$infos[$ext] = self::getExtInfo($ext);
		}

		return $infos;
	}



	/**
	 * Load a configuration file of an extension if it's available
	 *
	 * @param	String		$extKey		Extension key
	 * @param	String		$type		Type of the config file (=filename)
	 * @return	Boolean		Loading status
	 */
	public static function loadConfig($extKey, $type) {
		global $CONFIG;

		$filePath	= realpath(PATH_EXT . '/' . $extKey . '/config/' . $type . '.php');

		if( $filePath !== false && self::isPathInExtDir($extKey, $filePath) ) {
			if( is_file($filePath) ) {
				include_once($filePath);
				return true;
			}
		}

		return false;
	}



	/**
	 * Load rights config of an extension
	 *
	 * @param	String		$extKey
	 * @return	Boolean
	 */
	public static function loadRights($extKey) {
		return self::loadConfig($extKey, 'rights');
	}



	/**
	 * Load table config of an extension
	 *
	 * @param	String		$extKey
	 * @return	Boolean
	 */
	public static function loadTables($extKey) {
		return self::loadConfig($extKey, 'tables');
	}



	/**
	 * Load filter config of an extension
	 *
	 * @param	String		$extKey
	 * @return	Boolean
	 */
	public static function loadFilters($extKey) {
		return self::loadConfig($extKey, 'filters');
	}



	/**
	 * Load all configuration files of an extension
	 *
	 * @param	String		$extKey
	 */
	public static function loadAllConfig($extKey) {
		$extPath	= self::getExtPath($extKey);

		$configDir	= $extPath . '/config';
		$configFiles= array_slice(scandir($configDir), 2);

		foreach($configFiles as $file) {
			include_once( $configDir . '/' . $file );
		}
	}



	/**
	 * Load config of a type from all extension
	 *
	 * @param	String		$type
	 */
	public static function loadAllTypeConfig($type) {
		$extKeys	= self::getInstalledExtKeys();

		foreach($extKeys as $extKey) {
			self::loadConfig($extKey, $type);
		}
	}



	/**
	 * Load filter config from all extensions
	 *
	 */
	public static function loadAllFilters() {
		self::loadAllTypeConfig('filters');
	}



	/**
	 * Load rights config from all extensions
	 *
	 */
	public static function loadAllRights() {
		self::loadAllTypeConfig('rights');
	}



	/**
	 * Load table config from all extensions
	 *
	 */
	public static function loadAllTables() {
		self::loadAllTypeConfig('tables');
	}



	/**
	 * Load contextmenu config from all extensions
	 *
	 */
	public static function loadAllContextMenus() {
		self::loadAllTypeConfig('contextmenu');
	}



	/**
	 * Load form config from all extensions
	 *
	 */
	public static function loadAllForm() {
		global $CONFIG;

		require_once( PATH_CONFIG . '/form.php');

		self::loadAllTypeConfig('form');
	}



	/**
	 * Load asset config for all extensions
	 *
	 */
	public static function loadAllAssets() {
		global $CONFIG;

		self::loadAllTypeConfig('assets');
	}



	/**
	 * Load admin config for all extensions
	 *
	 */
	public static function loadAllAdmin() {
		global $CONFIG;

		self::loadAllTypeConfig('admin');
	}



	/**
	 * Load extension informations for all extensions
	 *
	 */
	public static function loadAllExtinfo() {
		global $CONFIG;

		self::loadAllTypeConfig('extinfo');
	}



	/**
	 * Load panelwidget config for all extensions
	 *
	 */
	public static function loadAllPanelWidget() {
		global $CONFIG;

		self::loadAllTypeConfig('panelwidgets');
	}

}

?>