<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Employeegetpoint extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Employeegetpoint';
	public $useTable = 'employeegetpoints';

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
			array('name' => 'sendeduserid', 'type' => 'like', 'field' => 'Employeegetpoint.sendeduserid')
	);

	// 検索対象のフィールド設定
	public $presetVars = array(
			array('field' => 'sendeduserid', 'type' => 'value')
	);
	*/

	/*
	public $validate = [
		'users_id' => [
			'rule' => 'notBlank',
			'message' => '付与相手を選んでください。'
		],
		'point' => [
			'rule' => 'notBlank',
			'message' => '付与するアサヒコイン数を選んでください。'
		],
	];
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
		],
		'SendedUser' => [
			'className' => 'User',
			'foreignKey' => 'sendeduserid',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	];
}
