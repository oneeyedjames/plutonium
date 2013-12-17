<?php

class LoaderTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		require_once PU_PATH_LIB . '/Loader.php';
	}

	public function testImport() {
		$lib_path = PU_PATH_BASE . '/libraries';

		Plutonium_Loader::autoload($lib_path);

		$paths = explode(PATH_SEPARATOR, get_include_path());

		$this->assertContains($lib_path, $paths);

		$file = Plutonium_Loader::import('Plutonium_Object');

		$this->assertEquals('Plutonium/Object', $file);
		$this->assertTrue(interface_exists('Plutonium_Accessible', false));

		$url = new Plutonium_Url();

		$this->assertInstanceOf('Plutonium_Url', $url);
	}
}

?>