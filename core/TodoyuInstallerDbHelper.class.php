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
	 * @param	String	$newDatabase
	 * @param	String	$database
	 * @param	String	$server
	 * @param	String	$username
	 * @param	String	$password
	 */
	public static function addDatabase($databaseName, array $dbConfig)	{
		$link	= mysql_connect($dbConfig['server'], $dbConfig['username'], $dbConfig['password']);
		$query	= 'CREATE DATABASE `' . $databaseName . '`';

		return @mysql_query($query, $link) !== false;



//
//
//			// Create new DB?
//		if( strlen($newDatabase) > 0 )	{
//			$conn	= mysql_connect($server, $username, $password);
//			$query	= 'CREATE DATABASE `' . $newDatabase . '`';
//
//			if( @mysql_query($query, $conn) == false )	{
//				throw new Exception('Can not create database ' . $newDatabase . ': (' . mysql_error() . ')');
//			}
//			$_SESSION['todoyuinstaller']['db']['database'] = $newDatabase;
//		} else if( $database == '0' )	{
//				// No DB name given or selected?
//			throw new Exception('Please select a database or enter a name');
//		}
	}



	/**
	 * Save DB configuration file 'config/db.php' with submitted data
	 *
	 * @param	String	$server
	 * @param	String	$username
	 * @param	String	$password
	 * @return	Integer	number of bytes written or false on failure
	 */
	public static function saveDbConfigInFile(array $dbConfig) {
		$tmpl	= 'install/view/configs/db.php.tmpl';
		$file	= PATH . '/config/db.php';

		return TodoyuFileManager::saveTemplatedFile($file, $tmpl, $dbConfig, true);
	}



	/**
	 * Import static data from SQL files
	 */
	public static function importAllTables() {
			// Get table structure from core tables.sql
		$fileCoreStructure	= TodoyuSQLManager::getCoreTablesFromFile();
			// Get table structure from all ext tables.sql
		$fileExtStructure	= TodoyuSQLManager::getExtTablesFromFile();
			// Merge the core and all ext table structures
		$fileTableStructure	= TodoyuSQLManager::mergeCoreAndExtTables($fileCoreStructure, $fileExtStructure);
			// Get table structure from database
		$dbTableStructure	= TodoyuDbAnalyzer::getTableStructures();
		$structureDifferences	= TodoyuSqlParser::getStructureDifferences($fileTableStructure, $dbTableStructure);






		$updateQueries	= TodoyuSQLManager::getStructureUpdateQueriesFromDifferences($structureDifferences);


		TodoyuDebug::printHtml($updateQueries, '$updateQueries');




		TodoyuDebug::printHtml($allTablesDb, '$allTablesDb');
		TodoyuDebug::printHtml($coreTablesFile, '$coreTablesFile');
//		TodoyuDebug::printHtml($extStructure);

		exit();


		return ;

		if ( count($coreStructure) > 0 ) {
			self::compileAndRunInstallerQueries($coreStructure);
		}
		if ( count($extStructure) > 0  ) {
			self::compileAndRunInstallerQueries($extStructure);
		}
	}






	/**
	 * Validate and update the admin password
	 *
	 * @param	String	$newPassword
	 * @param	String	$newPasswordConfirm
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
	 * Find all differences between 'tables.sql' files and DB of installed extensions
	 *
	 * @return	Array
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
		$newTables	= TodoyuSQLManager::getMissingTables($extTablesNames);

		$diff	= self::getDBStructureDifferences($newTables, $extTablesStructures, $extTablesStructuresInDB);

		return $diff;
	}



	/**
	 * Find differences between tables' column structures in 'tables.sql' files and DB
	 *
	 * @param	Array	$sqlStructures
	 * @param	Array	$dbStructures
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
	 * Check whether the installation has been carried out before
	 *
	 * @return	Boolean
	 */
	public static function isDatabaseConfigured() {
		return (	$GLOBALS['CONFIG']['DB']['autoconnect'] === true
				&&	$GLOBALS['CONFIG']['DB']['server']		!== ''
				&&	$GLOBALS['CONFIG']['DB']['username']	!== ''
				&&	$GLOBALS['CONFIG']['DB']['password']	!== ''
				&&	$GLOBALS['CONFIG']['DB']['database']	!== '');
	}



	/**
	 * Compile, clean, separate and run all queries included in given structural analysis results array
	 *
	 * @param	Array	$dbDiff
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