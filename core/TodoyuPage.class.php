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
 * Render a full page when reloading the whole brower window
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuPage {


	/**
	 * Path to template file
	 *
	 * @var	String
	 */
	private static $template;

	/**
	 * Data array for template rendering
	 *
	 * @var	Array
	 */
	private static $data = array();



	/**
	 * Initialize page object with
	 *
	 * @param	String		$template		Path to template
	 */
	public static function init($template) {
		self::setTemplate($template);

		// Add assets
		self::addCoreAssets();
		self::addDefaultAssets();

		self::addMetatag('Content-Type', $GLOBALS['CONFIG']['FE']['ContentType']);

		self::addJsOnloadedFunction('Todoyu.init.bind(Todoyu)');

		self::setBodyID(EXT);
	}



	/**
	 * Set page template. Normaly the page template will be set by the constructor
	 *
	 * @param	String		$tempalte
	 */
	public static function setTemplate($tempalte) {
		self::$template = $tempalte;
	}



	/**
	 * Add js and css files which are used by the core
	 *
	 */
	private static function addCoreAssets() {
		$jsFiles	= $GLOBALS['CONFIG']['FE']['PAGE']['assets']['js'];

		foreach($jsFiles as $jsFile) {
			self::addJavascript($jsFile['file'], $jsFile['position'], $jsFile['compress'], $jsFile['merge'], $jsFile['localize']);
		}

		$cssFiles	= $GLOBALS['CONFIG']['FE']['PAGE']['assets']['css'];

		foreach($cssFiles as $cssFile) {
			self::addStylesheet($cssFile['file'], $cssFile['media'], $cssFile['position'], $cssFile['compress'], $cssFile['merge']);
		}
	}



	/**
	 * Set attribute in data array
	 *
	 * @param	String		$name
	 * @param	Mixed		$value
	 */
	public static function set($name, $value) {
		self::$data[$name] = $value;
	}



	/**
	 * Remove attribute from data array
	 *
	 * @param	String		$name
	 */
	public static function remove($name) {
		unset(self::$data[$name]);
	}



	/**
	 * Append data to an array attribute
	 *
	 * @param	Stirng		$name
	 * @param	Mixed		$value
	 */
	public static function add($name, $value) {
		self::$data[$name][] = $value;
	}



	/**
	 * Prepend data to an array attribute
	 *
	 * @param	Stirng		$name
	 * @param	Mixed		$value
	 */
	public static function prepend($name, $value) {
		$tmp = self::$data[$name];

		self::$data[$name] = array_merge( array($value), $tmp);
	}



	/**
	 * Append a value to an string attribute
	 *
	 * @param	Stirng		$name
	 * @param	Mixed		$value
	 */
	public static function append($name, $value) {
		self::$data[$name] .= $value;
	}



	/**
	 * Set page title
	 *
	 * @param	String		$title
	 */
	public static function setTitle($title) {
		self::set('pagetitle', 'Todoyu: ' . TodoyuDiv::getLabel($title));
	}



	/**
	 * Set panelWidgets in page template
	 *
	 * @param	String		$panelWidgets
	 */
	public static function setPanelWidgets($panelWidgets) {
		self::set('panelWidgets', $panelWidgets);
	}



	/**
	 * Set body ID
	 *
	 * @param	String		$bodyID
	 */
	public static function setBodyID($bodyID) {
		self::set('bodyID', $bodyID);
	}



	/**
	 * Add a metatag
	 *
	 * @param	String		$name
	 * @param	String		$content
	 * @param	String		$httpEquiv
	 */
	public static function addMetatag($name, $content, $httpEquiv = '') {
		self::add(
			'metatags',
			array(
				'name'		=> $name,
				'content'	=> $content,
				'httpequiv'	=> $httpEquiv
			)
		);
	}



	/**
	 * Add a stylesheet to the current page. The stylesheet will be managed (merged, compressed)
	 *
	 * @param	String		$pathToFile			Path to original file
	 * @param	String		$media				Media type
	 * @param	Integer		$position			File position in loading order
	 * @param	Boolean		$compress			Compress content?
	 * @param	Boolean		$merge				Add content to merge file
	 */
	public static function addStylesheet($pathToFile, $media = 'all', $position = 100, $compress = true, $merge = true) {
		TodoyuPageAssetManager::addStylesheet($pathToFile, $media, $position, $compress, $merge);
	}



	/**
	 * Add a javascript to the current page. The script will be managed (merged, compressed, localized)
	 *
	 * @param	String		$pathToFile			Path to original file
	 * @param	Integer		$position			File position in loading order
	 * @param	Boolean		$compress			Compress content?
	 * @param	Boolean		$merge				Add content to merge file
	 * @param	Boolean		$localize			Localize content (replace [LLL:xxx] tags
	 */
	public static function addJavascript($pathToFile, $position = 100, $compress = true, $merge = true, $localize = true) {
		TodoyuPageAssetManager::addJavascript($pathToFile, $position, $compress, $merge, $localize);
	}



	/**
	 * Load extension css files
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 */
	public static function addExtStylesheets($ext, $type = 'default') {
		TodoyuExtensions::loadAllAssets();

		$files	= $GLOBALS['CONFIG']['EXT'][$ext]['assets'][$type]['css'];

		if( is_array($files) ) {
			foreach($files as $file) {
				if( empty($file['media']) ) {
					$file['media'] = 'all';
				}
				if( empty($file['position']) ) {
					$file['position'] = 100;
				}

				self::addStylesheet($file['file'], $file['media'], $file['position'], $file['compress'], $file['merge']);
			}

			return true;
		} else {
			return false;
		}
	}



	/**
	 * Load extension javascript files
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 */
	public static function addExtJavascript($ext, $type = 'default') {
		TodoyuExtensions::loadAllAssets();

		$files	= $GLOBALS['CONFIG']['EXT'][$ext]['assets'][$type]['js'];

		if( is_array($files) ) {
			foreach($files as $file) {
				self::addJavascript($file['file'], $file['position'], $file['compress'], $file['merge'], $file['localize']);
			}
		}
	}



	/**
	 * Load extension assets (javascript and css)
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 */
	public static function addExtAssets($ext, $type = 'public') {
		$types	= explode(',', $type);

		foreach($types as $type) {
			self::addExtJavascript($ext, $type);
			self::addExtStylesheets($ext, $type);
		}
	}



	/**
	 * Load all assets in the default keys of the extension config
	 *
	 */
	private static function addDefaultAssets() {
		TodoyuExtensions::loadAllAssets();

		$extKeys	= TodoyuExtensions::getInstalledExtKeys();

		foreach($extKeys as $extKey) {
			self::addExtAssets($extKey, 'default');
		}
	}



	/**
	 * Add inline javascript code
	 *
	 * @param	String		$jsCode
	 */
	public static function addJsInlines($jsCode) {
		self::add('jsInlines', $jsCode);
	}



	/**
	 * Prepend inline javascript code
	 *
	 * @param	String		$jsCode
	 */
	public static function prependJsInlines($jsCode) {
		self::prepend('jsInlines', $jsCode);
	}



	/**
	 * Add js functions which shall be called on dom loaded
	 *
	 * @param	String		$function
	 */
	public static function addJsOnloadedFunction($function) {
		self::addJsInlines('document.observe("dom:loaded", ' . $function . ');');
	}



	/**
	 * Add additional header data. Can be any html code
	 *
	 * @param	String		$headerData
	 */
	public static function addAdditionalHeaderData($headerData) {
		self::add('additionalHeaderData', $headerData);
	}



	/**
	 * Add an attribute to the body tag
	 *
	 * @param	String		$name
	 * @param	String		$value
	 */
	public static function addBodyAttribute($name, $value) {
		self::add('bodyAttributes', array(
			'name'	=> $name,
			'value'	=> $value
		));
	}



	/**
	 * Add a html element to the body
	 *
	 * @param	String		$elementHtml
	 */
	public static function addBodyElement($elementHtml) {
		self::add('bodyElements', $elementHtml);
	}



	/**
	 * Add javascripts and stylesheets to the page object variables
	 *
	 */
	private static function addJavascriptAndStylesheetsToPage() {
		TodoyuPageAssetManager::addAssetsToPage();
	}



	/**
	 * Call finishing functions which can modify the page just before the final rendering
	 *
	 */
	private static function callFinishingFunctions() {
		foreach($GLOBALS['CONFIG']['FE']['PAGE']['finish'] as $callback) {
			TodoyuDiv::callUserFunction($callback);
		}
	}



	/**
	 * Render all registered headlets
	 *
	 */
	private static function renderHeadlets() {
		if( TodoyuAuth::isLoggedIn() ) {
			$areaKey		= Todoyu::getAreaKey();
			$headletsLeft	= TodoyuHeadletRenderer::renderAreaHeadlets('LEFT', $areaKey);
			$headletsRight	= TodoyuHeadletRenderer::renderAreaHeadlets('RIGHT', $areaKey);

			self::set('headletsLeft', $headletsLeft);
			self::set('headletsRight', $headletsRight);
		}
	}



	/**
	 * Add JS localizable labels
	 *
	 */
	private static function addJSlocale() {
		$GLOBALS['CONFIG']['JS-LOCALE'] = array_unique( $GLOBALS['CONFIG']['JS-LOCALE'] );

		$labels	= array();
		foreach($GLOBALS['CONFIG']['JS-LOCALE'] as $key) {
			$labels[]	= chr(9) . '"' . $key . '": \'' . Label( $key ) . '\'';
		}

		self::$data['jsInlines'][] = chr(10) . 'var Locale = {' . chr(10) .  implode(',' . chr(10), $labels) . chr(10) . '};';
	}



	/**
	 * Render page with template
	 *
	 * @param	Boolean		$output		Print html code with echo
	 * @return	String
	 */
	public static function render() {
			// Add headlets
		self::renderHeadlets();

			// Add main navigation
		self::set('navigation', TodoyuRenderer::renderNavigation());

			// Call finishing functions
		self::callFinishingFunctions();

			// Add javascripts and stylesheet to page
		self::addJavascriptAndStylesheetsToPage();

			// Remove later ...
		//self::addJSlocale();

		return render(self::$template, self::$data);
	}



	/**
	 * Render page and send output with ECHO
	 *
	 */
	public static function display() {
		echo self::render();
	}

}

?>