<?php

use Plutonium\Loader;

class LoaderTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		require_once realpath(PU_PATH_LIB . '/Plutonium/Loader.php');
	}

	public function testImport() {
		$lib_path = realpath(PU_PATH_BASE . '/libraries');

		Loader::autoload($lib_path);

		$this->assertContains($lib_path, Loader::getPaths());

		$this->assertTrue(Loader::import('\Plutonium\AccessObject'));
		$this->assertTrue(interface_exists('\Plutonium\Accessible', false));
	}
}
