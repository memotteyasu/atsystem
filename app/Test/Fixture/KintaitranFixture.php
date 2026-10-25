<?php
/**
 * Kintaitran Fixture
 */
class KintaitranFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'datestart' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '始業時間'),
		'dateend' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '終業時間
'),
		'kintaikubun' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '出勤、休日出勤、有給etc'),
		'kyukeitime' => array('type' => 'time', 'null' => true, 'default' => null),
		'heijitsuzangyo' => array('type' => 'time', 'null' => true, 'default' => null),
		'heijitsushinya' => array('type' => 'time', 'null' => true, 'default' => null),
		'kyujitsuzangyo' => array('type' => 'time', 'null' => true, 'default' => null),
		'kyujitsushinya' => array('type' => 'time', 'null' => true, 'default' => null),
		'houteikyujitsu' => array('type' => 'time', 'null' => true, 'default' => null),
		'houteikyujitsushinya' => array('type' => 'time', 'null' => true, 'default' => null),
		'delflg' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'users_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'kintaidate' => array('type' => 'date', 'null' => true, 'default' => null, 'comment' => '勤怠日
'),
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
			'datestart' => '2018-09-25 05:29:30',
			'dateend' => '2018-09-25 05:29:30',
			'kintaikubun' => 1,
			'kyukeitime' => '05:29:30',
			'heijitsuzangyo' => '05:29:30',
			'heijitsushinya' => '05:29:30',
			'kyujitsuzangyo' => '05:29:30',
			'kyujitsushinya' => '05:29:30',
			'houteikyujitsu' => '05:29:30',
			'houteikyujitsushinya' => '05:29:30',
			'delflg' => 1,
			'users_id' => 1,
			'kintaidate' => '2018-09-25'
		),
	);

}
