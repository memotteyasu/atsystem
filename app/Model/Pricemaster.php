<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Pricemaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Pricemaster';
	public $useTable = 'pricemasters';

	// 検索プラグイン
	public $actsAs = ['Search.Searchable'];

	// 検索対象のフィルタ設定
	public $filterArgs = [
		['name' => 'departmentmasters_id', 'type' => 'value', 'field' => 'Pricemaster.departmentmasters_id'],
		['name' => 'pricename', 'type' => 'like', 'field' => 'Pricemaster.pricename'],
		['name' => 'pricename2', 'type' => 'like', 'field' => 'Pricemaster.pricename'],
	];

	// 検索対象のフィールド設定
	public $presetVars = [
		['field' => 'departmentmasters_id', 'type' => 'value'],
		['field' => 'pricename', 'type' => 'value'],
		['field' => 'pricename2', 'type' => 'value']
		//['field' => 'id', 'type' => 'value'],
	];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}
	//諸費用
	public function SelectPriceList($kaisyasid,$did) {
		$this->virtualFields = ['name' => 'concat(Pricemaster.pricename," / ",Pricemaster.price)'];
		return $this->find('list', [
			'fields' => [
				'id', 'name'
			],
			'order' => [
				'pricename' => 'asc',
			],
			'conditions' => [
				'Pricemaster.delflg' => 0,
			    'Pricemaster.kaisyas_id'=>$kaisyasid,
			    'Pricemaster.keihiflg'=>1,
			    'Pricemaster.departmentmasters_id'=>$did,
			],
			'recursive' => 0
		]);
	}
	//20230925　工程の価格用
	public function SelectPricemaster($pkey = null,$kaisyaid,$shippermastersid = null,$pcid = null) {
	    for($i=0;$i<18;$i++){
	        // 6工程分
	        if(!isset($pkey[$i])){
	            $pkey[$i] = 0;
	        }
	    }
	    return $this->find('all', [
	        'fields' => [
	            'id',
	            'pricename',
	            'price',
	            'pricecomment',
	            'Branchmaster.branchname',
				'key1',
				'key2',
				'key3',
				'key4',
				'key5',
				'key6',
				'key7',
				'key8',
				'key9',
				'key10',
				'key11',
				'key12',
				'key13',
				'key14',
				'key15',
				'key16',
				'key17',
				'key18',
				'updateflg'
	        ],
	        'conditions' => [
                'Pricemaster.delflg' => 0,
                'Pricemaster.key1'=>$pkey[0],
                'Pricemaster.key2'=>$pkey[1],
                'Pricemaster.key3'=>$pkey[2],
                'Pricemaster.key4'=>$pkey[3],
                'Pricemaster.key5'=>$pkey[4],
                'Pricemaster.key6'=>$pkey[5],
                'Pricemaster.key7'=>$pkey[6],
                'Pricemaster.key8'=>$pkey[7],
                'Pricemaster.key9'=>$pkey[8],
	            'Pricemaster.key10'=>$pkey[9],
	            'Pricemaster.key11'=>$pkey[10],
	            'Pricemaster.key12'=>$pkey[11],
	            'Pricemaster.key13'=>$pkey[12],
	            'Pricemaster.key14'=>$pkey[13],
	            'Pricemaster.key15'=>$pkey[14],
	            'Pricemaster.key16'=>$pkey[15],
	            'Pricemaster.key17'=>$pkey[16],
	            'Pricemaster.key18'=>$pkey[17],
	            'Pricemaster.kaisyas_id'=>$kaisyaid,
				'Pricemaster.shippermasters_id'=>$shippermastersid,
				//20240726 商品を条件から外した　'Pricemaster.productcontainers_id'=>$pcid
	        ],
	        'recursive' => 0
	    ]);
	}

	public function Checkpricemaster($pkey = null,$kaisyaid) {
	    for($i=0;$i<18;$i++){
	        if(!isset($pkey[$i])){
	            $pkey[$i] = 0;
	        }
	    }
	    return $this->find('first', [
	        'fields' => [
	            'id'
	        ],
	        'conditions' => [
	            'Pricemaster.delflg' => 0,
	            'Pricemaster.key1'=>$pkey[0],
	            'Pricemaster.key2'=>$pkey[1],
	            'Pricemaster.key3'=>$pkey[2],
	            'Pricemaster.key4'=>$pkey[3],
	            'Pricemaster.key5'=>$pkey[4],
	            'Pricemaster.key6'=>$pkey[5],
	            'Pricemaster.key7'=>$pkey[6],
	            'Pricemaster.key8'=>$pkey[7],
	            'Pricemaster.key9'=>$pkey[8],
	            'Pricemaster.key10'=>$pkey[9],
	            'Pricemaster.key11'=>$pkey[10],
	            'Pricemaster.key12'=>$pkey[11],
	            'Pricemaster.key13'=>$pkey[12],
	            'Pricemaster.key14'=>$pkey[13],
	            'Pricemaster.key15'=>$pkey[14],
	            'Pricemaster.key16'=>$pkey[15],
	            'Pricemaster.key17'=>$pkey[16],
	            'Pricemaster.key18'=>$pkey[17],
	            'Pricemaster.kaisyas_id'=>$kaisyaid,
	        ],
	        'recursive' => 0
	    ]);
	}
	public function ExtractionPrice($id) {
		return $this->find('first', [
			'fields' => ['price'],
			'conditions' => [
				'Pricemaster.id' => $id,
				'Pricemaster.delflg' => 0
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
		'pricename' => [
			'rule' => 'notBlank',
			'message' => '価格名を入力してください。'
		],
		'price' => [
			'rule' => 'notBlank',
			'message' => '価格を入力してください。'
		],
	];


/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = [
		'Departmentmaster' => [
			'className' => 'Departmentmaster',
			'foreignKey' => 'departmentmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	    'Shippermaster' => [
			'className' => 'Shippermaster',
			'foreignKey' => 'shippermasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	    /*
		'Loadingmaster' => [
			'className' => 'Loadingmaster',
			'foreignKey' => 'loadingmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		'Deliverymaster' => [
			'className' => 'Deliverymaster',
			'foreignKey' => 'deliverymasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		*/
	    /*
		'Productdetailmaster' => [
			'className' => 'Productdetailmaster',
			'foreignKey' => 'productdetailmasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	    */
	    'Productcontainer' => [
	        'className' => 'Productcontainer',
	        'foreignKey' => 'productcontainers_id',
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

	public $hasAndBelongsToMany = [
		'Order'=>[
			'className' => 'Ordertran',
			'with' => 'OrdertransPricemaster',
			'foreignKey' => 'pricemasters_id',
			'associationForeignKey' => 'ordertrans_id',
		],
	];

}
