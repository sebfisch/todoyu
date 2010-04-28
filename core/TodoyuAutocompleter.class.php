<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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
 * Handle autocompleter results
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuAutocompleter {

	/**
	 * List of registered autocompleters
	 * @var	Array
	 */
	private static $autocompleter = array();

	public static function addAutocompleter($name, $function, array $restrict = array()) {
		self::$autocompleter[$name]	= array(
			'function'	=> $function,
			'restrict'	=> $restrict
		);
	}

	public static function getAutocompleter($name) {
		return self::$autocompleter[$name];
	}


	public static function renderAutocompleteList($name, $input, array $formData = array()) {
		$results	= self::getResults($name, $input, $formData);

		$tmpl	= 'core/view/autocompletion.tmpl';
		$data 	= array(
			'results' => $results
		);

			// Send number of elements as header
		TodoyuHeader::sendTodoyuHeader('acElements', sizeof($results));

		return render($tmpl, $data);
	}



	/**
	 * Get autocompleter results for given input
	 *
	 * @param	String		$name			Name of the autocompleter type
	 * @param	String		$input			Text the user entered
	 * @param	Array		$formData		All other form data
	 * @return	Array
	 */
	public static function getResults($name, $input, array $formData = array()) {
		$autocompleter	= self::getAutocompleter($name);
		$result			= array();

			// Check for restrictions
		if( isset($autocompleter['restrict']) ) {
			restrict($autocompleter['restrict'][0], $autocompleter['restrict'][1]);
		}

			// Call datasource function for results
		if( TodoyuFunction::isFunctionReference($autocompleter['function']) ) {
			$result	= TodoyuFunction::callUserFunction($autocompleter['function'], $input, $formData, $name);
		} else {
			Todoyu::log('Invalid autocomplete function for name "' . $name . '": ' . $autocompleter['function'], LOG_LEVEL_ERROR);
		}

		return TodoyuArray::assure($result);
	}

}

?>