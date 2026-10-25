<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Orderexpensestran extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Orderexpensestran';
	public $useTable = 'orderexpensestrans';


/**
 * Validation rules
 *
 * @var array
 */
	public $belongsTo = [
		'Ordertran' => [
			'className' => 'Ordertran',
			'foreignKey' => 'ordertrans_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		]
	];
}
