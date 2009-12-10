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
	 * Check if database connection data is valid
	 *
	 * @param	Array		$data
	 * @return	Boolean
	 * @throws	Exception
	 */
	public static function checkDbConnection($data) {
		$conn	= @mysql_connect($data['server'], $data['username'], $data['password']);

		if( $conn === false ) {
			throw new Exception('Cannot connect to the database server "' . $data['server'] . '" ('.mysql_error().')');
		}

		return true;
	}



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
	 * Get available databases
	 *
	 * @return	Array
	 */
	public static function getAvailableDatabases($error = '')	{
		$dbData		= $_SESSION['todoyuinstaller']['db'];
		$databases	= array(0 => 'Please choose a database');

		$conn = mysql_connect($dbData['server'], $dbData['username'], $dbData['password']);

		$source = mysql_list_dbs($conn);

		while($row = mysql_fetch_object($source))	{
			$databases[$row->Database] = $row->Database;
		}

		return $databases;
	}



	/**
	 * Check DB for existence of given tables, return missing ones
	 *
	 *	@param	Array	$extTableNames
	 *	@return	Array
	 */
	private static function getMissingDbTables(array $tableNames) {
		$missingTables	= array_flip($tableNames);
		$tablesAmount	= count($tableNames) - 1;

		$query	= '	SELECT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME IN (';
		foreach($tableNames as $num => $tableName) {
			$query	.= '\'' . $tableName . '\'' . ($num < $tablesAmount ? ', ' : '');
		}
		$query	.= ')';

		$res	= Todoyu::db()->query($query);
		while(count($missingTables) > 0 && $row = Todoyu::db()->fetchAssoc($res))	{
			unset($missingTables[ $row['TABLE_NAME'] ]);
		}

		return array_flip($missingTables);
	}



	/**
	 * Save database configuration file with submitted data
	 */
	public static function saveDbConfigInFile() {
		$data	= array('db'	=> $_SESSION['todoyuinstaller']['db']);
		$tmpl	= 'install/view/db.php.tmpl';

		$config	= render($tmpl, $data);
		$code	= '<?php' . $config . '?>';
		$file	= PATH . '/config/db.php';

		file_put_contents($file, $code);
	}



	/**
	 * Import static data SQL files
	 */
	private static function importStaticData() {
			// Structure
		$fileStructure	= PATH . '/install/db/db_structure.sql';
		$structure		= file_get_contents($fileStructure);
		$structureParts	= explode(';', $structure);

		foreach($structureParts as $structurePart) {
			if( trim($structurePart) !== '' ) {
				Todoyu::db()->query($structurePart);
			}
		}

			// Data
		$fileData		= PATH . '/install/db/db_data.sql';
		$data			= file_get_contents($fileData);
//		$splitPattern	= "/;+(?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/";
//		$dataParts		= preg_split($splitPattern, $data);
		$dataParts		= explode(';', $data);

		// preg_split causes fatal error on windows systems. Bug?

		foreach($dataParts as $dataPart) {
			if( trim($dataPart) !== '' ) {
				Todoyu::db()->query($dataPart);
			}
		}
	}



	/**
	 * Update the admin password
	 *
	 * @param	String		$newPassword
	 */
	public static function updateAdminPassword($newPassword, $newPasswordConfirm) {
		if( ! ($newPassword == $newPasswordConfirm) )	{
			throw new Exception('Password confirmation was wrong!');
		}

		if( strlen($newPassword) < 5 )	{
			throw new Exception('Password needs at least 5 characters!');
		}

		$pass	= md5($newPassword);
		$table	= 'ext_user_user';
		$where	= 'username = \'admin\'';
		$fields	= array(
			'password'	=> $pass
		);

		Todoyu::db()->doUpdate($table, $where, $fields);
	}



	/**
	 *
	 *	@return	Boolean
	 */
	public static function isDBstructureUptodate() {
			// Get 'table.sql' definitions from extensions having them
		$extTablesSqls	= self::getInstalledExtTablesSqls();

			// Get all table names being declared
		$extTableNames	= self::extractTableNamesFromSqls($extTablesSqls);

			// Find missing tables
		$missingDbTables	=	self::getMissingDbTables($extTableNames);

			// Collect all columns declarations of all tables
//		$extTablesStructures	= self::getAllTableStructures($extTableNames);

			// Find tables with incomplete columns



//		$extTableStructuresInDB	= self::getExtTableStructuresFromDB($tablesToBeChecked);



//		TodoyuDebug::printHtml($extTablesSqls);
//		TodoyuDebug::printHtml($extTableNames);
//		TodoyuDebug::printHtml($missingDbTables);

		return true;
	}



	/**
	 * Get 'tables.sql' contents of all installed extensions
	 *
	 * 	@return	Array
	 */
	private static function getInstalledExtTablesSqls() {
		$tableSqls	= array();

		$extKeys	= TodoyuExtensions::getInstalledExtKeys();

		foreach($extKeys as $extKey) {
			$structure	= self::getExtTablesSql($extKey);

			if ( ! $structure === false ) {
				$tableSqls[$extKey]	= $structure;
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
	 * Extract table names from all given 'tables.sql' contents'
	 *
	 *	@param	Array	$extTableStructuresConf
	 *	@return	Array
	 */
	private static function extractTableNamesFromSqls(array $extTableStructuresConf) {
		$allTables	= array();

		foreach($extTableStructuresConf as $extKey => $sql) {
			$tables		= TodoyuInstallerSqlParser::extractTableNames($sql);
			$allTables	= array_merge($allTables, $tables);
		}

		return $allTables;
	}


}
?>