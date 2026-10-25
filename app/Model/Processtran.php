<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 */
class Processtran extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $name = 'Processtran';
	public $useTable = 'processtrans';
	public $actsAs = ['Containable'];

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	/*
	 * 050s系列用(add)
	 */
	public function SelectProcesstran050s($id) {
		return $this->find('all', [
			'fields' => [
				'Processtran.id',
				'Processtran.ordertrans_id',
				'Processtran.processstartdate',
				'Processtran.processstarttime',
				'Processtran.workddetailcomment',
				'Processtran.container_no',
				'Processtran.ref_no',
			    'Processtran.workplaces_id',
			    'Processtran.pdriver1id',
			    'Processtran.pdriver2id',
				'Processtran.timenote',
			    'PDriver1.usernameK',
			    'PDriver2.usernameK',
			    'Workdetailmaster.workname',
				'Workplace.workplacename',
			    'Workplace.workplaceshortname',
				'Productcontainer.pdconname'
			],
			'conditions' => [
				'AND' => [
					'Processtran.ordertrans_id' => $id,
					'Processtran.delflg' => 0
				]
			],
			'order' => [
			    'Processtran.id',
			    'Processtran.processstartdate',
			    'Processtran.processstarttime',
//				'Processtran.workdetailmasters_id' => 'asc'
			],
			'recursive' => 0
		]);
	}
	public function SelectProcesstran050containers($id) {
		return $this->find('all', [
			'fields' => [
				'Processtran.id',
				'Processtran.ordertrans_id',
				'Processtran.processstartdate',
				'Processtran.processstarttime',
				'Processtran.workddetailcomment',
				'Processtran.container_no',
				'Processtran.ref_no',
			    'Processtran.workplaces_id',
			    'Processtran.pdriver1id',
			    'Processtran.pdriver2id',
				'Processtran.timenote',
			    'PDriver1.usernameK',
			    'PDriver2.usernameK',
			    'Workdetailmaster.workname',
				'Workplace.workplacename',
			    'Workplace.workplaceshortname',
				'Processtran.productdetailmastername',
			],
			'conditions' => [
				'AND' => [
					'Processtran.ordertrans_id' => $id,
					'Processtran.delflg' => 0
				]
			],
			'order' => [
			    'Processtran.id',
			    'Processtran.processstartdate',
			    'Processtran.processstarttime',
			],
			'recursive' => 0
		]);
	}

	/*
	 * 051s系列用
	 */
	public function SelectProcesstran051s($id,$lid,$dlid,$did) {
		return $this->find('all', [
			'fields' => [
				'Processtran.id',
				'Processtran.ordertrans_id',
				'Processtran.workddetailcomment',
				'Processtran.processstartdate',
				'Processtran.processstarttime',
				//'Processtran.processenddate',
				//'Processtran.processendtime',
				//'Processtran.workdetailmasters_id',
				'Workdetailmaster.workname',
				'Workplace.workplaceshortname'
			],
			'conditions' => [
				'AND' => [
					'Ordertran.delflg' => 0,
					'Ordertran.deliverydate <=' => date('Y-m-d'),
					'Ordertran.loadingmasters_id' => $lid,
					'Ordertran.deliverymasters_id' => $dlid,
					'Processtran.delflg' => 0,
					'Ordertran.id !=' => $id
				]
			],
			'order' => [
				'Ordertran.loadingdate' => 'desc',
				'Ordertran.loadingtime' => 'desc',
				'Processtran.processstartdate' => 'asc',
				'Processtran.processstarttime' => 'asc',
				'Processtran.workdetailmasters_id' => 'asc'
			],
			'recursive' => 1
		]);
	}

	public function SelectProcesstran051s2($id,$lid,$dlid,$did) {
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.shippermasters_id',
				'Ordertran.loadingmasters_id',
				'Ordertran.deliverymasters_id',
				'Ordertran.productdetailmasters_id',
			],
			'conditions' => [
				'Ordertran.delflg' => 0,
				'Ordertran.deliverydate <=' => date('Y-m-d'),
				'Ordertran.loadingmasters_id' => $lid,
				'Ordertran.deliverymasters_id' => $dlid,
				'Processtran.delflg' => 0,
				'Ordertran.id !=' => $id,
			    'Ordertran.departmentmasters_id'=>$did,
			],
			'order' => [
				'Ordertran.deliverydate' => 'desc',
				'Ordertran.deliverytime' => 'desc',
				'Processtran.processstartdate' => 'asc',
				'Processtran.processstarttime' => 'asc',
				'Processtran.workdetailmasters_id' => 'asc'
			],
			'group' => ['Processtran.ordertrans_id'],
			'recursive' => 1
		]);
	}

	/*
	 * 2019-09-25 recursive=-1を1に変更した。Co051/copyのため。
	 */
	public function SelectProcesstran051s3($id) {
		return $this->find('all', [
			'conditions' => [
				'AND' => [
					'Processtran.ordertrans_id' => $id,
					'Processtran.delflg' => 0,
				]
			],
			'order' => [
				'Processtran.processstartdate' => 'asc',
				'Processtran.processstarttime' => 'asc',
			],
			'recursive' => 1
		]);
	}
	/*
	 * 040s系列用(運転手)
	 */
	public function SelectProcesstran040_1($uid,$paramsDate){
	    return $this->find('all',[
	        'fields'=>[
	            'Processtran.id',
	            'Processtran.ordertrans_id',
	            'Processtran.processstartdate',
	            'Processtran.processstarttime',
	            'Processtran.workddetailcomment',
	            'Processtran.container_no',
	            'Processtran.ref_no',
	            'Processtran.workplaces_id',
	            'Processtran.workdetailmasters_id',
	            'Workplace.workplacename',
	            'Productcontainer.pdconname',
	            'PDriver1.usernameK',
	            'PDriver2.usernameK',
	            'Workdetailmaster.workname',
	        ],
	        'conditions' => [
	            'AND'=>[
                    'Processtran.pdriver1id' => $uid,
                    'Processtran.delflg' => 0,
                    'Processtran.processstartdate'=>$paramsDate,
	            ]
	        ],
	        'order' => [
	            'Processtran.id'=>'asc'
	        ],
	        'recursive' => 1
	    ]);
	}
	/*
	 * 040s系列用(助手)
	 */
	public function SelectProcesstran040_2($uid,$paramsDate){
	    return $this->find('all',[
	        'fields'=>[
	            'Processtran.id',
	            'Processtran.ordertrans_id',
	            'Processtran.processstartdate',
	            'Processtran.processstarttime',
	            'Processtran.workddetailcomment',
	            'Processtran.container_no',
	            'Processtran.ref_no',
	            'Processtran.workplaces_id',
	            'Processtran.workdetailmasters_id',
	            'Workplace.workplacename',
	            'Productcontainer.pdconname',
	            'PDriver1.usernameK',
	            'PDriver2.usernameK',
	            'Workdetailmaster.workname',
	        ],
	        'conditions' => [
	            'AND'=>[
	                'Processtran.pdriver2id' => $uid,
	                'Processtran.delflg' => 0,
	                'Processtran.processstartdate'=>$paramsDate,
	            ]
	        ],
	        'order' => [
	            'Processtran.id'=>'asc'
	        ],
	        'recursive' => 1
	    ]);
	}

	/*
	 * 080s系列用(edit)
	 */
	public function SelectProcesstran080s($id) {
		return $this->find('all', [
			'fields' => [
				'Processtran.id',
				'Processtran.ordertrans_id',
				'Processtran.processstartdate',
				'Processtran.processstarttime',
				'Processtran.processenddate',
				'Processtran.processendtime',
				'Processtran.workddetailcomment',
				'Processtran.container_no',
				'Processtran.ref_no',
			    'Processtran.workplaces_id',
			    'Processtran.workdetailmasters_id',
			    'Processtran.productdetailmastername',
			    'Processtran.productcontainers_id',
			    'Workplace.workplacename',
			    'PDriver1.usernameK',
			    'PDriver2.usernameK',
				'Productcontainer.pdconname'
			],
			'conditions' => [
				'AND' => [
					'Processtran.ordertrans_id' => $id,
					'Processtran.delflg' => 0
				]
			],
			'contain' => [
				'PDriver1',
			    'PDriver2',
				'Workdetailmaster',
				'Workplace',
				'Productcontainer'
			],
			'order' => [
//				'Processtran.processstartdate' => 'asc',
//				'Processtran.processstarttime' => 'asc'
                'Processtran.id'=>'asc'
			],
			'recursive' => 0
		]);
	}

	/*
	 * Host011s工程データ抽出用（add/edit）
	 */
	public function SelectProcesstran($paramsDate,$uid){
		return $this->find('all', [
			'conditions' => [
				'AND' => [
					['Processtran.pdriver1id' => $uid],
					['Processtran.processstartdate' => $paramsDate],
					['Processtran.delflg' => 0],
				]
			],
			'contain' => [
				'Ordertran' => [
					'Loadingmaster' => [
						'fields' => 'id,loadingshortname',
					],
					'Deliverymaster' => [
						'fields' => 'id,deliveryshortname',
					],
					'Productdetailmaster' => [
						'fields' => 'id,pdshortname',
					],
					'Shippermaster' => [
						'fields' => 'id,shippershortname',
					],
					'Carmaster' => [
						'fields' => 'id,plateno',
					],
					'Processtran' => [
						'conditions' => [
							'AND' => [
								['Processtran.pdriver1id' => $uid],
								['Processtran.processstartdate' => $paramsDate],
								['Processtran.delflg' => 0],
							]
						],
					'order' => [
						'Processtran.processstartdate' => 'asc',
						'Processtran.processstarttime' => 'asc',
					],
						'Workdetailmaster' => [
							'fields' => 'id,workname',
						],
					],
					'order' => [
						'Ordertran.kaisen' => 'asc',
						'Ordertran.loadingdate' => 'asc',
						'Ordertran.loadingtime' => 'asc'
					]
				],
			],
			'group' => ['Processtran.ordertrans_id'],
			'recursive' => 0
		]);
	}
	public function ProcesstranDriver1($paramsDate,$bgid){
		$aa = array_keys($bgid);
	    return $this->find('list', [
	        'fields' => [
	            'Processtran.pdriver1id',
	            'PDriver1.usernameK'
	        ],
	        'conditions' => [
	            'NOT' => [
	                'pdriver1id' => null
	            ],
                'processstartdate' => $paramsDate,
	            'PDriver1.zaisekiflg' => 0,
	            'PDriver1.rolemasters_id >='=>28,
	            'PDriver1.rolemasters_id <='=>39,
	            'PDriver1.branchmasters_id'=>$aa
	        ],
	        'recursive' => 0
	    ]);

	}
	public function ProcesstranDriver2($paramsDate,$bgid){
		$aa = array_keys($bgid);
	    return $this->find('list', [
	        'fields' => [
	            'Processtran.pdriver2id',
	            'PDriver2.usernameK'
	        ],
	        'conditions' => [
	            'NOT' => [
	                'pdriver2id' => null
	            ],
	            'processstartdate' => $paramsDate,
	            'PDriver2.zaisekiflg' => 0,
	            'PDriver2.rolemasters_id >='=>28,
	            'PDriver2.rolemasters_id <='=>39,
	            'PDriver2.branchmasters_id'=>$aa
	        ],
	        'recursive' => 0
	    ]);

	}

	public function SelectProcesstrandriver1($paramsDate,$uid){
	    return $this->find('all', [
	        'conditions' => [
	            'AND' => [
	                ['Processtran.pdriver1id' => $uid],
	                ['Processtran.processstartdate' => $paramsDate],
	                ['Processtran.delflg' => 0],
	            ]
	        ],
	        'contain' => [
	            'PDriver1'=>[
	                'fields'=>'usernameK',
	            ],
	            'PDriver2'=>[
	                'fields'=>'usernameK',
	            ],
	            'Workplace'=>[
	                'fields'=>'workplacename',
	            ],
	            'Ordertran' => [
	                'fields'=>[
	                    'Ordertran.kaisen'
	                ],
	                'Loadingmaster' => [
	                    'fields' => 'id,loadingshortname',
	                ],
	                'Deliverymaster' => [
	                    'fields' => 'id,deliveryshortname',
	                ],
	                'Productdetailmaster' => [
	                    'fields' => 'id,pdshortname',
	                ],
	                'Shippermaster' => [
	                    'fields' => 'id,shippershortname',
	                ],
	                'Carmaster' => [
	                    'fields' => 'id,plateno,trailermasters_id,loadingaccount,trailerteate,branchmasters_id',
	                ],
	                'Workplace1'=>[
	                    'fields'=>'workplacename'
	                ],
	                'Workplace2'=>[
	                    'fields'=>'workplacename'
	                ],
	            ],
	            'Workdetailmaster' => [
	                'fields' => 'id,workname',
	            ],
	        ],
	        'order' => [
	            'Processtran.processstartdate' => 'asc',
	            'Processtran.processstarttime' => 'asc',
	        ],
	        'recursive' => 0
	    ]);
	}
	public function SelectProcesstrandriver2($paramsDate,$uid){
	    return $this->find('all', [
	        'conditions' => [
	            'AND' => [
	                ['Processtran.pdriver2id' => $uid],
	                ['Processtran.processstartdate' => $paramsDate],
	                ['Processtran.delflg' => 0],
	            ]
	        ],
	        'contain' => [
	            'PDriver1'=>[
	                'fields'=>'usernameK',
	            ],
	            'PDriver2'=>[
	                'fields'=>'usernameK',
	            ],
	            'Workplace'=>[
	                'fields'=>'workplacename',
	            ],
	            'Ordertran' => [
	                'fields'=>[
	                    'Ordertran.kaisen'
	                ],
	                'Loadingmaster' => [
	                    'fields' => 'id,loadingshortname',
	                ],
	                'Deliverymaster' => [
	                    'fields' => 'id,deliveryshortname',
	                ],
	                'Productdetailmaster' => [
	                    'fields' => 'id,pdshortname',
	                ],
	                'Shippermaster' => [
	                    'fields' => 'id,shippershortname',
	                ],
	                'Carmaster' => [
	                    'fields' => 'id,plateno,trailermasters_id,loadingaccount,trailerteate,branchmasters_id',
	                ],
	                'Workplace1'=>[
	                    'fields'=>'workplacename'
	                ],
	                'Workplace2'=>[
	                    'fields'=>'workplacename'
	                ],
	            ],
	            'Workdetailmaster' => [
	                'fields' => 'id,workname',
	            ],
	        ],
	        'order' => [
	            'Processtran.processstartdate' => 'asc',
	            'Processtran.processstarttime' => 'asc',
	        ],
	        'recursive' => 0
	    ]);
	}

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
	public $belongsTo = array(
		'Ordertran' => [
			'className' => 'Ordertran',
			'foreignKey' => 'ordertrans_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
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
		/*
		'Areamaster' => [
			'className' => 'Areamaster',
			'foreignKey' => 'areamasters_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
		*/
		'Workdetailmaster' => [
			'className' => 'Workdetailmaster',
			'foreignKey' => 'workdetailmasters_id',
			'conditions' => ['Workdetailmaster.delflg' => 0],
			'fields' => [
				'workname'
			],
			'order' => ''
		],
	    'PDriver1' => [
	        'className' => 'User',
	        'foreignKey' => 'pdriver1id',
	        'conditions' => ['PDriver1.zaisekiflg' => 0],
	        'fields' => [
	            'usernameK'
	        ],
	        'order' => ''
	    ],
	    'PDriver2' => [
	        'className' => 'User',
	        'foreignKey' => 'pdriver2id',
	        'conditions' => ['PDriver2.zaisekiflg' => 0],
	        'fields' => [
	            'usernameK'
	        ],
	        'order' => ''
	    ],
		
	    'Productcontainer' => [
			'className' => 'Productcontainer',
			'foreignKey' => 'productcontainers_id',
			'conditions' => ['Productcontainer.delflg' => 0],
			'fields' => [
				'pdconname',
				'pdconcode'
			],
			'order' => ''
		],
		
		'Workplace' => [
			'className' => 'Workplace',
			'foreignKey' => 'workplaces_id',
			'conditions' => ['Workplace.delflg' => 0],
			'order' => ''
		]
	);

}
