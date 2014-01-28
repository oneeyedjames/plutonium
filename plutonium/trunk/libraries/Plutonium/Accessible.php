<?php

interface Plutonium_Accessible {
	public function has($key);
	public function get($key, $default = null);
	public function set($key, $value = null);
	public function def($key, $value = null);
	public function del($key);
}
