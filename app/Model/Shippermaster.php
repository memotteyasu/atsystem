<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Shippermaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Shippermaster';
	public $useTable = 'shippermasters';

	// 検索プラグイン
	public $actsAs = array('Search.Searchable');

	// 検索対象のフィルタ設定
	public $filterArgs = [
		['name' => 'departmentmasters_id', 'type' => 'value', 'field' => 'Shippermaster.departmentmasters_id']
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

	public function ShipperList($kid) {
		return $this->find('list', [
			'fields' => ['id', 'shippershortname'],
			'order' => ['shippercode' => 'asc', 'departmentmasters_id' => 'asc', 'shippername' => 'asc'],
			'conditions' => [
			    'delflg' => 0,
			    'kaisyas_id'=>$kid
			]
		]);
	}

	/*
	 * Ch000s/Co000s/Oi000s荷主リスト絞り込み用
	 */
	public function SelectShipperList($did){
		return $this->find( 'list', [
			'fields' => ['Shippermaster.id','Shippermaster.shippershortname'],
			'conditions' => [
				'Shippermaster.departmentmasters_id' => $did,
				'Shippermaster.delflg' => 0,
			],
			'order' => ['shippercode' => 'asc', 'departmentmasters_id' => 'asc', 'shippername' => 'asc'],
		]);
	}
	/*
	 * Co081s用
	 */
	public function SelectShipperListCo081($did){
		return $this->find( 'list', [
			'fields' => ['Shippermaster.id','Shippermaster.shippershortname'],
			'conditions' => [
				'Shippermaster.departmentmasters_id in' => [4,7],
				'Shippermaster.delflg' => 0,
			],
			'order' => ['shippercode' => 'asc', 'departmentmasters_id' => 'asc', 'shippername' => 'asc'],
		]);
	}

	/*
	 * 120sPDF用
	 */
	public function SelectShipperName($sid){
		return $this->find( 'first', [
			'fields' => ['Shippermaster.id','Shippermaster.shippername'],
			'conditions' => [
				'Shippermaster.id' => $sid,
				'Shippermaster.delflg' => 0
			],
			'recursive' => -1
		]);
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
		'shippername' => [
			'rule' => 'notBlank',
			'message' => '荷主名称を入力してください。'
		],
		'shippershortname' => [
			'rule' => 'notBlank',
			'message' => '荷主通称名を入力してください。'
		],
		'shipperadd' => [
			'rule' => 'notBlank',
			'message' => '住所を入力してください。'
		],
	];


/**
 * belongsTo associations
 *
 * @var array
 */
	public $hasMany = [
		'Productdetailmaster' => [
			'className' => 'Productdetailmaster',
			'foreignKey' => 'shippermasters_id',
			'dependent' => false
		],
		'Pricemaster' => [
			'className' => 'Pricemaster',
			'foreignKey' => 'shippermasters_id',
			'dependent' => false
		],
	];

	public $belongsTo = array(
		'Departmentmaster' => array(
			'className' => 'Departmentmaster',
			'foreignKey' => 'departmentmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
