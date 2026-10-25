<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class OrdertranPricemaster extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'OrdertranPricemaster';
	public $useTable = 'ordertrans_pricemasters';

	public $actsAs = ['Containable'];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	public function SelectOrdertranPrice080s($id) {
		return $this->find('all', [
			'fields' => [
				'id', 'ordertrans_id', 'pricemasters_id', 'keihitaxflg'
			],
			'conditions' => [
				'OrdertranPricemaster.ordertrans_id' => $id,
				'OrdertranPricemaster.delflg' => 0
			],
			'contain' => [
				'Pricemaster' => [
					'fields' => [
						'id',
						'pricename',
						'price',
					    'pricecomment',
						'keihiflg',
						'updateflg'
					],
				]
			],
		    'order'=> [
		        'OrdertranPricemaster.created'=>'asc'
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
		'pricemasters_id' => [
			'rule' => 'notBlank',
			'message' => '請求内容を選択してください。'
		],
	];


/**
 * belongsTo associations
 *
 * @var array
 */

	 public $belongsTo = [
		'Ordertran' => [
			'className' => 'Ordertran',
			'foreignKey' => 'ordertrans_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		'Pricemaster' => [
			'className' => 'Pricemaster',
			'foreignKey' => 'Pricemasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	];

}
