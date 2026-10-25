<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Productdetailmaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Productdetailmaster';
	public $useTable = 'productdetailmasters';

	// 検索プラグイン
	public $actsAs = array('Search.Searchable');

	// 検索対象のフィルタ設定
	public $filterArgs = [
		['name' => 'shippermasters_id', 'type' => 'value', 'field' => 'Productdetailmaster.shippermasters_id'],
	    ['name' => 'pdname', 'type' => 'like', 'field' => 'Productdetailmaster.pdname'],
	];

	// 検索対象のフィールド設定
	public $presetVars = [
		['field' => 'shippermasters_id', 'type' => 'value'],
	    ['field' => 'pdname', 'type' => 'value']
	];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	public function SelectList(){
		return $this->find('list', [
			'fields' => ['id','pdname'],
			'conditions' => [
			    'delflg' => 0
			],
			'order' => [
				'pdcode' => 'asc',
				'pdname' => 'asc'
			],
		]);
	}

	public function pdnameList($did) {
		return $this->find('list', [
			'fields' => ['id', 'pdname'],
			'order' => [
				'pdcode' => 'asc',
				'pdname' => 'asc'
			],
			'conditions' => [
				'AND' => [
					'departmentmasters_id' => $did,
					'delflg' => 0
				]
			],
		]);
	}

	public function pdnameList2($sid) {
		return $this->find('list', [
			'fields' => ['id', 'pdname'],
			'order' => [
//				'pdcode' => 'asc',
				'pdname' => 'asc',
			],
			'conditions' => [
				'AND' => [
					'Productdetailmaster.shippermasters_id' => $sid,
					'Productdetailmaster.delflg' => 0,
				]
			],
		]);
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
		'pdcode' => [
			'rule' => 'notBlank',
			'message' => '詳細商品CDを入力してください。'
		],
		'pdname' => [
			'rule' => 'notBlank',
			'message' => '詳細商品名称を入力してください。'
		],
		'pdshortname' => [
			'rule' => 'notBlank',
			'message' => '詳細商品略称名を入力してください。'
		],
	];

	public $hasMany = [
		'Productcontainer' => [
			'className' => 'Productcontainer',
			'foreignKey' => 'productdetailmasters_id',
			'dependent' => false
		]
	];

	public $belongsTo = array(
		'Departmentmaster' => array(
			'className' => 'Departmentmaster',
			'foreignKey' => 'departmentmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Shippermaster' => [
			'className' => 'Shippermaster',
			'foreignKey' => 'shippermasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		]
	);

}
