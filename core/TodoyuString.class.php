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
 * String helper functions
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuString {



	/**
	 * Check if a string is utf-8 encoded
	 *
	 * @param	String		$stringToCheck
	 * @return	Boolean
	 */
	public static function isUTF8($stringToCheck) {
		if( function_exists('mb_detect_encoding') ) {
			return mb_detect_encoding($stringToCheck, 'UTF-8, ISO-8859-15, ISO-8859-1') === 'UTF-8';
		} else {
			return true; // Assume it's already utf8 as it should be. We cannot tell it anyway without this function
		}
	}



	/**
	 * Convert a string to UTF-8 if necessary
	 *
	 * @param	String		$stringToConvert
	 * @return	String
	 */
	public static function convertToUTF8($stringToConvert) {
		return self::isUTF8($stringToConvert) ? $stringToConvert : utf8_encode($stringToConvert);
	}



	/**
	 * Checking syntax of input email address
	 *
	 * @param	String		Input string to evaluate
	 * @return	Boolean		Returns true if the $email address (input string) is valid; Has a "@", domain name with at least one period and only allowed a-z characters.
	 */
	public static function isValidEmail($email)	{
		$email = trim ($email);
		if( strstr($email,' ') ) {
			return false;
		}

		return ereg('^[A-Za-z0-9\._-]+[@][A-Za-z0-9\._-]+[\.].[A-Za-z0-9]+$', $email) ? true : false;
	}



	/**
	 * Crop a text to a specific length. If text is cropped, a postfix will be added (default: ...)
	 * Per default, words will not be split and the text will mostly be a little bit shorter
	 *
	 * @param	String		$text
	 * @param	Integer		$length
	 * @param	String		$postfix
	 * @param	Boolean		$dontSplitWords
	 * @return	String
	 */
	public static function crop($text, $length, $postfix = '...', $dontSplitWords = true) {
		$length	= intval($length);

		if( strlen($text) > $length ) {
			$text	= utf8_decode($text);

			$cropped	= substr($text, 0, $length);
			$nextChar	= substr($text, $length, 1);

			if( $dontSplitWords === true && $nextChar !== ' ' ) {
				$spacePos	= strpos($cropped, ' ');
				$cropped	= substr($cropped, 0, $spacePos);
			}
			$cropped .= $postfix;

			$cropped = utf8_encode($cropped);
		} else {
			$cropped = $text;
		}

		return $cropped;
	}



	/**
	 * Wrap string with given pipe-separated wrapper string, e.g. HTML tags
	 *
	 * @param	String	$string
	 * @param	String	$wrap			<tag>|</tag>
	 * @return	String
	 */
	public static function wrap($string, $wrap)	{
		return str_replace('|', $string, $wrap);
	}



	/**
	 * Split a camel case formated string into its words
	 *
	 * @param	String		$string
	 * @return	Array
	 */
	public static function splitCamelCase($string) {
		return preg_split('/([A-Z][^A-Z]*)/', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
	}



	/**
	 * Convert an HTML snippet into plain text. Keep as much format information as possible
	 *
	 * @param	String		$html		HTML snippet
	 * @return	String		Text version
	 */
	public static function html2text($html) {
		return strip_tags($html);
		/*
		require_once( PATH_LIB . '/php/html2text/class.html2text.php' );

		$html2text = new html2text($html);
		$html2text->set_base_url(TODOYU_URL);

		return $html2text->get_text();
		*/
	}



	/**
	 * Get a substring around a keyword
	 *
	 * @param	String		$string			The whole text
	 * @param	String		$keyword		Keyword to find in the text
	 * @param	Integer		$charsBefore	Characters included before the keyword
	 * @param	Integer		$charsAfter		Characters included after the keyword
	 * @return	String		Substring with keyword surrounded by the original text
	 */
	public static function getSubstring($string, $keyword, $charsBefore = 20, $charsAfter = 20, $htmlEntities = true) {
		$charsBefore= intval($charsBefore);
		$charsAfter	= intval($charsAfter);
		$keyLen		= strlen(trim($keyword));
		$pos		= stripos($string, $keyword);
		$start		= TodoyuMath::intInRange($pos-$charsBefore, 0);
		$subLen		= $charsBefore + $keyLen + $charsAfter;

		if( $htmlEntities ) {
			$string = htmlentities(substr(html_entity_decode($string), $start, $subLen));
		} else {
			$string = substr($string, $start, $subLen);
		}

		return $string;
	}



	/**
	 * Generate a random password. Customizeable
	 *
	 * @param	Integer		$length
	 * @param	Boolean		$useLowerCase
	 * @param	Boolean		$useNumbers
	 * @param	Boolean		$useSpecialChars
	 * @param	Boolean		$useDoubleChars
	 * @return	String
	 */
	public static function generatePassword($length = 8, $useLowerCase = false, $useNumbers = true, $useSpecialChars = false, $useDoubleChars = true) {
		$length		= intval($length);
		$characters	= array_merge(range('a', 'z'), range('A', 'Z'));

		if( $useNumbers ) {
			$characters = array_merge($characters, range('0', '9'));
		}
		if( $useSpecialChars ) {
			$characters = array_merge($characters, array('#','&','@','$','_','%','?','+','-'));
		}
		if( $useDoubleChars ) {
			shuffle($characters);
			$characters = array_merge($characters, $characters);
		}

		// Shuffle array
		shuffle($characters);
		$password = substr(implode('', $characters), 0, $length);

		if( $useLowerCase ) {
			$password = strtolower($password);
		}

		return $password;
	}

}

?>