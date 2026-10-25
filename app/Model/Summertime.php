<?php
App::uses('AppModel', 'Model');
/**
 * Summertime Model
 *
 */
class Summertime extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Summertime';
	public $useTable = 'summertimes';

	// 検索プラグイン
	public $actsAs = ['Search.Searchable','Containable'];

	// 検索対象のフィルタ設定
	public $filterArgs = [
		['name' => 'branchmasters_id', 'type' => 'value', 'field' => 'Summertime.branchmasters_id'],
		['name' => 'departmentmasters_id', 'type' => 'value', 'field' => 'Summertime.departmentmasters_id']
	];

	// 検索対象のフィールド設定
	public $presetVars = [
		['field' => 'branchmasters_id', 'type' => 'value'],
		['field' => 'departmentmasters_id', 'type' => 'value']
	];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
		'summerstartdate' => [
			'rule' => 'notBlank',
			'message' => '開始日を入力してください。'
		],
		'summerenddate' => [
			'rule' => 'notBlank',
			'message' => '終了日を入力してください。'
		],
		'summertermtime' => [
			'rule' => 'notBlank',
			'message' => '就業時間を入力してください。'
		],
		'summerstarttime' => [
			'rule' => 'notBlank',
			'message' => '開始時間を入力してください。'
		],
		'summerendtime' => [
			'rule' => 'notBlank',
			'message' => '終了時間を入力してください。'
		],
	];


/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = [
		'Kaisya' => [
			'className' => 'Kaisya',
			'foreignKey' => 'kaisyas_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		'Departmentmaster' => [
			'className' => 'Departmentmaster',
			'foreignKey' => 'departmentmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		'Branchmaster' => [
			'className' => 'Branchmaster',
			'foreignKey' => 'branchmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	];
}
