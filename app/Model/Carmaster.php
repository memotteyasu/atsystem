<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Carmaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Carmaster';
	public $useTable = 'carmasters';

	// 検索プラグイン
	public $actsAs = array('Search.Searchable','Containable');

	// 検索対象のフィルタ設定
	public $filterArgs = [
		['name' => 'departmentmasters_id', 'type' => 'value', 'field' => 'Carmaster.departmentmasters_id'],
		['name' => 'branchmasters_id', 'type' => 'value', 'field' => 'Carmaster.branchmasters_id'],
		['name' => 'id', 'type' => 'value', 'field' => 'Carmaster.id'],
		['name' => 'scrapflg', 'type' => 'value', 'field' => 'Carmaster.scrapflg'],
	];

	// 検索対象のフィールド設定
	public $presetVars = [
		['field' => 'departmentmasters_id', 'type' => 'value'],
		['field' => 'branchmasters_id', 'type' => 'value'],
		['field' => 'id', 'type' => 'value'],
		['field' => 'scrapflg', 'type' => 'value'],
	];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	public function CarList($scrapflg = 0){
		return $this->find('list', [
			'fields' => ['id','plateno'],
			'conditions' => [
				'scrapflg' => $scrapflg,
				'delflg' => 0,
			],
			'order' => ['plateno' => 'asc'],
			'recursive' => -1,
		]);
	}

	public function CarListBranch($bid, $scrapflg = 0) {
		return $this->find('list', [
			'fields' => ['id', 'plateno'],
			'conditions' => [
				'branchmasters_id' => $bid,
				'scrapflg' => $scrapflg,
				'delflg' => 0,
			],
			'order' => ['plateno' => 'asc'],
			'recursive' => -1,
		]);
	}
	public function CarListGroup($bgid) {
		return $this->find('list', [
			'fields' => ['id', 'plateno'],
			'conditions' => [
				'Branchmaster.branchgroups_id'=>$bgid,
				'scrapflg' => 0
			],
			'order' => ['plateno' => 'asc'],
			'contain' => [
				'Branchmaster'
			],
			'recursive' => -1,
		]);
	}
	public function CarListGroupGas($bgid,$did) {
		return $this->find('list', [
			'fields' => ['id', 'plateno'],
			'conditions' => [
				'Branchmaster.branchgroups_id'=>$bgid,
				'departmentmasters_id' => $did,
				'scrapflg' => 0
			],
			'order' => ['plateno' => 'asc'],
			'contain' => [
				'Branchmaster'
			],
			'recursive' => -1,
		]);
	}

	public function CarListDepartment($did, $scrapflg = 0) {
		return $this->find('list', [
			'fields' => ['id', 'plateno'],
			'conditions' => [
				'departmentmasters_id' => $did,
				'scrapflg' => $scrapflg,
				'delflg' => 0,
			],
			'order' => ['plateno' => 'asc'],
			'recursive' => -1,
		]);
	}

	public function CarListW($bid, $did, $scrapflg = 0) {
		return $this->find('list', [
			'fields' => ['id', 'plateno'],
			'conditions' => [
				'branchmasters_id' => $bid,
				'departmentmasters_id' => $did,
				'scrapflg' => $scrapflg,
				'delflg' => 0,
			],
			'order' => ['plateno' => 'asc'],
			'recursive' => -1,
		]);
	}

	public function Oi140CarList($branchmastersIds = [1, 21, 29]) {
		return $this->find('all', [
			'fields' => ['Carmaster.id', 'Carmaster.carno', 'Carmaster.branchmasters_id', 'Carmaster.idemituplateno'],
			'conditions' => [
				'Carmaster.departmentmasters_id' => 1,
				'Carmaster.branchmasters_id' => $branchmastersIds,
				'Carmaster.idemituplateno !=' => '',
				'Carmaster.scrapflg' => 0,
				'Carmaster.delflg' => 0,
			],
			'order' => ['Carmaster.branchmasters_id' => 'asc', 'Carmaster.idemituplateno' => 'asc'],
			'contain' => ['Branchmaster'],
			'recursive' => 0,
		]);
	}

	/*
	 * Co000s部署IDと営業所ID取得用
	 */
	public function SelectCarList($cid){
		return $this->find('first', [
			'fields' => ['id','branchmasters_id','departmentmasters_id','displayno'],
			'conditions' => [
				'Carmaster.id' => $cid,
				'Carmaster.scrapflg' => 0
			],
			'order' => ['id' => 'asc'],
			'recursive' => 0,
		]);
	}

	/*
	 * Co110s 取得用
	 */
	public function CarnameList($did) {
		return $this->find('list', [
			'fields' => ['id', 'plateno'],
			'order' => [
				'plateno' => 'asc'
			],
			'conditions' => [
				'departmentmasters_id' => $did,
				'scrapflg' => 0,
			],
		]);
	}

	/*
	 * 稼働不可能な車両の抽出
	 */
	public function NotRunCar($paramsDate,$paramsDate2) {
		return $this->find('all', [
			'fields' => [
				'Carmaster.id',
				'Carmaster.plateno',
				'Carmaster.notrundatestart',
				'Carmaster.notrundateend',
				'Carmaster.notrunreason',
			],
			'conditions' => [
				'AND' => [
					'Carmaster.notrundateend >=' => $paramsDate,
					'Carmaster.notrundateend <=' => $paramsDate2,
				]
			],
			'contain' => [
				'Truckkind' => [
					'conditions' => [
						'OR' => [
							['Truckkind.delflg' => ''],
							['Truckkind.delflg' => 0],
						]
					]
				]
			],
			'recursive' => 2
		]);
	}
	//2023-04-13 order
	public function SelectCarmasterCo003s($paramsDate,$paramsDate2,$branchconditions,$cid){
		$carconditions = '';
		if($cid){
			$carconditions = ['Carmaster.id' => $cid];
		}
	    return $this->find('all',[
	        'order' => [
	            'Carmaster.displayno' => 'asc',
	        ],
	        'conditions' => [
	            $branchconditions,
				$carconditions,
	            'Carmaster.scrapflg' => 0
	        ],
	        'fields'=>[
	            'Carmaster.id',
	            'Carmaster.plateno',
	            'Carmaster.carno',
	            'Carmaster.trailermasters_id',
				'Carmaster.branchmasters_id',
	            'Trailermaster.trailerkind'
	        ],
	        'contain'=>[
	            'Trailermaster'
	        ],
	        'recursive' => 0
	    ]);
	}
	public function SelectCarmasterGa003s($paramsDate,$paramsDate2,$branchconditions){
	    return $this->find('all',[
	        'order' => [
	            'Carmaster.displayno' => 'asc',
	        ],
	        'conditions' => [
	            'Carmaster.scrapflg' => 0,
				'Carmaster.departmentmasters_id'=>7
	        ],
	        'fields'=>[
	            'Carmaster.id',
	            'Carmaster.plateno',
	            'Carmaster.carno',
	            'Carmaster.trailermasters_id',
				'Carmaster.branchmasters_id',
	            'Trailermaster.trailerkind'
	        ],
	        'contain'=>[
	            'Trailermaster'
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
		'trailerteate' => [
			'rule' => 'notBlank',
			'message' => '手当の金額を入力してください。'
		]
	];


/**
 * belongsTo associations
 *
 * @var array
 */
	public $hasMany = [
		'Ordertran' => [
			'className' => 'Ordertran',
			'foreignKey' => 'carmasters_id',
		],
		'Truckkind' => [
			'className' => 'Truckkind',
			'foreignKey' => 'carmasters_id',
		]
	];

	public $belongsTo = [
/*
	    'Containertype' => [
			'className' => 'Containertype',
			'foreignKey' => 'containertypes_id',
			'conditions' => ['Containertype.delflg' => 0],
			'fields' => [
				'contype',
				'contypeshort'
			],
			'order' => ''
		],
*/
		'Trailermaster' => [
			'className' => 'Trailermaster',
			'foreignKey' => 'trailermasters_id',
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
		'Departmentmaster' => [
			'className' => 'Departmentmaster',
			'foreignKey' => 'departmentmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		]
	];

	/*
	public $hasAndBelongsToMany = [
		'Productmaster' => [
			'className' => 'Productmaster',
			'joinTable' => 'productmasters_has_carmasters',
			'foreignKey' => 'carmasters_id',
			'associationForeignKey' => 'productmasters_id',
			'unique' => true
		]
	];
	*/

}
