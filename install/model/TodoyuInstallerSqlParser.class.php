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
		$sql	= self::trimLines($sql, true);
		$sql	= self::removeSqlComments($sql);

		return $sql;
	}



	/**
	 * Removes whitespace from lines' start, optionally remove all-whitespaced lines
	 *
	 *	@param	String	$sql
	 *	@return	String
	 */
	private static function trimLines($sql, $dropWhitespaceLines = true) {
		$lines	= explode("\n", $sql);
		$clean	= array();

		foreach($lines as $line) {
			$line	= trim($line);

			if ( strlen($line) > 0 || $dropWhitespaceLines !== true) {
				$clean[]	= $line;
			}
		}

		return implode("\n", $clean);
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
			$line	= trim($line);
				// Line is not a comment?
			if ( substr($line, 0, 2) !== '--' && $line[0] !== '#' ) {
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
			preg_match($pattern, $sql, $matches);

			foreach($matches as $match) {
				$tableName	= str_replace(
					array('TABLE ', 'IF NOT EXISTS', '', '(', '\'', '`', ' '),
					array('', '', '', '', '', '', ''),
					$match
				);

				if ( strlen($tableName) > 0 ) {
					$tableNames[]	= $tableName;
				}
			}
		}

		return array_unique($tableNames);
	}



	/**
	 * Extract one table name from SQL
	 *
	 *	@param	String	$tableSql
	 *	@return	String
	 */
	private static function extractSingleTableName($tableSql) {
		$tableName	= self::extractTableNames($tableSql);

		return $tableName[0];
	}



	/**
	 * Extract column name from SQL
	 *
	 *	@param	String	$sql
	 *	@return	String
	 */
	private static function extractColumnName($sql) {
		$sql	= trim($sql);
		$pattern= '/(?<=`).*(?=`)/';
		preg_match($pattern, $sql, $matches);

		return $matches[0];
	}



	/**
	 * Extract column type declaration
	 *
	 *	@param	String	$sql
	 *	@return	String
	 */
	private static function extractColumnType($sql) {
		$sql	= trim($sql);
		$pattern= '/(?<=`\\s)[a-z\\(\\)0-9]*/';
		preg_match($pattern, $sql, $matches);

		return $matches[0];
	}



	/**
	 * Extract column attributes declaration
	 *
	 *	@param	String	$sql
	 *	@return	String
	 */
	private static function extractColumnAttributes($sql) {
		$sql	= trim($sql);
		$pattern= '/(?<=\\)\\s)[a-zA-Z]*/';
		preg_match($pattern, $sql, $matches);

		$attributes	= trim($matches[0]);

		return $attributes !== 'NOT' ? $attributes : '';
	}



	/**
	 * Extract column null declaration
	 *
	 *	@param	String	$sql
	 *	@return	String
	 */
	private static function extractColumnNull($sql) {
		$sql	= trim($sql);
		$pattern= '/(NOT NULL|NULL)/';
		preg_match($pattern, $sql, $matches);
		$sql	= $matches[0];


		return $matches[0];
	}



	private static function extractColumnDefault($sql) {
		$sql	= trim($sql);
		$pattern= '/DEFAULT\\s\'[0-9a-zA-Z_]\'/';
		preg_match($pattern, $sql, $matches);

		$default	= $matches[0];

		return $default;
	}



	/**
	 * Extract extra from Sql column declaration
	 *
	 *	@param	Stringt	$sql
	 *	@param	Array	$partsToRemove
	 *	@return	String
	 */
	private static function extractColumnExtra($sql, array $partsToRemove) {
		foreach($partsToRemove as $remove) {
			$sql	= str_replace($remove, '', $sql);
		}
		$sql	= trim($sql);
		$sql	= str_replace(' ', '', $sql);

		return $sql;
	}



	/**
	 *	Get structural declarations of all tables' columns from SQL
	 *
	 *	@param	Array	$tableNames
	 *	@param	String	$tablesSql
	 */
	public function getAllTableStructures(array $tableNames, $tablesSql = '') {
		if ($tablesSql == '') {
			$tablesSql	= self::getInstalledExtTablesSqls();
		}
		if ( count($tableNames) == 0 ) {
			$tableNames	= TodoyuInstallerSqlParser::extractTableNames($tablesSql);
		}

			// Init structural definition data array
		$structures	= array();
		foreach($tableNames as $num => $tableName) {
			$structures[$tableName]['columns'] = array();
		}

			// Split SQL into single table definitions, parse them
		$tablesSqlArr	= explode(';', $tablesSql);
		foreach($tablesSqlArr as $num => $tableSql) {
				// Identify table, collect all column definitions per table
			$tableName	= self::extractSingleTableName($tableSql);

			if ( $tableName != '' ) {
				$tableColumns	= self::extractColumns($tableSql);

					// Append columns to table structure data
				$structures[$tableName]['columns']	= array_merge($structures[$tableName]['columns'], $tableColumns);
			}
		}

		return $structures;
	}



	/**
	 *	Extract table structure definition from SQL	(separated into table and columns definition)
	 *
	 *	@param	String	$tableSql
	 *	@return	Array
	 */
	private static function extractColumns($sql) {
		$sql		=	str_replace("\n", ' ', $sql);
		$columns	= array();

			// Extract code for all columns
		$pattern	= '/(?<=\\(\\s).*(?=.PRIMARY)/';
		preg_match($pattern, $sql, $matches);
		$allColumnsSql	= $matches[0];

			// Split into columns
		$colsSqlArr	= explode(',', $allColumnsSql);
		foreach($colsSqlArr as $columnSql) {
			$columnName	= self::extractColumnName($columnSql);
			if ( strlen($columnName) > 0 ) {
				$columns[$columnName]['field']		= '`' . $columnName . '`';
				$columns[$columnName]['type']		= self::extractColumnType($columnSql);
//				$columns[$columnName]['collation']	= '';
				$columns[$columnName]['attributes']	= self::extractColumnAttributes($columnSql);
				$columns[$columnName]['null']		= self::extractColumnNull($columnSql);
				$columns[$columnName]['default']	= self::extractColumnDefault($columnSql);

				$columns[$columnName]['extra']		= self::extractColumnExtra($columnSql, $columns[$columnName]);
			}
		}

		return $columns;
	}



	/**
	 * Get 'tables.sql' contents of all installed extensions
	 *
	 * 	@return	String
	 */
	public static function getInstalledExtTablesSqls() {
		$tableSqls	= array();

		$extKeys	= TodoyuExtensions::getInstalledExtKeys();

		foreach($extKeys as $extKey) {
			$structure	= self::getExtTablesSql($extKey);

			if ( ! $structure === false ) {
				$tableSqls	.= "\n" . $structure;
			}
		}

		return $tableSqls;
	}



	/**
	 * Get 'tables.sql' contents of given extension
	 *
	 *	@param	String	$extKey
	 */
	private static function getExtTablesSql($extKey) {
		$extPath	= TodoyuExtensions::getExtPath($extKey);
		$sqlPath	= $extPath . '/config/db/tables.sql';

		if ( file_exists( $sqlPath ) ) {
				// 'tables.sql' found
			$sql	= file_get_contents($sqlPath);
			$sql	= TodoyuInstallerSqlParser::cleanSql($sql);
		} else {
				// 'tables.sql' missing
			$sql	= false;
		}

		return $sql;
	}



	/**
	 * Find differences between tables' column structures in 'tables.sql' files and DB
	 *
	 *	@param	Array	$sqlStructures
	 *	@param	Array	$dbStructures
	 */
	public static function getStructureDifferences(array $sqlStructures, array $dbStructures) {
			// Compare each table, column from DB with declaration in 'tables.sql', filter out differing ones
		foreach($dbStructures as $tableName => $tableStructure) {
			foreach($tableStructure['columns'] as $columnName => $columnStructure) {
					// Check if column is declared identic in DB and tables.sql

					// Remove identic defined
				unset($sqlStructures[$tableName]['columns'][$columnName]);
			}
		}

			// Remove all tables that have been emptied completely
		foreach($sqlStructures as $tableName => $tableStructure) {
			if ( count($tableStructure['columns']) === 0 ) {
				unset($sqlStructures[$tableName]);
			}
		}

		return $sqlStructures;
	}
}

?>