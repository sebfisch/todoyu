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
 * Basis Render functions for extensions
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuRenderer {

	/**
	 * Render all navigations
	 *
	 * @return	String
	 */
	public static function renderNavigation() {
		$tmpl	= 'core/view/navi.tmpl';

		$data	= array(
			'navigation'=> TodoyuFrontend::getMenuEntries(),
			'active'	=> TodoyuFrontend::getActiveTab()
		);

		return render($tmpl, $data);
	}



	/**
	 * Call all registered render functions for an area (content,panel,menu,etc)
	 * The parameter will be passed to the render function in the same order as in the array
	 *
	 * @param	String		$extKey		Extension key
	 * @param	String		$area		Area to render (where functions are registered to)
	 * @param	Array		$params		Parameter to pass to the render function
	 * @return	String		HTML for the are delivered by all render functions
	 */
	public static function renderArea($extKey, $area = 'content', array $params = array()) {
		$content 	= '';
		$renderFuncs= $GLOBALS['CONFIG']['EXT'][$extKey]['renderer'][$area];

		if( is_array($renderFuncs) ) {
			foreach( $renderFuncs as $renderFunc ) {
				$funcRef	= explode('::', $renderFunc);
				$content	.= call_user_func_array($funcRef, $params);
			}
		}

		return $content;
	}


	/**
	 * Add area renderer function reference
	 *
	 * @param	String	$ext
	 * @param	String	$area
	 * @param	String	$funcRef
	 */
	public static function addAreaRenderer($ext, $area, $funcRef) {
		$GLOBALS['CONFIG']['EXT'][$ext]['renderer'][$area][] = $funcRef;
	}



	/**
	 * Render autocompletion results list
	 *
	 * @param	Array	$options
	 * @return	String
	 */
	public static function renderAutocompleteList(array $options) {
		$tmpl	= 'core/view/autocompletion.tmpl';
		$data 	= array(
			'results' => $options
		);

			// Send number of elements as header
		TodoyuHeader::sendTodoyuHeader('acElements', sizeof($options));

		return render($tmpl, $data);
	}

}

?>