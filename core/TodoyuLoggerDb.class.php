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
 * Database logger
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuLoggerDb {

	/**
	 * Write log message in database
	 *
	 * @param	String		$message
	 * @param	Integer		$level
	 * @param	Mixed		$data
	 * @param	Array		$info
	 * @param	String		$requestKey
	 */
	public static function log($message, $level, $data, $info, $requestKey) {
		$table	= $GLOBALS['CONFIG']['LOG']['MODES']['DB']['table'];

		$data 	= array(
			'date_create'	=> NOW,
			'id_user'		=> userid(),
			'requestkey'	=> $requestKey,
			'level'			=> intval($level),
			'file'			=> $info['fileshort'],
			'line'			=> $info['line'],
			'message'		=> $message,
			'data'			=> serialize($data)
		);

		return Todoyu::db()->doInsert($table, $data);
	}

}


?>