<?php

interface Plutonium_Database_Table_Delegate_Interface {
	public function select($args);
	public function insert(&$row);
	public function update(&$row);
	public function delete($id);
	
	public function create();
	public function modify();
	public function drop();
}

?>