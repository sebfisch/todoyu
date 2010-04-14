<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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



	/**
	 * Render content area
	 * Contains tab and body area
	 *
	 * @param	String		$body
	 * @param	String		$tabs
	 * @return	String
	 */
	public static function renderContent($body, $tabs) {
		$tmpl	= 'core/view/content.tmpl';
		$data	= array(
			'tabs'	=> $tabs,
			'body'	=> $body
		);

		return render($tmpl, $data);
	}

}

?>