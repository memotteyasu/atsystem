<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
/**
 * User Model
 *
 * @property 
 */
class User extends AppModel {
	public $name = 'User';
	public $useTable = 'users';

	// 検索プラグイン
	public $actsAs = ['Search.Searchable','Containable'];

	// 検索対象のフィルタ設定
	public $filterArgs = [
		['name' => 'departmentmasters_id', 'type' => 'value', 'field' => 'User.departmentmasters_id'],
		['name' => 'branchmasters_id', 'type' => 'value', 'field' => 'User.branchmasters_id'],
		['name' => 'zaisekiflg', 'type' => 'value', 'field' => 'User.zaisekiflg'],
	];

	// 検索対象のフィールド設定
	public $presetVars = [
		['field' => 'departmentmasters_id', 'type' => 'value'],
		['field' => 'branchmasters_id', 'type' => 'value'],
		['field' => 'zaisekiflg', 'type' => 'value']
	];

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			//	        $passwordHasher = new SimplePasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash(
				$this->data[$this->alias]['password']
			);
		}
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	/*
	 * Host010sデータ抽出用
	 */
	public function SelectUserHost010s($paramsDate,$paramsDate2,$branchconditions,$departmentconditions,$kaisyaid) {
		return $this->find( 'all', [
			'fields' => [
				'User.id',
				'User.usernameK',
				'User.branchmasters_id',
				'User.departmentmasters_id',
			],
			'conditions' => [
				$branchconditions,
				$departmentconditions,
				'User.zaisekiflg' => 0,
				'User.kaisyas_id'=>$kaisyaid,
				'User.rolemasters_id !='=>90
			],
			'contain' => [
				'Kintaitran' => [
					'fields' => [
						'Kintaitran.id',
						'Kintaitran.kintaidate',
						'Kintaitran.flgmasters_id',
					    'Kintaitran.tako1flg',
					    'Kintaitran.tako2flg',
					    'Kintaitran.tako3flg',
					    'Kintaitran.tako4flg',
					    'Kintaitran.rinjicalendarflg',
					],
					'conditions' => [
						'Kintaitran.kintaidate >= ' => $paramsDate,
						'Kintaitran.kintaidate <= ' => $paramsDate2,
					],
					'order'=>[
						'Kintaitran.kintaidate'=>'asc'
					]
				]
			],
			'order' => [
				'User.username' => 'asc',
			],
			'recursive' => 0
		]);
	}

	/*
	 * Host002bsデータ抽出用
	 */
	public function SelectUserHost002bs($paramsDate,$paramsDate2,$branchconditions,$departmentconditions,$kaisyaid) {
		return $this->find( 'all', [
			'fields' => [
				'User.id',
				'User.usernameK',
				'User.branchmasters_id',
				'User.departmentmasters_id',
			],
			'conditions' => [
				$branchconditions,
				$departmentconditions,
				'User.zaisekiflg' => 0,
				'User.kaisyas_id'=>$kaisyaid,
				'User.rolemasters_id !='=>90
			],
			'contain' => [
				'Kintaitran2' => [
					'fields' => [
						'Kintaitran2.id',
						'Kintaitran2.kintaidate',
						'Kintaitran2.flgmasters_id',
					    'Kintaitran2.tako1flg',
					    'Kintaitran2.tako2flg',
					    'Kintaitran2.tako3flg',
					    'Kintaitran2.tako4flg',
					    'Kintaitran2.rinjicalendarflg',
					],
					'conditions' => [
						'Kintaitran2.kintaidate >= ' => $paramsDate,
						'Kintaitran2.kintaidate <= ' => $paramsDate2,
					],
					'order'=>[
						'Kintaitran2.kintaidate'=>'asc'
					]
				]
			],
			'order' => [
				'User.username' => 'asc',
			],
			'recursive' => 0
		]);
	}


	/*
	 * Host017sデータ抽出用
	 */
	public function SelectUserHost017s($paramsDate,$paramsDate2,$branchconditions,$departmentconditions,$kaisyaid) {
		return $this->find( 'all', [
			'fields' => [
				'User.id',
				'User.usernameK',
				'User.branchmasters_id',
				'User.departmentmasters_id',
			],
			'conditions' => [
				$branchconditions,
				$departmentconditions,
				'User.zaisekiflg' => 0,
				'User.kaisyas_id'=>$kaisyaid,
				'User.rolemasters_id !='=>90
			],
			'contain' => [
				'Kousokutran' => [
					'conditions' => [
						'Kousokutran.kousokudate >= ' => $paramsDate,
						'Kousokutran.kousokudate <= ' => $paramsDate2,
					],
					'order'=>[
						'Kousokutran.kousokudate'=>'asc'
					]
				]
			],
			'order' => [
				'User.id' => 'asc',
			],
			'recursive' => 0
		]);
	}

	public function SelectUserHost050s($paramsDate,$branchconditions,$departmentconditions,$kaisyaid) {
	    return $this->find( 'all', [
	        'fields' => [
	            'User.id',
	            'User.usernameK',
	            'User.branchmasters_id',
	            'User.departmentmasters_id',
	        ],
	        'conditions' => [
	            $branchconditions,
	            $departmentconditions,
	            'User.zaisekiflg' => 0,
	            'User.kaisyas_id'=>$kaisyaid,
	            'User.rolemasters_id !='=>90
	        ],
	        'contain' => [
	            'Kintaitran' => [
	                'conditions' => [
	                    'Kintaitran.kintaidate' => $paramsDate,
	                ],
	            ],
	        ],
	        'order' => [
	            'User.id' => 'asc',
	        ],
	        'recursive' => 0
	    ]);
	}

	public function SelectUserHost060s($paramsDate,$paramsDate2,$branchconditions,$departmentconditions,$kaisyaid) {
	    return $this->find( 'all', [
	        'fields' => [
	            'User.id',
	            'User.usernameK',
	            'User.branchmasters_id',
	            'User.departmentmasters_id',
	        ],
	        'conditions' => [
	            $branchconditions,
	            $departmentconditions,
	            'User.zaisekiflg' => 0,
	            'User.kaisyas_id'=>$kaisyaid,
	            'User.rolemasters_id !='=>90
	        ],
	        'contain' => [
	            'Kintaitran' => [
	                'fields' => [
	                    'Kintaitran.id',
	                    'Kintaitran.kintaidate',
	                    'Kintaitran.flgmasters_id',
	                    'Kintaitran.kyukeitime',
	                    'Kintaitran.rinjicalendarflg',
	                ],
	                'conditions' => [
	                    'Kintaitran.kintaidate >= ' => $paramsDate,
	                    'Kintaitran.kintaidate <= ' => $paramsDate2,
	                ],
	                'order'=>[
	                    'Kintaitran.kintaidate'=>'asc'
	                ]
	            ]
	        ],
	        'order' => [
	            'User.id' => 'asc',
	        ],
	        'recursive' => 0
	    ]);
	}
	public function SelectUserHost061s($paramsDate,$kaisyaid) {
	    $firstDate = date('Y-m-d', strtotime('first day of ' . $paramsDate));
	    $lastDate = date('Y-m-d', strtotime('last day of ' . $paramsDate));
	    return $this->find( 'all', [
	        'fields' => [
	            'User.id',
	            'User.usernameK',
	            'User.branchmasters_id',
	            'User.departmentmasters_id',
	        ],
	        'conditions' => [
	            'User.zaisekiflg' => 0,
	            'User.kaisyas_id'=>$kaisyaid,
	            'AND'=>[
	                'User.rolemasters_id !='=>90,
	                'User.rolemasters_id >='=>28,
	                'User.rolemasters_id <='=>39
	            ],
	        ],
	        'contain' => [
	            'Kintaitran' => [
	                'fields' => [
	                    'Kintaitran.id',
	                    'Kintaitran.kintaidate',
	                    'Kintaitran.roumu_kousoku',
	                ],
	                'conditions' => [
	                    'Kintaitran.kintaidate >= ' => $firstDate,
	                    'Kintaitran.kintaidate <= ' => $lastDate,
	                ],
	                'order'=>[
	                    'Kintaitran.kintaidate'=>'asc'
	                ]
	            ]
	        ],
	        'order' => [
	            'User.id' => 'asc',
	        ],
	        'recursive' => 0
	    ]);
	}

	/*
	 * Host012sデータ抽出用
	 */
	public function SelectUserHost012s($paramsDate1,$paramsDate2,$branchconditions,$departmentconditions,$kaisyas_id) {
		return $this->find('all', [
			'fields' => [
				'User.id',
				'User.usernameK',
				'User.branchmasters_id',
				'User.departmentmasters_id'
			],
			'conditions' => [
				$branchconditions,
				$departmentconditions,
				'User.zaisekiflg' => 0,
				'User.kaisyas_id' => $kaisyas_id
			],
			'contain' => [
				'Kintaitran' => [
					'fields' => [
						'id','kintaidate','flgmasters_id'
					],
					'conditions' => [
						'Kintaitran.kintaidate >=' => $paramsDate1,
						'Kintaitran.kintaidate <=' => $paramsDate2,
						'Kintaitran.flgmasters_id' => 13
					],
					'order' => [
						'Kintaitran.kintaidate' => 'asc'
					]
				]
			],
			'order' => [
				'User.id' => 'asc'
			]
		]);
	}
	public function SelectUserHost012s2($paramsDate1,$paramsDate2,$branchconditions,$departmentconditions,$kaisyas_id) {
	    return $this->find('list', [
	        'fields' => [
	            'User.id',
	            'User.usernameK',
	        ],
	        'conditions' => [
	            $branchconditions,
	            $departmentconditions,
	            'User.zaisekiflg' => 0,
	            'User.kaisyas_id' => $kaisyas_id
	        ],
	        'contain' => [
	            'Kintaitran' => [
	                'fields' => [
	                    'id','kintaidate','flgmasters_id'
	                ],
	                'conditions' => [
	                    'Kintaitran.kintaidate >=' => $paramsDate1,
	                    'Kintaitran.kintaidate <=' => $paramsDate2,
	                    'Kintaitran.flgmasters_id' => 13
	                ],
	                'order' => [
	                    'Kintaitran.kintaidate' => 'asc'
	                ]
	            ]
	        ],
	        'order' => [
	            'User.usernameKK' => 'asc'
	        ]
	    ]);
	}
	/*
	 * Host013sデータ抽出用
	 */


	public function SelectUserHost013s($paramsDate,$paramsDate2,$branchconditions,$kaisyaid) {
	    return $this->find( 'all', [
	        'fields' => [
	            'User.id',
	            'User.usernameK',
	            'User.calendarflg'
	        ],
	        'conditions' => [
	            'User.branchmasters_id'=>$branchconditions,
	            'User.zaisekiflg' => 0,
	            'User.kaisyas_id'=>$kaisyaid,
	            'User.rolemasters_id >='=>30,
	            'User.rolemasters_id <='=>39

	        ],
	        'contain' => [
	            'Kintaitran' => [
	                'fields' => [
	                    'Kintaitran.kintaidate',
	                    'Kintaitran.flgmasters_id',
	                    'Kintaitran.tako_gokei_km'
	                ],
	                'conditions' => [
	                    'Kintaitran.kintaidate >= ' => $paramsDate,
	                    'Kintaitran.kintaidate <= ' => $paramsDate2,
	                ],
	                'order'=>[
	                    'Kintaitran.kintaidate'=>'asc'
	                ]
	            ]
	        ],
	        'order' => [
	            'User.usernameKK' => 'asc',
	        ],
	        'recursive' => 0
	    ]);
	}

// 	/*
// 	 * Host010sデータ抽出用：全ての絞り込み時
// 	 */
// 	public function SelectUserAll($paramsDate,$paramsDate2,$branchmasters_id,$departmentmasters_id){
// 		return $this->find( 'all', [
// 			'order' => [
// 				'User.id' => 'asc',
// 			],
// 			'conditions' => [
// 				'AND' => [
// 					['User.branchmasters_id' => $branchmasters_id],
// 					['User.departmentmasters_id' => $departmentmasters_id]
// 				],
// 			],
// 			'fields' => [
// 				'User.id',
// 				'User.usernameK',
// 				'User.branchmasters_id',
// 				'User.departmentmasters_id',
// 			],
// 			'contain' => [
// 				'Kintaitran' => [
// 					'conditions' => [
// 						'AND' => [
// 							['Kintaitran.kintaidate >= ' => $paramsDate],
// 							['Kintaitran.kintaidate <= ' => $paramsDate2],
// 						]
// 					],
// 					'fields' => [
// 						'Kintaitran.id',
// 						'Kintaitran.kintaidate',
// 						'Kintaitran.flgmasters_id',
// 					],
// 				]
// 			],
// 			//'recursive' => 2
// 		]);
// 	}

// 	/*
// 	 * Host010sデータ抽出用：営業所絞り込み時
// 	 */
// 	public function SelectUserBranch($paramsDate,$paramsDate2,$branchmasters_id){
// 		return $this->find( 'all', [
// 			'order' => [
// 				'User.id' => 'asc',
// 			],
// 			'conditions' => [
// 				['User.branchmasters_id' => $branchmasters_id]
// 			],
// 			'fields' => [
// 				'User.id',
// 				'User.usernameK',
// 				'User.branchmasters_id',
// 				'User.departmentmasters_id',
// 			],
// 			'contain' => [
// 				'Kintaitran' => [
// 					'conditions' => [
// 						'AND' => [
// 							['Kintaitran.kintaidate >= ' => $paramsDate],
// 							['Kintaitran.kintaidate <= ' => $paramsDate2],
// 						]
// 					],
// 					'fields' => [
// 						'Kintaitran.id',
// 						'Kintaitran.kintaidate',
// 						'Kintaitran.flgmasters_id',
// 					],
// 				]
// 			],
// 			//'recursive' => 2
// 		]);
// 	}

// 	/*
// 	 * Host010sデータ抽出用：部署絞り込み時
// 	 */
// 	public function SelectUserDepartment($paramsDate,$paramsDate2,$departmentmasters_id){
// 		return $this->find( 'all', [
// 			'order' => [
// 				'User.id' => 'asc',
// 			],
// 			'conditions' => [
// 				['User.departmentmasters_id' => $departmentmasters_id]
// 			],
// 			'fields' => [
// 				'User.id',
// 				'User.usernameK',
// 				'User.branchmasters_id',
// 				'User.departmentmasters_id',
// 			],
// 			'contain' => [
// 				'Kintaitran' => [
// 					'conditions' => [
// 						'AND' => [
// 							['Kintaitran.kintaidate >= ' => $paramsDate],
// 							['Kintaitran.kintaidate <= ' => $paramsDate2],
// 						]
// 					],
// 					'fields' => [
// 						'Kintaitran.id',
// 						'Kintaitran.kintaidate',
// 						'Kintaitran.flgmasters_id',
// 					],
// 				]
// 			],
// 			//'recursive' => 2
// 		]);
// 	}

// 	/*
// 	 * Host010s/040s系列データ抽出用：条件なし
// 	 */
// 	public function SelectUserData($paramsDate,$paramsDate2) {
// 		return $this->find('all', [
// 			'order' => [
// 				'User.id' => 'asc',
// 			],
// 			'fields' => [
// 				'User.id',
// 				'User.usernameK',
// 				'User.branchmasters_id',
// 				'User.departmentmasters_id',
// 			],
// 			'contain' => [
// 				'Kintaitran' => [
// 					'conditions' => [
// 						'AND' => [
// 							['Kintaitran.kintaidate >= ' => $paramsDate],
// 							['Kintaitran.kintaidate <= ' => $paramsDate2],
// 						]
// 					],
// 					'fields' => [
// 						'Kintaitran.id',
// 						'Kintaitran.kintaidate',
// 						'Kintaitran.flgmasters_id',
// 					],
// 				],
// 			],
// 		]);
// 	}

	public function SelectUserDriversList($did,$branchmasters_id = null) {
		return $this->find('list', [
			'fields' => ['id', 'usernameK'],
			'conditions' => [
				'zaisekiflg' => 0,
//				'departmentmasters_id' => $did,
//				'OR'=>[
					'branchmasters_id' => $branchmasters_id,
//					'multidriver'=>1
//				],
                'OR'=>[
                	'multidriver'=>1,
                    'AND'=>[
                        'rolemasters_id >='=>30,
                        'rolemasters_id <='=>39
                    ],
                ],
			],
			'order' => ['usernameKK' => 'asc']
		]);
	}

	public function SelectUserDriversListoil($did,$branchmasters_id = null) {
		return $this->find('list', [
			'fields' => ['id', 'usernameK'],
			'conditions' => [
				'zaisekiflg' => 0,
				'departmentmasters_id' => $did,
				'branchmasters_id' => $branchmasters_id,
			    'AND'=>[
			        'rolemasters_id >=' => 28,
			        'rolemasters_id <=' => 39
			    ]
			],
			'order' => ['usernameKK' => 'asc']
		]);
	}
//営業所を配列で渡している
	public function SelectUserDriversListchemi($did,$branchmasters = null,$mybranchmasters_id = null,$branchgroups_id = null) {
		//$this->virtualFields = ['nameandkaisya' => 'concat(User.usernameK ,"/", User.branchmasters_id)'];
		if($mybranchmasters_id == 1){ //本社なら条件を変更
			return $this->find('list', [
				'fields' => ['id', 'usernameK'],
				'conditions' => [
					'zaisekiflg' => 0,
					'AND'=>[
						'rolemasters_id >=' => 28,
						'rolemasters_id <=' => 39
					],
					'Branchmaster.branchgroups_id' => $branchgroups_id
				],
				'contain'=>[
					'Branchmaster.branchname'
				],
				'order' => ['usernameKK' => 'asc']
			]);
		}else{
			return $this->find('list', [
				'fields' => ['id', 'nameandkaisya'],
				'conditions' => [
					'zaisekiflg' => 0,
					'OR'=>[
						'branchmasters_id' => $branchmasters,
						//'multidriver'=>1
					],
					'AND'=>[
						'rolemasters_id >=' => 28,
						'rolemasters_id <=' => 39
					]
				],
				'contain'=>[
					'Branchmaster.branchname'
				],
				'order' => ['usernameKK' => 'asc']
			]);
		}
	}

	public function SelectUserDriversListgas($did,$branchmasters_id = null) {
		return $this->find('list', [
			'fields' => ['id', 'usernameK'],
			'conditions' => [
				'OR'=>[
					[
						'zaisekiflg' => 0,
						'OR'=>[
							'branchmasters_id' => $branchmasters_id,
							'multidriver'=>1
						],
						'rolemasters_id >='=>30,
						'rolemasters_id <='=>39
					],
					[
					   'zaisekiflg'=>0,
						'rolemasters_id'=>39,
					]
				]
			],
			'order' => ['usernameKK' => 'asc']
		]);
	}
	//協力会社用
	public function SelectUserDriversListpartner($branchmasters_id = null) {
		return $this->find('list', [
			'fields' => ['id', 'usernameK'],
			'conditions' => [
				'zaisekiflg' => 0,
				'branchmasters_id' => $branchmasters_id,
				'rolemasters_id >='=>30,
				'rolemasters_id <='=>39
			],
			'order' => ['usernameKK' => 'asc']
		]);
	}

	//branchmasterの内容を配列で渡すように変更
	public function SelectUserDriversList2($branchmasters) {
		return $this->find('list', [
			'fields' => ['User.id', 'User.usernameK'],
			'conditions' => [
				'User.zaisekiflg' => 0,
				'User.branchmasters_id' => $branchmasters,
			],
			'order' => ['User.usernameKK' => 'asc']
		]);
	}

	public function SelectUserMultiDriversList() {
		return $this->find('list', [
			'fields' => ['id', 'usernameK'],
			'conditions' => [
				'zaisekiflg' => 0,
				'multidriver' => 1,
			],
			'order' => ['usernameK' => 'asc']
		]);
	}



/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
// 		'kaisyakey' => array(
// 			'notBlank' => array(
// 				'rule' => array('notBlank'),
// 				'message' => '会社IDを入力してください',
// 				'allowEmpty' => false,
// 				//'required' => false,
// 				//'last' => false, // Stop validation after this rule
// 				//'on' => 'create', // Limit validation to 'create' or 'update' operations
// 			),
// 		),
		'username' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'ユーザーIDを入力してください',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => '重複しています',
			),
		),
		'password' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'パスワードを入力してください',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'kaisyas_id' => [
				'notBlank' => [
				'rule' => ['notBlank'],
				'message' => '会社を選んでください',
				'allowEmpty' => false,
			]
		],
		'rolemasters_id' => [
			'notBlank' => [
				'rule' => ['notBlank'],
				'message' => '権限を選んでください',
				'allowEmpty' => false,
			]
		],
		'usernameK' => [
			'notBlank' => [
				'rule' => ['notBlank'],
				'message' => '名前を入力してください',
				'allowEmpty' => false,
			]
		]
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $hasOne = [
		'Employeepoint' => [
			'className' => 'Employeepoint',
			'foreignKey' => 'users_id',
			'dependent' => true
		]
	];

	public $hasMany = [
		'Kintaitran' => [
			'className' => 'Kintaitran',
			'foreignKey' => 'users_id',
			'dependent' => false
		],
		'Kousokutran' => [
			'className' => 'Kousokutran',
			'foreignKey' => 'users_id',
			'dependent' => false
		],
		'Gasusetran' => [
			'className' => 'Gasusetran',
			'foreignKey' => 'users_id',
			'dependent' => false
		],
		'Kintaitran2' => [
			'className' => 'Kintaitran2',
			'foreignKey' => 'users_id',
			'dependent' => false
		],
	];

	public $belongsTo = array(
		'Kaisya' => array(
			'className' => 'Kaisya',
			'foreignKey' => 'kaisyas_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Rolemaster' => array(
			'className' => 'Rolemaster',
			'foreignKey' => 'rolemasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
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
		],
	);
}
