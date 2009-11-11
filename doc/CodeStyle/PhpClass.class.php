<?php

/**
 * This file shows how to style your code
 */
class MyCamelCaseClassName {

	/**
	 * Some description
	 *
	 * @var	String
	 */
	private $myVar = '';
	
	

	/**
	 * Add a good method description. Don't tell the obvious stuff!
	 *
	 * @param	Integer			$idRecord
	 * @param	Array			$data
	 * @param	AnotherClass 	$otherClass			Use typehints where possible
	 * @return	Integer			A function always has one type of "return value"
	 */
	public function doSomething($idRecord, array $data, AnotherClass $otherClass) {
			// Always validate parameters (in public functions)
		$idRecord	= intval($idRecord);

			// Check what you really want to know, and do this as exactly as possible
		if( $idRecord === 0 ) {

		}
		
		return $idRecord;
	}

	

	/**
	 * Render functions should always start with "render"
	 * A render function only should compose the data for the template.
	 * To calculate data or get data from the database, use a manager function
	 *
	 * @return	String
	 */
	public static function renderSomething() {
			// Define the path to the template file
		$tmpl	= 'ext/project/view/something.tmpl';
			// Define the data array for Dwoo
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