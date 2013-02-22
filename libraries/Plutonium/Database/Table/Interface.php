<?php

interface Plutonium_Database_Table_Interface {
	public function make($data = null);
	public function find($args = null);
	public function save(&$row);
	public function delete($id);
	public function validate(&$row);
}

?>