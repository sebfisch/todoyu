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
 * HTML filter to escape bad html code
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuHtmlFilter {

	/**
	 * Get bad html tags config array
	 *
	 * @return	Array
	 */
	private static function getBadTags() {
		return $GLOBALS['CONFIG']['SECURITY']['badHtmlTags'];
	}



	/**
	 * Clean HTML code with bad tags
	 *
	 * @param	String		$inputHTML
	 * @return	String
	 */
	public static function clean($inputHTML) {
		if( $inputHTML === null )	{
			return '';
		}
		$badTags	= self::getBadTags();

		foreach($badTags as $badTag) {
			$patternStandard	= '|<(' . $badTag . ')([^>]*)>(.*?)(</' . $badTag . '>)|sum';

			$inputHTML	= preg_replace_callback($patternStandard, array(self,'escapeBadTags'), $inputHTML);

			$patternSimple		= '|<(' . $badTag . ')([^>]*)>(.*?)|sum';

			$inputHTML	= preg_replace_callback($patternSimple, array(self,'escapeBadTag'), $inputHTML);
		}

		return $inputHTML;
	}



	/**
	 * Callback to escape bad HTML tags
	 *
	 * @param	Array		$match
	 * @return	String
	 */
	private static function escapeBadTags(array $match) {
		return '&lt;' . $match[1] . $match[2] . '&gt;' . nl2br(htmlentities($match[3])) . '&lt;' . $match[1] . '&gt;';
	}



	/**
	 * Callback to escape bad simple HTML tags
	 *
	 * @param	Array		$match
	 * @return	String
	 */
	private static function escapeBadTag(array $match)	{
		return htmlentities($match[0]);
	}
}

?>