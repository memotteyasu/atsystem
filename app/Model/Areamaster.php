<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Areamaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Areamaster';
	public $useTable = 'areamasters';

	// 検索プラグイン
	public $actsAs = array('Search.Searchable');


	// 検索対象のフィルタ設定
	public $filterArgs = [
		['name' => 'departmentmasters_id', 'type' => 'value', 'field' => 'Areamaster.departmentmasters_id']
	];

	// 検索対象のフィールド設定
	public $presetVars = [
		['field' => 'departmentmasters_id', 'type' => 'value']
	];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	/*
	// 検索対象のフィルタ設定
	public $filterArgs = array(
		array('name' => 'areaname', 'type' => 'value', 'field' => 'Areamaster.areaname')
	);

	// 検索対象のフィールド設定
	public $presetVars = array(
		array('field' => 'areaname', 'type' => 'value')
	);
	*/

	public function AreaList(){
		return $this->find('list', [
			'fields' => ['id','areaname'],
			'conditions' => ['delflg' => 0],
			'order' => ['areacode' => 'asc']
		]);
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
		'areacode' => [
			'rule' => 'notBlank',
			'message' => '地域CDを入力してください。'
		],
		'areaname' => [
			'rule' => 'notBlank',
			'message' => '地域名称を入力してください。'
		],
	];
}
