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
class TodoyuInstallerDbHelper {

	/**
	 * Add database
	 *
	 * @param	String	$error
	 */
	public static function addDatabase($error = '')	{
		if( strlen($_POST['database_new']) > 0 )	{
			$dbData		= $_SESSION['todoyuinstaller']['db'];
			$conn = mysql_connect($dbData['server'], $dbData['username'], $dbData['password']);
			if(@mysql_query('CREATE DATABASE `' . trim($_POST['database_new']) . '`', $conn) == false)	{
				throw new Exception('Can not create database ' . $_POST['database_new'] . ': (' . mysql_error() . ')');
			}
			$_SESSION['todoyuinstaller']['db']['database'] = $_POST['database_new'];
		} else if( $_POST['database'] != '0' )	{
			$_SESSION['todoyuinstaller']['db']['database'] = $_POST['database'];
		} else {
			throw new Exception('Please select a database or enter a name');
		}
	}



	/**
	 * Save database configuration file with submitted data
	 */
	public static function saveDbConfigInFile() {
//		echo '<pre>'.print_r($_SESSION['todoyuinstaller']['db'], true).'</pre>';
//		die();

		$data	= array(
			'db'	=> $_SESSION['todoyuinstaller']['db']
		);
		$tmpl	= 'install/view/configs/db.php.tmpl';

		$config	= render($tmpl, $data);
		$code	= '<?php' . $config . '?>';
		$file	= PATH . '/config/db.php';

		file_put_contents($file, $code);
	}



	/**
	 * Import static data SQL files
	 */
	public static function importStaticData() {
			// Structure (from core and extensions 'tables.sql' files)
		$coreStructure	= TodoyuInstallerDbHelper::getCoreDBstructures();
		$extStructure	= TodoyuInstallerDbHelper::getExtDBstructures();

		if ( count($coreStructure) > 0 ) {
			TodoyuInstallerDbHelper::compileAndRunInstallerQueries($coreStructure);
		}
		if ( count($extStructure) > 0  ) {
			TodoyuInstallerDbHelper::compileAndRunInstallerQueries($extStructure);
		}

			// Data
		$fileData		= PATH . '/install/config/db/db_data.sql';
		$data			= file_get_contents($fileData);

		$dataParts		= explode(';', $data);
		foreach($dataParts as $dataPart) {
			if( trim($dataPart) !== '' ) {
				Todoyu::db()->query($dataPart);
			}
		}
	}



	/**
	 * Update the admin password
	 *
	 * @param	String	$newPassword
	 */
	public static function updateAdminPassword($newPassword, $newPasswordConfirm) {
			// Validate
		if( ! ($newPassword == $newPasswordConfirm) )	{
			throw new Exception('Password confirmation was wrong!');
		}
		if( strlen($newPassword) < 5 )	{
			throw new Exception('Password needs at least 5 characters!');
		}

			// Store admin user in DB
		$pass	= md5($newPassword);
		$table	= 'ext_user_user';
		$where	= 'username = \'admin\'';
		$fields	= array(
			'password'	=> $pass
		);

		Todoyu::db()->doUpdate($table, $where, $fields);
	}



	/**
	 *	Get all core DB tables' structures for fresh install from 'tables.sql'
	 *
	 *	@return	Array
	 */
	public static function getCoreDBstructures() {
			// Get 'table.sql' definitions from extensions having them
		$tablesSql	= TodoyuExtensions::getCoreTablesSqls();

			// Get all table names being declared
		$tablesNames	= TodoyuSqlParser::extractTableNames($tablesSql);

			// Collect all columns declarations of all tables
		$tablesStructures	= TodoyuSqlParser::getAllTableStructures($tablesNames, $tablesSql);

			// Collect all tables' comparisom columns declarations as setup in DB
		$tablesStructuresInDB	= array();

			// During a fresh install all tables are new ;)
		$newTables	=  array_flip($tablesNames);

		$structures	= self::getDBStructureDifferences($newTables, $tablesStructures, $tablesStructuresInDB);

		return $structures;
	}



	/**
	 *	Get all extensions DB tables' structures for fresh install from 'tables.sql'
	 *
	 *	@return	Array
	 */
	public static function getExtDBstructures() {
			// Get 'table.sql' definitions from extensions having them
		$tablesSql	= TodoyuExtensions::getInstalledExtTablesSqls();

			// Get all table names being declared
		$tablesNames	= TodoyuSqlParser::extractTableNames($tablesSql);

			// Collect all columns declarations of all tables
		$tablesStructures	= TodoyuSqlParser::getAllTableStructures($tablesNames, $tablesSql);

			// Collect all tables' comparisom columns declarations as setup in DB
		$tablesStructuresInDB	= array();

			// During a fresh install all tables are new ;)
		$newTables	=  array_flip($tablesNames);

		$structures	= self::getDBStructureDifferences($newTables, $tablesStructures, $tablesStructuresInDB);

		return $structures;
	}



	/**
	 *	Find all differences between 'tables.sql' files and DB of installed extensions
	 *
	 *	@return	Array
	 */
	public static function getDBstructureDiff() {
			// Get 'table.sql' definitions from extensions having them
		$extTablesSql	= TodoyuExtensions::getInstalledExtTablesSqls();

			// Get all table names being declared
		$extTablesNames	= TodoyuSqlParser::extractTableNames($extTablesSql);

			// Collect all columns declarations of all tables (from tables.sql files)
		$extTablesStructures	= TodoyuSqlParser::getAllTableStructures($extTablesNames, $extTablesSql);

			// Collect all tables' comparisom columns declarations as setup in DB
		$extTablesStructuresInDB	= TodoyuDbAnalyzer::getTablesStructures($extTablesNames);

			// Compare: Find missing tables and tables with incomplete columns
		$newTables	= TodoyuDbAnalyzer::getMissingTables($extTablesNames);

		$diff	= self::getDBStructureDifferences($newTables, $extTablesStructures, $extTablesStructuresInDB);

		return $diff;
	}



	/**
	 * Find differences between tables' column structures in 'tables.sql' files and DB
	 *
	 *	@param	Array	$sqlStructures
	 *	@param	Array	$dbStructures
	 */
	private static function getDBStructureDifferences(array $newTables, array $sqlStructures, array $dbStructures) {
		$sqlStructuresBak	= $sqlStructures;

			// Compare each table, column from DB with declaration in 'tables.sql', filter out differing ones
		foreach($dbStructures as $tableName => $tableStructure) {

			foreach($tableStructure['columns'] as $columnName => $columnStructure) {
					// Check if column is declared identic in DB and tables.sql

				$dbColumn	= $columnStructure;
				if ( array_key_exists($columnName, $sqlStructures[$tableName]['columns']) ) {
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
							$sqlStructures[$tableName]['columns'][$colName]['query']	= TodoyuInstallerQueryRenderer::getUpdatingQuery($action, $tableName, $colName, $sqlStructures[$tableName]['columns'][$colName], $sqlStructuresBak);
						}
					}
				}
			}
		}

		return $sqlStructures;
	}



	/**
	 * Compile, clean, separate and run all queries included in given structural analysis results array
	 *
	 *	@param	Array	$dbDiff
	 */
	public static function compileAndRunInstallerQueries(array $dbStructures) {
		$query	= '';

		foreach($dbStructures as $table => $tableData) {
			foreach ($tableData['columns'] as $columnName => $columnProps) {
				$query .= $columnProps['query'] . "\n";
			}
		}

		$query	= TodoyuSqlParser::cleanSql($query);
		$query	= str_replace("\n", ' ', $query);
		$queries	= explode(';', $query);

		foreach($queries as $query) {
			$query	= trim($query);
			if ( $query !== '' ) {
				Todoyu::db()->query( $query . ';' );
			}
		}
	}


}
?>