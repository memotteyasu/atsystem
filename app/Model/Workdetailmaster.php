<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Workdetailmaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Workdetailmaster';
	public $useTable = 'workdetailmasters';

	// 検索プラグイン
	public $actsAs = array('Search.Searchable');

	// 検索対象のフィルタ設定
	public $filterArgs = [
	    ['name' => 'departmentmasters_id', 'type' => 'value', 'field' => 'Workdetailmaster.departmentmasters_id']
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

	public function SelectWorkdetailList($did){
		return $this->find('list', [
			'fields' => ['id','workname'],
			'conditions' => [
			    'departmentmasters_id' => $did,
				'delflg' => 0,
			],
			'order' => [
			    'displayno' => 'asc'
			]
		]);
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
	    'workname' => [
	        'rule' => 'notBlank',
	        'message' => '作業名称を入力してください。'
	    ],
	    'departmentmasters_id' => [
	        'rule' => 'notBlank',
	        'message' => '部署を選択してください。'
	    ],
	    'displayno' => [
	        'rule' => 'notBlank',
	        'message' => '表示順を入力してください。'
	    ],
	];

	public $belongsTo = array(
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
	);

}
