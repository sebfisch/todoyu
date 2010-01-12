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
 * Global error handler
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuErrorHandler {

	/**
	 * Flag. If true, the default php error handler will be called after the custom one
	 *
	 * @var	Boolean
	 */
	private static $callPhpErrorHandler = false;



	/**
	 * Handler for TodoyuDbException. Print well formatted error information
	 * in the current output format
	 *
	 * @param	TodoyuDbException		$exception
	 */
	public static function handleTodoyuDbException(TodoyuDbException $exception) {
		if( $GLOBALS['CONFIG']['DEBUG'] ) {
			ob_end_clean();

			$type = TodoyuHeader::getType();

			if( TodoyuRequest::isAjaxRequest() || $type === 'PLAIN' ) {
				echo $exception->getErrorAsPlain();
			} elseif( $type === 'JSON' ) {
				echo $exception->getErrorAsJson();
			} else {
				echo $exception->getErrorAsHtml(true);
			}

			exit();
		} else {
			self::endScriptClean('Database error!');
		}
	}



	/**
	 * Handle normal php errors. Disabled at the moment!
	 *
	 * @param	Integer		$errorno
	 * @param	String		$errorstr
	 * @param	String		$file
	 * @param	Integer		$line
	 * @param	Array		$context
	 * @return	Boolean
	 */
	public static function handleError($errorno, $errorstr, $file, $line, $context) {

		switch($errorno) {
			case E_ERROR:
			case E_WARNING:
			case E_PARSE:
			case E_COMPILE_ERROR:
			case E_COMPILE_WARNING:
				echo "CRITICAL ERROR: " . $errorstr;
				break;



			case E_STRICT:
				echo "PHP4 to 5 ERROR: " . $errorstr;
				break;



			case E_NOTICE:
				//echo "NOTICE";
				// Ignore notices
				break;



			case E_CORE_ERROR:
			case E_CORE_WARNING:
				echo "CORE ERROR: " . $errorstr;
				break;



			case E_USER_ERROR:
			case E_USER_WARNING:
				echo "USER ERROR: " . $errorstr;
				break;
		}

		return ! self::$callPhpErrorHandler;
	}



	/**
	 * Clean up and die
	 *
	 * @param	String	$message
	 */
	public static function endScriptClean($message) {
		ob_clean();

		TodoyuHeader::sendHeaderPlain();
		die('ERROR: ' . $message);
	}



	/**
	 * Render simple error message
	 *
	 * @param	$title			Error title
	 * @param	$message		Error message
	 * @return	String
	 */
	public static function renderError($title, $message) {
		$tmpl	= 'core/view/error.tmpl';
		$data	= array(
			'title'		=> $title,
			'message'	=> $message
		);

		return render($tmpl, $data);
	}

}

?>