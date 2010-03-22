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
	 * Initialize page object with given template
	 *
	 * @param	String		$template		Path to template
	 */
	public static function init($template) {
		self::setTemplate($template);

			// Load all page configuration provided by extensions
		self::loadExtPageConfig();

			// Add core assets
		self::addCoreAssets();
			// Add all assets of allowed extensions
		self::addExtAssets();

		self::addMetatag('Content-Type', $GLOBALS['CONFIG']['FE']['ContentType']);

		self::addJsOnloadedFunction('Todoyu.init.bind(Todoyu)', 1);
		self::addJsOnloadedFunction('Todoyu.Headlet.init.bind(Todoyu.Headlet)', 10);
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
	 * Add JS and CSS files which are used by the core
	 */
	private static function addCoreAssets() {
		$jsFiles	= TodoyuArray::assure($GLOBALS['CONFIG']['FE']['PAGE']['assets']['js']);
		$cssFiles	= TodoyuArray::assure($GLOBALS['CONFIG']['FE']['PAGE']['assets']['css']);

		foreach($jsFiles as $jsFile) {
			self::addJavascript($jsFile['file'], $jsFile['position'], $jsFile['compress'], $jsFile['merge'], $jsFile['localize']);
		}

		foreach($cssFiles as $cssFile) {
			self::addStylesheet($cssFile['file'], $cssFile['media'], $cssFile['position'], $cssFile['compress'], $cssFile['merge']);
		}
	}


	/**
	 * Add all extension assets of allowed extension. If not logged in, don't check
	 */
	private static function addExtAssets() {
		TodoyuExtensions::loadAllAssets();

		$extKeys	= TodoyuExtensions::getInstalledExtKeys();

		foreach($extKeys as $ext) {
			//if( allowed($ext, 'general:use') || ! TodoyuAuth::isLoggedIn() ) {
				self::addExtJavascript($ext);
				self::addExtStylesheets($ext);
			//}
		}
	}



	/**
	 * Load extension CSS files
	 *
	 * @param	String		$ext
	 */
	private static function addExtStylesheets($ext) {
		TodoyuExtensions::loadAllAssets();

		$files	= TodoyuArray::assure($GLOBALS['CONFIG']['EXT'][$ext]['assets']['css']);

		foreach($files as $file) {
			self::addStylesheet($file['file'], $file['media'], $file['position'], $file['compress'], $file['merge']);
		}
	}



	/**
	 * Load extension javascript files
	 *
	 * @param	String		$ext
	 */
	private static function addExtJavascript($ext) {
		$files	= TodoyuArray::assure($GLOBALS['CONFIG']['EXT'][$ext]['assets']['js']);

		foreach($files as $file) {
			self::addJavascript($file['file'], $file['position'], $file['compress'], $file['merge'], $file['localize']);
		}
	}



	/**
	 * Load all page configuration of the extensions
	 */
	private static function loadExtPageConfig() {
		TodoyuExtensions::loadAllPage();
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
	 * @param	String		$name
	 * @param	Mixed		$value
	 */
	public static function add($name, $value) {
		self::$data[$name][] = $value;
	}



	/**
	 * Prepend data to an array attribute
	 *
	 * @param	String		$name
	 * @param	Mixed		$value
	 */
	public static function prepend($name, $value) {
		$tmp = self::$data[$name];

		self::$data[$name] = array_merge( array($value), $tmp);
	}



	/**
	 * Append a value to an string attribute
	 *
	 * @param	String		$name
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
		self::set('pagetitle', TodoyuDiv::getLabel($title) . ' - todoyu');
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
	 * Adds js inline
	 *
	 * @param	String	$ext
	 * @param	String	$type
	 * @return	String
	 */
	public static function getExtJSinline($ext, $type = 'public')	{
		TodoyuExtensions::loadAllAssets();

		$files	= $GLOBALS['CONFIG']['EXT'][$ext]['assets'][$type]['js'];

		if( is_array($files) ) {
			foreach($files as $file) {
				$content.= 'Todoyu.Ui.loadJSFile(\''.$file['file'].'\');';
			}
		}

		return '<script type="text/javascript">'.$content.'</script>';
	}



	/**
	 * Adds css inline
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 * @return	String
	 */
	public static function getExtCSSinline($ext, $type = 'public')	{
		TodoyuExtensions::loadAllAssets();

		$files	= $GLOBALS['CONFIG']['EXT'][$ext]['assets'][$type]['css'];

		if( is_array($files) ) {
			foreach($files as $file) {
				$content.= 'Todoyu.Ui.loadCSSFile(\''.$file['file'].'\');';
			}
		}

		return '<script type="text/javascript">'.$content.'</script>';
	}



	/**
	 * Add inline javascript code
	 *
	 * @param	String		$jsCode
	 * @param	Integer		$position
	 */
	public static function addJsInline($jsCode, $position = 100) {
		self::add('jsInlines',
			array(
				'position'	=> $position,
				'code'		=> $jsCode
			)
		);
	}



	/**
	 * Add JS functions which shall be called on dom loaded
	 *
	 * @param	String		$function
	 * @param	Integer		$position
	 */
	public static function addJsOnloadedFunction($function, $position = 100) {
		self::addJsInline('document.observe("dom:loaded", ' . $function . ');', $position);
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
	 * Sort inline JavaScripts by position (key)
	 */
	public static function sortJSinlines() {
		self::$data['jsInlines']	= TodoyuArray::sortByLabel(self::$data['jsInlines'], 'position');
	}



	/**
	 * Add javascripts and stylesheets to the page object variables
	 */
	private static function addJavascriptAndStylesheetsToPage() {
		TodoyuPageAssetManager::addAssetsToPage();
	}



	/**
	 * Render all registered headlets
	 *
	 */
	private static function renderHead() {
		if( TodoyuAuth::isLoggedIn() ) {
			$head	= TodoyuHeadManager::render();

			self::set('head', $head);
		}
	}



	/**
	 * Render page with template
	 *
	 * @param	Boolean		$output		Print html code with echo
	 * @return	String
	 */
	public static function render() {
			// Call hook just before page is rendered
		TodoyuHookManager::callHook('core', 'renderPage');

			// Add headlets
		self::renderHead();

			// Add main navigation
		self::set('navigation', TodoyuRenderer::renderNavigation());

			// Add javascripts and stylesheet to page
		self::addJavascriptAndStylesheetsToPage();

		self::sortJSinlines();

		return render(self::$template, self::$data);
	}



	/**
	 * Render page and send output with ECHO
	 */
	public static function display() {
		echo self::render();
	}

}

?>