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

		return Todoyu::render($tmpl, $data);
	}



	/**
	 * Render autoCompletion results list
	 *
	 * @param	Array	$results
	 * @return	String
	 */
	public static function renderAutocompleteResults(array $results) {
		$tmpl	= 'core/view/autocomplete-list.tmpl';
		$data 	= array(
			'results' => $results
		);

			// Send number of elements as header
		TodoyuHeader::sendTodoyuHeader('acElements', sizeof($results));

		return Todoyu::render($tmpl, $data);
	}



	/**
	 * Render content area
	 * Contains tab and body area
	 *
	 * @param	String		$content
	 * @param	String		$tabs
	 * @return	String
	 */
	public static function renderContent($content, $tabs = null) {
		$tmpl	= 'core/view/content.tmpl';
		$data	= array(
			'tabs'	=> $tabs,
			'body'	=> $content
		);

		return Todoyu::render($tmpl, $data);
	}



	/**
	 * Render content for a iFrame which needs a JavaScript call to finish the action
	 * The JavaScript commands are automatically executed when iFrame is loaded. This is the same like an AJAX onComplete handler
	 *
	 * @param	String		$javaScriptCommands
	 * @return	String
	 */
	public static function renderUploadIFrameJsContent($javaScriptCommands) {
		$tmpl	= 'core/view/htmldoc.tmpl';
		$data	= array(
			'title'		=> 'Upload IFrame',
			'content'	=> TodoyuString::wrapScript($javaScriptCommands)
		);

		return Todoyu::render($tmpl, $data);
	}



	/**
	 * Render timerange selector form element
	 *
	 * @param	Integer		$id
	 * @param	String		$name
	 * @param	Array		$range
	 * @param	String		$nameWrap
	 * @return	String
	 */
	public static function renderTimerange($id, $name, array $range, $nameWrap = null) {
		$tmpl	= 'core/view/timerange.tmpl';
		$data	= array(
			'id'	=> $id,
			'range'	=> $range,
			'name'	=> is_null($nameWrap) ? $name : $nameWrap . '[' . $name . ']'
		);

		return Todoyu::render($tmpl, $data);
	}

}

?>