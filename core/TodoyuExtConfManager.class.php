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
 * Extension config manager. Manages writing /config/extensions.php file with current config
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuExtConfManager {

	/**
	 * Get path to extension config form
	 *
	 * @param	String		$extKey
	 * @return	String
	 */
	public static function getXmlPath($extKey) {
		return 'ext/' . $extKey . '/config/form/extconf.xml';
	}



	/**
	 * Check if extension config form exists
	 *
	 * @param	String		$extKey
	 * @return	Bool
	 */
	public static function hasExtConf($extKey) {
		$xmlPath = self::getXmlPath($extKey);

		return TodoyuFileManager::isFile($xmlPath);
	}



	/**
	 * Get extConf form
	 *
	 * @param	String		$extKey
	 * @return	TodoyuForm
	 */
	public static function getForm($extKey) {
		$xmlPath	= self::getXmlPath($extKey);

		$form	= new TodoyuForm($xmlPath);
		$form	= TodoyuFormHook::callBuildForm($xmlPath, $form, 0);

		$data	= self::getExtConf($extKey);
		$data	= TodoyuFormHook::callLoadData($xmlPath, $data, 0);

		$form->setUseRecordID(false);
		$form->setFormData($data);

			// Modify form fields
		$formAction	= TodoyuDiv::buildUrl(array(
			'ext'		=> 'sysmanager',
			'controller'=> 'extconf'
		));

		$form->setAttribute('onsubmit', 'return Todoyu.Ext.sysmanager.ExtConf.onSave(this)');
		$form->setAttribute('action', $formAction);
		$form->setAttribute('name', 'config');

		$form->addHiddenField('extension', $extKey, true);


			// Add save and cancel buttons
		$saveForm	= new TodoyuForm('ext/sysmanager/config/form/extconf-save.xml');
		$buttons	= $saveForm->getFieldset('save');

		$form->addFieldset('save', $buttons);

		return $form;
	}



	/**
	 * Save current configuration (installed extensions and their config)
	 *
	 */
	public static function saveExtConf() {
		$file	= PATH_CONFIG . '/extensions.php';
		$tmpl	= 'core/view/extensions.php.tmpl';
		$data	= array(
			'extConf'	=> array()
		);

		$extKeys	= TodoyuExtensions::getInstalledExtKeys();

		foreach($extKeys as $extKey) {
			$extConf	= self::getExtConf($extKey);

			$data['extConf'][$extKey] = addslashes(serialize($extConf));
		}

			// Save file
		TodoyuFileManager::saveTemplatedFile($file, $tmpl, $data);
	}



	/**
	 * Update an extension configuration
	 *
	 * @param	String		$extKey
	 * @param	Array		$data
	 */
	public static function updateExtConf($extKey, array $data) {
		self::setExtConf($extKey, $data);

		self::saveExtConf();
	}



	/**
	 * Set extension configuration array in
	 *
	 * @param	String		$extKey
	 * @param	Array		$data
	 */
	public static function setExtConf($extKey, array $data) {
		$GLOBALS['CONFIG']['EXT'][$extKey]['extConf'] = $data;
	}



	/**
	 * Get config array for an extension
	 *
	 * @param	String		$extKey
	 * @return	Array
	 */
	public static function getExtConf($extKey) {
		return TodoyuArray::assure($GLOBALS['CONFIG']['EXT'][$extKey]['extConf']);
	}

}


?>