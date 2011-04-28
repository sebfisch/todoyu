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
 * Configuration file manager
 * Save configuration to config files
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuConfigManager {

	/**
	 * Save data in template php file
	 *
	 * @param	String		$savePath
	 * @param	String		$templateFile
	 * @param	Array		$data
	 */
	public static function saveConfigFile($savePath, $templateFile, array $data) {
		TodoyuFileManager::saveTemplatedFile($savePath, $templateFile, $data, true);
	}



	/**
	 * Save system config configuration
	 * File: config/system.php
	 *
	 * @param	Array		$data
	 * @param	Boolean		$generateNewKey
	 */
	public static function saveSystemConfigConfig(array $data, $generateNewKey = false) {
		$savePath	= 'config/system.php';
		$template	= 'core/view/template/system.php.tmpl';

		if( $generateNewKey ) {
			$data['encryptionKey']	= TodoyuCrypto::makeEncryptionKey();
		} else {
			$data['encryptionKey']	= Todoyu::$CONFIG['SYSTEM']['encryptionKey'];
		}

		self::saveConfigFile($savePath, $template, $data);
	}



	/**
	 * Save settings configuration
	 * Write current content of Todoyu::$CONFIG['SETTINGS'] to config/settings.php
	 * To add new data, write the config first in the config variable
	 *
	 * File: config/system.php
	 */
	public static function saveSettingsConfig() {
		$data		= TodoyuArray::assure(Todoyu::$CONFIG['SETTINGS']);
		$prepared	= array();
		$savePath	= 'config/settings.php';
		$template	= 'core/view/template/settings.php.tmpl';

		foreach($data as $groupName => $groupConfig) {
			$prepared[$groupName] = array();

			foreach($groupConfig as $key => $value) {
				$prepared[$groupName][$key]	= TodoyuString::toPhpCodeString($value);
			}
		}

		$saveData	= array(
			'settings'	=> $prepared
		);

		self::saveConfigFile($savePath, $template, $saveData);
	}

}

?>