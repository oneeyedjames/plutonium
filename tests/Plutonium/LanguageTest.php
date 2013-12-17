<?php

class LanguageTest extends PHPUnit_Framework_TestCase {
	public function testLoad() {
		$config = new Plutonium_Object(array('code' => 'en'));

		$language = new Plutonium_Language($config);

		$this->assertEquals('en', $language->name);
		$this->assertEquals('en', $language->code);
		$this->assertEmpty($language->locale);

		$config = new Plutonium_Object(array('code' => 'en-us'));

		$language = new Plutonium_Language($config);

		$this->assertEquals('en-US', $language->name);
		$this->assertEquals('en', $language->code);
		$this->assertEquals('US', $language->locale);

		$config = new Plutonium_Object(array('code' => 'en', 'locale' => 'us'));

		$language = new Plutonium_Language($config);

		$this->assertEquals('en-US', $language->name);
		$this->assertEquals('en', $language->code);
		$this->assertEquals('US', $language->locale);

		$config = new Plutonium_Object(array('code' => 'en-uk', 'locale' => 'us'));

		$language = new Plutonium_Language($config);

		$this->assertEquals('en-US', $language->name);
		$this->assertEquals('en', $language->code);
		$this->assertEquals('US', $language->locale);
	}
}

?>