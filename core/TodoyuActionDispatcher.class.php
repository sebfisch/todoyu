<?php

class TodoyuActionDispatcher {
	
	private static function getExtension() {
		$ext	= TodoyuRequest::getExt();
		
		if( ! is_string($ext) ) {
			$ext	= TodoyuPreferenceManager::getLastExt();
			
			if( $ext === false ) {
				$ext = $GLOBALS['CONFIG']['FE']['DEFAULT']['ext'];
			}
		}
		
		return $ext;
	}
	
	private static function getController() {
		$ctrl	= TodoyuRequest::getController();
		
		if( ! is_string($ctrl) ) {
			$ctrl = $GLOBALS['CONFIG']['FE']['DEFAULT']['controller'];
		}
		
		return $ctrl;
	}
	
	private static function getCommand() {
		return TodoyuRequest::getCommand();
	}
	
	public static function dispatch() {
		if( self::isController(EXT, CONTROLLER) ) {
			$params		= TodoyuRequest::getAll();
			$controller	= self::getControllerObject(EXT, CONTROLLER, $params);
		} else {
			self::errorControllerNotFound(EXT, CONTROLLER);
		}
		
			// Execute command
		try {
			echo $controller->runAction(COMMAND);
		} catch(TodoyuControllerException $e) {
			$e->printError();			
		} catch(Exception $e) {
			die("Error: " . $e->getMessage());
		}
	}
	
	private static function errorControllerNotFound($ext, $controller) {
		ob_clean();
		
		TodoyuHeader::sendHeaderPlain();
		
		echo "Request controller not found!\n";
		echo "Extension: " . $ext . "\n";
		echo "Controller: " . $controller . "\n\n";
		
		$params	= TodoyuRequest::getAll();
		
		print_r($params);
		
		exit();		
	}
	
	private static function getControllerClassName($ext, $controller) {
		return 'Todoyu' . ucfirst(trim($ext)) . ucfirst(trim($controller)) . 'ActionController';
	}
	
	
	
	/**
	 * Get action controller object for the $ext-$controller combination
	 * @param	String		$ext
	 * @param	String		$controller
	 * @return	TodoyuActionController
	 */
	public static function getControllerObject($ext, $controller, array $params = array()) {
		$controllerClassName	= self::getControllerClassName($ext, $controller);
		
		return new $controllerClassName($params);
	}

	
	
	/**
	 * Check if a controller class exists
	 *
	 * @param	String		$ext
	 * @param	String		$controller
	 * @return	Bool
	 */
	public static function isController($ext, $controller) {
		$controllerClassName = self::getControllerClassName($ext, $controller);
		
		return class_exists($controllerClassName, true);
	}
	
	
}


?>