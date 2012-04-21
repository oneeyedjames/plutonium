<?php

interface Plutonium_Accessible {
	public function has($key);
	public function get($key, $default = NULL);
	public function set($key, $value = NULL);
	public function def($key, $value = NULL);
	public function del($key);
}

?>