<?php

class LoaderTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		require_once realpath(PU_PATH_LIB . '/Loader.php');
	}

	public function testImport() {
		$lib_path = realpath(PU_PATH_BASE . '/libraries');

		Plutonium_Loader::autoload($lib_path);

		$this->assertContains($lib_path, Plutonium_Loader::getPaths());

		$this->assertTrue(Plutonium_Loader::import('Plutonium_Object'));
		$this->assertTrue(interface_exists('Plutonium_Accessible', false));

		$url = new Plutonium_Url();

		$this->assertInstanceOf('Plutonium_Url', $url);
	}
}
