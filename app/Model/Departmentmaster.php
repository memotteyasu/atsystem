<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Departmentmaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Departmentmaster';
	public $useTable = 'departmentmasters';

	// 検索プラグイン
	public $actsAs = array('Search.Searchable');

	// 検索対象のフィルタ設定
	public $filterArgs = array(
		array('name' => 'departmentname', 'type' => 'like', 'field' => 'Departmentmaster.departmentname')
	);

	// 検索対象のフィールド設定
	public $presetVars = array(
		array('field' => 'departmentname', 'type' => 'value')
	);

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	/*
	 * 部署セレクトリスト
	 */
	public function SelectDepartmentList(){
		return $this->find('list', [
			'fields' => ['id','departmentname'],
			'conditions' => ['delflg' => 0],
			'order' => ['id' => 'asc']
		]);
	}

	public function DepartmentList($kaisyas_id){
		return $this->find('list', [
			'fields' => ['id','departmentname'],
			'conditions' => [
				'kaisyas_id' => $kaisyas_id,
				'delflg' => 0
			],
			'order' => ['id' => 'asc']
		]);
	}

/**
 * Validation rules
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
	    'Branchmaster' => [
	        'className' => 'Branchmaster',
	        'foreignKey' => 'branchmasters_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ],
	];
}
