<?php
App::uses('Gyoumumessage', 'Model');

/**
 * Gyoumumessage Test Case
 */
class GyoumumessageTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.gyoumumessage',
		'app.kaisyas',
		'app.departmentmasters'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Gyoumumessage = ClassRegistry::init('Gyoumumessage');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Gyoumumessage);

		parent::tearDown();
	}

}
