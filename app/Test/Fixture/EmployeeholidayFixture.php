<?php
/**
 * Employeeholiday Fixture
 */
class EmployeeholidayFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'applicationdate' => array('type' => 'date', 'null' => true, 'default' => null, 'comment' => '有給取得希望日'),
		'okflg' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '４０：受理
４１：却下'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'okuserid' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '受理した人'),
		'users_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
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
			'applicationdate' => '2019-01-27',
			'okflg' => 1,
			'created' => '2019-01-27 00:07:31',
			'modified' => '2019-01-27 00:07:31',
			'okuserid' => 1,
			'users_id' => 1
		),
	);

}
