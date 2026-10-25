<?php
App::uses('Personalmessage', 'Model');

/**
 * Personalmessage Test Case
 */
class PersonalmessageTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.personalmessage',
		'app.users'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Personalmessage = ClassRegistry::init('Personalmessage');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Personalmessage);

		parent::tearDown();
	}

}
