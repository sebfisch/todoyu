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
	public static function getColumn(array &$array, $columnName) {
		$column = array();

		foreach($array as $subArray) {
			$column[] = $subArray[$columnName];
		}

		return $column;
	}



	/**
	 * Get first key of associative array
	 *
	 * @param	Array	$array
	 * @return	String
	 */
	public static function getFirstKey($array) {
		reset($array);

		return key($array);
	}



	/**
	 * Get offset of given key in given array
	 *
	 * @param	Array		$array
	 * @param	String		$key
	 */
	public static function getKeyOffset(array $array, $key) {
		$keys	= array_keys($array);
		$keys	= array_flip($keys);

		return $keys[$key];
	}



	/**
	 * Get key of last element in associative array
	 *
	 * @param	Array	$array
	 * @return	String
	 */
	public static function getLastKey($array) {
		end($array);

		return key($array);
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
			foreach($array as $index => $value) {
				if( $value > 0 ) {
					$newArray[$index] = $value;
				}
			}
			$array = $newArray;
		}

		return $array;
	}



	/**
	 * Implode array with given delimiter, force items to be integers
	 *
	 * @param	Array	$array
	 * @param	String	$delimiter
	 * @return	String
	 */
	public static function intImplode($array = array(), $delimiter = ',') {
		foreach($array as $id => $value) {
			$array[$id] = intval($value);
		}

		return implode($delimiter, $array);
	}



	/**
	 * Rename key of an array, defined by mapping array. Only mapped keys will be in the reformed array
	 *
	 * @param	Array	$array
	 * @param	Array	$reformConfig		[old=>new,old=>new]
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
	 * @param	Array	$array
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
	public static function sortByLabel(array $unsortedArray, $sortByLabel = 'position', $reversed = false, $caseSensitive = false, $useNaturalSorting = true, $sortingFlag = SORT_REGULAR, $avoidDuplicateFieldKey = '') {
			// Use the labels as key
			// Prevent overwriting double labels
		$labelKeyArray		= array();
		$conflictCounter	= 0;

		foreach($unsortedArray as $index => $item) {
			$label	= $caseSensitive ? $item[$sortByLabel] : strtolower($item[$sortByLabel]);
			$key 	= array_key_exists($label, $labelKeyArray) ? $label . '-' . $conflictCounter++ : $label;

			$item['__key']	= $index;

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
				$labelKeyArray = self::removeDuplicates($labelKeyArray, $avoidDuplicateFieldKey);
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
	 * Get a new array which contains only elements that match the filter. The fieldvalue has to be in the list of the filter item
	 *
	 * @example
	 *
	 * Only keep items which have a uid between 1 and 9 AND have 352, 80, 440 or 240 pages
	 *
	 * $products 	= $this->getArray('*', 'ext_shop_products');
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
	public static function insertElement(array $array, $newKeyName, $newItem, $mode = 'after', $refKeyName = null, $replace = true) {
		$mode		= $mode === 'after' ? 'after' : 'before';
		$exists		= array_key_exists($refKeyName, $array);
		$refKeyName	= $exists ? $refKeyName : null;
		$newArray	= array();

			// Remove current element if it already exists and replace is set true
		if( $exists && $replace && $newKeyName === $refKeyName ) {
			unset($array[$refKeyName]);
		}

			// Stop here if key exists and replacing is disabled
		if( $exists === true && $replace === false && $newKeyName === $refKeyName ) {
				// No action if element already exists and replacing is disabled
			$newArray =& $array;
			TodoyuDebug::printHtml($array);
		} else {
				// If no reference set and mode is before, insert as first element
			if( $mode === 'before' && $refKeyName === null ) {
				$newArray[$newKeyName] = $newItem;
			}

			foreach($array as $key => $item) {
					// When insert reference element found
				if( $key === $refKeyName ) {
						// Insert new element before
					if( $mode === 'before' ) {
						$newArray[$newKeyName] = $newItem;
					}
						// Insert element
					$newArray[$key] = $item;
						// Insert new element after
					if( $mode === 'after' ) {
						$newArray[$newKeyName] = $newItem;
					}
				} else {
						// Normal key copy
					$newArray[$key] = $item;
				}
			}

				// If no reference set and mode is after, insert as last element
			if( $mode === 'after' && $refKeyName === null ) {
				$newArray[$newKeyName] = $newItem;
			}
		}

		return $newArray;
	}



	/**
	 * Convert a SimpleXmlElement structure to an associative array. All objects are casted to array
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
	 * Implode array and wrap all entries into single/double quotes
	 *
	 * @param	Array		$array				Items
	 * @param	String		$delimiter			Implode delimiter
	 * @param	Bool		$useDoubleQuotes	Use double quotes (") instead of single quotes (')
	 * @return	Array
	 */
	public static function implodeQuoted($array = array(), $delimiter = ',', $useDoubleQuotes = false) {
		$items	= array();
		$quote	= $useDoubleQuotes ? '"' : "'";

		foreach($array as $item) {
			$items[] = $quote . trim($item, $quote) . $quote;
		}

		return implode($delimiter, $items);
	}



	/**
	 * Make sure the variable is an array if it's not, there are two options: get an empty array or make the input the first element of the array
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
	 * @param	Mixed $value
	 * @param	Array $array
	 * @return	Array
	 */
	public static function unsetEntryByValue($value, array $array)	{
		if( in_array($value, $array) )	{
			unset($array[array_search($value, $array)]);
		}

		return $array;
	}


	/**
	 * Use logical AND conjunction upon (intersect) given sub arrays.
	 *
	 * @param	Array		$array
	 * @return	Array
	 */
	public static function intersectSubArrays(array $array) {
		if( sizeof($array) === 1 ) {
			return array_shift($array);
		} else {
			return call_user_func_array('array_intersect', $array);
		}
	}



	/**
	 * Use logical OR conjunction upon (merge) given sub array.
	 *
	 * @param	Array		$array
	 * @return	Array
	 */
	public static function mergeSubArrays(array $array) {
		if( sizeof($array) === 0 ) {
			return array();
		} elseif( sizeof($array) === 1 ) {
			return array_shift($array);
		} else {
			return call_user_func_array('array_merge', $array);
		}
	}



	/**
	 * Merge multiple arrays and return a unique array
	 * Combination of array_merge and array_unique
	 *
	 * @param	Array		Multiple array arguments like array_merge
	 * @return	Array
	 */
	public static function mergeUnique(/*arrays*/) {
		$funcArgs	= func_get_args();
		$merged		= call_user_func_array('array_merge', $funcArgs);

		return array_unique($merged);
	}



	/**
	 * Flatten array
	 *
	 * @param	Array	$array
	 * @return	Array
	 */
	public static function flatten(array $array){ //flattens multi-dim arrays (distroys keys)
		$flattened = array();

		foreach($array as $value){
			if( is_array($value) ){
				$flattened = array_merge($flattened, self::flatten($value));
			}else{
				array_push($flattened, $value);
			}
		}

		return $flattened;
	}



	/**
	 * Remove array entries by their value
	 *
	 * @param	Array		$array
	 * @param	Array		$valuesToRemove
	 * @param	Boolean		$reindex
	 * @return	Array
	 */
	public static function removeByValue(array $array, array $valuesToRemove, $reindex = true) {
		$array = array_diff($array, $valuesToRemove);

		if( $reindex ) {
			$array = array_merge($array);
		}

		return $array;
	}



	/**
	 * Remove duplicate entries in given field of array
	 *
	 * @param	Array	$array
	 * @param	String	$key
	 * @return	Array
	 */
	public static function removeDuplicates(array $array, $key) {
		$vals	 		= array();
		$cleanedArray	= array();

			// iterate all ass. sub arrays
		foreach($array as $entryID => $entryData) {
			$value		= $entryData[ $key ];

			if ( ! in_array($value, $vals) ) {
				$cleanedArray[ $entryID ]	= $entryData;
			}

			$vals[]	= $value;
		}

		return $cleanedArray;
	}



	/**
	 * Explode a list of integers
	 *
	 * @param	String		$delimiter			Character to split the list
	 * @param	String		$string				The list
	 * @param	Boolean		$onlyPositive		Set negative values zero
	 * @param	Boolean		$removeZeros		Remove all zero values
	 * @param	Boolean		$parseConstants		Parse constants?
	 * @return	Array
	 */
	public static function intExplode($delimiter, $string, $onlyPositive = false, $removeZeros = false, $parseConstants = false) {
		$string	= trim($string);

		if( $string === '' ) {
			return array();
		} else {
			$parts	= explode($delimiter, $string);

			return self::intval($parts, $onlyPositive, $removeZeros, $parseConstants);
		}
	}



	/**
	 * Explode a list and remove whitespaces around the values
	 *
	 * @param	String		$delimiter				Character to split the list
	 * @param	String		$string					The list
	 * @param	Boolean		$removeEmptyValues		Remove values which are empty afer trim()
	 * @return	Array
	 */
	public static function trimExplode($delimiter, $string, $removeEmptyValues = false) {
		$parts	= explode($delimiter, $string);
		$array	= array();

		foreach($parts as $value) {
			$value = trim($value);
			if( $value !== '' || $removeEmptyValues === false ) {
				$array[] = $value;
			}
		}

		return $array;
	}



	/**
	 * Trim all elements of an array. The elements have to be strings
	 *
	 * @param	Array		$array
	 */
	public static function trim(array $array) {
		foreach($array as $index => $value) {
			$array[$index] = trim($value);
		}
	}

}

?>