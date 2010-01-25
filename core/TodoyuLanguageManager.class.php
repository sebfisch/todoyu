<?php

class TodoyuLanguageManager {

	public static function getAvailableLanguages() {
		$languages	= TodoyuArray::assure($GLOBALS['CONFIG']['LANGUAGE']['available']);
		$options	= array();

		foreach($languages as $language) {
			$options[] = array(
				'value'	=> $language,
				'label'	=> Label('static_language.' . $language)
			);
		}

		return $options;
	}

}

?>