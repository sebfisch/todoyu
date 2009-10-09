<?php



class TodoyuExtConfManager {

	public static function getXmlPath($extKey) {
		return 'ext/' . $extKey . '/config/form/extconf.xml';
	}


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

		return $form;
	}


	public static function saveExtConf() {
		$file	= PATH_LOCALCONF . '/extensions.php';
		$tmpl	= 'core/view/extensions.php.tmpl';
		$data	= array();

		$extKeys= TodoyuExtensions::getInstalledExtKeys();

		$data['extList'] = implode(',',$extKeys);
		$data['extConf'] = array();

		foreach($extKeys as $extKey) {
			$extConf	= self::getExtConf($extKey);

			$data['extConf'][$extKey] = addslashes(serialize($extConf));
		}

			// Render file content
		$content= render($tmpl, $data);
			// Add php start and end tag
		$content= TodoyuDiv::wrapString($content, '<?php|?>');

		file_put_contents($file, $content);

//		TodoyuDebug::printHtml($content);
	}

	public static function updateExtConf($extKey, array $data) {
		self::setExtConf($extKey, $data);

		self::saveExtConf();
	}

	public static function setExtConf($extKey, array $data) {
		$GLOBALS['CONFIG']['EXT'][$extKey]['extConf'] = $data;
	}

	public static function getExtConf($extKey) {
		return TodoyuDiv::assureArray($GLOBALS['CONFIG']['EXT'][$extKey]['extConf']);
	}


	public static function load() {
		$extConf	= TodoyuDiv::assureArray($GLOBALS['CONFIG']['EXTCONF']);

		foreach($extConf as $extKey => $confString) {
			$GLOBALS['CONFIG']['EXT'][$extKey]['extConf'] = unserialize(stripslashes($confString));
		}
	}





}


?>