<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Truckkind extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Truckkind';
	public $useTable = 'truckkinds';
	public $actsAs = array('Containable');

	/*
	// 検索プラグイン
	public $actsAs = array('Search.Searchable');

	// 検索対象のフィルタ設定
	public $filterArgs = [
		['name' => 'shippermasters_id', 'type' => 'value', 'field' => 'Productdetailmaster.shippermasters_id']
	];

	// 検索対象のフィールド設定
	public $presetVars = [
		['field' => 'shippermasters_id', 'type' => 'value']
	];

	public function SelectList(){
		return $this->find('list', [
			'fields' => ['id','pdname'],
			'conditions' => '',
			'order' => ['id' => 'asc']
		]);
	}
	*/

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	/*
	 * 各000s車両リスト作成用
	 */
	public function SelectTruckkindList($pid) {
	    $this->virtualFields = ['name' => 'concat(Carmaster.plateno ,"/", Productdetailmaster.pdname)'];
//		$this->virtualFields = ['name' => 'concat(Carmaster.plateno ," ", Shippermaster.shippername, "/", Productdetailmaster.pdname)'];
//		$this->virtualFields = ['name' => 'concat(Carmaster.carno)'];
	    return $this->find('list', [
			'order' => [
				'Carmaster.plateno' => 'asc'
			],
			'fields' => ['Truckkind.id', 'name'],
			'conditions' => [
				'Truckkind.productdetailmasters_id' => $pid,
				'Truckkind.delflg' => 0
			],
			'recursive' => 0,
		]);
	}

	/*
	 * Oi140s車両リスト作成用
	 */
	public function SelectTruckkindListOi140s($bid,$did) {
	    $this->virtualFields = ['name' => 'concat(Carmaster.carno," (",Carmaster.plateno,")")'];
	    return $this->find('list', [
	        'order' => [
	            'Carmaster.carno' => 'asc'
	        ],
	        'fields' => ['Truckkind.id', 'name'],
	        'conditions' => [
	            'Carmaster.branchmasters_id' => $bid,
	            'Carmaster.departmentmasters_id'=>$did,
	            'Truckkind.delflg' => 0
	        ],
	        'recursive' => 0,
	    ]);
	}
	/*
	 * 各000s車両リスト作成用2
	 */
	public function SelectTruckkindList2() {
		return $this->find('list', [
			'fields' => ['Truckkind.id', 'Truckkind.carmasters_id'],
			'conditions' => ['Truckkind.delflg' => 0]
		]);
	}

	/*
	 * 000sデータ抽出用(コンテナ用・default)
	 */
	public function SelectTruckkind000s($paramsDate,$paramsDate2,$shipperconditions,$productconditions,$branchconditions){
		$this->Behaviors->load('EagerLoader.EagerLoader');

		return $this->find( 'all', [
			'order' => [
				'Carmaster.plateno' => 'asc',
				'Truckkind.productdetailmasters_id' => 'asc',
			],
			'conditions' => [
				'AND' => [
					$shipperconditions,
					$productconditions,
					$branchconditions,
					['Truckkind.delflg' => 0]
				]
			],
			'fields' => [
				'Truckkind.id',
				'Truckkind.carmasters_id',
				'Truckkind.shippermasters_id',
				'Truckkind.productdetailmasters_id',
			    'Carmaster.plateno',
			    'Carmaster.carno',
//			    'Carmaster.containertypes_id',
			    'Carmaster.trailermasters_id',
			    'Productdetailmaster.pdname',
				'Shippermaster.shippershortname',
			],
			'contain' => [
				'Carmaster' => [
			        'Trailermaster',
					//'fields' => ['containertypes_id'],
// 					'Containertype' => [
// 						'fields' => [
// 							'Containertype.contypeshort'
// 						],
// 						'conditions' => [
// 							'Containertype.delflg' => 0
// 						]
// 					]
				],
				'Shippermaster',
				'Productdetailmaster',
				'Ordertran' => [
					'fields' => [
						'Ordertran.loadingmasters_id',
						'Ordertran.deliverymasters_id',
						'Ordertran.loadingdate',
						'Ordertran.loadingtime',
						'Ordertran.deliverydate',
						'Ordertran.deliverytime',
						'Ordertran.color',
					    'Ordertran.correctdate',
					    'Ordertran.check1flg'
					],
					'order' => [
						'Ordertran.kaisen' => 'asc',
						'Ordertran.deliverydate' => 'asc',
						'Ordertran.deliverytime' => 'asc'
					],
					'conditions' => [
						'Ordertran.delflg' => 0,
						['OR' => [
							'Ordertran.loadingdate >= ' => $paramsDate,
							'Ordertran.deliverydate >= ' => $paramsDate
						]],
						['OR' => [
							'Ordertran.loadingdate <= ' => $paramsDate2,
							'Ordertran.deliverydate <= ' => $paramsDate2
						]]

// 						'AND' => [
// 							['Ordertran.loadingdate >= ' => $paramsDate],
// 							['Ordertran.loadingdate <= ' => $paramsDate2],
// 							['Ordertran.deliverydate >= '=> $paramsDate],
// 							['Ordertran.deliverydate <= ' => $paramsDate2],
// 							['Ordertran.delflg' => 0]
// 						],
					],
					'Processtran' => [
						'fields' => [
							'id',
							'processstartdate',
							'processstarttime',
							'workddetailcomment',
						],
						'order' => [
//							'processstartdate' => 'asc',
//							'processstarttime' => 'asc',
                            'id'=>'asc',
							'workdetailmasters_id' => 'asc'
						],
						'Workdetailmaster',
						'Workplace',
					],
				],
			],
			'recursive' => 1,
		]);
	}
	/*
	 * 20201013 Co000s Test用
	 */
	public function SelectTruckkindCo000sTest($paramsDate,$paramsDate2,$shipperconditions,$branchconditions){
		$this->Behaviors->load('EagerLoader.EagerLoader');

		return $this->find( 'all', [
			'order' => [
			    'Carmaster.plateno' => 'asc',
				'Truckkind.productdetailmasters_id' => 'asc',
			],
			'conditions' => [
				$shipperconditions,
				$branchconditions,
				'Truckkind.delflg' => 0
			],
			'fields' => [
				'Truckkind.id',
				'Truckkind.carmasters_id',
				'Truckkind.shippermasters_id',
				'Truckkind.productdetailmasters_id',
			    'Carmaster.plateno',
			    'Carmaster.carno',
//			    'Carmaster.containertypes_id',
			    'Carmaster.trailermasters_id',
			    'Productdetailmaster.pdname',
				'Shippermaster.shippershortname',
			],
			'group' => [
				'Truckkind.carmasters_id'
			],
			'contain' => [
				'Carmaster' => [
					'Trailermaster',
				],
				'Shippermaster',
				'Productdetailmaster',
			],
			'recursive' => 0,
		]);
	}
	public function SelectTruckkindCo003s($paramsDate,$paramsDate2,$shipperconditions,$branchconditions){
	    $this->Behaviors->load('EagerLoader.EagerLoader');
	    return $this->find( 'all', [
	        'order' => [
	            'Carmaster.plateno' => 'asc',
	            'Truckkind.productdetailmasters_id' => 'asc',
	        ],
	        'conditions' => [
	            $shipperconditions,
	            $branchconditions,
	            'Truckkind.delflg' => 0
	        ],
	        'fields' => [
	            'Truckkind.id',
	            'Truckkind.carmasters_id',
	            'Truckkind.shippermasters_id',
	            'Truckkind.productdetailmasters_id',
	            'Carmaster.plateno',
	            'Carmaster.carno',
	            'Carmaster.trailermasters_id',
	            'Productdetailmaster.pdname',
	            'Shippermaster.shippershortname',
	        ],
	        'group' => [
	            'Truckkind.carmasters_id'
	        ],
	        'contain' => [
	            'Carmaster' => [
	                'Trailermaster',
	            ],
	            'Shippermaster',
	            'Productdetailmaster',
	        ],
	        'recursive' => 0,
	    ]);
	}

	/*
	 * 000sデータ抽出用(化学品用)
	 */
	public function SelectTruckkind000sc($paramsDate,$paramsDate2,$shipperconditions,$productconditions,$branchconditions){
	    $this->Behaviors->load('EagerLoader.EagerLoader');
	    return $this->find( 'all', [
	        'order' => [
	            'Carmaster.displayno' => 'asc',
	            'Truckkind.productdetailmasters_id' => 'asc',
	        ],
	        'conditions' => [
	            'AND' => [
	                $shipperconditions,
	                $productconditions,
	                $branchconditions,
	                ['Truckkind.delflg' => 0]
	            ]
	        ],
	        'fields' => [
	            'Truckkind.id',
	            'Truckkind.carmasters_id',
	            'Truckkind.shippermasters_id',
	            'Truckkind.productdetailmasters_id',
	            'Carmaster.plateno',
	            'Carmaster.carno',
	            //			    'Carmaster.containertypes_id',
	            'Carmaster.trailermasters_id',
	            'Productdetailmaster.pdname',
	            'Shippermaster.shippershortname',
	        ],
	        'contain' => [
	            'Carmaster' => [
	                'Trailermaster',
	                //'fields' => ['containertypes_id'],
	            // 					'Containertype' => [
	                // 						'fields' => [
	                    // 							'Containertype.contypeshort'
	                    // 						],
	                // 						'conditions' => [
	                    // 							'Containertype.delflg' => 0
	                    // 						]
	                // 					]
	            ],
	            'Shippermaster',
	            'Productdetailmaster',
	            'Ordertran' => [
	                'fields' => [
	                    'Ordertran.loadingmasters_id',
	                    'Ordertran.deliverymasters_id',
	                    'Ordertran.loadingdate',
	                    'Ordertran.loadingtime',
	                    'Ordertran.deliverydate',
	                    'Ordertran.deliverytime',
	                    'Ordertran.color',
	                    'Ordertran.correctdate',
	                    'Ordertran.check1flg'
	                ],
	                'order' => [
	                    'Ordertran.kaisen' => 'asc',
	                    'Ordertran.deliverydate' => 'asc',
	                    'Ordertran.deliverytime' => 'asc'
	                ],
	                'conditions' => [
	                    'Ordertran.delflg' => 0,
	                    ['OR' => [
	                        'Ordertran.loadingdate >= ' => $paramsDate,
	                        'Ordertran.deliverydate >= ' => $paramsDate
	                    ]],
	                    ['OR' => [
	                        'Ordertran.loadingdate <= ' => $paramsDate2,
	                        'Ordertran.deliverydate <= ' => $paramsDate2
	                    ]]

	                    // 						'AND' => [
	                        // 							['Ordertran.loadingdate >= ' => $paramsDate],
	                        // 							['Ordertran.loadingdate <= ' => $paramsDate2],
	                        // 							['Ordertran.deliverydate >= '=> $paramsDate],
	                        // 							['Ordertran.deliverydate <= ' => $paramsDate2],
	                        // 							['Ordertran.delflg' => 0]
	                        // 						],
	                ],
	                'Processtran' => [
	                    'fields' => [
	                        'id',
	                        'processstartdate',
	                        'processstarttime',
	                        'workddetailcomment',
	                    ],
	                    'order' => [
	                    //							'processstartdate' => 'asc',
	                    //							'processstarttime' => 'asc',
	                        'id'=>'asc',
	                        'workdetailmasters_id' => 'asc'
	                    ],
	                    'Workdetailmaster',
	                    'Workplace',
	                ],
	            ],
	        ],
	        'recursive' => 1,
	    ]);
	}

	/*
	 * 020sデータ抽出用
	 */
	public function SelectTruckkind020s($paramsDate,$branchmasters_id,$did) {
		return $truckkinds = $this->find('all', [
			'fields' => [
				'Truckkind.id',
				'Truckkind.carmasters_id',
				'Truckkind.shippermasters_id',
				'Truckkind.productdetailmasters_id',
				'Carmaster.plateno',
				'Productdetailmaster.pdname',
			],
			'contain' => [
				'Carmaster',
				'Shippermaster',
				'Productdetailmaster',
				'Ordertran' => [
					'conditions' => [
						'AND' => [
							['Ordertran.deliverydate' => $paramsDate],
							['Ordertran.branchmasters_id' => $branchmasters_id],
							['Ordertran.departmentmasters_id' => $did],
							['Ordertran.delflg' => 0],
						]
					],
					'fields' => [
						'Ordertran.id',
						'Ordertran.deliverydate',
						'Ordertran.deliverytime',
						'Ordertran.deliverymasters_id',
						'Ordertran.kaisen',
						'Ordertran.container_no',
						'Ordertran.ref_no'
					],
					'order' => [
						'Ordertran.deliverydate' => 'asc',
						'Ordertran.deliverytime' => 'asc',
					],
				]
			],
			'order' => [
				'Carmaster.plateno' => 'asc',
			],
		]);
	}

	/*
	 * Oi001sデータ抽出用：全ての絞り込み時
	 */
	public function SelectTruckkindAll2($paramsDate,$shippermasters_id,$productdetailmasters_id){
		return $this->find( 'all', [
			'order' => [
				'Truckkind.carmasters_id' => 'asc',
			],
			'conditions' => [
				'AND' => [
					['Truckkind.shippermasters_id' => $shippermasters_id],
					['Truckkind.productdetailmasters_id' => $productdetailmasters_id]
				]
			],
			'fields' => [
				'Truckkind.id',
				'Truckkind.carmasters_id',
				'Truckkind.shippermasters_id',
				'Truckkind.productdetailmasters_id',
				'Carmaster.plateno',
				'Productdetailmaster.pdname',
			],
			'contain' => [
				'Carmaster',
				'Shippermaster',
				'Productdetailmaster',
				'Ordertran' => [
					'order' => [
						'Ordertran.deliverymasters_id' => 'asc'
					],
					'conditions' => [
						'Ordertran.deliverydate' => $paramsDate
					]
				]
			]
		]);
	}

	/*
	 * Oi001sデータ抽出用：荷主絞り込み時
	 */
	public function SelectTruckkindShipper2($paramsDate,$shippermasters_id){
		return $this->find( 'all', [
			'order' => [
				'Truckkind.carmasters_id' => 'asc',
			],
			'conditions' => [
				['Truckkind.shippermasters_id' => $shippermasters_id],
			],
			'fields' => [
				'Truckkind.id',
				'Truckkind.carmasters_id',
				'Truckkind.shippermasters_id',
				'Truckkind.productdetailmasters_id',
				'Carmaster.plateno',
				'Productdetailmaster.pdname',
			],
			'contain' => [
				'Carmaster',
				'Shippermaster',
				'Productdetailmaster',
				'Ordertran' => [
					'order' => [
						'Ordertran.deliverymasters_id' => 'asc'
					],
					'conditions' => [
						'Ordertran.deliverydate' => $paramsDate
					]
				]
			]
		]);
	}

	/*
	 * Oi001sデータ抽出用：商品絞り込み時
	 */
	public function SelectTruckkindProductdetail2($paramsDate,$productdetailmasters_id){
		return $this->find( 'all', [
			'order' => [
				'Truckkind.carmasters_id' => 'asc',
			],
			'conditions' => [
				['Truckkind.productdetailmasters_id' => $productdetailmasters_id],
			],
			'fields' => [
				'Truckkind.id',
				'Truckkind.carmasters_id',
				'Truckkind.shippermasters_id',
				'Truckkind.productdetailmasters_id',
				'Carmaster.plateno',
				'Productdetailmaster.pdname',
			],
			'contain' => [
				'Carmaster',
				'Shippermaster',
				'Productdetailmaster',
				'Ordertran' => [
					'order' => [
						'Ordertran.deliverymasters_id' => 'asc'
					],
					'conditions' => [
						'Ordertran.deliverydate' => $paramsDate
					]
				]
			]
		]);
	}

/**
 * Validation rules
 *
 * @var array
 */
	/*
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
	*/

	public $hasMany = [
		'Ordertran' => [
			'className' => 'Ordertran',
			'foreignKey' => 'truckkinds_id',
			'dependent' => false,
			'conditions' => ['Ordertran.delflg' => 0]
		]
	];

	public $belongsTo = array(
		'Productdetailmaster' => array(
			'className' => 'Productdetailmaster',
			'foreignKey' => 'productdetailmasters_id',
			'conditions' => ['Productdetailmaster.delflg' => 0],
			'fields' => [
				'pdname',
				'pdshortname',
				'pdcode'
			],
			'order' => ''
		),
		'Shippermaster' => [
			'className' => 'Shippermaster',
			'foreignKey' => 'shippermasters_id',
			'conditions' => ['Shippermaster.delflg' => 0],
			'fields' => [
				'shippername',
				'shippershortname'
			],
			'order' => ''
		],
		'Carmaster' => [
			'className' => 'Carmaster',
			'foreignKey' => 'carmasters_id',
			'conditions' => ['Carmaster.scrapflg' => 0],
			'fields' => [
				'plateno',
				'branchmasters_id',
                'trailermasters_id'
			],
			'order' => ''
		],
	);

}
