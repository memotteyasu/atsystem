<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Workplace extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Workplace';
	public $useTable = 'workplaces';

// 	// 検索プラグイン
 	public $actsAs = ['Search.Searchable'];

// 	// 検索対象のフィルタ設定
 	public $filterArgs = [
 		['name' => 'departmentmasters_id', 'type' => 'value', 'field' => 'Workplace.departmentmasters_id'],
// 		//['name' => 'branchmasters_id', 'type' => 'value', 'field' => 'Loadingmaster.branchmasters_id'],
 		['name' => 'workplacename', 'type' => 'like', 'field' => 'Workplace.workplacename'],
 	];

// 	// 検索対象のフィールド設定
 	public $presetVars = [
 		['field' => 'departmentmasters_id', 'type' => 'value'],
// 		['field' => 'branchmasters_id', 'type' => 'value'],
 		['field' => 'workplacename', 'type' => 'value']
 	];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

// 	/*
// 	 * Ch000s/Co000s/Oi000s積地リスト絞り込み用
// 	 */
// 	public function SelectLoadingList($did){
// 		return $this->find( 'list', [
// 			'fields' => ['Loadingmaster.id','Loadingmaster.loadingname'],
// 			'order' => ['Loadingmaster.loadingnamekana' => 'asc'],
// 			'conditions' => [
// 				'Loadingmaster.departmentmasters_id' => $did,
// 				'Loadingmaster.delflg' => 0
// 			]
// 		]);
// 	}

	public function SelectWorkplaceList2($did){
		return $this->find( 'list', [
			'fields' => ['Workplace.id','Workplace.workplacename'],
			//'order' => ['Workplace.loadingnamekana' => 'asc'],
			'conditions' => [
			    'Workplace.delflg' => 0,
			    'departmentmasters_id'=>$did
			],
		    'order'=>[
		        'Workplace.workplacename'
		    ]
		]);
	}

/**
 * Validation rules
 *
 * @var array
 */
// 	public $validate = [
// 		'loadingname' => [
// 			'rule' => 'notBlank',
// 			'message' => '積地名称を入力してください。'
// 		],
// 		'loadingshortname' => [
// 			'rule' => 'notBlank',
// 			'message' => '積地略名称を入力してください。'
// 		],
// /*
// 	    'loadingadd' => [
// 			'rule' => 'notBlank',
// 			'message' => '住所を入力してください。'
// 		],
// 		'loadingtel' => [
// 			'rule' => 'notBlank',
// 			'message' => '電話番号を入力してください。'
// 		],
// */
// 	];

/**
 * belongsTo associations
 *
 * @var array
 */
	public $hasMany = [
		'Workplaceimage' => [
			'className' => 'Workplaceimage',
			'foreignKey' => 'workplaces_id',
		]
	];

 	public $belongsTo =[
 		'Departmentmaster' => [
 			'className' => 'Departmentmaster',
 			'foreignKey' => 'departmentmasters_id',
 			'conditions' => '',
 			'fields' => '',
 			'order' => ''
		 ],
// 		'Branchmaster' => [
// 			'className' => 'Branchmaster',
// 			'foreignKey' => 'branchmasters_id',
// 			'conditions' => '',
// 			'fields' => '',
// 			'order' => ''
// 		]
 	];
}
