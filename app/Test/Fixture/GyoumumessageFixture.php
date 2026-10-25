<?php
/**
 * Gyoumumessage Fixture
 */
class GyoumumessageFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'gyoumucomment' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '業務コメント', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'delflg' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'kaisyas_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'どの会社のメッセージか分かるように。ブランクは全員共通'),
		'departmentmasters_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
			'gyoumucomment' => 'Lorem ipsum dolor sit amet',
			'created' => '2018-08-12 23:00:33',
			'modified' => '2018-08-12 23:00:33',
			'delflg' => 1,
			'kaisyas_id' => 1,
			'departmentmasters_id' => 1
		),
	);

}
