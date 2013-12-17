<?php

class Plutonium_Document extends Plutonium_Object {
	protected static $_path = null;

	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(Plutonium_Application::getPath() . '/documents');

		return self::$_path;
	}

	public static function newInstance($format, $args) {
		$name = strtolower($format);
		$type = ucfirst($name) . 'Document';
		$file = self::getPath() . DS . $name . '.php';

		return Plutonium_Loader::getClass($file, $type, __CLASS__, $args);
	}

	protected $_application = null;

	protected $_type = null;
	protected $_lang = 'en-US';

	protected $_title   = null;
	protected $_descrip = null;

	public function __construct($args) {
		$this->_application = $args->application;
	}

	public function __get($key) {
		switch ($key) {
			case 'application':
				return $this->_application;
		}
	}

	public function getTitle() {
		return $this->_title;
	}

	public function setTitle($title) {
		$this->_title = $title;
	}

	public function display() {
		echo $this->_descrip;
	}
}

?>