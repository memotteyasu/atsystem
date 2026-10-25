<?php
App::uses('Calendarmaster', 'Model');

/**
 * Calendarmaster Test Case
 */
class CalendarmasterTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.calendarmaster'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Calendarmaster = ClassRegistry::init('Calendarmaster');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Calendarmaster);

		parent::tearDown();
	}

}
