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
 * @subpackage	TodoyuSqlParser
 */
class TodoyuSqlParser {

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
	private static function extractSingleTableName($sql) {
		$tableName	= self::extractTableNames($sql);

		return isset($tableName[0]) ? $tableName[0] : false;
	}



	/**
	 * Extract table keys from SQL
	 *
	 *	@param	String	$tableSql
	 *	@return	Array
	 */
	private static function extractTableKeys($sql) {
		$sql	= trim($sql);

		$pattern= '/KEY\\s`[a-z]*`\\s\\(`[a-z_]*`\\)/';
		preg_match_all($pattern, $sql, $matches);

		if ( count($matches) > 0 ) {
			$keys	= $matches[0];
		} else {
			$keys	= false;
		}

		return $keys;
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

		if ( count($matches) > 0 ) {
			$name	= $matches[0];
		} else {
			$name	= false;
		}

		return $name;
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

		if ( count($matches) > 0 ) {
			$attributes	= trim($matches[0]);
			$attributes	= ! in_array($attributes, array('NOT', 'DEFAULT')) ? $attributes : '';
		} else {
			$attributes	= false;
		}

		return $attributes;
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

		if ( count($matches) > 0) {
			$null	= $matches[0];
		} else {
			$null	= false;
		}

		return $null;
	}



	/**
	 * Extract column dfault declaration
	 *
	 *	@param	String	$sql
	 *	@return	String
	 */
	private static function extractColumnDefault($sql) {
		$sql	= trim($sql);
		$pattern= '/(DEFAULT|default)\\s\'[0-9a-zA-Z_]\'/';
		preg_match($pattern, $sql, $matches);

		if ( count($matches) > 0 ) {
			$default	= $matches[0];
			$default	= str_replace('default ', 'DEFAULT ', $default);
		} else {
			$default = false;
		}

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
			$sql	= str_replace(strtolower($remove), '', $sql);
		}
		$sql	= trim($sql);
		$sql	= strtoupper($sql);

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

				$structures[$tableName]['keys']		= self::extractTableKeys($tableSql);
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

		if ( count($matches) > 0 ) {
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
		}

		return $columns;
	}







	/**
	 * Find differences between tables' column structures in 'tables.sql' files and DB
	 *
	 *	@param	Array	$sqlStructures
	 *	@param	Array	$dbStructures
	 */
	public static function getStructureDifferences($newTables, array $sqlStructures, array $dbStructures) {
		$sqlStructuresBak	= $sqlStructures;

			// Compare each table, column from DB with declaration in 'tables.sql', filter out differing ones
		foreach($dbStructures as $tableName => $tableStructure) {
			foreach($tableStructure['columns'] as $columnName => $columnStructure) {
					// Check if column is declared identic in DB and tables.sql

				$dbColumn	= $columnStructure;
				$sqlColumn	= $sqlStructures[$tableName]['columns'][$columnName];

				if ( is_array($sqlColumn) ) {
					$colDiff	= array_diff_assoc($sqlColumn, $dbColumn);
					if ( count($colDiff) === 0 ) {
							// Remove identic defined
						unset($sqlStructures[$tableName]['columns'][$columnName]);
					} else {
							// Add lookups
						$sqlStructures[$tableName]['columns'][$columnName . '_SQL']	= $sqlColumn;
						$sqlStructures[$tableName]['columns'][$columnName . '_DB']	= $dbColumn;
						$sqlStructures[$tableName]['columns'][$columnName . '_DIFF']= $colDiff;
					}
				}
			}
		}

			// Cleanup
		foreach($sqlStructures as $tableName => $tableStructure) {
				// Remove all tables that have been emptied completely
			if ( count($tableStructure['columns']) === 0 ) {
				unset($sqlStructures[$tableName]);
			} else {

					// Parse diff result, add updating queries
				foreach($sqlStructures[$tableName]['columns'] as $colName => $colStructure) {
					if ( strstr($colName, '_SQL') === false && strstr($colName, '_DB') === false && strstr($colName, '_DIFF') === false ) {
						$action	= '';

						if (array_key_exists($tableName, $newTables) ) {
							if ( $colName === 'id' ) {
									// Query to create table with id-field
								$action	= 'CREATE';
							}
						} else {
								// No Diff? column is to be added new
							if ( ! array_key_exists($colName . '_DIFF', $sqlStructures[$tableName]['columns']) ) {
								$action	= 'ADD';
								$sqlStructures[$tableName]['columns'][$colName]['action']	= $action;
							} else {
									// Diff exists, column is to be altered
								$action	= 'ALTER';
								$sqlStructures[$tableName]['columns'][$colName]	= $sqlStructures[$tableName]['columns'][$colName . '_SQL'];
								$sqlStructures[$tableName]['columns'][$colName]['action']	= $action;
								unset($sqlStructures[$tableName]['columns'][$colName . '_DB']);
								unset($sqlStructures[$tableName]['columns'][$colName . '_SQL']);
							}
						}

						if ( $action !== '' ) {
								// Get query
							$sqlStructures[$tableName]['columns'][$colName]['query']	= self::getUpdatingQuery($action, $tableName, $colName, $sqlStructures[$tableName]['columns'][$colName], $sqlStructuresBak);
						}
					}
				}
			}
		}

		return $sqlStructures;
	}



	/**
	 * Render query to carry out DB updates
	 *
	 *	@param	String	$action
	 *	@param	String	$tableName
	 *	@param	String	$colName
	 *	@param	Array	$colStructure
	 *	@return	String
	 */
	private static function getUpdatingQuery($action, $tableName, $colName, array $colStructure, $allTablesStructure = array()) {
		switch($action)	{
				// Create table
			case 'CREATE':
				$tableColumnsSql	= self::getMultipleColumnsQueryPart($allTablesStructure[$tableName]['columns']);
				$keys				= self::getKeysQueryPart($allTablesStructure[$tableName]['keys']);

				$query	= 'CREATE TABLE `' . $tableName . '` ( '	. "\n"
						. $tableColumnsSql . ', '					. "\n"
						. 'PRIMARY KEY  (`id`)'
						. ($keys !== '' ? ', '. "\n" : '')
						. $keys						 				. "\n"
						. ') ENGINE=MyISAM  DEFAULT CHARSET=utf8 ; ' . "\n";
				break;

				// Add column
			case 'ADD':
				$query	= 'ALTER TABLE `' . $tableName . '` ADD '	. "\n"
						. self::getFieldColumnsQueryPart($colStructure)
						. ';';
				break;

				// Alter column
			case 'ALTER':
				$query	= 'ALTER TABLE `' . $tableName . '` CHANGE ' . "\n"
						. self::getFieldColumnsQueryPart($colStructure)
						. ';';
				break;
		}

		return $query;
	}




	private static function getFieldColumnsQueryPart(array $colStructure) {
		$query	= $colStructure['field'] . ' '
				. $colStructure['type'] . ' '
				. $colStructure['attributes'] . ' '
				. $colStructure['null'] . ' '
				. $colStructure['default'] . ' '
				. $colStructure['extra'] . ' ';

		return str_replace('  ', ' ', $query);;
	}




	private static function getMultipleColumnsQueryPart($columnsStructure) {
		$queryParts	= array();
		foreach($columnsStructure as $colName => $colProps) {
			$queryParts	[]= self::getFieldColumnsQueryPart($colProps);
		}
		$query	= implode(', ' . "\n", $queryParts);

		return $query;
	}



	private static function getKeysQueryPart(array $keysArr) {
		$query	= implode(', ' . "\n", $keysArr);

		return trim($query);
	}

}

?>