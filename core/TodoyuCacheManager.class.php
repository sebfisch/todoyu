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
 * Dynamic context menu loaded by ajax request
 * Extensions can register menu items for menu types
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuCacheManager {

	/**
	 * Clear all cache
	 * Call all registered clearCache hooks
	 *
	 */
	public static function clearAllCache() {
		TodoyuHookManager::callHook('core', 'clearCache');
	}



	/**
	 * Clear asset cache (js+css)
	 *
	 */
	public static function clearAssetCache() {
		self::clearCacheFolder('js');
		self::clearCacheFolder('css');
	}



	/**
	 * Clear locale cache (compiled locale files)
	 *
	 */
	public static function clearLocaleCache() {
		self::clearCacheFolder('locale');
	}



	/**
	 * Clear template cache
	 *
	 */
	public static function clearTemplateCache() {
		self::clearCacheFolder('tmpl/cache');
		self::clearCacheFolder('tmpl/compile');
	}



	/**
	 * Clear a specific cache folter (all its content)
	 *
	 * @param	String		$cacheFolder		Relative path to filter from cache directory
	 */
	private static function clearCacheFolder($cacheFolder) {
		$pathToFolder	= TodoyuFileManager::pathAbsolute(PATH_CACHE . '/' . $cacheFolder);

		TodoyuFileManager::deleteFolderContent($pathToFolder);
	}

}


?>