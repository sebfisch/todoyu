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
 * SQL parser
 *
 * @package		Todoyu
 * @subpackage	Installer
 */
class TodoyuSQLParser {

	/**
	 * Extract all table names from SQL
	 *
	 * @param	String	$sql
	 * @return	Array
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

				if( strlen($tableName) > 0 ) {
					$tableNames[]	= $tableName;
				}
			}
		}

		$tableNames	= array_unique($tableNames);

		return $tableNames;
	}



	/**
	 * Extract one table name from SQL
	 *
	 * @param	String	$tableSql
	 * @return	String
	 */
	private static function extractSingleTableName($sql) {
		$tableName	= self::extractTableNames($sql);

		return isset($tableName[0]) ? $tableName[0] : false;
	}



	/**
	 * Extract table keys from SQL
	 *
	 * @param	String	$tableSql
	 * @return	Array
	 */
	private static function extractTableKeys($keySQL) {
		$keySQL	= trim($keySQL);
		$keys	= array();

		if( $keySQL !== '' ) {
			$keysSQL= explode(', ', $keySQL);
			$pattern= '/([A-Za-z]*(?:\s*)KEY) (?:`(\w+)`)*(?:\s*)\((.*)\)/';

			foreach($keysSQL as $keySQL) {
				preg_match($pattern, $keySQL, $match);

				$keys[] = array(
					'type'	=> $match[1] === 'PRIMARY KEY' ? 'PRIMARY' : ($match[1] === 'KEY' ? 'INDEX' : trim(str_ireplace('KEY', '', $match[1]))),
					'name'	=> $match[1] === 'PRIMARY KEY' ? 'PRIMARY' : $match[2],
					'fields'=> explode(',', str_replace('`', '', $match[3]))
				);
			}
		}

		return $keys;
	}



	/**
	 * Extract column name from SQL
	 *
	 * @param	String	$sql
	 * @return	String
	 */
	private static function extractColumnName($sql) {
		$sql	= trim($sql);
		$pattern= '/(?<=`).*(?=`)/';
		preg_match($pattern, $sql, $matches);

		if( count($matches) > 0 ) {
			$name	= $matches[0];
		} else {
			$name	= false;
		}

		return $name;
	}



	/**
	 * Extract column type declaration
	 *
	 * @param	String	$sql
	 * @return	String
	 */
	private static function extractColumnType($columnSQL) {
		$remove	= array(
			'NOT NULL',
			'NULL',
			'AUTO_INCREMENT'
		);

		$default	= self::extractColumnDefault($columnSQL);
		if( $default !== '' ) {
			$remove[] = $default;
		}

		$pattern	= '/`\w+`(.*)/';
		preg_match($pattern, $columnSQL, $matches);

		$type		= trim(str_ireplace($remove, '', $matches[1]));

		return $type;
	}



	/**
	 * Extract column attributes declaration
	 *
	 * @param	String	$sql
	 * @return	String
	 */
	private static function extractColumnAttributes($sql) {
		return '';
		$sql	= trim($sql);
		$pattern= '/(?<=\\)\\s)[a-zA-Z]*/';
		preg_match($pattern, $sql, $matches);

		if( count($matches) > 0 ) {
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
	 * @param	String	$sql
	 * @return	String
	 */
	private static function extractColumnNull($sql) {
		$sql	= trim($sql);
		$pattern= '/(NOT NULL|NULL)/';
		preg_match($pattern, $sql, $matches);

		if( count($matches) > 0) {
			$null	= $matches[0];
		} else {
			$null	= false;
		}

		return $null;
	}



	/**
	 * Extract column dfault declaration
	 *
	 * @param	String	$sql
	 * @return	String
	 */
	private static function extractColumnDefault($columnSQL) {
		$columnSQL	= str_replace('default ', 'DEFAULT ', $columnSQL);
		$pattern	= '/(DEFAULT \'[\w]?\')/';
		preg_match($pattern, $columnSQL, $match);

		return sizeof($match) > 0 ? $match[1] : '';

//
//		$columnSQL	= trim($columnSQL);
//		$pattern= '/(DEFAULT|default)\\s\'[0-9a-zA-Z_]+\'/';
//		preg_match($pattern, $columnSQL, $matches);
//
//		if( count($matches) > 0 ) {
//			$default	= $matches[0];
//			$default	= str_replace('default ', 'DEFAULT ', $default);
//		} else {
//			$default = false;
//		}
//
//		return $default;
	}



	/**
	 * Extract extra from SQL column declaration
	 *
	 * @param	Stringt	$sql
	 * @param	Array	$partsToRemove
	 * @return	String
	 */
	private static function extractColumnExtra($columnSQL) {
		$extra	= '';

		if( stristr($columnSQL, 'AUTO_INCREMENT') ) {
			$extra = 'AUTO_INCREMENT';
		}

		return $extra;


//		foreach($partsToRemove as $remove) {
//			$sql	= str_replace($remove, '', $sql);
//			$sql	= str_replace(strtolower($remove), '', $sql);
//		}
//		$sql	= trim($sql);
//		$sql	= strtoupper($sql);
//
//		return $sql;
	}



	/**
	 * Get structural declarations of all tables' columns from SQL
	 *
	 * @param	Array	$tableNames
	 * @param	String	$tablesSql
	 */
	public function getAllTableStructures(array $tableNames, $tablesSql = '') {
		if($tablesSql == '') {
			$tablesSql	= self::getInstalledExtTablesSqls();
		}
		if( count($tableNames) == 0 ) {
			$tableNames	= TodoyuSQLParser::extractTableNames($tablesSql);
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

			if( $tableName != '' ) {
				$tableColumns	= self::extractColumns($tableSql);

					// Append columns to table structure data
				$structures[$tableName]['columns']	= array_merge($structures[$tableName]['columns'], $tableColumns);

				if(! array_key_exists('keys', $structures[$tableName]) ) {
					$structures[$tableName]['keys']		= self::extractTableKeys($tableSql);
				}
			}
		}

		return $structures;
	}



	/**
	 * Extract table structure definition from SQL (separated into table and columns definition)
	 *
	 * @param	String	$tableSql
	 * @return	Array
	 */
	private static function extractColumns($sql) {
		$sql		= str_replace("\n", ' ', $sql);
		$columns	= array();

			// Extract code for all columns
		$pattern	= '/(?<=\\(\\s).*(?=.PRIMARY)/';
//		$pattern	= '/(?<=\\(\\s)(.|\\s)*(?=\\).)/';
		preg_match($pattern, $sql, $matches);

		if( count($matches) > 0 ) {
			$allColumnsSql	= $matches[0];
				// Split into columns
			$colsSqlArr	= explode(',', $allColumnsSql);
			foreach($colsSqlArr as $columnSql) {
				$columnSql	= trim($columnSql);
				if( strstr($columnSql, 'PRIMARY KEY') === false ) {
					$columnName	= self::extractColumnName($columnSql);

					if( strlen($columnName) > 0 ) {
						$columns[$columnName]['field']		= '`' . $columnName . '`';
						$columns[$columnName]['type']		= self::extractColumnType($columnSql);
		//				$columns[$columnName]['collation']	= '';
						$columns[$columnName]['attributes']	= self::extractColumnAttributes($columnSql);
						$columns[$columnName]['null']		= self::extractColumnNull($columnSql);
						$columns[$columnName]['default']	= self::extractColumnDefault($columnSql);

						$columns[$columnName]['extra']		= self::extractColumnExtra($columnSql, $columns[$columnName]);
							// 'extra' and 'attributes' confused? swop them!
							//	@todo fix extraction regex to prevent this
						if( strstr($columns[$columnName]['extra'], 'SIGNED') !== false && $columns[$columnName]['attributes'] == '' ) {
							$columns[$columnName]['attributes']	= strtolower($columns[$columnName]['extra']);
							$columns[$columnName]['extra']	= '';
						}
					}
				}
			}
		}

		return $columns;
	}







	/**
	 * Find differences between tables' column structures in 'tables.sql' files and DB
	 *
	 * @param	Array	$sqlStructures
	 * @param	Array	$dbStructures
	 */
	public static function getStructureDifferencesX($newTables, array $sqlStructures, array $dbStructures) {
		$sqlStructuresBak	= $sqlStructures;

			// Compare each table, column from DB with declaration in 'tables.sql', filter out differing ones
		foreach($dbStructures as $tableName => $tableStructure) {
			foreach($tableStructure['columns'] as $columnName => $columnStructure) {
					// Check if column is declared identic in DB and tables.sql

				$dbColumn	= $columnStructure;
				$sqlColumn	= $sqlStructures[$tableName]['columns'][$columnName];

				if( is_array($sqlColumn) ) {
					$colDiff	= array_diff_assoc($sqlColumn, $dbColumn);
					if( count($colDiff) === 0 ) {
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
			if( count($tableStructure['columns']) === 0 ) {
				unset($sqlStructures[$tableName]);
			} else {

					// Parse diff result, add updating queries
				foreach($sqlStructures[$tableName]['columns'] as $colName => $colStructure) {
					if( strstr($colName, '_SQL') === false && strstr($colName, '_DB') === false && strstr($colName, '_DIFF') === false ) {
						$action	= '';

						if(array_key_exists($tableName, $newTables) ) {
							if( $colName === 'id' ) {
									// Query to create table with id-field
								$action	= 'CREATE';
							}
						} else {
								// No Diff? column is to be added new
							if( ! array_key_exists($colName . '_DIFF', $sqlStructures[$tableName]['columns']) ) {
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

						if( $action !== '' ) {
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
	 * @param	String	$action
	 * @param	String	$tableName
	 * @param	String	$colName
	 * @param	Array	$colStructure
	 * @return	String
	 */
	private static function getUpdatingQuery($action, $tableName, $colName, array $colStructure, $allTablesStructure = array()) {
		switch($action) {
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



	public static function parseCreateQuery($query) {
		$query	= str_replace("\n", ' ', $query);
		$info	= array(
			'table'		=> '',
			'columns'	=> array()
		);

		$pattern1	= '/CREATE TABLE ([A-Za-z ]*)`(\w+)` \((.*)\)(.*)/';
		preg_match_all($pattern1, $query, $matches);

		$columnsKeySQL	= self::splitColumnKeySQL($matches[3][0]);
		$columnsSQL		= explode(',', $columnsKeySQL['columns']);

		$info['table']	= $matches[2][0];
		$info['extra']	= $matches[4][0];
		$info['keys']	= self::extractTableKeys($columnsKeySQL['keys']);

			// Parse columns
		foreach($columnsSQL as $columnSQL) {
				// Parse normal column
			$columnName	= self::extractColumnName($columnSQL);

			$info['columns'][$columnName] = array(
				'field'		=> $columnName,
				'type'		=> self::extractColumnType($columnSQL),
				'attributes'=> self::extractColumnAttributes($columnSQL),
				'null'		=> self::extractColumnNull($columnSQL),
				'default'	=> self::extractColumnDefault($columnSQL),
				'extra'		=> self::extractColumnExtra($columnSQL)
			);
		}

//		TodoyuDebug::printHtml($info, '$info');

		return $info;
	}


	private static function splitColumnKeySQL($SQL) {
		$strPosPrimary	= stripos($SQL, 'PRIMARY KEY ');
		$strPosUnique	= stripos($SQL, 'UNIQUE KEY ');
		$strPosFulltext	= stripos($SQL, 'FULLTEXT KEY ');
		$strPosKey		= stripos($SQL, 'KEY ');
		$info			= array(
			'columns'	=> $SQL
		);
		$keyPositions	= array();

		if( $strPosPrimary !== false ) 	$keyPositions[] = $strPosPrimary;
		if( $strPosUnique !== false ) 	$keyPositions[] = $strPosUnique;
		if( $strPosFulltext !== false ) $keyPositions[] = $strPosFulltext;
		if( $strPosKey !== false ) 		$keyPositions[] = $strPosKey;

		if( sizeof($keyPositions) > 0 ) {
			$pos	= min($keyPositions);

			$info['columns']= trim(substr($SQL, 0, $pos-1), ',');
			$info['keys']	= substr($SQL, $pos);
		}

		return $info;
	}



}

?>