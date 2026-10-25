<?php
App::uses('Flgmaster', 'Model');

/**
 * Flgmaster Test Case
 */
class FlgmasterTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.flgmaster'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Flgmaster = ClassRegistry::init('Flgmaster');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Flgmaster);

		parent::tearDown();
	}

}
