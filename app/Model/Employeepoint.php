<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Employeepoint extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Employeepoint';
	public $useTable = 'employeepoints';

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	/*
	// 検索プラグイン
	public $actsAs = array('Search.Searchable');

	// 検索対象のフィルタ設定
	public $filterArgs = array(
		array('name' => 'users_id', 'type' => 'like', 'field' => 'Employeepoint.users_id')
	);

	// 検索対象のフィールド設定
	public $presetVars = array(
		array('field' => 'users_id', 'type' => 'value')
	);
	*/

/**
 * Validation rules
 *
 * @var array
 */
	public $belongsTo = [
		'User' => [
			'className' => 'User',
			'foreignKey' => 'users_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		]
	];
}
