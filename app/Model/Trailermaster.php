<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Trailermaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Trailermaster';
	public $useTable = 'trailermasters';

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	public function TrailerList() {
		return $this->find('list', [
			'fields' => ['id','trailerkind'],
			'conditions' => ['delflg' => 0]
		]);
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $hasMany = [
		'Carmaster' => [
			'className' => 'Carmaster',
			'foreignKey' => 'trailermasters_id',
		]
	];

}
