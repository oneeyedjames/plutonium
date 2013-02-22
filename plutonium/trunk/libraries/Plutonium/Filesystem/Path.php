<?php

class Plutonium_Filesystem_Path {
	protected $_path = null;
	
	public function __construct($path) {
		
	}
	
	public static function clean($path) {
		$this->_path = preg_replace('|[/\\\\]+|', DS, $path);
	}
}

?>