<?php
/**
 * Deliverymastersimage Fixture
 */
class DeliverymastersimageFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'deliverymasters_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'img_file_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'imgurl' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '現場写真のパス名
', 'charset' => 'utf8'),
		'imgcomment' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '写真に対するコメント
', 'charset' => 'utf8'),
		'delflg' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
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
			'deliverymasters_id' => 1,
			'img_file_name' => 'Lorem ipsum dolor sit amet',
			'imgurl' => 'Lorem ipsum dolor sit amet',
			'imgcomment' => 'Lorem ipsum dolor sit amet',
			'delflg' => 1,
			'created' => '2019-05-13 08:54:23',
			'modified' => '2019-05-13 08:54:23'
		),
	);

}
