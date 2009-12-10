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
 * Installer
 *
 * @package		Todoyu
 * @subpackage	InstallerDbHelper
 */
class TodoyuInstallerSqlParser {

	/**
	 * Cleans given SQL from whitespace, comments, etc.
	 *
	 *	@param	String	$sql
	 *	@return	String
	 */
	public static function cleanSql($sql) {
		$sql	= self::removeWhiteSpaceFromStartOfLines($sql);
		$sql	= self::removeSqlComments($sql);
		$sql	= self::removeWhiteSpaceLines($sql);

		return $sql;
	}



	/**
	 * Removes whitespace from lines' start
	 *
	 *	@param	String	$sql
	 *	@return	String
	 */
	private static function removeWhiteSpaceFromStartOfLines($sql) {
		$cleanSQL	= array();
		$lines		= explode("\n", $sql);

		foreach($lines as $line) {
				// Reduce multiple whitespaces to one
			$cleanLine	= preg_replace("/(\s){2,}/",'$1', $line);

				// 1st char is space or tab?
			if ( $cleanLine[0] == ' ' || $cleanLine[0] == "\t" ) {
					// Remove 1st char
				$cleanLine	= substr($cleanLine, 1);
			}
			$cleanSql[]	= $cleanLine . ' ';
		}

		return implode("\n", $cleanSql);
	}


	/**
	 * Remove comments from within SQL
	 *
	 *	@param	String	$sql
	 *	@return	String
	 */
	private static function removeSqlComments($sql) {
		$cleanSQL	= array();
		$lines		= explode("\n", $sql);

		foreach($lines as $line) {
				// Line is not a comment?
			if ( substr($line, 0, 2) !== '--' && $line[0] !== '#' ) {
				$cleanSql[]	= $line;
			}
		}

		return implode("\n", $cleanSql);
	}



	/**
	 * Remove lines consisting from whitespace only
	 *
	 *	@param	String	$sql
	 *	@return	String
	 */
	private static function removeWhiteSpaceLines($sql) {
		$cleanSQL	= array();
		$lines		= explode("\n", $sql);

		foreach($lines as $line) {
			$tmp	= str_replace(array("\t", ' '), array('', ''), $line);
			if ( strlen($tmp) > 0 ) {
				$cleanSql[]	= $line;
			}
		}

		return implode("\n", $cleanSql);
	}



	/**
	 * Extract all table names from SQL
	 *
	 *	@param	String	$sql
	 *	@return	Array
	 */
	public static function extractTableNames($sql) {
		$tableNames	= array();

		$parts	= explode(';', $sql);
		foreach($parts as $sql) {
			$pattern	= '/TABLE\\s*([IF NOT EXISTS\\s]*)[\\w\'`]([a-z_]+)[\\w\'`]\s\\(/';
			preg_match($pattern, $sql, $matches, PREG_OFFSET_CAPTURE, 3);

			foreach($matches as $match) {
				$tableName	= str_replace(
					array('TABLE ', 'IF NOT EXISTS', '', '(', '\'', '`', ' '),
					array('', '', '', '', '', '', ''),
					$match[0]
				);

				if ( strlen($tableName) > 0 ) {
					$tableNames[]	= $tableName;
				}
			}
		}

		return array_unique($tableNames);
	}

}
?>