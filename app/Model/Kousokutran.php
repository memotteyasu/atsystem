<?php
App::uses('AppModel', 'Model');
/**
 * Kousokutran Model
 *
 * @property Users $Users
 */
class Kousokutran extends AppModel {
	public $name = 'Kousokutran';
	public $useTable = 'kousokutrans';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
	);

	public $belongsTo = array(
		'Kaisya' => [
			'className' => 'Kaisya',
			'foreignKey' => 'kaisyas_id',
			'conditions' => '',
			'order' => ''
		],
		'User' => [
			'className' => 'User',
			'foreignKey' => 'users_id',
			'conditions' => '',
			'order' => ''
		]
	);
}
