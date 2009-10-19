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
 * Various datasource methods
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuDatasource {



	/**
	 * Fetch records from 'static_....' table
	 *
	 * @param	String	$type		table postfix (will be prefixed with 'static_')
	 * @param	String	$where		optional WHERE-clause
	 */
	public static function getStaticRecords($type, $where = '') {
		$fields	= '*';
		$table	= 'static_' . $type;

		return Todoyu::db()->getArray($fields, $table, $where);
	}


	public static function getStaticRecordOptions($type, $keyValue, $keyLabel, $localize = true) {
		$records	= self::getStaticRecords($type);

		if( $localize ) {
			foreach($records as $index => $record) {
				$records[$index]['label']		= self::getStaticLabel($type, $record[$keyLabel]);
			}
			$keyLabel = 'label';
		}

		$reform	= array(
			$keyValue	=> 'value',
			$keyLabel	=> 'label'
		);

		$options= TodoyuArray::reform($records, $reform, true);
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


	public static function getStaticLabel($type, $key) {
		$labelKey	= 'static_' . $type . '.' . $key;

		return TodoyuLocale::getLabel($labelKey);
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

		return self::getStaticOptions( 'country', 'name' );
	}



	public static function getCountries() {
		return self::getStaticRecords('country');
	}


	public static function getCountryOptions() {
		return self::getStaticRecordOptions('country', 'id', 'iso_alpha3', true);
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
	 * @return Array
	 */
	public static function autocompleteRegions( $sword ) {
			// get id of region input element (should be something like 'company-address-0-0-field-region')
		$inputElementID = TodoyuRequest::getParam('acelementid');

			// get index of address fieldset containing the region and country field
		$addressIndex = explode('-', $inputElementID);
		$addressIndex = $addressIndex[2];

		$formData		= TodoyuRequest::getAll();
		$countryID		= $formData['company']['address'][ $addressIndex ]['id_country'];
		$countryIsoNum	= Todoyu::db()->getFieldValue('iso_num', 'static_country', 'id =' . $countryID );

			// get suggestion values (regions beginning with so far typed text of 'region' field)
		$whereClause	= ' iso_num_country = ' . $countryIsoNum;
		$values			= self::getStaticValsBeginningWith( 'country_zone', 'localname', $whereClause, $sword, 'id', true, true );

		return $values;
	}



	/**
	 * Get region label
	 *
	 * @param	Mixed	$idRegion	int: fetch from DB, string: return as-is
	 * @return	String
	 *
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
	 *
	 */
	public static function getCountryLabel( $idCountry ) {
		$idCountry = intval($idCountry);

		$res = Todoyu::db()->getRecord('static_country', $idCountry );
		$region	= Label('static_country.' . $res['iso_alpha3'] . '.name');

		return $region;
	}




}


?>