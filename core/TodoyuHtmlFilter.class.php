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
	 * Split text into chunks of given max. length, preserving HTML entities
	 *
	 * @todo	Find a working alternative. This functions split wherever it wants (ex: hrefs)
	 * @param	String	$string
	 * @param	Integer	$maxLen
	 * @return	String
	 */
	public static function entitySafeLimitWordsLen($string, $maxLen = 45) {
		$replace= array(
			"\n"	=> "\n ",
			'><'	=> '> <'
		);
		$string	= str_replace(array_keys($replace), array_values($replace), $string);
		$words	= explode(' ', $string);

		$out	= '';
		foreach($words as $word) {
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
	private static function htmlSafeChunkSplit($html, $size, $delim) {
		$pos	= 0;
		$out	= '';

		for($i = 0; $i < strlen($html); $i++) {
			if( $pos >= $size && ! $unsafe ) {
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