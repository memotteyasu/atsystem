<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Invoice extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Invoice';
	public $useTable = 'invoices';
	public $actsAs = ['Containable'];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	/*
	 * 100s系列データ抽出用
	 */
	public function SelectInvoice100s($paramsDate,$paramsDate2,$did,$shipperconditions,$branchconditions) {
	    return $this->find('all', [
	        'fields' => [
	            'Invoice.id',
	            'Invoice.honorific',
	            'Invoice.billingdate',
	            'Invoice.paymentdate',
	            'Invoice.invoicenumber',
	            'Invoice.subject',
				'Invoice.pagetype',
				'Invoice.tax_flg',
	            'Shippermaster.shippername',
				'Shippermaster.billtype'
	        ],
	        'conditions' => [
	            'AND' => [
	                ['Invoice.billingdate >=' => $paramsDate],
	                ['Invoice.billingdate <=' => $paramsDate2],
	                ['Shippermaster.departmentmasters_id' => $did],
	                ['Invoice.delflg'=>0],
					$shipperconditions,
					$branchconditions
	            ]
	        ],
	        'order' => [
	            'Invoice.billingdate' => 'asc',
	            'Invoice.invoicenumber' => 'asc',
	            'Invoice.id' => 'asc',
	        ],
	        'recursive' => 1
	    ]);
	}
	public function SelectInvoice140s2($paramsDate,$paramsDate2,$did,$shipperconditions) {
	    return $this->find('all', [
	        'fields' => [
	            'Invoice.id',
	            'Invoice.honorific',
	            'Invoice.billingdate',
	            'Invoice.paymentdate',
	            'Invoice.invoicenumber',
	            'Invoice.subject',
				'Shippermaster.shippername',
				'Branchmaster.branchname'
	        ],
	        'conditions' => [
	            'AND' => [
	                ['Invoice.billingdate >=' => $paramsDate],
	                ['Invoice.billingdate <=' => $paramsDate2],
	                ['Shippermaster.departmentmasters_id' => $did],
	                ['Invoice.delflg'=>0],
	                $shipperconditions,
	            ]
	        ],
	        'order' => [
	            'Invoice.billingdate' => 'asc',
	            'Invoice.invoicenumber' => 'asc',
	            'Invoice.id' => 'asc',
	        ],
	        'recursive' => 1
	    ]);
	}

	/*
	 * 120s系列データ抽出用
	 */
	public function SelectInvoice120s2($paramsDate,$paramsDate2,$did,$shipperconditions,$branchmasters_id) {
	    return $this->find('all', [
	        'fields' => [
	            'Invoice.id',
	            'Invoice.honorific',
	            'Invoice.billingdate',
	            'Invoice.paymentdate',
	            'Invoice.invoicenumber',
	            'Invoice.subject',
	            'Shippermaster.shippername'
	        ],
	        'conditions' => [
	            'AND' => [
	                ['Invoice.billingdate >=' => $paramsDate],
	                ['Invoice.billingdate <=' => $paramsDate2],
	                ['Invoice.mirror_flg' => 1],
	                ['Shippermaster.departmentmasters_id' => $did],
					$shipperconditions,
					['Invoice.branchmasters_id' => $branchmasters_id],
	            ]
	        ],
	        'order' => [
	            'Invoice.billingdate' => 'asc',
	            'Invoice.invoicenumber' => 'asc',
	            'Invoice.id' => 'asc',
	        ],
	        'recursive' => 0
	    ]);
	}
	public function SelectInvoice140s($paramsDate,$paramsDate2,$did,$branchconditions) {
	    return $this->find('all', [
	        'fields' => [
	            'Invoice.id',
	            'Invoice.billingdate',
	            'Invoice.invoicenumber',
	            'Invoice.subject',
	            'Invoice.total',
	        ],
	        'conditions' => [
	            'Invoice.billingdate >=' => $paramsDate,
	            'Invoice.billingdate <=' => $paramsDate2,
	            'Invoice.departmentmasters_id' => $did,
	            'Invoice.delflg' => 0,
	            $branchconditions
	        ],
	        'order' => [
	            'Carmaster.plateno' => 'asc',
	        ],
	        'recursive' => 1
	    ]);
	}
	public function SelectInvoice140index($paramsDate,$paramsDate2,$did,$bid) {
	    return $this->find('all', [
	        'fields' => [
	            'Invoice.id',
	            'Invoice.billingdate',
	            'Invoice.invoicenumber',
	            'Invoice.subject',
	            'Invoice.total',
	        ],
	        'conditions' => [
	            'Invoice.billingdate >=' => $paramsDate,
	            'Invoice.billingdate <=' => $paramsDate2,
	            'Invoice.departmentmasters_id' => $did,
	            'Invoice.branchmasters_id' => $bid,
	            'Invoice.pagetype'=>'Oi140s',
	            'Invoice.delflg' => 0,
	        ],
	        'order' => [
	            'Invoice.billingdate' => 'asc',
	        ],
	        'recursive' => 0
	    ]);
	}

	public function SelectOi140Invoices($paramsDate,$paramsDate2,$did,$bid = null) {
		$conditions = [
			'Invoice.billingdate >=' => $paramsDate,
			'Invoice.billingdate <=' => $paramsDate2,
			'Invoice.departmentmasters_id' => $did,
			'Invoice.pagetype' => 'Oi140s',
			'Invoice.delflg' => 0,
		];
		if ($bid) {
			$conditions['Invoice.branchmasters_id'] = $bid;
		}

		return $this->find('all', [
			'fields' => [
				'Invoice.id',
				'Invoice.honorific',
				'Invoice.billingdate',
				'Invoice.paymentdate',
				'Invoice.invoicenumber',
				'Invoice.subject',
				'Shippermaster.shippername',
				'Branchmaster.branchname'
			],
			'conditions' => $conditions,
			'order' => [
				'Invoice.billingdate' => 'asc',
				'Invoice.invoicenumber' => 'asc',
				'Invoice.id' => 'asc',
			],
			'recursive' => 1
		]);
	}

	public $validate = [
		'shippermasters_id' => [
			'rule' => 'notBlank',
			'message' => '請求先を選択して下さい。'
		],
		'subject' => [
			'rule' => 'notBlank',
			'message' => '件名を入力して下さい。'
		],
		'paymentdate' => [
			'rule' => 'notBlank',
			'message' => 'お支払い期限を入力して下さい。'
		],
	];

	public $hasMany = [
		'Invoicedetail' => [
			'className' => 'Invoicedetail',
			'foreignKey' => 'invoices_id',
		    'dependent' => true,
		]
	];
	public $belongsTo = [
	    'Shippermaster' => [
	        'className' => 'Shippermaster',
	        'foreignKey' => 'shippermasters_id',
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
	    'Branchmaster' => [
	        'className' => 'Branchmaster',
	        'foreignKey' => 'branchmasters_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ],
	    'Carmaster' => [
	        'className' => 'Carmaster',
	        'foreignKey' => 'carmasters_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ],
	];
}
