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
		return mb_detect_encoding($stringToCheck, 'UTF-16, UTF-8, ISO-8859-15, ISO-8859-1, ASCII') === 'UTF-8';
	}



	/**
	 * Convert a string to UTF-8 if necessary
	 *
	 * @param	String		$stringToConvert
	 * @return	String
	 */
	public static function convertToUTF8($stringToConvert, $from = 'UTF-16') {
		return iconv($from, 'UTF-8', $stringToConvert);
	}



	/**
	 * Get string as UTF-8 if it's not already
	 *
	 * @param	String		$string
	 * @return	String
	 */
	public static function getAsUtf8($string) {
		return self::isUTF8($string) ? $string : self::convertToUTF8($string);
	}



	/**
	 * Checking syntax of input email address
	 *
	 * @param	String		Input string to evaluate
	 * @return	Boolean		Returns true if the $email address (input string) is valid; Has a "@", domain name with at least one period and only allowed a-z characters.
	 */
	public static function isValidEmail($email) {
		$email = trim ($email);
		if( strstr($email,' ') ) {
			return false;
		}

		$regexp	= '#^[A-Za-z0-9\._-]+[@][A-Za-z0-9\._-]+[\.].[A-Za-z0-9]+$#';

		return preg_match($regexp, $email) === 1;

//		return (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) ? true : false;
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

		if( mb_strlen($text, 'utf-8') > $length ) {
			$cropped	= mb_substr($text, 0, $length, 'utf-8');
			$nextChar	= mb_substr($text, $length, 1, 'utf-8');

				// Go back to last word ending
			if( $dontSplitWords === true && $nextChar !== ' ' && mb_stristr($cropped, ' ', null, 'utf-8') !== false ) {
				$spacePos	= mb_strrpos($cropped, ' ', 0, 'utf-8');
				$cropped	= mb_substr($cropped, 0, $spacePos, 'utf-8');
			}
			$cropped .= $postfix;
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
	public static function wrap($string, $wrap) {
		return str_replace('|', $string, $wrap);
	}



	/**
	 * Split a camel case formatted string into its words
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
		$keyLen		= mb_strlen(trim($keyword));
		$pos		= mb_stripos($string, $keyword);
		$start		= TodoyuNumeric::intInRange($pos-$charsBefore, 0);
		$subLen		= $charsBefore + $keyLen + $charsAfter;

		if( $htmlEntities ) {
			$string	= html_entity_decode($string);
		}

		$string = mb_substr($string, $start, $subLen);

		if( $htmlEntities ) {
			$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
		}

		return trim($string);
	}



	/**
	 * Add an element to a separated list (ex: coma separated)
	 *
	 * @param	String		$list
	 * @param	String		$value
	 * @param	String		$separator
	 * @param	Boolean		$unique
	 * @return	String
	 */
	public static function addToList($list, $value, $separator = ',', $unique = false) {
		$items	= explode($separator, $list);
		$items[]= $value;

		if( $unique ) {
			$items = array_unique($items);
		}

		return implode($separator, $items);
	}



	/**
	 * Check if an element is in a separated list string (ex: comma separated)
	 *
	 * @param	String		$item				Element to check for
	 * @param	String		$listString			List with concatenated elements
	 * @param	String		$listSeparator		List element separating character
	 * @return	Boolean
	 */
	public static function isInList($item, $listString, $listSeparator = ',') {
		$list	= explode($listSeparator, $listString);

		return in_array($item, $list);
	}



	/**
	 * Remove duplicate entries from list
	 *
	 * @param	String	$listString
	 * @return	String
	 */
	public static function listUnique($listString, $listSeparator = ',') {
		$list = TodoyuArray::trimExplode($listSeparator, $listString);
		$list = array_unique($list);

		return implode($listSeparator, $list);
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



	/**
	 * Format a filesize in the gb/mb/kb/b and add label
	 *
	 * @param	Integer		$filesize
	 * @param	Array		$labels			Custom label array (overrides the default labels
	 * @param	Boolean		$noLabel		Don't append label
	 * @return	String
	 */
	public static function formatSize($filesize, array $labels = null, $noLabel = false) {
			// Have to use floatval instead of intval because of the max range of integer supports only for up to 2,5GB..
		$filesize	= round(floatval($filesize), 0);

		if( is_null($labels) ) {
			if( $noLabel === false ) {
				$labels = array(
					'gb'	=> Label('file.size.gb'),
					'mb'	=> Label('file.size.mb'),
					'kb'	=> Label('file.size.kb'),
					'b'		=> Label('file.size.b')
				);
			} else {
				$labels	= array();
			}
		}

		if( $filesize > 1073741824 ) { 		// GB
			$size	= $filesize / (1024 * 1024 * 1024);
			$label	= $labels['gb'];
		} elseif( $filesize > 1048576 ) {	// MB
			$size	= $filesize / (1024 * 1024);
			$label	= $labels['mb'];
		} elseif( $filesize > 1024 ) {		// KB
			$size	= $filesize / 1024;
			$label	= $labels['kb'];
		} else {							// B
			$size	= $filesize;
			$label	= $labels['b'];
		}

			// Show only a decimal when smaller then 10
		$dez	= $size >= 10 ? 0 : 1;
		$size	= round($size, $dez);

		return number_format($size, $dez, '.', '') . ( $noLabel ? '' : ' ' . $label);
	}



	/**
	 * Parse label if there is a label reference (LLL:)
	 * Else, just return the label
	 *
	 * @param	String		$label		Label or label reference
	 * @param	String		$locale		For output for this locale
	 * @return	String		Real label
	 */
	public static function getLabel($label, $locale = null) {
		if( ! is_string($label) ) {
			return '';
		} elseif( strncmp('LLL:', $label, 4) === 0 ) {
			$labelKey = substr($label, 4);
			return TodoyuLabelManager::getLabel($labelKey, $locale);
		} else {
			return $label;
		}
	}



	/**
	 * Wrap into JS tag
	 *
	 * @param	String	$jsCode
	 * @return	String
	 */
	public static function wrapScript($jsCode) {
		return '<script language="javascript" type="text/javascript">' . $jsCode . '</script>';
	}



	/**
	 * Build an URL with given parameters prefixed with todoyu path
	 *
	 * @param	Array		$params		Parameters as key=>value
	 * @param	String		$hash		Hash (#hash)
	 * @param	Boolean		$absolute	Absolute URL with host server
	 * @return	String
	 */
	public static function buildUrl(array $params = array(), $hash = '', $absolute = false) {
		$query		= rtrim(PATH_WEB, '/\\') . '/index.php';
		$queryParts	= array();

			// Add question mark if there are query parameters
		if( sizeof($params) > 0 ) {
			$query .= '?';
		}

			// Add all parameters encoded
		foreach($params as $name => $value) {
			$queryParts[] = $name . '=' . urlencode($value);
		}

			// Concatinate
		$query .= implode('&', $queryParts);

			// Add hash
		if( ! empty($hash) ) {
			$query .= '#' . $hash;
		}

			// Add absolute server url
		if( $absolute ) {
			$query = SERVER_URL . $query;
		}

		return $query;
	}



	/**
	 * Get short md5 hash of a string
	 *
	 * @param	String		$string
	 * @return	String		10 characters md5 hash value of the string
	 */
	public static function md5short($string) {
		return substr(md5($string), 0, 10);
	}



	/**
	 * Analyze version string and return array of contained sub versions and attributes
	 *
	 * @param	String		$versionString
	 * @return	Array		[major,minor,revision,status]
	 */
	public static function getVersionInfo($versionString) {
		$info			= array();

		if( strpos($versionString, '-') !== false ) {
			$temp	= explode('-', $versionString);
			$version= explode('.', $temp[0]);
			$status	= $temp[1];
		} else {
			$version= explode('.', $versionString);
			$status	= 'stable';
		}

		$info['full']		= $versionString;
		$info['major']		= intval($version[0]);
		$info['minor']		= intval($version[1]);
		$info['revision']	= intval($version[2]);
		$info['status']		= $status;

		return $info;
	}



	/**
	 * Explode string and trim the parts
	 * Alias of TodoyuArray::trimExplode()
	 *
	 * @see		TodoyuArray::trimExplode()
	 * @param	String		$delimiter
	 * @param	String		$string
	 * @param	Boolean		$removeEmptyValues
	 * @return	Array
	 */
	public static function trimExplode($delimiter, $string, $removeEmptyValues = false) {
		return TodoyuArray::trimExplode($delimiter, $string, $removeEmptyValues);
	}



	/**
	 * Extract the headers from a full HTTP response (including headers and content)
	 *
	 * @param	String		$responseContent
	 * @return	Array
	 */
	public static function extractHttpHeaders($responseContent) {
			// Split header and content
		list($headerString) = explode("\r\n\r\n", $responseContent);

		return self::extractHeadersFromString($headerString);
	}



	/**
	 * Extract header pairs from a HTTP header string
	 *
	 * @param	String		$headerString
	 * @return	Array		array
	 */
	public static function extractHeadersFromString($headerString) {
			// Split header pairs
		$headerPairs= explode("\r\n", $headerString);
		$headers	= array();

			// Add HTTP staus as status key
		$headers['status'] = array_shift($headerPairs);

			// Add the rest of the header pairs
		foreach($headerPairs as $headerPair) {
			list($key, $value) = explode(':', $headerPair, 2);
			$headers[trim($key)] = trim($value);
		}

		return $headers;
	}



	/**
	 * Takes a clear text message, finds all URLs and substitutes them by HTML hyperlinks
	 *
	 * @param	String	$text	Message content
	 * @return	String
	 */
	public static function replaceUrlWithLink($htmlContent) {
				// Find full links with prefixed protocol
		$patternFull	= '/(^|[^"])((?:http|https|ftp|ftps):\/\/[-\w@:%+.~#?&;\/=]+)/';
		$replaceFull	= '\1<a href="\2"  target="_blank">\2</a>';

			// Find links which are not prefixed with a protocol, use http
		$patternSimple	= '/(^|[> ])((?:[\w\.-]+)\.(?:[\w-]{2,})\.(?:[a-zA-Z-]{2,6})[-\w@:%+.~#?&;\/=]*)/';
		$replaceSimple	= '\1<a href="http://\2" target="_blank">\2</a>';

			// Find mailto links
		$patternEmail	= '/(^|["> ])((?:[\w-\.]+)@(?:[\w-\.]{2,})\.(?:\w{2,6}))/';
		$replaceEmail	= '\1<a href="mailto:\2">\2</a>';

			// Replace urls
		$htmlContent	= preg_replace($patternFull, $replaceFull, $htmlContent);
		$htmlContent	= preg_replace($patternSimple, $replaceSimple, $htmlContent);
		$htmlContent	= preg_replace($patternEmail, $replaceEmail, $htmlContent);

		return $htmlContent;
	}



	/**
	 * Clean RTE text
	 *  - Remove empty paragraphs from the beginning
	 *  - Remove <pre> tags and add <br> tags for the newlines
	 *
	 * @param	String		$text
	 * @return	String
	 */
	public static function cleanRTEText($text) {
		if( substr($text, 0, 13) === '<p>&nbsp;</p>' ) {
			$text	= substr($text, 13);
		}

		if( strpos($text, '<pre>') !== false ) {
			$prePattern	= '/<pre>(.*?)<\/pre>/s';
			$text		= preg_replace_callback($prePattern, array(self,'callbackPreText'), $text);
		}

		return trim($text);
	}



	/**
	 * Callback for cleanRTEText
	 * Add <br> tags inside the <pre> tags
	 *
	 * @param	Array		$matches
	 * @return	String
	 */
	private static function callbackPreText(array $matches) {
		return nl2br(trim($matches[1]));
	}



}

?>