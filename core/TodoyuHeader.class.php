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

/** Send HTTP headers
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuHeader {

	/**
	 * Current content type
	 *
	 * @var	String
	 */
	private static $type = 'HTML';

	/**
	 * Stauts flag if headers already have been sent (with the Header class)
	 *
	 * @var	Boolean
	 */
	private static $sent = false;



	/**
	 * Set sent status
	 *
	 */
	public static function setSent() {
		self::$sent = true;
	}



	/**
	 * Check if headers already have been sent
	 *
	 * @return	Boolean
	 */
	public static function isSent() {
		return self::$sent;
	}



	/**
	 * Set type as currently sent content type
	 *
	 * @param	String	$type
	 */
	public static function setType($type) {
		self::$type = $type;
	}



	/**
	 * Get currently set content type
	 *
	 * @return	String
	 */
	public static function getType() {
		return self::$type;
	}



	/**
	 * Send HTML header
	 *
	 */
	public static function sendHeaderHTML() {
		self::setType('HTML');
		self::sendContentType('text/html');
	}



	/**
	 * Send JSON header
	 *
	 */
	public static function sendHeaderJSON() {
		self::setType('JSON');
		self::sendContentType('application/json');
	}



	/**
	 * Send XML header
	 *
	 */
	public static function sendHeaderXML() {
		self::setType('XML');
		self::sendContentType('text/xml');
	}



	/**
	 * Send plaintext header
	 *
	 */
	public static function sendHeaderPlain() {
		self::setType('PLAIN');
		self::sendContentType('text/plain');
	}



	/**
	 * Send content type header
	 *
	 * @param	String		$type
	 * @param	String		$charset
	 */
	public static function sendContentType($type, $charset = 'utf-8') {
		self::sendHeader('Content-type', "$type;charset=$charset");
	}



	/**
	 * Send headers to prevent any caching
	 *
	 */
	public static function sendNoCacheHeaders() {
		self::sendHeader('Cache-Control', 'no-cache, must-revalidate');
		self::sendHeader('Expires', date('r', 0));
		self::sendHeader('Pragma', 'no-cache');
	}



	/**
	 * Send a hash header
	 *
	 * @param	String		$hash
	 */
	public static function sendHashHeader($hash) {
		self::sendTodoyuHeader('Hash', $hash);
	}



	/**
	 * Send a header prefixed with 'Todoyu-'
	 *
	 * @param	String		$name
	 * @param	String		$value
	 */
	public static function sendTodoyuHeader($name, $value) {
		self::sendHeader('Todoyu-' . $name, $value);
	}



	/**
	 * Send Todoyu error header, which marks the current response as failed
	 * This means mostly a submission (form value) was not valid and the form
	 * has to be displayed again
	 *
	 * Can automaticly be checked by js: var hasError = response.hasTodoyuError()
	 */
	public static function sendTodoyuErrorHeader() {
		self::sendTodoyuHeader('error', 1);
	}



	/**
	 * Send custom header
	 *
	 * @param	String		$name
	 * @param	String		$value
	 */
	public static function sendHeader($name, $value) {
		header($name . ': ' . $value);
	}



	/**
	 * Redirect to a todoyu page
	 *
	 * @param	String		$ext
	 * @param	String		$action
	 * @param	Array		$addParams
	 */
	public static function redirect($ext, $controller = 'ext', array $addParams = array()) {
		$params		= array(
			'ext' 		=> $ext,
			'controller'=> $controller
		);
		$params	= array_merge($params, $addParams);

		$url	= TodoyuDiv::buildUrl($params);

		self::sendHeader('Location', $url);
		exit();
	}



	/**
	 * Reload request with the same arguments
	 * All arguments will be converted to GET arguments
	 *
	 */
	public static function reload() {
		$remove			= array('ext', 'controller');
		$requestParams	= TodoyuRequest::getAll();
		$redirectParams	= array_diff($requestParams, $remove);

		self::redirect($requestParams['ext'], $requestParams['controller'], $redirectParams);
	}

}


?>