<?php

interface Plutonium_Database_Table_Interface {
	public function make($data = NULL);
	public function find($args = NULL);
	public function save(&$row);
	public function delete($id);
	public function validate(&$row);
}

?>