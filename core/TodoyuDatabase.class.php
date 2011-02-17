<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
 * Database management class
 *
 * @package		Todoyu
 * @subpackage	Core
 * @exception	TodoyuDbException
 */
class TodoyuDatabase {

	/**
	 * Holds the only instance if available.
	 * Singleton Pattern
	 *
	 * @var	Database
	 */
	private static $instance = null;

	/**
	 * Database connection link
	 *
	 * @var	Resource
	 */
	private $link = null;

	/**
	 * History if all executed queries
	 *
	 * @var	Array
	 */
	private $queryHistory = array();

	/**
	 * Database configuration
	 *
	 * @var	Array
	 */
	private $config;



	/**
	 * Get the only instance of the database object
	 * Singleton Pattern
	 *
	 * @param	Array		$config			Configuration
	 * @return	Database
	 */
	public static function getInstance($config) {
		if( is_null(self::$instance) ) {
			self::$instance = new self($config);
		}

		return self::$instance;
	}



	/**
	 * Constructor is only available over getInstance()
	 *
	 * @param	Array		$config
	 */
	private function __construct($config) {
		$this->config = $config;

		if( $this->config['autoconnect'] !== false ) {
			$this->connect();
		}
	}



	/**
	 * Prevent object cloning
	 */
	private function __clone() {

	}



	/**
	 * Connect to the database, using the configuration array
	 */
	private function connect() {
			// Decide how to connect to mysql server
		$mysqlFunc	= $this->config['persistent'] ? 'mysql_pconnect' : 'mysql_connect';

			// Connect to mysql server
		$this->link	= @call_user_func($mysqlFunc, $this->config['server'], $this->config['username'], $this->config['password']);

			// Check if connection to server has failed
		if( $this->link === false ) {
			$this->printConnectionError(mysql_error(), mysql_errno());
			exit();
		}

			// Select database
		$selectedStatus = @mysql_select_db($this->config['database'], $this->link);

			// Check if database selection has failed
		if( $selectedStatus === false ) {
			$this->printSelectDbError(mysql_error(), mysql_errno());
			exit();
		}

		$this->initConnection();
	}



	/**
	 * Initialize database connection
	 * Use utf8 names and clear sql_mode
	 */
	private function initConnection() {
		$this->query("SET sql_mode='ANSI'");
		$this->query('SET NAMES utf8;');
	}



	/**
	 * Check if todoyu is connected to the database
	 *
	 * @return	Boolean
	 */
	public function isConnected() {
		return is_null($this->link) === false;
	}



	/**
	 * Add a query to the query history
	 *
	 * @param	String		$query
	 */
	private function addToHistory($query) {
		$this->queryHistory[] = $query;
	}



	/**
	 * Check whether the database is connected
	 *
	 * @return	Boolean
	 */
	public function hasLink() {
		return !is_null($this->link);
	}



	/**
	 * Get query history
	 *
	 * @return	Array
	 */
	public function getQueryHistory() {
		return $this->queryHistory;
	}



	/**
	 * Escape string for queries
	 *
	 * @param	String		$string
	 * @return	String
	 */
	public function escape($string) {
		return is_float($string) ? str_replace(',', '.', (string)$string) : mysql_real_escape_string($string, $this->link);
	}



	/**
	 * Escape all values in the array
	 * Optional it's available to quote all fields. $noQuoteFields can disable this function for specific fields
	 *
	 * @param	Array		$array				Array to escape (name => value pairs)
	 * @param	Boolean		$quoteFields		Quote the fields (field will be surrounded by single quotes:')
	 * @param	Array		$noQuoteFields		If $quoteFields is enabled, this fields will be ignored for quoting
	 * @return	Array
	 */
	public function escapeArray(array $array, $quoteFields = false, array $noQuoteFields = array()) {
			// Only escape the field if they will not be quoted, quoteArray() escapes the field by itself
		if( $quoteFields  ) {
			$array = $this->quoteArray($array, $noQuoteFields);
		} else {
			foreach($array as $key => $value) {
				$array[$key] =  $this->escape($value);
			}
		}

		return $array;
	}



	/**
	 * Quote all fields in an array
	 *
	 * @param	Array		$array
	 * @param	Array		$noQuoteFields
	 * @return	Array
	 */
	public function quoteArray(array $array, array $noQuoteFields = array()) {
		foreach($array as $key => $value) {
			if( !in_array($key, $noQuoteFields) ) {
				$array[$key] = $this->quote($value, true);
			}
		}

		return $array;
	}



	/**
	 * Quote a string value.
	 *
	 * @param	String		$value
	 * @return	String
	 */
	public function quote($value, $escape = false) {
		$value = $escape ? $this->escape($value) : $value;

		return '\'' . $value . '\'';
	}



	/**
	 * Wrap value in backticks
	 *
	 * @param	String		$value
	 * @return	String
	 */
	public function backtick($value) {
		if( stristr($value, '.') !== false ) {
			$value = str_replace('.', '`.`', $value);
		}

		return '`' . $value . '`';
	}



	/**
	 * Wrap all elements of an array in backticks
	 *
	 * @param	Array		$array
	 * @return	Array
	 */
	public function backtickArray(array $array) {
		return array_map(array($this, 'backtick'), $array);
	}



	/**
	 * Quote a fieldname. Optionaly, the tablename is prefixed
	 *
	 * @param	String		$fieldname
	 * @param	String		$tablename
	 * @return	String		Fieldname in backticks
	 */
	public function quoteFieldname($fieldname, $tablename = null) {
		$fieldname	= '`' . $fieldname . '`';

		if( ! is_null($tablename) ) {
			$fieldname = '`' . $tablename . '`.' . $fieldname;
		}

		return $fieldname;
	}



	/**
	 * Build a select query
	 *
	 * @param	String		$fields
	 * @param	String		$table
	 * @param	String		$where
	 * @param	String		$groupBy
	 * @param	String		$orderBy
	 * @param	String		$limit
	 * @return	String
	 */
	public function buildSELECTquery($fields, $table, $where = '', $groupBy = '', $orderBy = '', $limit = '') {

		$query = 'SELECT ' . $fields . ' FROM ' . $table;

		if( $where != '' ) {
			$query .= ' WHERE ' . $where;
		}

		if( $groupBy != '' ) {
			$query .= ' GROUP BY ' . $groupBy;
		}

		if( $orderBy != '' ) {
			$query .= ' ORDER BY ' . $orderBy;
		}

		if( $limit != '' ) {
			$query .= ' LIMIT ' . $limit;
		}

		return $query;
	}



	/**
	 * Build and execute a select query
	 *
	 * @param	String		$fields
	 * @param	String		$table
	 * @param	String		$where
	 * @param	String		$groupBy
	 * @param	String		$orderBy
	 * @param	String		$limit
	 * @return	Resource
	 */
	public function doSelect($fields, $table, $where = '', $groupBy = '', $orderBy = '', $limit = '') {
		$query = $this->buildSELECTquery($fields, $table, $where, $groupBy, $orderBy, $limit);

		return $this->query($query);
	}



	/**
	 * Select a row. Only one row will be selected.
	 * Limit parameter is only usefull to set an offset.
	 *
	 * @param	String		$fields
	 * @param	String		$table
	 * @param	String		$where
	 * @param	String		$groupBy
	 * @param	String		$orderBy
	 * @param	String		$limit
	 * @return	Array
	 */
	public function doSelectRow($fields, $table, $where = '', $groupBy = '', $orderBy = '', $limit = '1') {
		$query	= $this->buildSELECTquery($fields, $table, $where, $groupBy, $orderBy, $limit);
		$result	= $this->query($query);

		if( $this->hasRows($result) ) {
			$row = $this->fetchAssoc($result);
			$this->freeResult($result);
			return $row;
		} else {
			return false;
		}
	}



	/**
	 * Build insert query
	 *
	 * @param	String		$table
	 * @param	Array		$fieldNameValues
	 * @param	Array		$noQuoteFields
	 * @return	String
	 */
	public function buildINSERTquery($table, array $fieldNameValues, array $noQuoteFields = array()) {
		$fieldNames		= implode(',', $this->backtickArray(array_keys($fieldNameValues)));
		$fieldValues	= implode(',', $this->quoteArray(array_values($fieldNameValues), $noQuoteFields));

		$query = '	INSERT INTO ' . $table
				. ' (' . $fieldNames . ')'
				. ' VALUES (' . $fieldValues . ')';

		return $query;
	}



	/**
	 * Build a select from INFORMATION_SCHEMA.COLUMNS query
	 *
	 * @param	String		$fields
	 * @param	String		$where
	 * @return	String
	 */
	public function buildSELECTinformationSchemaColumnsQuery($fields, $where = '') {
		$dbName	= Todoyu::$CONFIG['DB']['database'];

		$query	 = 'SELECT ' . $fields . ' FROM INFORMATION_SCHEMA.COLUMNS ';
		$query	.= ' WHERE TABLE_SCHEMA = \'' . $dbName . '\' ';

		if( $where != '' ) {
			$query .= ' AND ' . $where;
		}

		return $query;
	}



	/**
	 * Build and execute an insert query
	 *
	 * @param	String		$table
	 * @param	Array		$fieldNameValues
	 * @param	Array		$noQuoteFields
	 * @return	Integer		Autogenerated ID
	 */
	public function doInsert($table, array $fieldNameValues, array $noQuoteFields = array()) {
		$query = $this->buildINSERTquery($table, $fieldNameValues, $noQuoteFields);

		$this->query($query);

		return $this->getLastInsertID();
	}



	/**
	 * Build delete query
	 *
	 * @param	String		$table
	 * @param	String		$where
	 * @param	String		$limit
	 * @return	String
	 */
	public function buildDELETEquery($table, $where, $limit = '') {
		$query	= 'DELETE FROM ' . $table . ' WHERE ' . $where;

		if( $limit != '' ) {
			$query .= ' LIMIT ' . $limit;
		}

		return $query;
	}



	/**
	 * Build and execute a delete query
	 *
	 * @param	String		$table
	 * @param	String		$where
	 * @param	String		$limit
	 * @return	Integer		Num affected (deleted) rows
	 */
	public function doDelete($table, $where, $limit = '') {
		$query	= $this->buildDELETEquery($table, $where, $limit);

		$this->query($query);

		return $this->getAffectedRows();
	}



	/**
	 * Set record(s) deleted = 1
	 *
	 * @param	String		$table
	 * @param	String		$where
	 * @return	Integer		Num affected (updated) rows
	 */
	public function setDeleted($table, $where) {
		$fieldNameValues = array(
			'deleted'	=> 1
		);

		return $this->doUpdate($table, $where, $fieldNameValues);
	}



	/**
	 * Build an update query
	 *
	 * @param	String		$table
	 * @param	String		$where
	 * @param	Array		$fieldNameValues
	 * @param	Array		$noQuoteFields
	 * @return	String
	 */
	public function buildUPDATEquery($table, $where, array $fieldNameValues, array $noQuoteFields = array()) {
		$fieldNameValues= $this->escapeArray($fieldNameValues, true, $noQuoteFields);
		$fields			= array();

		foreach($fieldNameValues as $key => $quotedValue) {
			if( in_array($key, $noQuoteFields) ) {
				$fields[] = $key . ' = ' . $quotedValue;
			} else {
				$fields[] = $this->backtick($key) . ' = ' . $quotedValue;
			}
		}

		$query = 'UPDATE ' . $table . ' SET ';
		$query .= implode(', ', $fields);
		$query .= ' WHERE ' . $where;

		return $query;
	}



	/**
	 * Update database
	 *
	 * @param	String		$table
	 * @param	String		$where
	 * @param	Array		$fieldNameValues
	 * @param	Array		$noQuoteFields
	 * @return	Integer		Num affected (updated) rows
	 */
	public function doUpdate($table, $where, array $fieldNameValues, array $noQuoteFields = array()) {
		$query	= $this->buildUPDATEquery($table, $where, $fieldNameValues, $noQuoteFields);

		$this->query($query);

		return $this->getAffectedRows();
	}



	/**
	 * Update a record
	 *
	 * @param	String		$table
	 * @param	Integer		$idRecord
	 * @param	Array		$fieldNameValues
	 * @param	Array		$noQuoteFields
	 * @return	Boolean		Was record updated?
	 */
	public function doUpdateRecord($table, $idRecord, array $fieldNameValues, array $noQuoteFields = array()) {
		$where	= 'id = ' . intval($idRecord);

		return $this->doUpdate($table, $where, $fieldNameValues, $noQuoteFields) === 1;
	}



	/**
	 * Execute a select on the database, but only return if there is a result for this query
	 *
	 * @param	String		$fields
	 * @param	String		$table
	 * @param	String		$where
	 * @param	String		$groupBy
	 * @param	String		$limit
	 * @return	Boolean
	 */
	public function hasResult($fields, $table, $where, $groupBy = '', $limit = '') {
		$cacheID	= 'hasresult:' . sha1(serialize(func_get_args()));

		if( ! TodoyuCache::isIn($cacheID) ) {
			$result	= $this->doSelect($fields, $table, $where, $groupBy, '', $limit);
			$hasRes	= $this->getNumRows($result) > 0 ;

			TodoyuCache::set($cacheID, $hasRes);
		}

		return TodoyuCache::get($cacheID);
	}



	/**
	 * Build a FIND_IN_SET SQL statement so search in a comma separated field
	 *
	 * @param	String		$itemToFind
	 * @param	String		$fieldname
	 * @return	String
	 */
	public function buildFindInSetQuery($itemToFind, $fieldname) {
		$itemToFind = $this->escape($itemToFind);

		return "FIND_IN_SET('$itemToFind', $fieldname) != 0";
	}



	/**
	 * Build a like query to search multiple strings in multiple fields with LIKE %word%
	 *
	 * @param	Array		$searchWords			Words to search for
	 * @param	Array		$searchInFields			Fields which have to match the $searchWords
	 * @param	Boolean		$negate
	 * @return	String		Where part condition
	 */
	public function buildLikeQuery(array $searchWords, array $searchInFields, $negate = false) {
		$searchWords= $this->escapeArray($searchWords);
		$fieldWheres= array();

			// Build an AND-group for all searchwords
		foreach($searchWords as $searchWord) {
			$fieldParts = array();

				// Build an OR-group for all searchfields
			foreach($searchInFields as $fieldName) {
				$fieldParts[] = $fieldName . ($negate ? ' NOT ' : '') . ' LIKE \'%' . $searchWord . '%\'';
			}

				// Concat field wheres with each words inside
			$fieldWheres[] = implode(' OR ', $fieldParts);
		}

		$where = '((' . implode(') AND (', $fieldWheres) . '))';

		return $where;
	}



	/**
	 * Switch a boolean value in database (0 or 1) to the oposite value
	 * 0 => 1, 1 => 0
	 *
	 * @param	String		$table			Tablename
	 * @param	Integer		$idRecord		ID of the record
	 * @param	String		$fieldname		Fieldname to toggle
	 * @return	Integer
	 */
	public function doBooleanInvert($table, $idRecord, $fieldname) {
		$where	= 'id = ' . intval($idRecord);
		$toggle	= $this->buildBooleanInvert($table, $fieldname);
		$update	= array($fieldname => $toggle);

		return $this->doUpdate($table, $where, $update, array($fieldname)) === 1;
	}



	/**
	 * Build a boolean invert SQL command
	 *
	 * @param	String		$table
	 * @param	String		$fieldname
	 * @return	String
	 */
	public function buildBooleanInvert($table, $fieldname) {
		return $this->quoteFieldname($fieldname, $table) . ' XOR 1';
	}



	/**
	 * Get error message of last executed query
	 *
	 * @return	String
	 */
	public function getError() {
		return mysql_error($this->link);
	}



	/**
	 * Get error number of last executed query
	 *
	 * @return	Integer
	 */
	public function getErrorNo() {
		return mysql_errno($this->link);
	}



	/**
	 * Execute a query on the database
	 *
	 * @param	String		$query
	 * @return	Resource
	 */
	public function query($query) {
			// Add query to history if enabled
		if( $this->config['queryHistory'] ) {
			$this->addToHistory($query);
		}
			// If not connected, try again (will prompt an error if not possible)
		if( ! $this->isConnected() ) {
			$this->connect();
		}

		$resource	= mysql_query($query, $this->link);

		try {
			if( $resource === false ) {
				throw new TodoyuDbException($this->getError(), $this->getErrorNo(), $query);
			}
		} catch(TodoyuDbException $e) {
			TodoyuErrorHandler::handleTodoyuDbException($e);
		}

		return $resource;
	}



	/**
	 * Get amount of rows in the result set
	 *
	 * @param	Resource	$resource
	 * @return	Integer
	 */
	public function getNumRows($resource) {
		return mysql_num_rows($resource);
	}



	/**
	 * Check if a result contains result rows. Detect empty resultsets
	 *
	 * @param	Resource	$result
	 * @return	Boolean
	 */
	public function hasRows($result) {
		return $this->getNumRows($result) > 0;
	}



	/**
	 * Get amount of affected rows by the last query
	 *
	 * @return	Integer
	 */
	public function getAffectedRows() {
		return mysql_affected_rows($this->link);
	}



	/**
	 * Get id which was generated for the last row inserted in the database
	 *
	 * @return	Integer
	 */
	public function getLastInsertID() {
		return mysql_insert_id($this->link);
	}



	/**
	 * Get last executed query from query history
	 *
	 * @return	String
	 */
	public function getLastQuery() {
		$index	= sizeof($this->queryHistory);

			// Inform about disabled history
		if( ! $this->config['queryHistory'] ) {
			Todoyu::log('Tried to get last query, but history is disabled. Change in db config', TodoyuLogger::LEVEL_NOTICE);
		}

		return $this->queryHistory[$index-1];
	}



	/**
	 * Free a resource to save memory if the resource isn't needed anymore
	 *
	 * @param	Resource		$resource
	 * @return	Boolean
	 */
	public function freeResult($resource) {
		return mysql_free_result($resource);
	}



	/**
	 * Get a row by ID (Primary key)
	 *
	 * @param	String		$table
	 * @param	Integer		$idRow
	 * @return	Array		Or false if row doesn't exist
	 */
	public function getRowByID($table, $idRow) {
			// build cache id
		$cacheKey	= TodoyuRecordManager::makeRecordQueryKey($table, $idRow);

			// Check if row is already cached
		if( TodoyuCache::isIn($cacheKey) ) {
			return TodoyuCache::get($cacheKey);
		} else {
				// Fetch row from database, if not in cache
			$where		= 'id = ' . abs($idRow);
			$resource	= $this->doSelect('*', $table, $where);

				// Is a record was found, fetch it
			if( $this->hasRows($resource) ) {
				$row	= $this->fetchAssoc($resource);
					// Remove resource form memory
				$this->freeResult($resource);
					// Add row to cache
				TodoyuCache::set($cacheKey, $row);
			} else {
				$row	= false;
			}

			return $row;
		}
	}



	/**
	 * Get record from table. Alias for getRowByID()
	 *
	 * @param	String		$table
	 * @param	Integer		$idRecord
	 * @return	Array		Or false if row doesn't exist
	 */
	public function getRecord($table, $idRecord) {
		return $this->getRowByID($table, $idRecord);
	}



	/**
	 * Get a record by query. It hasn't to be a "record", its just a single row result
	 *
	 * @param	String		$fields
	 * @param	String		$table
	 * @param	String		$where
	 * @param	String		$groupBy
	 * @param	String		$order
	 * @return	Array		Or false
	 */
	public function getRecordByQuery($fields, $table, $where = '', $groupBy = '', $order = '') {
		$rows	= $this->getArray($fields, $table, $where, $groupBy, $order, '1');

		return sizeof($rows) === 1 ? $rows[0] : false;
	}



	/**
	 * Delete a record by ID
	 *
	 * @param	String		$table
	 * @param	Integer		$idRecord
	 * @return	Boolean
	 */
	public function deleteRecord($table, $idRecord) {
		$where	= 'id = ' . intval($idRecord);

		return $this->doDelete($table, $where, 1) === 1;
	}



	/**
	 * Add a new record to the database
	 *
	 * @param	String		$table			Table where the record is stored
	 * @param	Array		$fieldValues	Fieldname and value pairs
	 * @param	Array		$noQuoteFields	Fields which should not be quoted
	 * @return	Integer		New ID of the record
	 */
	public function addRecord($table, array $fieldValues, array $noQuoteFields = array()) {
		return $this->doInsert($table, $fieldValues, $noQuoteFields);
	}



	/**
	 * Check if a record exists in a table
	 *
	 * @param	String		$table
	 * @param	Integer		$idRecord
	 * @return	Boolean
	 */
	public function isRecord($table, $idRecord) {
		$idRecord	= intval($idRecord);

		$where	= 'id = ' . $idRecord;

		return $this->hasResult('id', $table, $where);
	}



	/**
	 * Get a column from database
	 *
	 * @param	String		$field						Single field to select
	 * @param	String		$table						Table
	 * @param	String		$where						Where
	 * @param	String		$groupBy					Group
	 * @param	String		$orderBy					Order
	 * @param	String		$limit						Limit
	 * @param	String		$resultFieldName			Field name which will be in the SQL result. (ex: "id as idTask"). Not needed if identical with $field
	 * @param	String		$indexField					Field to use as index instead of automatically generated numeric indexes
	 * @return	Array
	 */
	public function getColumn($field, $table, $where = '', $groupBy = '', $orderBy = '', $limit = '', $resultFieldName = '', $indexField = '') {
		$fields	= $field;

			// If an index field is used, it have to be selected too in the
//		if( $indexField !== '' ) {
//			$fields .= ',' . $indexField;
//		}

		$rows	= $this->getArray($fields, $table, $where, $groupBy, $orderBy, $limit);
		$key	= $resultFieldName === '' ? $field : $resultFieldName ;
		$column	= array();

		foreach($rows as $row) {
			if( $indexField === '' ) {
				$column[] = $row[$key];
			} else {
				$column[$row[$indexField]] = $row[$key];
			}
		}

		return $column;
	}



	/**
	 * Get all selected rows as an array
	 *
	 * @param	String		$fields
	 * @param	String		$table
	 * @param	String		$where
	 * @param	String		$groupBy
	 * @param	String		$orderBy
	 * @param	String		$limit
	 * @param	String		$indexField
	 * @return	Array
	 */
	public function getArray($fields, $table, $where = '', $groupBy = '', $orderBy = '', $limit = '', $indexField = false) {
		$cacheID	= sha1(serialize(func_get_args()));

		if( TodoyuCache::isIn($cacheID) ) {
			return TodoyuCache::get($cacheID);
		} else {
			$resource	= $this->doSelect($fields, $table, $where, $groupBy, $orderBy, $limit);
			$array		= $this->resourceToArray($resource, $indexField);

			$this->freeResult($resource);

			TodoyuCache::set($cacheID, $array);

			return $array;
		}
	}



	/**
	 * Get an array with the $indexField value as array-key
	 * Alias for getArray()
	 *
	 * @param	String		$indexField
	 * @param	String		$fields
	 * @param	String		$table
	 * @param	String		$where
	 * @param	String		$groupBy
	 * @param	String		$orderBy
	 * @param	String		$limit
	 * @return	Array
	 */
	public function getIndexedArray($indexField, $fields, $table, $where = '', $groupBy = '', $orderBy = '', $limit = '') {
		return $this->getArray($fields, $table, $where, $groupBy, $orderBy, $limit, $indexField);
	}



	/**
	 * The the value of a single field.
	 * The query should limit the result rows to 1 (all others are ignored anyway)
	 *
	 * @param	String		$field					Field the fetch
	 * @param	String		$table
	 * @param	String		$where
	 * @param	String		$groupBy
	 * @param	String		$orderBy
	 * @param	String		$limit
	 * @param	String		$resultFieldName		If field isn't the fieldname in the resultset (possibily with table prefix...), set the name here
	 * @return	String
	 */
	public function getFieldValue($field, $table, $where = null, $groupBy = null, $orderBy = null, $limit = null, $resultFieldName = null) {
		$resource	= $this->doSelect($field, $table, $where, $groupBy, $orderBy, $limit);
		$key		= is_null($resultFieldName) ? $field : $resultFieldName ;
		$value		= false;

		if( $this->getNumRows($resource) > 0 ) {
			$row	= $this->fetchAssoc($resource);
			$value	= $row[ $key ];
		}

		return $value;
	}



	/**
	 * Update a row in a table defined by its ID
	 *
	 * @param	String		$table				Table where the record is stored
	 * @param	Integer		$idRecord			ID of the record
	 * @param	Array		$fieldValues		Field names with values
	 * @param	Array		$noQuoteFields		Fields which should not be quoted (because they are functions or something)
	 * @return	Boolean
	 */
	public function updateRecord($table, $idRecord, array $fieldValues, array $noQuoteFields = array()) {
		$where = 'id = ' . intval($idRecord);

		$this->doUpdate($table, $where, $fieldValues, $noQuoteFields);

		return $this->getAffectedRows() === 1;
	}



	/**
	 * Check if a query would have a result
	 *
	 * @param	String		$query
	 * @return	Boolean
	 */
	public function queryHasResult($query) {
		$resource = $this->query($query);

		return $this->getNumRows($resource) > 0;
	}



	/**
	 * Fetch a row (array) out of a mysql result
	 * Numeric indexes
	 *
	 * @param	Resource	$result
	 * @return	Array
	 */
	public static function fetchRow($result) {
		return mysql_fetch_row($result);
	}



	/**
	 * Fetch a row (array) out of a mysql result
	 * Associative array with fieldnames
	 *
	 * @param	Resource	$result
	 * @return	Array
	 */
	public static function fetchAssoc($result) {
		return mysql_fetch_assoc($result);
	}



	/**
	 * Fetch a row (object) out of a mysql result
	 * StdObject with fields as public member variables
	 *
	 * @param	Resource	$result
	 * @param	String		$className
	 * @param	Array		$classParams
	 * @return	Object
	 */
	public static function fetchObject($result, $className = null, array $classParams = null) {
		return mysql_fetch_object($result, $className, $classParams);
	}



	/**
	 * Check if last executed query has caused an error
	 *
	 * @return	Boolean
	 */
	public function hasErrorState() {
		return $this->getErrorNo() !== 0;
	}



	/**
	 * Fetch all rows in a result set into an array
	 * Use getArray() if you need all rows of a result
	 *
	 * @param	Resource	$resource
	 * @param	String		$indexField
	 * @return	Array
	 */
	public static function resourceToArray($resource, $indexField = false) {
		$array	= array();

		while( $row = self::fetchAssoc($resource) ) {
			if( $indexField !== false ) {
				$array[$row[$indexField]] = $row;
			} else {
				$array[] = $row;
			}
		}

		return $array;
	}



	/**
	 * Get total found rows of last result
	 * The last query had to include SQL_CALC_FOUND_ROWS
	 *
	 * @return	Integer
	 */
	public function getTotalFoundRows() {
		$query	= 'SELECT FOUND_ROWS() as rows';

		$result	= $this->query($query);
		$row	= $this->fetchAssoc($result);

		return intval($row['rows']);
	}



	/**
	 * Get tables of the database
	 *
	 * @return	Array
	 */
	public function getTables() {
		$dbName	= $this->config['database'];

		$query	= 'SHOW TABLES FROM ' . $this->quoteFieldname($dbName);
		$result	= $this->query($query);

		$rows	= $this->resourceToArray($result);

		$tables	= TodoyuArray::getColumn($rows, 'Tables_in_' . $dbName);

		return $tables;
	}



	/**
	 * Check whether a table exists in the database
	 *
	 * @param	String		$table
	 * @return	Boolean
	 */
	public function hasTable($table) {
		return in_array($table, $this->getTables());
	}



	/**
	 * Truncate a table
	 *
	 * @param	String		$table
	 * @return	Boolean
	 */
	public function truncate($table) {
		if( $this->hasTable($table) ) {
			$this->query('TRUNCATE TABLE ' . $this->quoteFieldname($table));

			return $this->getAffectedRows() > 0;
		} else {
			return false;
		}
	}



	/**
	 * Drop a table
	 *
	 * @param	String		$table
	 */
	public function drop($table) {
		$this->query('DROP TABLE IF EXISTS ' . $this->quoteFieldname($table));
	}



	/**
	 * Get fields of a table
	 *
	 * @param	String		$table
	 * @param	Boolean		$onlyFieldnames
	 * @return	Array
	 */
	public function getFields($table, $onlyFieldnames = false) {
		$query		= 'SHOW COLUMNS FROM ' . $table;
		$resource	= $this->query($query);

		$fields		= $this->resourceToArray($resource);

		if( $onlyFieldnames ) {
			$fieldNames = array();
			foreach($fields as $fieldInfo) {
				$fieldNames[] = $fieldInfo['Field'];
			}
			$fields = $fieldNames;
		}

		return $fields;
	}



	/**
	 * Get all keys (indexes) of a table
	 *
	 * @param	String		$table
	 * @param	Boolean		$onlyKeynames
	 * @return	Array
	 */
	public function getTableKeys($table, $onlyKeynames = false) {
		$query		= 'SHOW KEYS FROM ' . $table;
		$resource	= $this->query($query);

		$keys		= $this->resourceToArray($resource);

		if( $onlyKeynames ) {
			$keyNames = array();
			foreach($keys as $keyInfo) {
				$keyNames[] = $keyInfo['Key_name'];
			}
			$keys = $keyNames;
		}

		return $keys;
	}



	/**
	 * Get database connection config
	 *
	 * @param	String		$key		Config key name
	 * @return	Mixed		Array or String
	 */
	public function getConfig($key = null) {
		return is_null($key) ? $this->config : $this->config[$key];
	}



	/**
	 * Get version of database server
	 *
	 * @return	String
	 */
	public function getVersion() {
		$q = 'SELECT VERSION() as version';

		$result	= $this->query($q);
		$info	= $this->resourceToArray($result);

		return trim($info[0]['version']);
	}



	/**
	 * Print database connection error message
	 * @param	String		$error
	 * @param	Integer		$errorNo
	 */
	private function printConnectionError($error, $errorNo) {
		ob_end_clean();

		$title	= 'Cannot connect to the server "' . htmlentities($this->config['server'], ENT_QUOTES, 'UTF-8') . '"';
		$message= $error . '<br/><br />Check server or change in config/db.php';

		include('core/view/error.html');
	}



	/**
	 * Print database selection error message
	 * @param	String		$error
	 * @param	Integer		$errorNo
	 */
	private function printSelectDbError($error, $errorNo) {
		ob_end_clean();

		$title	= 'Failed selecting database';
		$message= 'Cannot select database "' . htmlentities($this->config['database'], ENT_QUOTES, 'UTF-8') . '" on server ' . htmlentities($this->config['server'], ENT_QUOTES, 'UTF-8') . '<br />Check server or change in config/db.php<br />' . $error;

		include('core/view/error.html');
	}

}

?>