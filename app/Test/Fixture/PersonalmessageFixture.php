<?php
/**
 * Personalmessage Fixture
 */
class PersonalmessageFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'personalcomment' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '個人用のコメント', 'charset' => 'utf8'),
		'delflg' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'users_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
			'personalcomment' => 'Lorem ipsum dolor sit amet',
			'delflg' => 1,
			'users_id' => 1
		),
	);

}
