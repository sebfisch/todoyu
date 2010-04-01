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

			$patternSimple	= '|<(' . $badTag . ')([^>]*)>(.*?)|sum';

			$inputHTML	= preg_replace_callback($patternSimple, array(self,'escapeBadTag'), $inputHTML);
		}

		return $inputHTML;
	}



	/**
	 * Split text into chunks of given max. length, preserving HTML entities
	 *
	 * @param	String	$string
	 * @param	Integer	$maxLen
	 * @return	String
	 */
	public static function entitySafeLimitWordsLen($string, $maxLen = 45) {
		$string	= str_replace("\n", "\n ", $string);
		$words	= explode(' ', $string);

		$out	= '';
		foreach ($words as $word) {
//			$out .= chunk_split($word, $maxLen, ' ') .  ' ';
			$out .= self::htmlSafeChunkSplit($word, $maxLen, ' ') .  ' ';
		}

		return $out;
	}




	/**
	 * Split string into chunks of given size, keeping HTML tags and entities intact
	 *
	 * @param	String		$html
	 * @param	Integer		$size
	 * @param	String		$delim
	 * @return	String
	 */
	public static function htmlSafeChunkSplit($html, $size, $delim) {
		$pos	= 0;
		$out	= '';

		for($i = 0; $i < strlen($html); $i++) {
			if($pos >= $size && ! $unsafe) {
				$out	.= $delim;
				$unsafe	= 0;
				$pos	= 0;
			}

			$c	= substr($html, $i, 1);

			if( strstr('&<', $c) !== false ) {
				$unsafe	= 1;
			} elseif( strstr(';>', $c) !== false ) {
				$unsafe	= 0;
			}

			$out	.= $c;
			$pos++;
		}

		return $out;
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