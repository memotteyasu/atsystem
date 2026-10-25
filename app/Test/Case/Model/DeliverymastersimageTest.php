<?php
App::uses('Deliverymastersimage', 'Model');

/**
 * Deliverymastersimage Test Case
 */
class DeliverymastersimageTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.deliverymastersimage',
		'app.deliverymasters'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Deliverymastersimage = ClassRegistry::init('Deliverymastersimage');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Deliverymastersimage);

		parent::tearDown();
	}

}
