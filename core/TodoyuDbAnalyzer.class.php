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
 * @subpackage	Core
 */
class TodoyuDbAnalyzer {

	/**
	 * Get available databases on server
	 *
	 * @param 	Array		$dbConfig		Connectio configuration [server,username,password]
	 * @return	Array
	 */
	public static function getDatabasesOnServer(array $dbConfig)	{
		$ignore = array(
			'information_schema',
			'mysql',
			'phpmyadmin'
		);

		$link		= mysql_connect($dbConfig['server'], $dbConfig['username'], $dbConfig['password']);
		$resource	= mysql_list_dbs($link);

		$rows		= TodoyuDatabase::resourceToArray($resource);
		$databases	= TodoyuArray::getColumn($rows, 'Database');
		$databases	= array_diff($databases, $ignore);

		return $databases;
	}



	/**
	 * Check if database connection data is valid
	 *
	 * @param	Array		$data
	 * @return	Boolean
	 * @throws	Exception
	 */
	public static function checkDbConnection($server, $username, $password) {
		$status	= @mysql_connect($server, $username, $password);
		$info	= array(
			'status'	=> true
		);

		if( $status === false ) {
			$info['status']	= false;
			$info['error']	= mysql_error();
		}

		return $info;
	}



	/**
	 * Get table and column structure from database.
	 * Only check for tables with the todoyu format:
	 *  - ext_*
	 *  - static_*
	 *  - system_*
	 *
	 * @return	Array
	 */
	public static function getTableStructures() {
		$fields	= '	TABLE_NAME,
					COLUMN_NAME,
					COLUMN_DEFAULT,
					IS_NULLABLE,
					DATA_TYPE,
					CHARACTER_MAXIMUM_LENGTH,
					CHARACTER_SET_NAME,
					COLUMN_TYPE,
					EXTRA';
		$table	= 'INFORMATION_SCHEMA.COLUMNS';
		$where	= '	`TABLE_SCHEMA` = ' . Todoyu::db()->quote(Todoyu::db()->getConfig('database')) . ' AND
					(`TABLE_NAME` LIKE \'system_%\' OR `TABLE_NAME` LIKE \'ext_%\' OR `TABLE_NAME` LIKE \'static_%\')';
		$order	= 'TABLE_NAME';

		$columns= Todoyu::db()->getArray($fields, $table, $where, '', $order);

		$structure	= array();

		foreach($columns as $column) {
			$tableName	= $column['TABLE_NAME'];
			$columnName	= $column['COLUMN_NAME'];

				// If table not yet registered, add table information
			if( ! array_key_exists($tableName, $structure) ) {
				$structure[$tableName] = array(
					'table'		=> $tableName,
					'columns'	=> array(),
					'extra'		=> '',
					'keys'		=> array()
				);

					// Find keys in database
				$tableKeys	= self::getTableKeys($tableName);

				foreach($tableKeys as $tableKey) {
					$structure[$tableName]['keys'][] = array(
						'type'	=> $tableKey['name'] === 'PRIMARY' ? $tableKey['type'] : $tableKey['type'] . ' KEY',
						'name'	=> $tableKey['name'] === 'PRIMARY' ? '' : $tableKey['name'],
						'fields'=> $tableKey['field']
					);
				}
			}

			$structure[$tableName]['columns'][$columnName] = array(
				'field'		=> $columnName,
				'type'		=> $column['COLUMN_TYPE'],
				'attributes'=> '',
				'null'		=> $column['IS_NULLABLE'] === 'YES' ? '' : 'NOT NULL',
				'default'	=> 'DEFAULT \'' . $column['COLUMN_DEFAULT'] . '\'',
				'extra'		=> strtoupper($column['EXTRA'])
			);
		}

		return $structure;
	}



	/**
	 * Get keys (indexes) for a table
	 *
	 * @param	String		$tableName
	 * @return	Array
	 */
	public static function getTableKeys($tableName) {
		$fields	= ' tc.CONSTRAINT_TYPE as type,
					tc.CONSTRAINT_NAME as name,
					kcu.COLUMN_NAME as field';
		$table	= ' INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc,
					INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu';
		$where	= ' tc.TABLE_SCHEMA 	= \'' . Todoyu::db()->getConfig('database') . '\' AND
					tc.TABLE_NAME 		= \'' . $tableName . '\' AND
					tc.CONSTRAINT_NAME	= kcu.CONSTRAINT_NAME AND
					kcu.TABLE_SCHEMA	= \'' . Todoyu::db()->getConfig('database') . '\' AND
					kcu.TABLE_NAME		= \'' . $tableName . '\'';

		return Todoyu::db()->getArray($fields, $table, $where);
	}

}

?>