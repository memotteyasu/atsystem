<?php
App::uses('Kintaitran', 'Model');

/**
 * Kintaitran Test Case
 */
class KintaitranTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.kintaitran',
		'app.users'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Kintaitran = ClassRegistry::init('Kintaitran');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Kintaitran);

		parent::tearDown();
	}

}
