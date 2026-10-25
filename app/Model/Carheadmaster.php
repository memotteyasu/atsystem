<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Carheadmaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Carheadmaster';
	public $useTable = 'carheadmasters';

	// 検索プラグイン
	public $actsAs = array('Search.Searchable','Containable');

	// 検索対象のフィルタ設定
	public $filterArgs = [
		['name' => 'departmentmasters_id', 'type' => 'value', 'field' => 'Carheadmaster.departmentmasters_id'],
		['name' => 'branchmasters_id', 'type' => 'value', 'field' => 'Carheadmaster.branchmasters_id'],
		['name' => 'id', 'type' => 'value', 'field' => 'Carheadmaster.id'],
		['name' => 'scrapflg', 'type' => 'value', 'field' => 'Carheadmaster.scrapflg'],
	];

	// 検索対象のフィールド設定
	public $presetVars = [
		['field' => 'departmentmasters_id', 'type' => 'value'],
		['field' => 'branchmasters_id', 'type' => 'value'],
		['field' => 'id', 'type' => 'value'],
		['field' => 'scrapflg', 'type' => 'value'],
	];


	public function CarList(){
		return $this->find('list', [
			'fields' => ['id','plateno'],
			'conditions' => ['delflg' => 0],
			'order' => ['plateno' => 'asc'],
			'recursive' => -1,
		]);
	}

	public function CarListBranch($bid) {
		return $this->find('list', [
			'fields' => ['id', 'plateno'],
			'conditions' => [
				'branchmasters_id' => $bid,
				'delflg' => 0
			],
			'order' => ['plateno' => 'asc'],
			'recursive' => -1,
		]);
	}

	public function CarListDepartment($did) {
		return $this->find('list', [
			'fields' => ['id', 'plateno'],
			'conditions' => [
				'departmentmasters_id' => $did,
				'delflg' => 0
			],
			'order' => ['plateno' => 'asc'],
			'recursive' => -1,
		]);
	}

	public function CarListW($bid,$did) {
		return $this->find('list', [
			'fields' => ['id', 'plateno'],
			'conditions' => [
				'branchmasters_id' => $bid,
				'departmentmasters_id' => $did,
				'delflg' => 0
			],
			'order' => ['plateno' => 'asc'],
			'recursive' => -1,
		]);
	}


	/*
	 * 稼働不可能な車両の抽出
	 */
	public function NotRunCar($paramsDate,$paramsDate2) {
		return $this->find('all', [
			'fields' => [
				'Carheadmaster.id',
				'Carheadmaster.plateno',
				'Carheadmaster.notrundatestart',
				'Carheadmaster.notrundateend',
				'Carheadmaster.notrunreason',
			],
			'conditions' => [
				'AND' => [
					'Carheadmaster.notrundateend >=' => $paramsDate,
					'Carheadmaster.notrundateend <=' => $paramsDate2,
				]
			],
			'recursive' => 2
		]);
	}
	public function SelectCarmasterCo003s($paramsDate,$paramsDate2,$branchconditions){
	    return $this->find('all',[
	        'order' => [
	            'Carheadmaster.plateno' => 'asc',
	        ],
	        'conditions' => [
	            $branchconditions,
	            'Carheadmaster.scrapflg' => 0
	        ],
	        'fields'=>[
	            'Carheadmaster.id',
	            'Carheadmaster.plateno',
	            'Carheadmaster.carno',
	        ],
	        'recursive' => 0
	    ]);
	}
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
		/*
		'carno' => [
			'rule' => 'notBlank',
			'message' => '出光ナンバーを入力してください。'
		],
		*/
		'plateno' => [
			'rule' => 'notBlank',
			'message' => 'ナンバーを入力してください。'
		],
	];


/**
 * belongsTo associations
 *
 * @var array
 */
	public $hasMany = [
		'Ordertran' => [
			'className' => 'Ordertran',
			'foreignKey' => 'carheadmasters_id',
		],
	];

	public $belongsTo = [
		'Branchmaster' => [
			'className' => 'Branchmaster',
			'foreignKey' => 'branchmasters_id',
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
		]
	];
}
