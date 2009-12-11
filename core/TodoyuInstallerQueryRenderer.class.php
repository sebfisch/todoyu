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
 * @subpackage	TodoyuInstallerQueryRenderer
 */
class TodoyuInstallerQueryRenderer {

	/**
	 * Render query to carry out DB updates
	 *
	 *	@param	String	$action
	 *	@param	String	$tableName
	 *	@param	String	$colName
	 *	@param	Array	$colStructure
	 *	@return	String
	 */
	public static function getUpdatingQuery($action, $tableName, $colName, array $colStructure, $allTablesStructure = array()) {
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