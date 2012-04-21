<?php

class Plutonium_Filesystem_Folder {
	protected $_path = NULL;
	
	public function __construct($path) {
		$this->_path = $path;
	}
	
	public function exists() {
		return file_exists($this->_path);
	}
	
	public function isFolder() {
		return is_dir($this->_path);
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
	
	public function create() {
		return mkdir($this->_path, 755, TRUE);
	}
	
	public function delete() {
		return rmdir($this->_path);
	}
	
	public function getFolders() {
		$folders = FALSE;
		
		if (is_dir($this->_path)) {
			if ($dir = opendir($this->_path)) {
				$folders = array();
				
				while (($file = readdir($dir)) !== FALSE) {
					if ($file != '.' && $file != '..') {
						$path = $this->_path . DS . $file;
						if (is_dir($path)) $folders[] = $file;
					}
				}
				
				closedir($dir);
			}
		}
		
		return $folders;
	}
	
	public function getFiles() {
		$folders = FALSE;
		
		if (is_dir($this->_path)) {
			if ($dir = opendir($this->_path)) {
				$folders = array();
				
				while (($file = readdir($dir)) !== FALSE) {
					if ($file != '.' && $file != '..') {
						$path = $this->_path . DS . $file;
						if (is_file($path)) $folders[] = $file;
					}
				}
				
				closedir($dir);
			}
		}
		
		return $folders;
	}
}

?>