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
* it under the terms of the BSC License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Debug helper. Print useful debug messages in different mime types
 * and limit the output to a list of defined users.
 * Also allows to send debug output with filePhp to FireBug
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuDebug {

	/**
	 * Get information about the position debug was called
	 *
	 * @return	Array
	 */
	private static function getCaller() {
		$backtrace = debug_backtrace();

		$backtrace[1]['fileshort'] = str_replace(PATH, '', $backtrace[1]['file']);

		return $backtrace[1];
	}



	/**
	 * Check if the current user is listed in the username list
	 *
	 * @param	String		$usernames		Comma seperated usernames
	 * @return	Boolean
	 */
	private static function isCurrentUser($usernames) {
		$currentUsername	= Todoyu::person()->getUsername();
		$checkUsernames		= explode(',', $usernames);

		return in_array($currentUsername, $checkUsernames);
	}



	/**
	 * Get PHP formated information about given variable
	 *
	 * @todo	check and improve for different var types
	 *
	 * @param	Mixed		$var
	 * @param	Integer		$niv
	 */
	public static function phpFormat($var, $indent = '&nbsp;&nbsp;', $niv = 0) {
		$str = '';

		if ( is_array($var) ) {
			$str .= 'array(<br />';

			foreach($var as $k=>$v) {
				for( $i = 0; $i < $niv; $i++) {
					$str .= $indent;
				}

				$str .= $indent . '\'' . $k . '\' => &nbsp;';
				$str .= self::phpFormat($v, $indent, $niv + 1);
			}
		} else if( is_object($var) ) {
			$str .= '[object]-class = [' . get_class($var) . ']-method=[';

			$arr = get_class_methods($var);

			foreach ($arr as $method) {
				$str .= $method . '(), ';
			}

			$str .= ']-';
			$str .= self::phpFormat(get_object_vars($var), $indent, $niv + 1);
		} else {
			$str .= '\'' . $var . '\',<br />';
		}

		return($str);
	}



	/**
	 * Print debug message in plain text
	 *
	 * @param	Mixed		$item		Item to debug
	 * @param	String		$title		Title for debug output
	 * @param	String		$usernames	Only this listed users shall see the debug output
	 */
	public static function printPHP($item, $title = '', $usernames = null, $return = false) {
		if( ! is_null($usernames) && ! self::isCurrentUser($usernames) ) {
			return;
		}

		$tmpl	= 'core/view/debug_php.tmpl';
		$data	= array(
			'title'		=> $title,
			'debug'		=> self::phpFormat($item),
			'backtrace'	=> $backtrace ? print_r( debug_backtrace(), true ) : '',
			'caller'	=> self::getCaller()
		);

		$debug	= render($tmpl, $data);

		if ( $return === true ) {
			return $debug;
		} else {
			echo $debug;
		}
	}



	/**
	 * Print debug message in plain text
	 *
	 * @param	Mixed		$item		Item to debug
	 * @param	String		$title		Title for debug output
	 * @param	String		$usernames	Only this listed users shall see the debug output
	 */
	public static function printPlain($item, $title = '', $usernames = null) {
		if( ! is_null($usernames) && ! self::isCurrentUser($usernames) ) {
			return;
		}

		TodoyuHeader::sendHeaderPlain();

		$caller = self::getCaller();

		$output	= "\n";
		if( $title != '' ) {
			$output .= 'DEBUG: ' . $title . "\n";
		}

		$output .= str_repeat('=', 70) . "\n";
		$output .= $caller['file'] . ' : ' . $caller['line'] . "\n";
		$output .= str_repeat('=', 70) . "\n";
		$output .= print_r($item, true);
		$output .= "\n\n";

		echo $output;
	}



	/**
	 * Print debug message as HTML
	 *
	 * @param	Mixed		$item		Item to debug
	 * @param	String		$title		Title for debug output
	 * @param	String		$usernames	Only this listed users shall see the debug output
	 */
	public static function printHtml($item, $title = '', $usernames = null, $backtrace = false) {
		if( ! is_null($usernames) && !self::isCurrentUser($usernames) ) {
			return;
		}

		if( $item === false || $item === true || $item === '' || $item === null ) {
			ob_start();
			var_dump($item);
			$debug = ob_get_flush();
		} else {
			$debug = print_r($item, true);
		}

		$tmpl	= 'core/view/debug_html.tmpl';
		$data	= array(
			'title'		=> $title,
			'debug'		=> $debug,
			'backtrace'	=> $backtrace ? print_r( debug_backtrace(), true ) : '',
			'caller'	=> self::getCaller()
		);

		echo render($tmpl, $data);
	}



	/**
	 * Print debug message as JSON
	 *
	 * @param	Mixed		$item		Item to debug
	 * @param	String		$title		Title for debug output
	 * @param	String		$usernames	Only this listed users shall see the debug output
	 */
	public static function printJson($item, $title, $usernames = null) {
		echo "NO JSON DEBUG AT THE MOMENT";
	}



	/**
	 * Print debug message in firebug
	 *
	 * @param	Mixed		$item
	 * @param	String		$title
	 * @param	String		$usernames
	 */
	public static function printInFirebug($item, $title = '', $usernames = null) {
		if( ! is_null($usernames) && !self::isCurrentUser($usernames) ) {
			return;
		}

		self::firePhp()->log($item, $title);
	}



	/**
	 * Print the last executed query in firebug
	 *
	 * @param	String		$ident			Special identifier
	 */
	public static function printLastQueryInFirebug($ident = null) {
		$title	= 'Last Query';

		if( ! is_null($ident) ) {
			$title .= ' (' . $ident . ')';
		}

		self::printInFirebug(Todoyu::db()->getLastQuery(), $title);
	}



	/**
	 * Get firePhp Instance
	 *
	 * @return	FirePhp
	 */
	public static function firePhp() {
		return FirePHP::getInstance(true);
	}



	/**
	 * Print function backtrace debug
	 *
	 */
	public static function printBacktrace() {
		$backtrace	= debug_backtrace();
		array_shift($backtrace);

		self::printHtml($backtrace, 'Backtrace');
	}

}



?>