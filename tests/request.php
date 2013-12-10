<?php

class IncludesTest extends PHPUnit_Framework_TestCase {
	static $config;

	public function setUp() {
		$pu_path = dirname(dirname(__FILE__));

		$_SERVER['DOCUMENT_ROOT'] = $pu_path;
		$_SERVER['SCRIPT_FILENAME'] = $pu_path . '/index.php';

		$_SERVER['SERVER_NAME'] = 'plutonium.dev';

		require_once $pu_path . '/constants.php';
		require_once $pu_path . '/config.php';

		require_once $pu_path . '/libraries/Plutonium/Loader.php';

		Plutonium_Loader::autoload($pu_path . '/libraries');

		if (is_null(self::$config))
			self::$config = new Plutonium_Object($config);
	}

	public function testMethod() {
		$_SERVER['REQUEST_METHOD'] = 'GET';

		$request = new Plutonium_Request(self::$config->system);

		$this->assertEquals($request->method, 'GET');

		$_SERVER['REQUEST_METHOD'] = 'POST';

		$request = new Plutonium_Request(self::$config->system);

		$this->assertEquals($request->method, 'POST');

		$_GET['_method'] = 'PUT';

		$request = new Plutonium_Request(self::$config->system);

		$this->assertEquals($request->method, 'PUT');
	}

	public function testHost() {
		$_SERVER['SERVER_NAME'] = 'main.plutonium.dev';

		$request = new Plutonium_Request(self::$config->system);

		$this->assertEquals($request->host, 'main');
		$this->assertNull($request->module);

		$_SERVER['SERVER_NAME'] = 'site.main.plutonium.dev';

		$request = new Plutonium_Request(self::$config->system);

		$this->assertEquals($request->host,   'main');
		$this->assertEquals($request->module, 'site');
	}

	public function testPath() {
		$_SERVER['REQUEST_URI'] = '/path/to/resource.ext';

		$request = new Plutonium_Request(self::$config->system);

		$this->assertEquals($request->path, 'path/to/resource');
		$this->assertEquals($request->format, 'ext');

		$_SERVER['REQUEST_URI'] = '/path/to/resource';

		$request = new Plutonium_Request(self::$config->system);

		$this->assertEquals($request->path, 'path/to/resource');
		$this->assertNull($request->format);
	}
}

?>