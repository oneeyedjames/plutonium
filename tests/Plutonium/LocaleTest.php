<?php

class LocaleTest extends PHPUnit_Framework_TestCase {
	public function testConstruct() {
		$locale = new Plutonium_Locale('en');

		$this->assertEquals('en', $locale->name);
		$this->assertEquals('en', $locale->language);
		$this->assertEmpty($locale->country);

		$locale = new Plutonium_Locale(array(
			'language' => 'en'
		));

		$this->assertEquals('en', $locale->name);
		$this->assertEquals('en', $locale->language);
		$this->assertEmpty($locale->country);

		$locale = new Plutonium_Locale(new Plutonium_Object(array(
			'language' => 'en'
		)));

		$this->assertEquals('en', $locale->name);
		$this->assertEquals('en', $locale->language);
		$this->assertEmpty($locale->country);

		$locale = new Plutonium_Locale('en-us');

		$this->assertEquals('en-US', $locale->name);
		$this->assertEquals('en', $locale->language);
		$this->assertEquals('US', $locale->country);

		$locale = new Plutonium_Locale(array(
			'language' => 'en',
			'country'  => 'US'
		));

		$this->assertEquals('en-US', $locale->name);
		$this->assertEquals('en', $locale->language);
		$this->assertEquals('US', $locale->country);

		$locale = new Plutonium_Locale(new Plutonium_Object(array(
			'language' => 'en',
			'country'  => 'US'
		)));

		$this->assertEquals('en-US', $locale->name);
		$this->assertEquals('en', $locale->language);
		$this->assertEquals('US', $locale->country);
	}
}
