<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Productcontainer extends AppModel {
    public $name = 'Productcontainer';
    public $useTable = 'productcontainers';

    // 検索プラグイン
    public $actsAs = array('Search.Searchable');

    // 検索対象のフィルタ設定
    public $filterArgs = [
//20200912        ['name' => 'productdetailmasters_id', 'type' => 'value', 'field' => 'Productcontainer.productdetailmasters_id'],
        ['name' => 'pdconname', 'type' => 'like', 'field' => 'Productcontainer.pdconname'],
    ];

    // 検索対象のフィールド設定
    public $presetVars = [
//2020-912        ['field' => 'productdetailmasters_id', 'type' => 'value'],
        ['field' => 'pdconname', 'type' => 'value']
    ];

    public function beforeSave($option = []) {
    	if(empty($this->id)){
    		$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
    	}
    	$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
    	return true;
    }

    public function SelectProductcontainerList($productdetailmasters_id) {
		return $this->find('list', [
			'fields' => [
				'Productcontainer.id', 'Productcontainer.pdconname'
			],
			'conditions' => [
				'Productcontainer.productdetailmasters_id' => $productdetailmasters_id,
			    'Productcontainer.delflg' => 0
			],
			'order' => [
				'Productcontainer.pdconname' => 'asc'
			]
		]);
	}

	public function SelectProductcontainerList2($pdid) {
		return $this->find('list', [
			'fields' => [
				'Productcontainer.id', 'Productcontainer.pdconname'
			],
			'conditions' => [
				'Productcontainer.delflg' => 0,
				'Productcontainer.productdetailmasters_id' => $pdid
			],
			'order' => [
				'Productcontainer.pdconname' => 'asc'
			]
		]);
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = [
		'pdconname' => [
			'rule' => 'notBlank',
			'message' => '商品名を入力してください。'
		],
		'pdconprice' => [
			'rule' => 'numeric',
			'message' => '価格を入力してください。'
		],
	];
	public $belongsTo = array(
	    'Productdetailmaster' => [
	        'className' => 'Productdetailmaster',
	        'foreignKey' => 'productdetailmasters_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ],
	);
}
