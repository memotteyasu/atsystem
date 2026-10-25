<?php
/**
 * Gasusetran Fixture
 */
class GasusetranFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'gaskind' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'utf8_general_ci', 'comment' => '1:レギュラー,2:ハイオク、3:軽油', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'chargedate' => array('type' => 'date', 'null' => true, 'default' => null, 'comment' => '給油日
'),
		'amount' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '使用量'),
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
			'gaskind' => 'Lorem ip',
			'created' => '2018-12-27 08:48:02',
			'modified' => '2018-12-27 08:48:02',
			'chargedate' => '2018-12-27',
			'amount' => 1,
			'users_id' => 1
		),
	);

}
