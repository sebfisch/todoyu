<?php

/**
 * This file shows how to style your code
 */
class MyCamelCaseClassName {

	/**
	 * Add a good method description. Don't tell the obvious stuff!
	 *
	 * @param	Integer			$idRecord
	 * @param	AnOtherClass 	$otherClass			Use typehints where possible
	 * @return	Integer			The return value MUST always be from the same type
	 */
	public function doSomethingWithARecord($idRecord, AnOtherClass $otherClass) {
			// Always validate parameters
		$idRecord	= intval($idRecord);

			// Check what you really want to know, and do this as exactly as possible
		if( $idRecord === 0 ) {

		}

			// Bad style (what are we checking for?)
		if( $idRecord ) {

		}

		return $idRecord + $idRecord;
	}


	/**
	 * Render functions should always start with "render"
	 * A render function only should compose the data for the template.
	 * The make calculations and get data from the database, use a manager function
	 *
	 * @return	String
	 */
	public static function renderSomething() {
			// Define the path to the template
		$tmpl	= 'ext/project/view/something.tmpl';
			// Define the data array for dwoo
		$data	= array(
			'key1'	=> 'somedata',
			'subkey'=> array(
				'sub1'	=> 3,
				'sub55'	=> 5555
			)
		);

		return render($tmpl, $data);
	}

}


?>