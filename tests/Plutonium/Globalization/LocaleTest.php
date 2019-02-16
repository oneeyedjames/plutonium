<?php

use Plutonium\AccessObject;
use Plutonium\GLobalization\Locale;

class LocaleTest extends PHPUnit_Framework_TestCase {
	public function testConstruct() {
		$locale = new Locale('en');

		$this->assertEquals('en', $locale->name);
		$this->assertEquals('en', $locale->language);
		$this->assertEmpty($locale->country);

		$locale = new Locale(array(
			'language' => 'en'
		));

		$this->assertEquals('en', $locale->name);
		$this->assertEquals('en', $locale->language);
		$this->assertEmpty($locale->country);

		$locale = new Locale(new AccessObject(array(
			'language' => 'en'
		)));

		$this->assertEquals('en', $locale->name);
		$this->assertEquals('en', $locale->language);
		$this->assertEmpty($locale->country);

		$locale = new Locale('en-us');

		$this->assertEquals('en-US', $locale->name);
		$this->assertEquals('en', $locale->language);
		$this->assertEquals('US', $locale->country);

		$locale = new Locale(array(
			'language' => 'en',
			'country'  => 'US'
		));

		$this->assertEquals('en-US', $locale->name);
		$this->assertEquals('en', $locale->language);
		$this->assertEquals('US', $locale->country);

		$locale = new Locale(new AccessObject(array(
			'language' => 'en',
			'country'  => 'US'
		)));

		$this->assertEquals('en-US', $locale->name);
		$this->assertEquals('en', $locale->language);
		$this->assertEquals('US', $locale->country);
	}
}
