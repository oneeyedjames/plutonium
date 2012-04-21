<?php

abstract class Plutonium_Database_Table_Delegate_Abstract implements Plutonium_Database_Table_Delegate_Interface {
	protected $_table = NULL;
	
	public function __construct($table) {
		$this->_table = $table;
	}
}

?>