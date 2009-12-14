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
class TodoyuDbAnalyzer {

	/**
	 * Get available databases
	 *
	 * @return	Array
	 */
	public static function getAvailableDatabases($dbData, $error = '')	{
		$databases	= array(0 => 'Please choose a database');

		$conn = mysql_connect($dbData['server'], $dbData['username'], $dbData['password']);
		$source = mysql_list_dbs($conn);

		while($row = mysql_fetch_object($source))	{
			$databases[$row->Database] = $row->Database;
		}

		return $databases;
	}



	/**
	 * Check if database connection data is valid
	 *
	 * @param	Array		$data
	 * @return	Boolean
	 * @throws	Exception
	 */
	public static function checkDbConnection(array $data = array()) {
		if ( array_key_exists('server', $data) && array_key_exists('username', $data) && array_key_exists('password', $data) ) {
			$conn	= @mysql_connect($data['server'], $data['username'], $data['password']);

			if( $conn === false ) {
				throw new Exception('Cannot connect to the database server "' . $data['server'] . '" ('.mysql_error().')');
			}

			$valid	= true;
		} else {
			$valid	= false;
		}

		return $valid;
	}


	/**
	 * Get given tables' declarations as available in DB
	 *
	 *	@param	Array	$tablesNames
	 *	@return Array
	 */
	public static function getTablesStructures(array $tablesNames) {
		$tablesAmount	= count($tablesNames) - 1;
		$query	= '	SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME IN (';

		$count	= 0;
		foreach($tablesNames as $tableName) {
			$query	.= '\'' . $tableName . '\'' . ($count < $tablesAmount ? ', ' : '');
			$count++;
		}
		$query	.= ')';

		$structure	= array();

		$res	= Todoyu::db()->query($query);
		while($row = Todoyu::db()->fetchAssoc($res))	{
			$tableName	= $row['TABLE_NAME'];
			$columnName	= $row['COLUMN_NAME'];

				// Get field, type, attributes, null, default, extra
			$field		= '`' . $columnName . '`';
			$type		= trim(strtolower($row['COLUMN_TYPE']));

			if ( strstr($type, ' ') !== false) {
				$typeParts	= explode(' ', $type);
				$type		= $typeParts[0];
				$attributes	= $typeParts[1];
			} else {
				$attributes	= '';
			}

			$null		= $row['IS_NULLABLE'] == 'YES' ? '' : 'NOT NULL';
			$default	= strlen($row['COLUMN_DEFAULT']) > 0 ? 'DEFAULT \'' . $row['COLUMN_DEFAULT'] . '\'' : '';
			$extra		= strtoupper($row['EXTRA']);

				// Collect column structe data
			$structure[$tableName]['columns'][$columnName]['field']			= $field;
			$structure[$tableName]['columns'][$columnName]['type']			= $type;
//			$structure[$tableName]['columns'][$columnName]['collation']	= '';
			$structure[$tableName]['columns'][$columnName]['attributes']	= $attributes;
			$structure[$tableName]['columns'][$columnName]['null']			= $null;
			$structure[$tableName]['columns'][$columnName]['default']		= $default;
			$structure[$tableName]['columns'][$columnName]['extra']			= $extra;
		}

		return $structure;
	}



	/**
	 * Check DB for existence of given tables, return missing ones
	 *
	 *	@param	Array	$extTableNames
	 *	@return	Array
	 */
	public static function getMissingTables(array $tablesNames) {
		$missingTables	= array_flip($tablesNames);
		$tablesAmount	= count($tablesNames) - 1;

		$query	= '	SELECT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME IN (';
		$count	= 0;
		foreach($tablesNames as $num => $tableName) {
			$query	.= '\'' . $tableName . '\'' . ($count < $tablesAmount ? ', ' : '');
			$count++;
		}
		$query	.= ')';

		$res	= Todoyu::db()->query($query);
		while(count($missingTables) > 0 && $row = Todoyu::db()->fetchAssoc($res))	{
			unset($missingTables[ $row['TABLE_NAME'] ]);
		}

		return $missingTables;
	}


}

?>