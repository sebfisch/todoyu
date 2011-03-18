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
 * HTML filter to escape bad HTML code
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuHtmlFilter {

	/**
	 * Get bad HTML tags config array
	 *
	 * @return	Array
	 */
	private static function getBadTags() {
		return Todoyu::$CONFIG['SECURITY']['badHtmlTags'];
	}



	/**
	 * Clean HTML code with bad tags
	 *
	 * @param	String		$html
	 * @return	String
	 */
	public static function clean($html) {
		if( trim($html) === '' ) {
			return '';
		}

		$badTags	= self::getBadTags();

		foreach($badTags as $badTag) {
			$patternStandard	= '|<(' . $badTag . ')([^>]*)>(.*?)(</' . $badTag . '>)|sum';

			$html	= preg_replace_callback($patternStandard, array('TodoyuHtmlFilter','escapeBadTags'), $html);

			$patternSimple	= '|<(' . $badTag . ')([^>]*)>(.*?)|sum';

			$html	= preg_replace_callback($patternSimple, array('TodoyuHtmlFilter','escapeBadTag'), $html);
		}

		return $html;
	}



	/**
	 * Callback to escape bad HTML tags
	 *
	 * @param	Array		$match
	 * @return	String
	 */
	private static function escapeBadTags(array $match) {
		return '&lt;' . $match[1] . $match[2] . '&gt;' . nl2br(htmlentities($match[3], ENT_QUOTES, 'UTF-8')) . '&lt;' . $match[1] . '&gt;';
	}



	/**
	 * Callback to escape bad simple HTML tags
	 *
	 * @param	Array		$match
	 * @return	String
	 */
	private static function escapeBadTag(array $match) {
		return htmlentities($match[0], ENT_QUOTES, 'UTF-8');
	}

}

?>