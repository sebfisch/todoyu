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
 * Array helper functions
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuArray {


	/**
	 * Extract a single field from a array
	 *
	 * @param	Array		$array
	 * @param	String		$columnName
	 * @return	Array
	 */
	public static function getColumn(&$array, $columnName) {
		$column = array();

		foreach($array as $subArray) {
			$column[] = $subArray[$columnName];
		}

		return $column;
	}



	/**
	 * Convert all array values to integers. This means all 'non-integer' will be 0
	 * If $onlyPositive is true, all negative integers will be zero too
	 * If $onlyPositive and $removeZeros are true, new array will contain only positive integers
	 *
	 * @param	Array		$array			Dirty array
	 * @param	Boolean		$onlyPositive	Set negative values zero
	 * @param	Boolean		$removeZeros	Remove all zero values
	 * @param	Boolean		$parseConstants	Parse constants?
	 * @return	Array		Integer array
	 */
	public static function intval($array, $onlyPositive = false, $removeZeros = true, $parseConstants = false) {
		if( ! is_array($array) ) {
			return array();
		}

			// Make integers
		foreach($array as $index => $value) {
			if (! $parseConstants) {
				$array[$index] = intval($value);
			} else {
				$array[$index] = defined($value) ? constant($value) : intval($value);
			}
		}

			// Set negative values zero
		if( $onlyPositive ) {
			foreach($array as $index => $value) {
				if( $value <= 0 ) {
					$array[$index] = 0;
				}
			}
		}

			// Remove zeros
		if( $removeZeros ) {
			$newArray = array();
			foreach($array as $value) {
				if( $value > 0 ) {
					$newArray[] = $value;
				}
			}
			$array = $newArray;
		}

		return $array;
	}



	/**
	 * Rename key of an array, defined by mapping array. Only mapped keys will be in the reformed array
	 *
	 * @param	Array		$array
	 * @param	Array		$reformConfig		[old=>new,old=>new]
	 * @return	Array
	 */
	public static function reform(array $array, array $reformConfig, $copyAllData = false) {
		$reformedArray	= array();

		foreach($array as $item) {
			$tempItem = $copyAllData ? $item : array();
			foreach($reformConfig as $oldKey => $newKey) {
				$tempItem[$newKey] = $item[$oldKey];
			}
			$reformedArray[] = $tempItem;
		}

		return $reformedArray;
	}



	/**
	 * Stripslashes on all array values and subarrays
	 *
	 * @param	Array		$array
	 * @return	Array
	 */
	public static function stripslashes(array $array) {
		foreach($array as $key => $value) {
			if( is_array($value) ) {
				$array[$key] = self::stripslashes($value);
			} else {
				$array[$key] = stripslashes($value);
			}
		}

		return $array;
	}



	/**
	 * Sort an array by a specified label. Allows advanced sorting configuration
	 *
	 * @param	Array		$unsortedArray			Original array
	 * @param	String		$sortByLabel			Labelkey to sort by
	 * @param	Boolean		$reversed				Reverse order
	 * @param	Boolean		$caseSensitive			Sort case sensitive. Lower case string are sorted as extra group at the end
	 * @param	Boolean		$useNaturalSorting		Sort as a human would do. Ex: Image1, Image2, Image 10, Image20
	 * @param	Integer		$sortingFlag			Flag for normal (not natural) sorting. Use constants: SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING
	 * @return	Array		Sorted array
	 */
	public static function sortByLabel($unsortedArray, $sortByLabel = 'position', $reversed = false, $caseSensitive = false, $useNaturalSorting = true, $sortingFlag = SORT_REGULAR, $avoidDuplicateFieldKey = '') {
			// Use the labels as key
			// Prevent overwriting double labels
		$labelKeyArray		= array();
		$conflictCounter	= 0;

		foreach($unsortedArray as $index => $item) {
			$label	= $caseSensitive ? $item[$sortByLabel] : strtolower($item[$sortByLabel]);
			$key 	= array_key_exists($label, $labelKeyArray) ? $label . '-' . $conflictCounter++ : $label;

			$labelKeyArray[$key] 	= $item;
		}

			// If no natural sorting is needed, we can take the built-in functions
		if( $useNaturalSorting === false ) {
			if( $reversed ) {
				krsort($labelKeyArray, $sortingFlag);
			} else {
				ksort($labelKeyArray, $sortingFlag);
			}

				// Filter for duplicate field contents,  if requested
			if ($avoidDuplicateFieldKey != '') {
				$labelKeyArray = TodoyuDiv::array_remove_duplicates($labelKeyArray, $avoidDuplicateFieldKey);
			}

			$sortedArray = array_values($labelKeyArray);
		} else {
				// Natural sorting
			$labels	= array_keys($labelKeyArray);
			natsort($labels);

				// Reverse keys if requested
			if( $reversed ) {
				$labels = array_reverse($labels);
			}

				// Load items in the new order into a new array
			$sortedArray = array();
			foreach($labels as $label) {
				$sortedArray[] = $labelKeyArray[$label];
			}
		}

		return $sortedArray;
	}



	/**
	 * Get a new array which contains only elements that match
	 * the filter. The fieldvalue has to be in the list of the filter item
	 *
	 * @example
	 *
	 * Only keep items which have a uid between 1 and 9 und have 352, 80, 440 or 240 pages
	 *
	 * $products 	= $this->getArray('*', 'tx_sfpshop_products');
	 * $filter 		= array('uid' 	=> array(1,2,3,4,5,6,7,8,9),
	 * 						'pages'	=> array(352,80,440,240));
	 * $filteredProducts = tx_sfp::arrayFilter($prodcuts, $filter);
	 *
	 *
	 * @param	Array		$dataArray			Array with the element which are checked against the filter
	 * @param	Array		$filterArray		The filter. It's elements are the fieldnames with the allowed values
	 * @param	Boolean		$matching			Normaly you'll get the matching elements. FALSE gives you the elements which don't match
	 * @param	Boolean		$preserveKeys		Keep the array keys. Else they will be replaced by numeric keys
	 * @return	Array		The filtered array
	 */
	public static function filter(array $dataArray, array $filterArray, $matching = true, $preserveKeys = false ) {
		$passed = array();

			// Check each item
		foreach($dataArray as $key => $itemArray) {
			$match = true;

				// Check if all filters success. Stop if one fails
			foreach($filterArray as $fieldname => $allowedValues) {
				if( !in_array($itemArray[$fieldname], $allowedValues) ) {
					$match = false;
					break;
				}
			}

				// Add to result if the matching is the same as requested
			if( $match === $matching ) {
				$passed[$key] = $itemArray;
			}
		}

		return $preserveKeys ? $passed : array_values($passed);
	}



	/**
	 * Prefix array value with a string. Postfix is also available
	 *
	 * @param	Array		$array
	 * @param	String		$prefix
	 * @param	String		$postfix
	 * @return	Array
	 */
	public static function prefix(array $array, $prefix = '', $postfix = '') {
		foreach($array as $index => $value) {
			$array[$index] = $prefix . $value . $postfix;
		}

		return $array;
	}



	/**
	 * Insert an element into an associative array.
	 * The base array should have named keys. Insert position can be defined by $beforeItem.
	 * If an element with the specified key already exists, it will be replace, except if $replace is false.
	 *
	 * @param 	Array		$array			Base array to insert new item into
	 * @param	Mixed		$newArrayItem	New array item
	 * @param	String		$keyname		Keyname of the new array item
	 * @param	String		$beforeItem		Insert new item before this key. If no key specified, the new element will be appended
	 * @param	Boolean		$replace		Replace an existing element
	 * @return	Array		Array with new item inside
	 */

	public static function insertElement(array $array, $newArrayItem, $keyname, $beforeItem = false, $replace = true) {
		$arrayKeys	= array_flip(array_keys($array));
		$position	= $arrayKeys[$beforeItem];
		$exists		= array_key_exists($keyname, $array);

			// Stop here if key exists and replacing is disabled
		if( $exists === true && $replace === false ) {
			return $array;
		}

			// If no insert position defined or not found, append new item
		if( $beforeItem === false || $position === null ) {
			$array[$keyname] = $newArrayItem;
		} else {
				// Split array at insert position
			$itemsBefore= array_slice($array, 0, $position, true);
			$itemsAfter	= array_slice($array, $position, 1000, true);

				// Append new item to the first half
			$itemsBefore[$keyname] = $newArrayItem;

				// Concat both parts
			$array = array_merge($itemsBefore, $itemsAfter);
		}

		return $array;
	}



	/**
	 * Convert a SimpleXmlElement structure to an associative array
	 * All objects are casted to array
	 *
	 * @param	SimpleXmlElement	$xml	XML object or array (which possibily contains XML objects)
	 * @return	Array
	 */
	public static function fromSimpleXml($xml) {
		$array = (array)$xml;

		foreach($array as $index => $value) {
			if( $value instanceof SimpleXMLElement || is_array($value) ) {
				$array[$index] = self::fromSimpleXML($value);
			}
		}

		return $array;
	}



	/**
	 * Remove data if key matches with one in $keysToRemove
	 *
	 * @param	Array		$array					The array with the data
	 * @param	Array		$keysToRemove			Array which contains the key to remove. Ex: [userFunc,config,useless]
	 * @return	Array		Array which doesn't contain the specified keys
	 */
	public static function removeKeys($array, array $keysToRemove) {
		foreach($keysToRemove as $keyToRemove) {
			unset($array[$keyToRemove]);
		}

		return $array;
	}



	/**
	 * Implode array wrap all entries into single quotes
	 *
	 * @param	Array	$array
	 * @param	String	$delimiter
	 * @return 	Array
	 */
	public static function implodeQuoted($array = array(), $delimiter = ',') {
		$items	= array();

		foreach($array as $item) {
			if ( $item[0] != '\'' && substr($item, -1, 1) != '\'' ) {
					// not single quoted yet, do it now
				$items[]	= '\'' . $item . '\'';
			} else {
				$items[] = $item;
			}
		}

		return implode($delimiter, $items);
	}



	/**
	 * Make sure the variable is an array
	 * If it's not, there are two options: get an empty array or make the input the first element of the array
	 *
	 *
	 * @param	Mixed		$input
	 * @param	Bool		$convert		Convert to array element or get an empty array
	 * @return	Array
	 */
	public static function assure($input, $convert = false) {
		return is_array($input) ? $input : ($convert ? array($input) : array());
	}



	/**
	 * Removes array entrie by its value
	 *
	 * @example
	 *
	 * $array = array(0 => 'foo', 1 => 'bar')
	 * $value = 'bar'
	 *
	 * $newArray = unsetArrayByValue($value, $array);
	 *
	 * $newArray -> array(0 => 'fo0')
	 *
	 *
	 * @param mixed $value
	 * @param array $array
	 * @return array
	 * @todo	review this function and its name!
	 */
	public static function unsetEntrieByValue($value, array $array)	{
		if(in_array($value, $array))	{
			unset($array[array_search($value, $array)]);
		}

		return $array;
	}

}

?>