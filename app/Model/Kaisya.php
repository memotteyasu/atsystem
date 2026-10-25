<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Kaisya extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Kaisya';
	public $useTable = 'kaisyas';

	// 検索プラグイン
	public $actsAs = array('Search.Searchable');

	// 検索対象のフィルタ設定
	public $filterArgs = array(
		array('name' => 'companyname', 'type' => 'like', 'field' => 'Kaisya.companyname')
	);

	// 検索対象のフィールド設定
	public $presetVars = array(
		array('field' => 'companyname', 'type' => 'value')
	);

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	public function SelectKaisyaName($kaisyas_id) {
		return $this->find('first', [
			'conditions' => [
				'Kaisya.id' => $kaisyas_id,
				'delflg' => 0
			],
			'recursive' => 0,
		]);
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $hasMany = [
		/*
		'User' => [
			'className' => 'User',
			'foreignKey' => 'kaisyas_id',
		],
		*/
		'Departmentmaster' => [
			'className' => 'Departmentmaster',
			'foreignKey' => 'kaisyas_id',
			'dependent' => true
		]
	];
}
