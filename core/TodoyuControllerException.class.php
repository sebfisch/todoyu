<?php

class TodoyuControllerException extends Exception {
	
	private $ext;
	
	private $ctrl;
	
	private $action;
	
	public function __construct($ext, $ctrl, $action, $message, $code = 0) {
		parent::__construct($message, $code);
		
		$this->ext		= $ext;
		$this->ctrl		= $ctrl;
		$this->action	= $action;
	}
	
	public function getExt() {
		return $this->ext;
	}
	
	public function getController() {
		return $this->ctrl;
	}
	
	public static function getAction() {
		return $this->action;
	}
	
	public function printError() {
		TodoyuDebug::printInFirebug($this->getMessage(), 'Controller Exception');
	}
	
}

?>