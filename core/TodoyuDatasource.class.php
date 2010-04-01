<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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
 * Various datasource methods
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuDatasource {

	/**
	 * Get records from 'static_....' table
	 *
	 * @param	String	$type		table postfix (will be prefixed with 'static_')
	 * @param	String	$where		optional WHERE-clause
	 */
	public static function getStaticRecords($type, $where = '') {
		$fields	= '*';
		$table	= 'static_' . $type;

		return Todoyu::db()->getArray($fields, $table, $where);
	}



	/**
	 * Get options based on a static table
	 *
	 * @param	String		$type			Record type (language, country, etc)
	 * @param	String		$fieldValue		Field for value
	 * @param	String		$fieldLabel		Field for label (use value if null)
	 * @param	String		$where			Optional where clause
	 * @param	Boolean		$localize		Localize record label
	 * @return	Array
	 */
	public static function getStaticRecordOptions($type, $fieldValue, $fieldLabel = null, $where = '', $localize = true) {
		$records	= self::getStaticRecords($type, $where);

			// Label field is value field if not set
		if( is_null($fieldLabel) ) {
			$fieldLabel = $fieldValue;
		}

			// Localize record
		if( $localize ) {
			foreach($records as $index => $record) {
				$records[$index]['label'] = self::getStaticLabel($type, $record[$fieldLabel]);
			}
			$fieldLabel = 'label';
		}

			// Reform the array to work as options source
		$reform	= array(
			$fieldValue	=> 'value',
			$fieldLabel	=> 'label'
		);
		$options= TodoyuArray::reform($records, $reform, true);

			// Sort array by label
		$options= TodoyuArray::sortByLabel($options, 'label');

		return $options;
	}




	/**
	 * Render options array (each containing 'value' and 'label') for select element from 'static_...' table
	 *
	 * @param	String	$tablePostfix		table postfix (will be prefixed with 'static_')
	 * @param	String	$labelKey
	 * @param	String	$valueField
	 * @param	Boolean	$sortByLabel
	 * @return	Array
	 */
	public static function getStaticOptions($type, $labelKey = 'name', $valueField = 'id', $sortByLabel = true ) {
		$records			= self::getStaticRecords($type);
		$selectorEntries	= self::localizeStaticRecords($records, $type, $labelKey, $valueField, $sortByLabel );

		$options	= array();
		foreach($selectorEntries as $value => $label) {
			$options[] = array(
				'value'		=> $value,
				'label'		=> $label
			);
		}

		return $options;
	}



	/**
	 * Get label to static record of given type and key
	 *
	 * @param	String	$type
	 * @param	String	$key
	 * @return	String
	 */
	public static function getStaticLabel($type, $key) {
		$labelKey	= 'static_' . $type . '.' . $key;

		return TodoyuLanguage::getLabel($labelKey);
	}



	/**
	 * Localize given static records with given label
	 *
	 * @param	Array	$records
	 * @param	String	$tablePostfix
	 * @param	String	$labelKey
	 * @param	String	$valueField
	 * @return	Array
	 */
	public static function localizeStaticRecords( $records, $tablePostfix = 'country', $labelKey = 'name', $valueField = 'id', $sortByLabel = true ) {
		$entries	= array();
		$labelField	= self::getStaticTableLabelIdentifierField( $tablePostfix );

			// render localized value entries
		foreach( $records as $id => $record ) {
			$labelIdentifier	= self::getLabelIdentifier($record, $labelField);
			$labelName			= 'static_' . $tablePostfix . '.' . $labelIdentifier . '.' . $labelKey;

				// add entry
			$entries[ $record[$valueField] ]	= Label( $labelName );
		}

			// have entries be alphabetically sorted by their label
		if ($sortByLabel) {
			asort($entries);
		}

		return $entries;
	}



	/**
	 * Render label identifier (e.g. 'USA.AL.localname')
	 *
	 * @param	Array	$dataArray
	 * @param	Mixed	$labelField		either name of a field or array of multiple field names
	 * @return	String
	 */
	public static function getLabelIdentifier($dataArray, $labelField) {
		if ( is_array($labelField) ) {
				// identifier is built from multiple fields
			$labelIdentifier	= array();
			foreach( $labelField as $curLabelfield ) {
				$labelIdentifier[]	= $dataArray[ $curLabelfield ];
			}
			$labelIdentifier	= implode('.', $labelIdentifier);
		} else {
				// identifier is single field value
			$labelIdentifier	= $dataArray[ $labelField ];
		}

		return $labelIdentifier;
	}



	/**
	 * Get label field identifier (column name(s) whose values identify the specific localized value)
	 *
	 * @param	String	$tablePostfix
	 * @return	String
	 */
	public static function getStaticTableLabelIdentifierField( $tablePostfix = 'country') {
		switch( $tablePostfix ) {
			case 'territory':
				$labelField	= 'iso_num';
				break;

			case 'currency':
				$labelField	= 'iso_alpha';
				break;

			case 'country_zone':
				$labelField	= array('iso_alpha3_country', 'code');
				break;

			case 'country': default:
				$labelField	= 'iso_alpha3';
				break;
		}

		return $labelField;
	}



	/**
	 * Get static_country options for select element
	 *
	 * @return	Array
	 */
	public static function getStaticCountryOptions() {

		return self::getStaticOptions('country', 'name');
	}



	/**
	 * Get static DB records from 'sys_country'
	 *
	 * @return	Array
	 */
	public static function getCountries() {
		return self::getStaticRecords('country');
	}



	/**
	 * Get countries options config (iso alpha3 from static DB table 'sys_country')
	 *
	 * @return	Array
	 */
	public static function getCountryOptions() {
		return self::getStaticRecordOptions('country', 'id', 'iso_alpha3');
	}



	/**
	 * Get options config of available languages (iso alpha2 from static DB table 'sys_language')
	 *
	 * @param	String	$where
	 * @return	Array
	 */
	public static function getLanguageOptions($where = '') {
		return self::getStaticRecordOptions('language', 'iso_alpha2', null, $where);
	}



	/**
	 * Render vals array (each 'value' => 'label') from 'static_...' table records whose localized label begins with given search word
	 *
	 * @param	String	$tablePostfix		table postfix (will be prefixed with 'static_')
	 * @param	String	$labelKey			last segment of locale-key (to allow for multiple label types of the same record)
	 * @param	String	$whereClause		where clause to limit record gathering
	 * @param	String	$beginningWith		so far given input text
	 * @param	String	$valueField			DB field containing the value
	 * @param	Boolean	$renderLabelAsValue Render resulting list with values being the same as the label?
	 * @param	Boolean	$sortByLabel		have it alphabeticly reordered by the localized label?
	 * @return	Array
	 */
	public static function getStaticValsBeginningWith( $tablePostfix = 'country', $labelKey = 'name', $whereClause = '', $beginningWith = '', $valueField = 'id', $renderLabelAsValue = false, $sortByLabel = true ) {
		$records			= self::getStaticRecords($tablePostfix, $whereClause );
		$valEntries	= self::localizeStaticRecords( $records, $tablePostfix, $labelKey, $valueField, $sortByLabel );

		$entries		= array();
		$beginningWith	= strtolower( $beginningWith );
		$strlenSword	= strlen( $beginningWith );

		foreach($valEntries as $value => $label) {
			if ( strncmp( strtolower($label), $beginningWith, $strlenSword) == 0 ) {
					// add only entries with the searched beginning
				$entries[ $renderLabelAsValue ? $label : $value ] = $label;
			}
		}

		if ($sortByLabel) {
			asort($entries);
		}

		return $entries;
	}



	/**
	 * Get region values (to current country) to render autocompleter suggestion from
	 *
	 * @param	String		$sword
	 * @param	Integer		$idCountry
	 * @return	Array
	 */
	public static function autocompleteRegions($sword, $idCountry) {
		$idCountry	= intval($idCountry);

		$field	= 'iso_num';
		$table	= 'static_country';
		$where	= 'id =' . $idCountry;

		$countryIsoNum	= Todoyu::db()->getFieldValue($field, $table, $where);

			// Get suggestion values (regions beginning with so far typed text of 'region' field)
		$where	= 'iso_num_country = ' . $countryIsoNum;
		$values	= self::getStaticValsBeginningWith('country_zone', 'localname', $where, $sword, 'id', true, true);

		return $values;
	}



	/**
	 * Get region label
	 *
	 * @param	Mixed	$idRegion	int: fetch from DB, string: return as-is
	 * @return	String
	 */
	public static function getRegionLabel( $idRegion ) {
		if ( is_int( $idRegion ) ) {
			$res = Todoyu::db()->getRecord('static_country_zone', $countryID );
			$region	= Label('static_country_zone.' . $res['iso_alpha3_country'] . '.' . $res['code'] . 'localname' );
		} else {
			$region	= $idRegion;
		}

		return $region;
	}



	/**
	 * Get country label
	 *
	 * @param	Mixed	$idCountry	int: fetch from DB, string: return as-is
	 * @return	String
	 */
	public static function getCountryLabel( $idCountry ) {
		$idCountry = intval($idCountry);

		$res = Todoyu::db()->getRecord('static_country', $idCountry );
		$country	= Label('static_country.' . $res['iso_alpha3'] );

		return $country;
	}



	/**
	 * Return short label of given country
	 *
	 * @param	Integer	$idCountry
	 * @return	String
	 */
	public static function getCountryShort( $idCountry )	{
		$idCountry = intval($idCountry);

		$res = Todoyu::db()->getRecord('static_country', $idCountry);

		return $res['iso_alpha2'];
	}

}

?>