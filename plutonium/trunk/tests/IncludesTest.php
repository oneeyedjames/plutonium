<?php

class IncludesTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$_SERVER['SCRIPT_FILENAME'] = realpath(PU_PATH_BASE . '/index.php');
		$_SERVER['SERVER_NAME'] = 'plutonium.dev';

		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI']    = '/';
	}

	public function testConstants() {
		$this->assertNotEmpty(DS);
		$this->assertNotEmpty(PS);
		$this->assertNotEmpty(LS);
		$this->assertNotEmpty(FS);
		$this->assertNotEmpty(BS);

		$this->assertNotEmpty(PU_PATH_BASE);
		$this->assertNotEmpty(PU_PATH_ROOT);
		$this->assertNotEmpty(PU_URL_ROOT);
		$this->assertNotEmpty(PU_URL_BASE);

		$this->assertTrue(defined('PU_URL_PATH'));
	}
}

?>
