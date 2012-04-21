<?php

class Plutonium_Filesystem_File {
	protected $_path = NULL;
	
	public function __construct($path) {
		$this->_path = Plutonium_Filesystem_Path::clean($path);
	}
	
	public function exists() {
		return file_exists($this->_path);
	}
	
	public function isFile() {
		return is_file($this->_path);
	}
	
	public function isReadable() {
		return is_readable($this->_path);
	}
	
	public function isWriteable() {
		return is_writable($this->_path);
	}
	
	public function isExecutable() {
		return is_executable($this->_path);
	}
	
	public function getName() {
		return basename($this->_path);
	}
	
	public function getPath() {
		return dirname($this->_path);
	}
	
	public function getContents() {
		return file_get_contents($this->_path);
	}
	
	public function putContents($data) {
		return file_put_contents($this->_path, $data);
	}
	
	public function copy($path) {
		$path = Plutonium_Filesystem_Path::clean($path);
		return copy($this->_path, $path);
	}
	
	public function move($path) {
		$path = Plutonium_Filesystem_Path::clean($path);
		return rename($this->_path, $path);
	}
	
	public function delete() {
		return unlink($this->_path);
	}
	
	public function upload($path) {
		$path = Plutonium_Filesystem_Path::clean($path);
		
		if (is_uploaded_file($this->_path)) {
			return move_uploaded_file($this->_path, $path);
		}
		
		return FALSE;
	}
}

?>