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

/**
 * Manage Todoyu extensions
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuExtensions {

	/**
	 * Get extension keys of all installed extensions
	 *
	 * @return	Array
	 */
	public static function getInstalledExtKeys() {
		return TodoyuArray::assure(Todoyu::$CONFIG['EXT']['installed']);
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
	 * Check if file path is in the path of the extension
	 *
	 * @param	String		$extKey
	 * @param	String		$filePath
	 * @return	Boolean
	 */
	public static function isPathInExtDir($extKey, $path) {
		$path = TodoyuFileManager::pathAbsolute($path);

			// Extension path
		$extPath	= self::getExtPath($extKey);

			// Check if the extension path is the first part of the file path (position = 0)
		return strpos($path, $extPath) === 0;
	}



	/**
	 * Get full path of the extension
	 * This is the path an extension would have. Doesn't mean the path exists or extension is installed
	 *
	 * @param	String		$extKey
	 * @return	String		Absolute path to extension
	 */
	public static function getExtPath($extKey, $appendPath = '') {
		return TodoyuFileManager::pathAbsolute(PATH_EXT . DIR_SEP . $extKey . DIR_SEP . trim($appendPath, '/\\'));
	}



	/**
	 * Get extension information
	 *
	 * @param	String		$extKey			Extension key
	 * @return	Array		Or false if not defined
	 */
	public static function getExtInfo($extKey) {
		self::loadConfig($extKey, 'extinfo');

		if( is_array(Todoyu::$CONFIG['EXT'][$extKey]['info']) ) {
			return Todoyu::$CONFIG['EXT'][$extKey]['info'];
		} else {
			return false;
		}
	}



	/**
	 * Get extension version
	 *
	 * @param	String		$extKey
	 * @return	String|Boolean
	 */
	public static function getExtVersion($extKey) {
		$info	= self::getExtInfo($extKey);

		if( $info !== false ) {
			return $info['version'];
		} else {
			return '0.0.0';
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
		$filePath	= realpath(PATH_EXT . DIR_SEP . $extKey . DIR_SEP . 'config' . DIR_SEP . $type . '.php');

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

		$configDir	= $extPath . DIR_SEP . 'config';
		$configFiles= array_slice(scandir($configDir), 2);

		foreach($configFiles as $file) {
			include_once( $configDir . DIR_SEP . $file );
		}
	}



	/**
	 * Load config of a type from all extension (require /config/type.php files of extensions)
	 *
	 * @param	String		$type
	 */
	public static function loadAllTypeConfig($type) {
		$extKeys	= self::getInstalledExtKeys();

		foreach($extKeys as $extKey) {
			self::loadConfig($extKey, $type);
		}

			// Check if a config in core is available
		$coreConf	= PATH_CONFIG . '/' . $type . '.php';
		if( is_file($coreConf) ) {
			require_once($coreConf);
		}
	}



	/**
	 * Load filter config from all extensions
	 */
	public static function loadAllFilters() {
		self::loadAllTypeConfig('filters');
	}



	/**
	 * Load rights config from all extensions
	 */
	public static function loadAllRights() {
		self::loadAllTypeConfig('rights');
	}



	/**
	 * Load context menu config from all extensions
	 */
	public static function loadAllContextMenus() {
		self::loadAllTypeConfig('contextmenu');
	}



	/**
	 * Load form config from all extensions
	 */
	public static function loadAllForm() {
		require_once( PATH_CONFIG . '/form.php');

		self::loadAllTypeConfig('form');
	}



	/**
	 * Load asset config for all extensions
	 */
	public static function loadAllAssets() {
		self::loadAllTypeConfig('assets');
	}



	/**
	 * Load admin config for all extensions
	 */
	public static function loadAllAdmin() {
		self::loadAllTypeConfig('admin');
	}



	/**
	 * Load extension informations for all extensions
	 */
	public static function loadAllExtinfo() {
		self::loadAllTypeConfig('extinfo');
	}



	/**
	 * Load panelwidget config for all extensions
	 */
	public static function loadAllPanelWidget() {
		self::loadAllTypeConfig('panelwidgets');
	}



	/**
	 * Load all page config (tabs, etc)
	 */
	public static function loadAllPage() {
		self::loadAllTypeConfig('page');
	}



	/**
	 * Load all search config (/config/search.php files of all loaded extensions)
	 */
	public static function loadAllSearch() {
		self::loadAllTypeConfig('search');
	}



	/**
	 * Load all create configs (/config/create.php files of all loaded extensions)
	 */
	public static function loadAllCreate() {
		self::loadAllTypeConfig('create');
	}



	/**
	 * Load all boot configs (/config/boot.php)
	 */
	public static function loadAllBoot() {
		self::loadAllTypeConfig('boot');
	}



	/**
	 * Load all init configs (config/init.php)
	 */
	public static function loadAllInit() {
		self::loadAllTypeConfig('init');
	}



	/**
	 * Add extension paths to the autoload config
	 * Adds the default paths (model,controller) and custom paths from $extraPaths
	 *
	 * @param	String		$extKey
	 * @param	Array		$extraPaths
	 */
	public static function addExtAutoloadPaths($extKey, array $extraPaths = array()) {
		$extDir	= TodoyuExtensions::getExtPath($extKey);

		Todoyu::addIncludePath($extDir . '/model');
		Todoyu::addIncludePath($extDir . '/controller');

		foreach($extraPaths as $extraPath) {
			$path	= TodoyuFileManager::pathAbsolute($extDir . '/' . $extraPath);

			if( is_dir($path) ) {
				Todoyu::addIncludePath($path);
			}
		}
	}



	/**
	 * Check whether given extension depends on other extensions
	 *
	 * @param	String		$extKey
	 * @return	Boolean
	 */
	public static function hasDependencies($extKey) {
		$dependencies	= self::getDependencies($extKey);

		return sizeof($dependencies) > 0;
	}



	/**
	 * Get keys of extensions the given extension depends on
	 *
	 * @param	String	$extKey
	 * @return	Array
	 */
	public static function getDependencies($extKey) {
		$extInfo	= self::getExtInfo($extKey);

		return $extInfo['constraints']['depends'];
	}



	/**
	 * Check whether other extensions depend on given extension
	 *
	 * @param	String		$extKey
	 * @return	Boolean
	 */
	public static function hasDependents($extKey) {
		$dependents	= self::getDependents($extKey);

		return sizeof($dependents) > 0;
	}



	/**
	 * Get all dependents of an extensions
	 *
	 * @param	String		$extKeyToCheck
	 * @return	Array
	 */
	public static function getDependents($extKeyToCheck) {
		self::loadAllExtinfo();

		$dependents	= array();
		$extKeys	= self::getInstalledExtKeys();

		foreach($extKeys as $extKey) {
			$dependInfo	= Todoyu::$CONFIG['EXT'][$extKey]['info']['constraints']['depends'];

			if( is_array($dependInfo) ) {
				if( array_key_exists($extKeyToCheck, $dependInfo) ) {
					$dependents[] = $extKey;
				}
			}
		}

		return $dependents;
	}



	/**
	 * Check if an extension has the system flag (should not be uninstalled)
	 *
	 * @param	String		$extKey
	 * @return	Boolean
	 */
	public static function isSystemExtension($extKey) {
		self::loadConfig($extKey, 'extinfo');

		return Todoyu::$CONFIG['EXT'][$extKey]['info']['constraints']['system'] === true;
	}



	/**
	 * Check whether the extension has conflicts
	 *
	 * @param	String		$extKey
	 * @return	Boolean
	 */
	public static function hasConflicts($extKey) {
		return sizeof(self::getConflicts($extKey)) > 0;
	}



	/**
	 * Check whether the extension conflicts with another installed extension
	 *
	 * @param	String		$extKeyToCheck
	 * @return	Array		List of extensions which conflict with the checked one
	 */
	public static function getConflicts($extKeyToCheck) {
		self::loadAllExtinfo();

		$conflicts	= array();
		$extKeys	= self::getInstalledExtKeys();

		foreach($extKeys as $extKey) {
			$conflictInfo	= Todoyu::$CONFIG['EXT'][$extKey]['info']['constraints']['conflict'];

			if( is_array($conflictInfo) ) {
				if( array_key_exists($extKeyToCheck, $conflictInfo) ) {
					$conflicts[] = $extKey;
				}
			}
		}

		return $conflicts;
	}



	/**
	 * Get installed extension version
	 *
	 * @param	String		$extKey
	 * @return	String
	 */
	public static function getVersion($extKey) {
		self::loadAllExtinfo();

		return Todoyu::$CONFIG['EXT'][$extKey]['info']['version'];
	}



	/**
	 * Add all paths of the installed extension to the autoload path
	 */
	public static function addAllExtensionAutoloadPaths() {
		$installedExtensions	= self::getInstalledExtKeys();

			// First add all include paths
		foreach($installedExtensions as $extKey) {
			self::addExtAutoloadPaths($extKey);
		}
	}



	/**
	 * Load all extensions
	 */
	public static function loadAllExtensions() {
		self::loadAllBoot();
		self::loadAllInit();
	}

}

?>