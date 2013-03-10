<?php

class Plutonium_Module_Router {
	protected $_values = array();
	
	public function match($path) {
		if (isset($path[0])) {
			$this->_values['resource'] = $path[0];
			
			if (isset($path[1])) {
				if (is_numeric($path[1])) {
					$this->_values['id']   = $path[1];
					
					if (isset($path[2])) {
						$this->_values['action'] = $path[2];
						$this->_values['layout'] = $path[2];
					}
				} else {
					$this->_values['action'] = $path[1];
					$this->_values['layout'] = $path[1];
				}
			}
		}
	}
}

?>