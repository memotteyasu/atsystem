<?php
App::uses('Gasusetran', 'Model');

/**
 * Gasusetran Test Case
 */
class GasusetranTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.gasusetran'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Gasusetran = ClassRegistry::init('Gasusetran');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Gasusetran);

		parent::tearDown();
	}

}
