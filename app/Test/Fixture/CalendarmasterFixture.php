<?php
/**
 * Calendarmaster Fixture
 */
class CalendarmasterFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'restdate' => array('type' => 'date', 'null' => true, 'default' => null),
		'restflg' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => true, 'comment' => '0:通常、1:法定外休日、2:法定休日'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'id_UNIQUE' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'restdate' => '2019-08-17',
			'restflg' => 1
		),
	);

}
