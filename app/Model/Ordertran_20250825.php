<?php
App::uses('AppModel', 'Model');
/**
 * Ordertran Model
 *
 * @property Shippermasters $Shippermasters
 * @property Productdetailmasters $Productdetailmasters
 * @property LoadingmastersHasDeliverymasters $LoadingmastersHasDeliverymasters
 */
class Ordertran extends AppModel {

	public $name = 'Oredertran';
	public $useTable = 'ordertrans';
	public $actsAs = array('Containable');
	//public $actsAs = array('EagerLoader.EagerLoader');

	public function beforeSave($option = []) {
		if(empty($this->id)){
			$this->data[$this->alias]['created_user'] = Configure::read('logged_user_id');
		}
		$this->data[$this->alias]['modified_user'] = Configure::read('logged_user_id');
		return true;
	}

	/*
	 * 20201013 Co000s Test用
	 */
	public function SelectOrdertranCo000s($paramsDate,$paramsDate2,$did,$shipperconditions,$branchconditions) {
		$this->Behaviors->load('EagerLoader.EagerLoader');

		return $this->find('all', [
			'fields' => [
				'Ordertran.carmasters_id',
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
				'Ordertran.departmentmasters_id' => $did,
				$shipperconditions,
				$branchconditions,
				['OR' => [
					'Ordertran.loadingdate >= ' => $paramsDate,
					'Ordertran.deliverydate >= ' => $paramsDate
				]],
				['OR' => [
					'Ordertran.loadingdate <= ' => $paramsDate2,
					'Ordertran.deliverydate <= ' => $paramsDate2
				]]
			],
			'contain' => [
				'Processtran' => [
					'fields' => [
						'id',
						'processstartdate',
						'processstarttime',
						'workddetailcomment',
					],
					'order' => [
					    'processstartdate',
					    'processstarttime',
					    'id'=>'asc',
//						'workdetailmasters_id' => 'asc'
					],
					'Workdetailmaster',
					'Workplace',
				],
			],
			'recursive' => 0
		]);
	}
	public function SelectOrdertranCo003s($paramsDate,$paramsDate2,$did,$branchconditions) {
	    $this->Behaviors->load('EagerLoader.EagerLoader');

	    return $this->find('all', [
	        'fields' => [
	            'Ordertran.carmasters_id',
	            'Ordertran.loadingmasters_id',
	            'Ordertran.deliverymasters_id',
	            'Ordertran.loadingdate',
	            'Ordertran.loadingtime',
	            'Ordertran.deliverydate',
	            'Ordertran.deliverytime',
	            'Ordertran.color',
	            'Ordertran.correctdate',
	            'Ordertran.check1flg',
	            'Ordertran.kaisen',
				'Ordertran.printcompflg'
	        ],
	        'order' => [
	            'Ordertran.loadingdate' => 'asc',
	            'Ordertran.deliverydate' => 'asc',
	            'Ordertran.kaisen' => 'asc',
	            'Ordertran.deliverytime' => 'asc'
	        ],
	        'conditions' => [
	            'Ordertran.delflg' => 0,
	            'Ordertran.departmentmasters_id' => $did,
	            $branchconditions,
	            ['OR' => [
	                'Ordertran.loadingdate >= ' => $paramsDate,
	                'Ordertran.deliverydate >= ' => $paramsDate
	            ]],
	            ['OR' => [
	                'Ordertran.loadingdate <= ' => $paramsDate2,
	                'Ordertran.deliverydate <= ' => $paramsDate2
	            ]]
	        ],
	        'contain' => [
	            'Processtran' => [
	                'fields' => [
	                    'id',
	                    'processstartdate',
	                    'processstarttime',
	                    'workddetailcomment',
						'container_no'
	                ],
	                'order' => [
	                    'id'=>'asc',
	                    'processstartdate',
	                    'processstarttime',
	                    //						'workdetailmasters_id' => 'asc'
	                ],
	                'Workdetailmaster',
	                'Workplace',
	            ],
	        ],
	        'recursive' => 0
	    ]);
	}

	/*
	 * 030s用
	 */
	public function SelectOrdertran030s($paramsDate,$did,$branchconditions) {
		$this->Behaviors->load('EagerLoader.EagerLoader');

		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.branchmasters_id',
			    'Workplace1.workplaceshortname',
			    'Workplace2.workplaceshortname',
			    'Productdetailmaster.pdname',
			    'Ordertran.confirmflg',
			    'Ordertran.confirmdate'
			],
			'conditions' => [
				'AND' => [
				    'OR'=>[
				    'Ordertran.loadingdate' => $paramsDate,
				    'Ordertran.deliverydate' => $paramsDate,
	               ],
				    'Carmaster.departmentmasters_id' => $did,
					'Ordertran.delflg' => 0,
					$branchconditions
				],
			],
			'contain' => [
				'Shippermaster',
				'Carmaster' => [
//					'Containertype'
                    'Trailermaster'
				],
				'Deliverymaster',
				'Productdetailmaster',
				'Driver1',
				'Driver2',
				'Processtran' => [
				    'conditions'=>[
//2020-10-29				        'Processtran.processstartdate'=> $paramsDate
				    ],
					'order' => [
					    'Processtran.id' => 'asc',
					    'Processtran.processstartdate' => 'asc',
						'Processtran.processstarttime' => 'asc',
					],
					'fields' => [
						'Processtran.id',
						'Processtran.processstartdate',
						'Processtran.processstarttime',
						'Processtran.workddetailcomment',
						'Processtran.container_no',
						'Processtran.ref_no',
					    'PDriver1.usernameK',
					    'PDriver2.usernameK',
					    'Processtran.confirmdate'
					],
					'Workdetailmaster',
					'Workplace',
					'Productcontainer',
				    'PDriver1',
				    'PDriver2'
				],
			    'Workplace1',
			    'Workplace2',
			],
			'order' => [
				'Carmaster.plateno' => 'asc',
			    'Ordertran.kaisen' => 'asc'
			],
			'recursive' => 0
		]);
	}

	public function SelectOrdertran030containers($paramsDate,$did,$branchconditions) {
		$this->Behaviors->load('EagerLoader.EagerLoader');

		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.branchmasters_id',
			    'Workplace1.workplaceshortname',
			    'Workplace2.workplaceshortname',
			    'Ordertran.confirmflg',
			    'Ordertran.confirmdate'
			],
			'conditions' => [
				'AND' => [
				    'OR'=>[
				    'Ordertran.loadingdate' => $paramsDate,
				    'Ordertran.deliverydate' => $paramsDate,
	               ],
				    'Carmaster.departmentmasters_id' => $did,
					'Ordertran.delflg' => 0,
					$branchconditions
				],
			],
			'contain' => [
				'Shippermaster',
				'Carmaster' => [
//					'Containertype'
                    'Trailermaster'
				],
				'Deliverymaster',
				'Driver1',
				'Driver2',
				'Processtran' => [
				    'conditions'=>[
//2020-10-29				        'Processtran.processstartdate'=> $paramsDate
				    ],
					'order' => [
					    'Processtran.id' => 'asc',
					    'Processtran.processstartdate' => 'asc',
						'Processtran.processstarttime' => 'asc',
					],
					'fields' => [
						'Processtran.id',
						'Processtran.processstartdate',
						'Processtran.processstarttime',
						'Processtran.workddetailcomment',
						'Processtran.container_no',
						'Processtran.ref_no',
					    'PDriver1.usernameK',
					    'PDriver2.usernameK',
					    'Processtran.confirmdate',
						'Processtran.productdetailmastername'
					],
					'Workdetailmaster',
					'Workplace',
				    'PDriver1',
				    'PDriver2'
				],
			    'Workplace1',
			    'Workplace2',
			],
			'order' => [
				'Carmaster.plateno' => 'asc',
			    'Ordertran.kaisen' => 'asc'
			],
			'recursive' => 0
		]);
	}

	public function SelectOrdertran530s($paramsDate,$did,$branchconditions) {
//	    debug($branchconditions);
	    $this->Behaviors->load('EagerLoader.EagerLoader');

	    return $this->find('all', [
	        'fields' => [
	            'Ordertran.id',
	            'Ordertran.loadingdate',
	            'Ordertran.loadingtime',
	            'Ordertran.deliverydate',
	            'Ordertran.deliverytime',
	            'Ordertran.branchmasters_id',
	            'Workplace1.workplaceshortname',
	            'Workplace2.workplaceshortname',
	            'Productdetailmaster.pdname',
	            'Ordertran.confirmflg',
	            'Ordertran.confirmdate'
	        ],
	        'conditions' => [
                'OR'=>[
                    'Ordertran.loadingdate' => $paramsDate,
                    'Ordertran.deliverydate' => $paramsDate,
                ],
                'Carmaster.departmentmasters_id' => $did,
                'Ordertran.delflg' => 0,
                $branchconditions
	        ],
	        'contain' => [
	            'Shippermaster',
	            'Ordertranimage',
	            'Carmaster' => [
	                'conditions'=>[
	                    $branchconditions
	                ],
	                'Trailermaster'
	            ],
	            'Deliverymaster',
	            'Productdetailmaster',
	            'Driver1',
	            'Driver2',
	            'Processtran' => [
	                'order' => [
	                    'Processtran.processstartdate' => 'asc',
	                    'Processtran.processstarttime' => 'asc',
	                    'Processtran.id' => 'asc',
	                ],
	                'fields' => [
	                    'Processtran.id',
	                    'Processtran.processstartdate',
	                    'Processtran.processstarttime',
	                    'Processtran.workddetailcomment',
	                    'Processtran.container_no',
	                    'Processtran.ref_no',
	                    'PDriver1.usernameK',
	                    'PDriver2.usernameK',
	                    'Processtran.confirmdate'
	                ],
	                'Workdetailmaster',
	                'Workplace',
	                'Productcontainer',
	                'PDriver1',
	                'PDriver2'
	            ],
	            'Workplace1',
	            'Workplace2',
	        ],
	        'order' => [
	            'Carmaster.plateno' => 'asc',
	            'Ordertran.kaisen' => 'asc'
	        ],
	        'recursive' => 0
	    ]);
	}

	/*
	 * 050s系列用
	 */
	public function SelectOrdertran050s($paramsDate,$did,$branchmasters_id,$check1flg) {
	    if($branchmasters_id){
	        return $this->find('all', [
	            'fields' => [
	                'Ordertran.id',
	                'Ordertran.loadingdate',
	                'Ordertran.loadingtime',
	                'Ordertran.deliverydate',
	                'Ordertran.deliverytime',
	                'Ordertran.kaisen',
	                'Ordertran.carmasters_id',
	                'Ordertran.truckkinds_id',
	                'Ordertran.productdetailmasters_id',
	                'Ordertran.check1flg',
					'Ordertran.created_user',
	                'Ordertran.check1_user',
	                'Shippermaster.shippershortname',
	                'Carmaster.plateno',
	                'Carmaster.branchmasters_id',
	                'Productdetailmaster.pdname',
	                'Workplace1.workplaceshortname',
	                'Workplace2.workplaceshortname',
	            ],
	            'conditions' => [
	                'Ordertran.loadingdate >=' => $paramsDate,
	                'Ordertran.departmentmasters_id' => $did,
	                'Carmaster.departmentmasters_id' => $did,
	                'Ordertran.branchmasters_id' => $branchmasters_id,
	                'Ordertran.delflg' => 0,
	                'Ordertran.check1flg' => $check1flg
	            ],
	            'order' => [
	                'Ordertran.shippermasters_id' => 'asc',
	                'Carmaster.plateno' => 'asc',
	                'Ordertran.kaisen' => 'asc',
	            ],
	            'contain'=>[
	                'Shippermaster',
	                'Carmaster',
	                'Productdetailmaster',
	                'Workplace1',
	                'Workplace2',
	                'Processtran'=>[
	                    'order'=>[
	                        'Processtran.id',
	                        'Processtran.processstartdate',
	                        'Processtran.processstarttime',
	                    ],
	                ],
	            ],
	            'recursive' => 1
	        ]);

	    }else{
	        return $this->find('all', [
	            'fields' => [
	                'Ordertran.id',
	                'Ordertran.loadingdate',
	                'Ordertran.loadingtime',
	                'Ordertran.deliverydate',
	                'Ordertran.deliverytime',
	                'Ordertran.kaisen',
	                'Ordertran.carmasters_id',
	                'Ordertran.truckkinds_id',
	                'Ordertran.productdetailmasters_id',
	                'Ordertran.check1flg',
	                'Ordertran.check1_user',
	                'Shippermaster.shippershortname',
	                'Carmaster.plateno',
	                'Carmaster.branchmasters_id',
	                'Productdetailmaster.pdname',
	                'Workplace1.workplaceshortname',
	                'Workplace2.workplaceshortname',
	            ],
	            'conditions' => [
	                'Ordertran.loadingdate >=' => $paramsDate,
	                'Ordertran.departmentmasters_id' => $did,
	                'Carmaster.departmentmasters_id' => $did,
	                'Ordertran.delflg' => 0,
	                'Ordertran.check1flg' => $check1flg
	            ],
	            'order' => [
	                'Ordertran.shippermasters_id' => 'asc',
	                'Carmaster.plateno' => 'asc',
	                'Ordertran.kaisen' => 'asc',
	            ],
	            'contain'=>[
	                'Shippermaster',
	                'Carmaster',
	                'Productdetailmaster',
	                'Workplace1',
	                'Workplace2',
	                'Processtran'=>[
	                    'order'=>[
	                        'Processtran.processstartdate',
	                        'Processtran.processstarttime',
	                        'Processtran.id',
	                    ],
	                ],
	            ],
	            'recursive' => 1
	        ]);
	    }
	}

	/*
	 * co050s系列用
	 */
	public function SelectOrdertranco050s($paramsDate,$did,$branchmasters,$check1flg) {
	        return $this->find('all', [
	            'fields' => [
	                'Ordertran.id',
	                'Ordertran.loadingdate',
	                'Ordertran.loadingtime',
	                'Ordertran.deliverydate',
	                'Ordertran.deliverytime',
	                'Ordertran.kaisen',
	                'Ordertran.carmasters_id',
	                'Ordertran.truckkinds_id',
	                'Ordertran.productdetailmasters_id',
	                'Ordertran.check1flg',
	                'Ordertran.check1_user',
	                'Shippermaster.shippershortname',
	                'Carmaster.plateno',
	                'Carmaster.branchmasters_id',
	                'Productdetailmaster.pdname',
	                'Workplace1.workplaceshortname',
	                'Workplace2.workplaceshortname',
	            ],
	            'conditions' => [
	                'Ordertran.loadingdate >=' => $paramsDate,
	                'Ordertran.departmentmasters_id' => $did,
	                'Carmaster.departmentmasters_id' => $did,
	                'Ordertran.branchmasters_id' => $branchmasters,
	                'Ordertran.delflg' => 0,
	                'Ordertran.check1flg' => $check1flg
	            ],
	            'order' => [
	                'Ordertran.shippermasters_id' => 'asc',
	                'Carmaster.plateno' => 'asc',
	                'Ordertran.kaisen' => 'asc',
	            ],
	            'contain'=>[
	                'Shippermaster',
	                'Carmaster',
	                'Productdetailmaster',
	                'Workplace1',
	                'Workplace2',
	                'Processtran'=>[
	                    'order'=>[
	                        'Processtran.id',
	                        'Processtran.processstartdate',
	                        'Processtran.processstarttime',
	                    ],
	                ],
	            ],
	            'recursive' => 1
	        ]);

	}
	/*
	 * co052s系列用(日別承認済み)
	 * check1flgは常に１
	 */
	public function SelectOrdertranco052s($paramsDate,$did,$branchmasters,$check1flg) {
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.kaisen',
				'Ordertran.carmasters_id',
				'Ordertran.truckkinds_id',
				'Ordertran.productdetailmasters_id',
				'Ordertran.check1flg',
				'Ordertran.check1_user',
				'Shippermaster.shippershortname',
				'Carmaster.plateno',
				'Carmaster.branchmasters_id',
				'Productdetailmaster.pdname',
				'Workplace1.workplaceshortname',
				'Workplace2.workplaceshortname',
			],
			'conditions' => [
				'Ordertran.loadingdate' => $paramsDate,
				'Ordertran.departmentmasters_id' => $did,
				'Ordertran.branchmasters_id' => $branchmasters,
				'Ordertran.delflg' => 0,
				//'Ordertran.check1flg' => $check1flg
			],
			'order' => [
				'Ordertran.shippermasters_id' => 'asc',
				'Carmaster.plateno' => 'asc',
				'Ordertran.kaisen' => 'asc',
			],
			'contain'=>[
				'Shippermaster',
				'Carmaster',
				'Productdetailmaster',
				'Processtran' => [
					'order' => [
					    'Processtran.id' => 'asc',
					    'Processtran.processstartdate' => 'asc',
						'Processtran.processstarttime' => 'asc',
					],
					'fields' => [
						'Processtran.id',
						'Processtran.processstartdate',
						'Processtran.processstarttime',
						'Processtran.workddetailcomment',
						'Processtran.container_no',
						'Processtran.ref_no',
					    'Processtran.confirmdate',
						'Processtran.delflg'
					],
					'Workdetailmaster',
					'Workplace',
					'Productcontainer',
				],
			    'Workplace1',
			    'Workplace2',
			],
			'recursive' => 1
		]);

	}
	/*
	 * co055s系列用(innerno=999だけを抽出)
	 */
	public function SelectOrdertranco055s($paramsDate,$did,$branchmasters) {
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.kaisen',
				'Ordertran.carmasters_id',
				'Ordertran.truckkinds_id',
				'Ordertran.productdetailmasters_id',
				'Ordertran.check1flg',
				'Ordertran.check1_user',
				'Shippermaster.shippershortname',
				'Carmaster.plateno',
				'Carmaster.branchmasters_id',
				'Productdetailmaster.pdname',
				'Workplace1.workplaceshortname',
				'Workplace2.workplaceshortname',
			],
			'conditions' => [
				'Ordertran.loadingdate >=' => $paramsDate,
				'Ordertran.departmentmasters_id' => $did,
				'Carmaster.innerno like' => '999%',
				'Ordertran.branchmasters_id' => $branchmasters,
				'Ordertran.delflg' => 0,
			],
			'order' => [
				'Ordertran.shippermasters_id' => 'asc',
				'Carmaster.plateno' => 'asc',
				'Ordertran.kaisen' => 'asc',
			],
			'contain'=>[
				'Shippermaster',
				'Carmaster',
				'Productdetailmaster',
				'Workplace1',
				'Workplace2',
				'Processtran'=>[
					'order'=>[
						'Processtran.processstartdate',
						'Processtran.processstarttime',
						'Processtran.id',
					],
				],
			],
			'recursive' => 1
		]);

	}

/*
	 * 050s系列用（add）
	 */
	public function SelectOrdertran050s2($id) {
		return $this->find('first', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.productnote',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.kaisen',
				'Ordertran.loadingnote',
				'Ordertran.deliverynote',
				'Ordertran.loadingmasters_id',
				'Ordertran.deliverymasters_id',
			    'Ordertran.created_user',
			    'Ordertran.modified_user',
			    'Ordertran.check1flg',
			    'Ordertran.check1_user',
			    'Shippermaster.shippershortname',
				'Carmaster.plateno',
			    'Carmaster.branchmasters_id',
				'Productdetailmaster.pdname',
				'Productcontainer.pdconname',
			    'Workplace1.workplaceshortname',
			    'Workplace2.workplaceshortname',
			],
			'conditions' => [
				'AND' => [
					'Ordertran.id' => $id,
					'Ordertran.delflg' => 0,
				],
			],
		    'contain'=>[
		        'Shippermaster',
		        'Carmaster',
		        'Productdetailmaster',
		        'Productcontainer',
		        'Workplace1',
		        'Workplace2',
				'Ordertranimage',
		        'Processtran'=>[
		            'order'=>[
		                'Processtran.id',
		                'Processtran.processstartdate',
		                'Processtran.processstarttime',
		            ],
		        ],
		    ],
		    'recursive' => 1
		]);
	}

	/*
	 * 050s系列用（pdf）
	 */
	public function SelectOrdertran050spdf($paramsDate,$did,$branchmasters_id) {
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.kaisen',
			    'Ordertran.carmasters_id',
				'Ordertran.productdetailmasters_id',
				'Carmaster.plateno',
			    'Carmaster.branchmasters_id',
				'Productdetailmaster.pdname',
			    'Workplace1.workplaceshortname',
			    'Workplace2.workplaceshortname',
			],
			'conditions' => [
                'OR'=>[
                    'Ordertran.loadingdate' => $paramsDate,
                    'Ordertran.deliverydate' => $paramsDate,
                ],
			    'Ordertran.departmentmasters_id' => $did,
				'Carmaster.departmentmasters_id' => $did,
				'Ordertran.branchmasters_id' => $branchmasters_id,
				'Ordertran.delflg' => 0,
			],
			'order' => [
				'Carmaster.plateno' => 'asc',
				'Productdetailmaster.pdname' => 'asc',
				'Ordertran.loadingdate' => 'asc',
				'Ordertran.loadingtime' => 'asc',
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
				'Ordertran.kaisen' => 'asc',
			],
			'recursive' => 0
		]);
	}
	public function SelectOrdertran050containerspdf($paramsDate,$did,$branchmasters_id) {
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.kaisen',
			    'Ordertran.carmasters_id',
				'Carmaster.plateno',
			    'Carmaster.branchmasters_id',
			    'Workplace1.workplaceshortname',
			    'Workplace2.workplaceshortname',
			],
			'conditions' => [
                'OR'=>[
                    'Ordertran.loadingdate' => $paramsDate,
                    'Ordertran.deliverydate' => $paramsDate,
                ],
			    'Ordertran.departmentmasters_id' => $did,
				'Carmaster.departmentmasters_id' => $did,
				'Ordertran.branchmasters_id' => $branchmasters_id,
				'Ordertran.delflg' => 0,
			],
			'order' => [
				'Carmaster.plateno' => 'asc',
				'Ordertran.loadingdate' => 'asc',
				'Ordertran.loadingtime' => 'asc',
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
				'Ordertran.kaisen' => 'asc',
			],
			'recursive' => 0
		]);
	}

	/*
	 * 051s系列用(copy)
	 */
	/*
	public function SelectOrdertran051s2($id) {
		return $this->find('first', [
			'fields' => [
				'Ordertran.id',
			],
			'conditions' => [
				'Ordertran.id' => $id,
			],
			'contain' => [
				'Processtran' => [
					'conditions' => [
						'OR' => [
							['Processtran.delflg' => ''],
							['Processtran.delflg' => 0]
						]
					],
					'order' => [
						'Processtran.processstartdate' => 'asc',
						'Processtran.processstarttime' => 'asc'
					]
				]
			],
			'recursive' => 0,
		]);
	}
	*/

	public function SelectOrdertran051s($id) {
		return $this->find('first', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
			],
			'conditions' => [
				'Ordertran.id' => $id,
			],
			'recursive' => 0,
		]);
	}
	/*
	 * co050s系列用
	 */
	public function SelectOrdertranco056s($paramsDate,$did,$branchmasters) {
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.kaisen',
				'Ordertran.carmasters_id',
				'Ordertran.truckkinds_id',
				'Ordertran.productdetailmasters_id',
				'Ordertran.check1flg',
				'Ordertran.check1_user',
				'Ordertran.billconfirmdate',
				'Ordertran.cancelper',
				'Ordertran.cancelprice',
				'Shippermaster.shippershortname',
				'Carmaster.plateno',
				'Carmaster.branchmasters_id',
				'Productdetailmaster.pdname',
				'Workplace1.workplaceshortname',
				'Workplace2.workplaceshortname',
			],
			'conditions' => [
				'Ordertran.loadingdate >=' => $paramsDate,
				'Ordertran.departmentmasters_id' => $did,
				'Carmaster.departmentmasters_id' => $did,
				'Ordertran.branchmasters_id' => $branchmasters,
				'Ordertran.delflg' => 3
			],
			'order' => [
				'Ordertran.shippermasters_id' => 'asc',
				'Carmaster.plateno' => 'asc',
				'Ordertran.kaisen' => 'asc',
			],
			'contain'=>[
				'Shippermaster',
				'Carmaster',
				'Productdetailmaster',
				'Workplace1',
				'Workplace2',
				'Processtran'=>[
					'order'=>[
						'Processtran.id',
						'Processtran.processstartdate',
						'Processtran.processstarttime',
					],
				],
			],
			'recursive' => 1
		]);

	}

	/*
	 * 080s系列データ抽出用
	 */
	public function SelectOrdertran080s($paramsDate,$paramsDate2,$did,$shipperconditions,$flgconditions,$branchmasters_id){
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
			    'Ordertran.correctflg',
			    'Ordertran.amount',
			    'Ordertran.price',
			    'Shippermaster.shippershortname',
			    'Deliverymaster.deliveryshortname',
			    'Loadingmaster.loadingshortname',
			    'Carmaster.plateno',
				'Productdetailmaster.pdname',
				'Truckkind.id',
			    'Productcontainer.pdconname',
			    'Workplace1.workplaceshortname',
			    'Workplace2.workplaceshortname',
			],
			'conditions' => [
				'AND' => [
					['Ordertran.deliverydate >=' => $paramsDate],
					['Ordertran.deliverydate <=' => $paramsDate2],
					$shipperconditions,
					$flgconditions,
					['Ordertran.branchmasters_id' => $branchmasters_id],
					['Ordertran.departmentmasters_id' => $did],
					['Ordertran.delflg' => 0],
				]
			],
			'contain' => [
				'Shippermaster',
				'Carmaster',
				'Deliverymaster',
			    'Loadingmaster',
				'Productdetailmaster',
			    'Productcontainer',
				'Processtran',
				'Truckkind',
			    'Workplace1',
			    'Workplace2',
			],
			'order' => [
//				'Ordertran.deliverydate' => 'asc',
//				'Ordertran.deliverytime' => 'asc',
                'Ordertran.id'=>'asc',
				'Carmaster.plateno' => 'asc'
				//'Truckkind.id' => 'asc',
			],
			'recursive' => 1
		]);
	}
	/*
	 * 080 index用
	 */
	public function SelectOrdertran080_index($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions){
	    return $this->find('all', [
	        'fields' => [
	            'Ordertran.id',
	            'Ordertran.loadingdate',
	            'Ordertran.loadingtime',
	            'Ordertran.deliverydate',
	            'Ordertran.deliverytime',
	            'Ordertran.correctflg',
	            'Ordertran.amount',
	            'Ordertran.price',
	            'Shippermaster.shippershortname',
	            'Deliverymaster.deliveryshortname',
	            'Loadingmaster.loadingshortname',
	            'Carmaster.plateno',
	            'Productdetailmaster.pdname',
	            'Truckkind.id',
	            'Productcontainer.pdconname',
	            'Workplace1.workplaceshortname',
	            'Workplace2.workplaceshortname',
	        ],
	        'conditions' => [
	            'AND' => [
	                ['Ordertran.deliverydate >=' => $paramsDate],
	                ['Ordertran.deliverydate <=' => $paramsDate2],
	                $shipperconditions,
					$cidconditions,
					['Ordertran.branchmasters_id' => $selectbid],
	                ['Ordertran.departmentmasters_id' => $did],
	                ['Ordertran.delflg' => 0],
	                ['Ordertran.confirmflg' => 1],
	            ]
	        ],
	        'contain' => [
	            'Shippermaster',
	            'Carmaster',
	            'Deliverymaster',
	            'Loadingmaster',
	            'Productdetailmaster',
	            'Productcontainer',
	            'Truckkind',
	            'Workplace1',
	            'Workplace2',
	        ],
	        'order' => [
	        //				'Ordertran.deliverydate' => 'asc',
	        //				'Ordertran.deliverytime' => 'asc',
	            'Ordertran.id'=>'asc',
	            'Carmaster.plateno' => 'asc'
	            //'Truckkind.id' => 'asc',
	        ],
	        'recursive' => 1
	    ]);
	}
	public function SelectOrdertran080_index_container($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions){
	    return $this->find('all', [
	        'fields' => [
	            'Ordertran.id',
	            'Ordertran.loadingdate',
	            'Ordertran.loadingtime',
	            'Ordertran.deliverydate',
	            'Ordertran.deliverytime',
	            'Ordertran.correctflg',
	            'Ordertran.amount',
	            'Ordertran.price',
				'Ordertran.billconfirmdate',
				'Ordertran.billconfirmflg',
				'Ordertran.taxflg',
	            'Shippermaster.shippershortname',
	            'Deliverymaster.deliveryshortname',
	            'Loadingmaster.loadingshortname',
	            'Carmaster.plateno',
				'Carmaster.branchmasters_id',
	            'Truckkind.id',
	            'Workplace1.workplaceshortname',
	            'Workplace2.workplaceshortname',
				'Tantomaster.tantoname'
	        ],
	        'conditions' => [
	            'AND' => [
	                ['Ordertran.deliverydate >=' => $paramsDate],
	                ['Ordertran.deliverydate <=' => $paramsDate2],
	                $shipperconditions,
					$cidconditions,
					['Ordertran.branchmasters_id' => $selectbid],
	                ['Ordertran.departmentmasters_id' => $did],
	                ['Ordertran.delflg' => 0],
	            ]
	        ],
	        'contain' => [
	            'Shippermaster',
	            'Carmaster',
	            'Deliverymaster',
	            'Loadingmaster',
	            'Truckkind',
	            'Workplace1',
	            'Workplace2',
				'Tantomaster'
	        ],
	        'order' => [
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
	            //'Ordertran.id'=>'asc',
	            //'Carmaster.plateno' => 'asc'
	            //'Truckkind.id' => 'asc',
	        ],
	        'recursive' => 1
	    ]);
	}
	//Ch用
	//請求日で抽出
	public function SelectOrdertran081_index($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions){
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.correctflg',
				'Ordertran.amount',
				'Ordertran.price',
				'Ordertran.billconfirmdate',
				'Ordertran.billconfirmflg',
				'Shippermaster.shippershortname',
				'Deliverymaster.deliveryshortname',
				'Loadingmaster.loadingshortname',
				'Carmaster.plateno',
				'Truckkind.id',
				'Workplace1.workplaceshortname',
				'Workplace2.workplaceshortname',
				'Tantomaster.tantoname'
			],
			'conditions' => [
				'AND' => [
					['Ordertran.billconfirmdate >=' => $paramsDate],
					['Ordertran.billconfirmdate <=' => $paramsDate2],
					$shipperconditions,
					$cidconditions,
					['Ordertran.branchmasters_id' => $selectbid],
					['Ordertran.departmentmasters_id' => $did],
					['Ordertran.delflg' => [0,3]],
				]
			],
			'contain' => [
				'Shippermaster',
				'Carmaster',
				'Deliverymaster',
				'Loadingmaster',
				'Truckkind',
				'Workplace1',
				'Workplace2',
				'Tantomaster'
			],
			'order' => [
				'Ordertran.carmasters_id' => 'asc',
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
			],
			'recursive' => 1
		]);
	}
	//請求日で抽出(csv用)
	public function SelectOrdertran081_csv($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions){
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.billconfirmdate',
				'Ordertran.billconfirmflg',
				'Ordertran.delflg',
				'Ordertran.cancelprice',
				'Carmaster.plateno',
				'Tantomaster.tantoname'
			],
			'conditions' => [
				'AND' => [
					['Ordertran.billconfirmdate >=' => $paramsDate],
					['Ordertran.billconfirmdate <=' => $paramsDate2],
					$shipperconditions,
					$cidconditions,
					['Ordertran.branchmasters_id' => $selectbid],
					['Ordertran.departmentmasters_id' => $did],
					['Ordertran.delflg' => [0,3]],
				]
			],
			'contain' => [
				'Carmaster',
				'Tantomaster',
				'Processtran' => [
					'order' => [
						'Processtran.id' => 'asc',
						'Processtran.processstartdate' => 'asc',
						'Processtran.processstarttime' => 'asc',
					],
					'fields' => [
						'Processtran.id',
						'Processtran.container_no',
						'Processtran.ref_no',
						'Processtran.productdetailmastername',
						'Processtran.productcontainers_id'
					],
					'conditions'=>[
						'Processtran.delflg'=>0
					],
				],
			],
			'order' => [
				'Ordertran.carmasters_id' => 'asc',
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
			],
			'recursive' => 1
		]);
	}
	//請求日で抽出(ordercar用)
	public function SelectOrdertran081_ordercar($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions){
		return $this->find('all', [
			'fields' => [
				'Carmaster.plateno',
				'sum(Ordertran.price) as sumprice'
			],
			'conditions' => [
				'AND' => [
					['Ordertran.billconfirmdate >=' => $paramsDate],
					['Ordertran.billconfirmdate <=' => $paramsDate2],
					$shipperconditions,
					$cidconditions,
					['Ordertran.branchmasters_id' => $selectbid],
					['Ordertran.departmentmasters_id' => $did],
					['Ordertran.delflg' => [0,3]],
				]
			],
			'group' => [
				'Ordertran.carmasters_id'
			],
			'recursive' => 0
		]);
	}

	

	//請求日で抽出
	public function SelectOrdertran081_index_container($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions){
	    return $this->find('all', [
	        'fields' => [
	            'Ordertran.id',
	            'Ordertran.loadingdate',
	            'Ordertran.loadingtime',
	            'Ordertran.deliverydate',
	            'Ordertran.detailbilldate',
	            'Ordertran.correctflg',
	            'Ordertran.amount',
	            'Ordertran.price',
				'Ordertran.billconfirmdate',
				'Ordertran.billconfirmflg',
				'Ordertran.delflg',
				'Ordertran.cancelper',
				'Ordertran.cancelprice',
	            'Shippermaster.shippershortname',
	            'Deliverymaster.deliveryshortname',
	            'Loadingmaster.loadingshortname',
	            'Carmaster.plateno',
	            'Truckkind.id',
	            'Workplace1.workplaceshortname',
	            'Workplace2.workplaceshortname',
				'Tantomaster.tantoname'
	        ],
	        'conditions' => [
	            'AND' => [
	                ['Ordertran.billconfirmdate >=' => $paramsDate],
	                ['Ordertran.billconfirmdate <=' => $paramsDate2],
	                $shipperconditions,
					$cidconditions,
					['Ordertran.branchmasters_id' => $selectbid],
	                ['Ordertran.departmentmasters_id' => $did],
	                ['Ordertran.delflg' => [0,3]],
	            ]
	        ],
	        'contain' => [
	            'Shippermaster',
	            'Carmaster',
	            'Deliverymaster',
	            'Loadingmaster',
	            'Truckkind',
	            'Workplace1',
	            'Workplace2',
				'Tantomaster'
	        ],
	        'order' => [
				'Ordertran.carmasters_id' => 'asc',
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
	        ],
	        'recursive' => 1
	    ]);
	}
	//請求日で抽出(csv用)
	public function SelectOrdertran081_csv_container($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions){
	    return $this->find('all', [
	        'fields' => [
	            'Ordertran.id',
	            'Ordertran.loadingdate',
	            'Ordertran.deliverydate',
	            'Ordertran.detailbilldate',
				'Ordertran.billconfirmdate',
				'Ordertran.billconfirmflg',
				'Ordertran.delflg',
				'Ordertran.cancelprice',
	            'Carmaster.plateno',
				'Tantomaster.tantoname'
	        ],
	        'conditions' => [
	            'AND' => [
	                ['Ordertran.billconfirmdate >=' => $paramsDate],
	                ['Ordertran.billconfirmdate <=' => $paramsDate2],
	                $shipperconditions,
					$cidconditions,
					['Ordertran.branchmasters_id' => $selectbid],
	                ['Ordertran.departmentmasters_id' => $did],
	                ['Ordertran.delflg' => [0,3]],
	            ]
	        ],
	        'contain' => [
	            'Carmaster',
				'Tantomaster',
				'Processtran' => [
					'order' => [
					    'Processtran.id' => 'asc',
					    'Processtran.processstartdate' => 'asc',
						'Processtran.processstarttime' => 'asc',
					],
					'fields' => [
						'Processtran.id',
						'Processtran.container_no',
						'Processtran.ref_no',
						'Processtran.productdetailmastername',
						'Processtran.productcontainers_id'
					],
					'conditions'=>[
						'Processtran.delflg'=>0
					],
				],
	        ],
	        'order' => [
				'Ordertran.carmasters_id' => 'asc',
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
	        ],
	        'recursive' => 1
	    ]);
	}
	//請求日で抽出(ordercar用)
	public function SelectOrdertran081_ordercar_container($paramsDate,$paramsDate2,$did,$shipperconditions,$selectbid,$cidconditions){
	    return $this->find('all', [
	        'fields' => [
				'Carmaster.plateno',
				'sum(Ordertran.price) as sumprice'
	        ],
	        'conditions' => [
	            'AND' => [
	                ['Ordertran.billconfirmdate >=' => $paramsDate],
	                ['Ordertran.billconfirmdate <=' => $paramsDate2],
	                $shipperconditions,
					$cidconditions,
					['Ordertran.branchmasters_id' => $selectbid],
	                ['Ordertran.departmentmasters_id' => $did],
	                ['Ordertran.delflg' => [0,3]],
	            ]
	        ],
			'group' => [
				'Ordertran.carmasters_id'
			],
	        'recursive' => 0
	    ]);
	}

	//課税対象(tax_flg=0)　免税（tax_flg=1) 20250304 混在で出すので削除した
	//20240706 tantomasterを外してみた。detailbilldateを追加した
	public function SelectOrdertran200_index_container($paramsDate,$paramsDate2,$did,$shipperconditions,$carconditions,$branchmasterconditions){
		return $this->find('all', [
	        'fields' => [
	            'Ordertran.id',
	            'Ordertran.loadingdate',
	            'Ordertran.loadingtime',
	            'Ordertran.deliverydate',
	            'Ordertran.deliverytime',
	            'Ordertran.correctflg',
	            'Ordertran.amount',
	            'Ordertran.price',
				'Ordertran.branchmasters_id',
	            'Ordertran.carmasters_id',
				'Ordertran.invoice_flg',
				'Ordertran.tantomasters_id',
				'Ordertran.delflg',
				'Ordertran.cancelper',
				'Ordertran.cancelprice',
				'Ordertran.billconfirmdate',
				'Ordertran.detailbilldate',
				'Ordertran.taxflg',
				'Tantomaster.tantoname',
	            'Shippermaster.shippershortname',
	            'Deliverymaster.deliveryshortname',
	            'Loadingmaster.loadingshortname',
	            'Carmaster.plateno',
	            'Truckkind.id',
	            'Workplace1.workplaceshortname',
	            'Workplace2.workplaceshortname',
	        ],
	        'conditions' => [
	            'AND' => [
	                ['Ordertran.billconfirmdate >=' => $paramsDate],
	                ['Ordertran.billconfirmdate <=' => $paramsDate2],
	                $shipperconditions,
					$branchmasterconditions,
					$carconditions,
	                ['Ordertran.departmentmasters_id' => $did],
//	                ['Ordertran.carmasters_id' => $cid],
//	                ['Ordertran.correctflg' => 1],
					'OR'=>[
						['Ordertran.delflg' => 0],
						['Ordertran.delflg' => 3],
					]
				]
	        ],
	        'contain' => [
	            'Shippermaster',
	            'Carmaster',
	            'Deliverymaster',
	            'Loadingmaster',
	            'Truckkind',
	            'Workplace1',
	            'Workplace2',
				'Processtran',
				'Tantomaster'
	        ],
	        'order' => [
	        	'Ordertran.deliverydate' => 'asc',
	        	'Ordertran.deliverytime' => 'asc',
	            'Ordertran.id'=>'asc',
	            'Carmaster.plateno' => 'asc'
	            //'Truckkind.id' => 'asc',
	        ],
	        'recursive' => 1
	    ]);
	}

	/*
	 * 080s系列用（edit）
	 */
	 public function SelectOrdertran080s2($id) {
		return $this->find('first', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.productnote',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.kaisen',
				'Ordertran.loadingnote',
				'Ordertran.deliverynote',
				'Ordertran.loadingmasters_id',
				'Ordertran.deliverymasters_id',
				'Ordertran.correctdate',
				'Ordertran.correctamount',
				'Ordertran.price',
			    'Ordertran.amount',
				'Ordertran.taxflg',
				'Ordertran.shippermasters_id',
				'Ordertran.productcontainers_id',
				'Ordertran.billconfirmdate',
				'Ordertran.detailbilldate',
				'Ordertran.billconfirmflg',
				'Ordertran.tantomasters_id',
				'Shippermaster.shippershortname',
				// 'Deliverymaster.deliveryname',
				// 'Loadingmaster.loadingname',
				'Carmaster.plateno',
			    'Carmaster.branchmasters_id',
				'Productdetailmaster.pdname',
			    'Productcontainer.pdconname',
			    'Workplace1.workplaceshortname',
			    'Workplace2.workplaceshortname',
			],
			'conditions' => [
				'AND' => [
					'Ordertran.id' => $id,
					'Ordertran.delflg' => 0,
				],
			],
			'recursive' => 0
		]);
	}

	/*
	 * 080s系列用（価格抜き出し）
	 */
	public function ExtractionPrice($id) {
		return $this->find('first', [
			'fields' => ['price'],
			'conditions' => [
				'Ordertran.id' => $id,
				'Ordertran.delflg' => 0
			],
			'recursive' => -1
		]);
	}

	/*
	 * 090s系列データ抽出用
	 */
	public function SelectOrdertran090s($paramsDate,$did,$branchconditions){
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.driver1id',
				'Ordertran.driver2id',
				//'Shippermaster.shippershortname',
				//'Loadingmaster.loadingshortname',
				//'Deliverymaster.deliveryshortname',
				'Carmaster.plateno',
				'Productdetailmaster.pdname',
				//'Truckkind.id',
				'Carmaster.departmentmasters_id',
				'Ordertran.kaisen',
				'Ordertran.confirmflg',
			    'Workplace1.workplaceshortname',
			    'Workplace2.workplaceshortname',
			],
			'conditions' => [
				'AND' => [
					['Ordertran.deliverydate' => $paramsDate],
					$branchconditions,
					['Ordertran.departmentmasters_id' => $did],
//					['Ordertran.confirmflg' => 0],
					['Ordertran.delflg' => 0],
				],
			],
			'contain' => [
				'Shippermaster',
				'Carmaster',
				'Loadingmaster',
				'Deliverymaster',
				'Productdetailmaster',
				'Truckkind',
				'Processtran' => [
					'order' => [
						'Processtran.processstartdate' => 'asc',
						'Processtran.processstarttime' => 'asc',
					],
					'fields' => [
						'Processtran.id',
						'Processtran.pdriver1id',
					],
// 					'conditions'=>[
// 						'Processtran.delflg' => 0,
// 					]
				],
			    'Workplace1',
			    'Workplace2',
			],
			'order' => [
				'Carmaster.plateno' => 'asc',
				'Ordertran.loadingdate' => 'asc',
				'Ordertran.loadingtime' => 'asc',
				'Ordertran.kaisen' => 'asc'
			],
			'recursive' => 1
		]);
	}

	/*
	 * 091sデータ抽出用
	 */
	public function SelectOrdertran091s ($paramsDate,$did,$branchconditions) {
		return $this->find('all', [
			'conditions' => [
				'AND' => [
					[
					    'OR'=>[
					        'Ordertran.loadingdate' => $paramsDate,
					        'Ordertran.deliverydate' => $paramsDate
					    ],

					],
					['Ordertran.departmentmasters_id' => $did],
					$branchconditions,
					['Ordertran.delflg !=' => 1],
					['Ordertran.confirmflg !=' => 0],
					'OR' => [
						['Ordertran.dr1confirmflg !=' => 1],
						['Ordertran.dr2confirmflg !=' => 1]
					]
				],
			],
			'fields' => [
				'Ordertran.id',
				'Ordertran.driver1id',
				'Ordertran.driver2id',
				'Ordertran.dr1confirmflg',
				'Ordertran.dr1confirmdate',
				'Ordertran.dr2confirmflg',
				'Ordertran.dr2confirmdate',
				'Ordertran.dr1mailflg',
				'Ordertran.dr2mailflg',
				'Carmaster.plateno',
				'Productdetailmaster.pdname',
				'Driver1.usernameK',
				'Driver2.usernameK'
			],
			'order' => [
				'Carmaster.plateno' => 'asc'
			]
		]);
	}

	/*
	 * Co091sメールデータ抽出用
	 */
	public function SelectMailOrdertran($paramsDate,$did,$branchconditions){
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Driver1.usernameK',
				'Driver1.useremail',
				'Driver2.usernameK',
				'Driver2.useremail',
			],
			'conditions' => [
				'AND' => [
					['Ordertran.deliverydate' => $paramsDate->format('Y-m-d')],
					['Ordertran.departmentmasters_id' => $did],
					$branchconditions,
					['Ordertran.driver1id !=' => null],
					//['Ordertran.driver2id !=' => null],
					['Ordertran.delflg !=' => 1],
					['Ordertran.confirmflg' => 0],
				],
			],
			'order' => [
				'Ordertran.carmasters_id' => 'asc',
				'Ordertran.deliverytime' => 'asc',
			],
			'recursive' => 0,
		]);
	}

	/*
	 * 110s系列データ抽出用
	 */
	public function SelectOrdertran110s($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id) {
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.correctflg',
			    'Ordertran.invoice_flg',
				'Shippermaster.shippershortname',
				'Deliverymaster.deliveryshortname',
				'Carmaster.plateno',
				'Productdetailmaster.pdname',
				'Truckkind.id',
			    'Workplace1.workplacename',
			    'Workplace2.workplacename',
			],
			'conditions' => [
				'AND' => [
 					['Ordertran.deliverydate >=' => $paramsDate],
 					['Ordertran.deliverydate <=' => $paramsDate2],
 					['Ordertran.departmentmasters_id' => $did],
					$shipperconditions,
					$productconditions,
					$carconditions,
					['Ordertran.branchmasters_id' => $branchmasters_id],
					['Ordertran.correctflg' => 1],
					['Ordertran.delflg' => 0],
				]
			],
			'order' => [
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
				'Truckkind.id' => 'asc',
			],
			'recursive' => 1
		]);
	}
	public function SelectOrdertranPricemaster110s($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id) {
	    return $this->find('all', [
	        'fields' => [
	            'Ordertran.id',
	            'Ordertran.shippermasters_id',
	            'Ordertran.correctamount',
	            'Ordertran.price',
	            'Ordertran.deliverydate',
	            'Workplace1.workplacename',
	            'Workplace2.workplacename',
	        ],
	        'conditions' => [
	            'AND' => [
	                ['Ordertran.deliverydate >=' => $paramsDate->format('Y-m-d')],
	                ['Ordertran.deliverydate <=' => $paramsDate2->format('Y-m-d')],
	                ['Ordertran.departmentmasters_id' => $did],
	                $shipperconditions,
	                $productconditions,
					$carconditions,
					['Ordertran.branchmasters_id' => $branchmasters_id],
	                ['Ordertran.correctflg' => 1],
	                ['Ordertran.invoice_flg !=' => 1],
	                ['Ordertran.delflg' => 0],
	            ]
	        ],
	        'contain'=>[
	            'Workplace1',
	            'Workplace2',
	            'Price'=>[
	                'fields'=>[
	                    'pricename',
	                    'keihiflg',
	                    'price',
	                ]
	            ],
	            'Processtran'=>[
	                'fields'=>[
	                    'container_no',
	                    'ref_no',
	                    'productcontainers_id'
	                ]
	            ],
	        ],
	        'order' => [
	            'Ordertran.deliverydate' => 'asc',
	            'Ordertran.deliverytime' => 'asc',
	        ],
	        'recursive' => 1
	    ]);
	}

	public function SelectOrdertran110s2($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id) {
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.productdetailmasters_id',
				'Ordertran.shippermasters_id',
				'Ordertran.correctamount',
				'Ordertran.price',
				'Productdetailmaster.id',
				'Productdetailmaster.pdname',
				'Productdetailmaster.pdshortname',
				'Productdetailmaster.pdcode',
			    'Workplace1.workplacename',
			    'Workplace2.workplacename',
			],
			'conditions' => [
				'AND' => [
					['Ordertran.deliverydate >=' => $paramsDate->format('Y-m-d')],
					['Ordertran.deliverydate <=' => $paramsDate2->format('Y-m-d')],
					['Ordertran.departmentmasters_id' => $did],
					$shipperconditions,
					$productconditions,
					$carconditions,
					['Ordertran.branchmasters_id' => $branchmasters_id],
				    ['Ordertran.correctflg' => 1],
				    ['Ordertran.invoice_flg !=' => 1],
				    ['Ordertran.delflg' => 0],
				]
			],
			'order' => [
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
			],
			'recursive' => 1
		]);
	}

	public function SelectOrdertran110s3($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id) {
		return $this->find('list', [
			'fields' => [
				'Ordertran.shippermasters_id',
				'Shippermaster.shippername'
			],
			'conditions' => [
				'AND' => [
					['Ordertran.deliverydate >=' => $paramsDate->format('Y-m-d')],
					['Ordertran.deliverydate <=' => $paramsDate2->format('Y-m-d')],
					['Ordertran.departmentmasters_id' => $did],
					$shipperconditions,
					$productconditions,
					$carconditions,
					['Ordertran.branchmasters_id' => $branchmasters_id],
					['Ordertran.correctflg' => 1],
					['Ordertran.delflg' => 0],
				]
			],
			'order' => ['Shippermaster.shippercode' => 'asc'],
			'recursive' => 1
		]);
	}

	/*
	 * 運転手情報で抽出
	 */
	public function SelectOrdertran040s1($uid,$paramsDate){
	    return $this->find('all',[
	        'conditions' => [
	            'AND' => [
	                'OR'=>[
	                    'Ordertran.loadingdate' => $paramsDate,
	                    'Ordertran.deliverydate' => $paramsDate,
	                ],
	                'Ordertran.delflg' => 0,
	                'Ordertran.driver1id'=>$uid
	            ],
	        ],
	        'contain' => [
	            'Loadingmaster' => [
	                'fields' => [
	                    'id',
	                    'loadingname',
	                ],
	                'Loadingmasterimage'
	            ],
	            'Deliverymaster' => [
	                'fields' => [
	                    'id',
	                    'deliveryname',
	                ],
	                'Deliverymasterimage'
	            ],
	            'Productdetailmaster' => [
	                'fields' => [
	                    'id',
	                    'pdname'
	                ]
	            ],
	            'Shippermaster' => [
	                'fields' => [
	                    'id',
	                    'shippershortname'
	                ]
	            ],
	            'Processtran' => [
	                'order' => [
//	                    'Processtran.processstartdate' => 'asc',
//	                    'Processtran.processstarttime' => 'asc',
                        'id'=>'asc'
	                ],
	                'fields' => [
	                    'Processtran.id',
	                    'Processtran.workdetailmasters_id',
	                    'Processtran.workddetailcomment',
	                    'Processtran.processstartdate',
	                    'Processtran.processstarttime',
	                    'Processtran.processenddate',
	                    'Processtran.processendtime',
	                    'Processtran.workplaces_id',
	                    'Processtran.pdriver1id',
	                    'Processtran.pdriver2id'
	                ],
	                'conditions'=>[
	                    'Processtran.pdriver1id'=>$uid
	                ],
	                'Workdetailmaster',
	                'Workplace',
	            ],
	            'Workplace1'=>[
	                'fields'=> [
	                    'Workplace1.workplacename',
	                ]
	            ],
	            'Workplace2'=>[
	                'fields'=> [
	                    'Workplace2.workplacename',
	                ]
	            ],
	            'Carmaster'=>[
	                'fields'=> [
	                    'Carmaster.branchmasters_id',
	                    'Carmaster.trailermasters_id',
	                    'Carmaster.trailerteate',
	                    'Carmaster.plateno',
	                    'Carmaster.loadingaccount',
	                ]
	            ],
	        ],
	        'recursive' => 1
	    ]);
	}
	/*
	 * 助手情報で抽出
	 */
	public function SelectOrdertran040s2($uid,$paramsDate){
	    return $this->find('all',[
	        'conditions' => [
	            'AND' => [
	                'OR'=>[
	                    'Ordertran.loadingdate' => $paramsDate,
	                    'Ordertran.deliverydate' => $paramsDate,
	                ],
	                'Ordertran.delflg' => 0,
	                'Ordertran.driver2id'=>$uid
	            ],
	        ],
	        'contain' => [
	            'Loadingmaster' => [
	                'fields' => [
	                    'id',
	                    'loadingname',
	                ],
	                'Loadingmasterimage'
	            ],
	            'Deliverymaster' => [
	                'fields' => [
	                    'id',
	                    'deliveryname',
	                ],
	                'Deliverymasterimage'
	            ],
	            'Productdetailmaster' => [
	                'fields' => [
	                    'id',
	                    'pdname'
	                ]
	            ],
	            'Shippermaster' => [
	                'fields' => [
	                    'id',
	                    'shippershortname'
	                ]
	            ],
	            'Processtran' => [
	                'order' => [
	                    'id'=>'asc'
	                ],
	                'fields' => [
	                    'Processtran.id',
	                    'Processtran.workdetailmasters_id',
	                    'Processtran.workddetailcomment',
	                    'Processtran.processstartdate',
	                    'Processtran.processstarttime',
	                    'Processtran.processenddate',
	                    'Processtran.processendtime',
	                    'Processtran.workplaces_id',
	                    'Processtran.pdriver1id',
	                    'Processtran.pdriver2id'
	                ],
	                'conditions'=>[
	                    'Processtran.pdriver2id'=>$uid
	                ],
	                'Workdetailmaster',
	                'Workplace',
	            ],
	            'Workplace1'=>[
	                'fields'=> [
	                    'Workplace1.workplacename',
	                ]
	            ],
	            'Workplace2'=>[
	                'fields'=> [
	                    'Workplace2.workplacename',
	                ]
	            ],
	            'Carmaster'=>[
	                'fields'=> [
	                    'Carmaster.branchmasters_id',
	                    'Carmaster.trailermasters_id',
	                    'Carmaster.trailerteate',
	                    'Carmaster.plateno',
	                    'Carmaster.loadingaccount',
	                ]
	            ],
	        ],
	        'recursive' => 1
	    ]);
	}
	/*
	 * 120s系列データ抽出用
	 */
	public function SelectOrdertran120s($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id) {
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.correctflg',
				'Shippermaster.shippershortname',
				'Deliverymaster.deliveryshortname',
				'Carmaster.plateno',
				'Truckkind.id',
			    'Workplace1.workplacename',
			    'Workplace2.workplacename',
			],
			'conditions' => [
				'AND' => [
					['Ordertran.deliverydate >=' => $paramsDate],
					['Ordertran.deliverydate <=' => $paramsDate2],
					['Ordertran.departmentmasters_id' => $did],
					$shipperconditions,
					$productconditions,
					$carconditions,
					['Ordertran.branchmasters_id' => $branchmasters_id],
					['Ordertran.correctflg' => 1],
					['Ordertran.delflg' => 0],
				]
			],
			'order' => [
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
				'Truckkind.id' => 'asc',
			],
			'recursive' => 1
		]);
	}

	public function SelectOrdertran120spdf($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id) {
		$this->virtualFields['sum'] = 0;
		return $this->find('all', [
			'fields' => [
				'Ordertran.carmasters_id',
				'Carmaster.plateno',
				'Shippermaster.shippershortname',
				'sum(Ordertran.price) as Ordertran__sum',
			],
			'conditions' => [
				'AND' => [
					['Ordertran.deliverydate >=' => $paramsDate],
					['Ordertran.deliverydate <=' => $paramsDate2],
					['Ordertran.departmentmasters_id' => $did],
					$shipperconditions,
					$productconditions,
					$carconditions,
					['Ordertran.branchmasters_id' => $branchmasters_id],
					['Ordertran.correctflg' => 1],
					['Ordertran.delflg' => 0],
				]
			],
			'order' => [
				'Ordertran.carmasters_id' => 'asc'
			],
			'recursive' => 0,
			'group' => [
				'Ordertran.carmasters_id',
				//'Ordertran.shippermasters_id'
			]
		]);
	}

	/*
	 * 130s系列データ抽出用
	 */
	public function SelectOrdertran130s($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id) {
		$this->Behaviors->load('EagerLoader.EagerLoader');
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.loadingdate',
				'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.correctflg',
				'Ordertran.invoice_flg',
			    'Ordertran.price',
				'Shippermaster.shippershortname',
				'Deliverymaster.deliveryshortname',
				'Carmaster.plateno',
				'Productdetailmaster.pdname',
			    'Workplace1.workplaceshortname',
			    'Workplace2.workplaceshortname',
			],
			'conditions' => [
				['Ordertran.deliverydate >=' => $paramsDate],
				['Ordertran.deliverydate <=' => $paramsDate2],
				['Ordertran.departmentmasters_id' => $did],
				$shipperconditions,
				$productconditions,
				$carconditions,
				['Ordertran.branchmasters_id' => $branchmasters_id],
				['Ordertran.correctflg' => 1],
				['Ordertran.delflg' => 0],
			],
			'contain' => [
				'Shippermaster' => [
					'conditions' => ['Shippermaster.delflg' => 0]
				],
				'Deliverymaster' => [
					'conditions' => ['Deliverymaster.delflg' => 0]
				],
				'Carmaster' => [
					'conditions' => ['Carmaster.scrapflg' => 0]
				],
				'Productdetailmaster' => [
					'conditions' => ['Productdetailmaster.delflg' => 0]
				],
			    'Workplace1',
			    'Workplace2',
/*
			    'Price' => [
					'fields' => [
						'Price.pricename',
						'Price.price',
						'Price.pricecomment',
						'OrdertranPricemaster.invoice_flg'
					],
					'conditions' => [
						'Price.delflg' => 0,
						'OrdertranPricemaster.delflg' => 0,
					],
					'order' => [
						'Price.pricename' => 'asc',
						'Price.price' => 'asc'
					],
					'Productdetailmaster' => [
						'fields' => ['pdname'],
						'conditions' => ['delflg' => 0]
					],
					'Shippermaster' => [
						'fields' => ['shippershortname'],
						'conditions' => ['delflg' => 0]
					],
					'Loadingmaster' => [
						'fields' => ['loadingname'],
						'conditions' => ['delflg' => 0]
					],
					'Deliverymaster' => [
						'fields' => ['deliveryname'],
						'conditions' => ['delflg' => 0]
					]
				]
*/
			],
			'order' => [
				'Carmaster.plateno' => 'asc',
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
				'Ordertran.kaisen' => 'asc',
			],
			'recursive' => 0
		]);
	}

	public function SelectOrdertran130s2($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id) {
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.productdetailmasters_id',
				'Ordertran.shippermasters_id',
				'Ordertran.correctamount',
				'Ordertran.price',
				'Ordertran.container_no',
				'Ordertran.ref_no',
				'Ordertran.invoice_flg',
				'Productdetailmaster.id',
				'Productdetailmaster.pdname',
				'Productdetailmaster.pdshortname',
				'Productdetailmaster.pdcode',
				'Productcontainer.pdconname',
			],
			'conditions' => [
				['Ordertran.deliverydate >=' => $paramsDate->format('Y-m-d')],
				['Ordertran.deliverydate <=' => $paramsDate2->format('Y-m-d')],
				['Ordertran.departmentmasters_id' => $did],
				$shipperconditions,
				$productconditions,
				$carconditions,
				['Ordertran.branchmasters_id' => $branchmasters_id],
				['Ordertran.correctflg' => 1],
				['Ordertran.delflg' => 0],
				['Ordertran.invoice_flg' => 0]
			],
			'contain' => [
				'Shippermaster' => [
					'conditions' => ['Shippermaster.delflg' => 0]
				],
				'Deliverymaster' => [
					'conditions' => ['Deliverymaster.delflg' => 0]
				],
				'Carmaster' => [
					'conditions' => ['Carmaster.scrapflg' => 0]
				],
				'Productdetailmaster' => [
					'conditions' => ['Productdetailmaster.delflg' => 0],
				],
				'Productcontainer' => [
					'conditions' => ['Productcontainer.delflg' => 0]
				],
				'Price' => [
					'conditions' => [
						'Price.delflg' => 0,
						'OrdertranPricemaster.delflg' => 0,
						//'OrdertranPricemaster.invoice_flg' => 0
					],
					'order' => [
					    'OrdertranPricemaster.created'=>'asc',
//						'Price.pricename' => 'asc',
//						'Price.price' => 'asc'
					]
				]
			],
			'order' => [
				'Carmaster.plateno' => 'asc',
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
				'Ordertran.kaisen' => 'asc',
			],
			'recursive' => 0
		]);
	}

	public function SelectOrdertran130s3($paramsDate,$paramsDate2,$did,$shipperconditions,$productconditions,$carconditions,$branchmasters_id) {
		return $this->find('list', [
			'fields' => [
				'Ordertran.shippermasters_id',
				'Shippermaster.shippername'
			],
			'conditions' => [
				['Ordertran.deliverydate >=' => $paramsDate->format('Y-m-d')],
				['Ordertran.deliverydate <=' => $paramsDate2->format('Y-m-d')],
				['Ordertran.departmentmasters_id' => $did],
				$shipperconditions,
				$productconditions,
				$carconditions,
				['Ordertran.branchmasters_id' => $branchmasters_id],
				['Ordertran.correctflg' => 1],
				['Ordertran.delflg' => 0],
			],
			'order' => ['Shippermaster.shippercode' => 'asc'],
			'recursive' => 1
		]);
	}

	/*
	 * 140s系列データ抽出用
	 */
	public function SelectOrdertran140s($paramsDate,$paramsDate2,$did,$branchconditions) {
		$this->Behaviors->load('EagerLoader.EagerLoader');
		return $this->find('all', [
			'fields' => [
				'Ordertran.id',
				//'Ordertran.loadingdate',
				//'Ordertran.loadingtime',
				'Ordertran.deliverydate',
				'Ordertran.deliverytime',
				'Ordertran.branchmasters_id',
				'Workplace1.workplaceshortname',
				'Workplace2.workplaceshortname',
				'Productdetailmaster.pdname',
				'Ordertran.confirmflg',
			],
			'conditions' => [
				'Ordertran.loadingdate >=' => $paramsDate,
				'Ordertran.loadingdate <=' => $paramsDate2,
				'Carmaster.departmentmasters_id' => $did,
				'Ordertran.delflg' => 0,
				$branchconditions
			],
			'contain' => [
				'Shippermaster',
				'Carmaster' => [
//					'Containertype'
                    'Trailermaster'
				],
				'Deliverymaster',
				'Productdetailmaster',
				'Driver1',
				'Driver2',
				'Processtran' => [
					'conditions'=>[
						'Processtran.processstartdate >='=> $paramsDate,
						'Processtran.processstartdate <='=> $paramsDate2
					],
					'order' => [
//						'Processtran.processstartdate' => 'asc',
//						'Processtran.processstarttime' => 'asc',
						'Processtran.id' => 'asc',
					],
					'fields' => [
						'Processtran.id',
						'Processtran.processstartdate',
						'Processtran.processstarttime',
						'Processtran.workddetailcomment',
						'Processtran.container_no',
						'Processtran.ref_no',
						'PDriver1.usernameK',
						'PDriver2.usernameK'
					],
					'Workdetailmaster',
					'Workplace',
					'Productcontainer',
					'PDriver1',
					'PDriver2'
				],
				'Workplace1',
				'Workplace2',
			],
			'order' => [
				'Carmaster.plateno' => 'asc',
				'Ordertran.kaisen' => 'asc'
			],
			'recursive' => 0
		]);
	}
//didではなくbidに変更20200326
	public function OrderDriver1($paramsDate,$bid) {
		return $this->find('list', [
			'fields' => [
				'Ordertran.driver1id',
				'Driver1.usernameK'
			],
			'conditions' => [
				'NOT' => [
					'driver1id' => null
				],
			    'OR'=>[
			        'deliverydate' => $paramsDate,
			        'loadingdate' => $paramsDate,
			    ],
				'Driver1.zaisekiflg' => 0,
			    'Driver1.rolemasters_id >='=>30,
			    'Driver1.rolemasters_id <='=>39,
			    'Driver1.branchmasters_id'=>$bid
			],
			'recursive' => 0
		]);
	}

	public function OrderDriver2($paramsDate,$bid) {
		return $this->find('list', [
			'fields' => [
				'Ordertran.driver2id',
				'Driver2.usernameK'
			],
			'conditions' => [
				'NOT' => [
					'driver2id' => null
				],
			    'OR'=>[
			        'deliverydate' => $paramsDate,
			        'loadingdate' => $paramsDate,
			    ],
			    'Driver2.zaisekiflg' => 0,
			    'Driver2.rolemasters_id >='=>30,
			    'Driver2.rolemasters_id <='=>39,
			    'Driver2.branchmasters_id'=>$bid
			],
			'recursive' => 0
		]);
	}
	/*
	 * 030s/driverresult用
	 */
	public function SelectOrdertran030driverresult($driver1id,$workplace1id,$workplace2id) {
	    return $this->find('first', [
	        'fields' => [
	            'Ordertran.deliverydate',
	            'Driver2.usernameK'
	        ],
	        'conditions' => [
	            'AND' => [
	                ['Ordertran.workplaces_id1' => $workplace1id],
	                ['Ordertran.workplaces_id2' => $workplace2id],
	                ['Ordertran.driver1id'=>$driver1id],
	                ['Ordertran.delflg' => 0],
	            ],
	        ],
	        'order' => [
	            'Ordertran.deliverydate' => 'desc',
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
		'shippermasters_id' => [
			'rule' => 'notBlank',
			'message' => '荷主を選択してください。'
		 ],
	    'productdetailmasters_id' => [
	        'rule' => 'notBlank',
	        'message' => '商品を選択してください。'
	    ],
	    'amount' => [
	        'rule' => 'numeric',
	        'message' => '数量を入力してください。'
	    ],
		'truckkinds_id' => [
			'rule' => 'notBlank',
			'message' => '車両を選択してください。'
		],
// 		'loadingmasters_id' => [
// 			'rule' => 'notBlank',
// 			'message' => '積地を選択してください。'
// 		],
// 		'deliverymasters_id' => [
// 			'rule' => 'notBlank',
// 			'message' => '納入先を選択してください。'
// 		],
		'correctdate' => [
			'rule' => 'notBlank',
			'message' => '納入実績日時を入力してください。'
		],
		'correctamount' => [
			'rule' => 'notBlank',
			'message' => '出荷数量を入力してください。'
		],
	];

/**
 * belongsTo associations
 *
 * @var array
 */
	public $hasMany = [
		'Processtran' => [
			'className' => 'Processtran',
			'foreignKey' => 'ordertrans_id',
			'conditions' => ['Processtran.delflg' => 0],
			'dependent' => true,
		    'order' => ['processstartdate','processstarttime','id']
		],
	    'Ordertranimage'=>[
	        'className' => 'Ordertranimage',
	        'foreignKey' => 'ordertrans_id',
	        'conditions' => ['Ordertranimage.delflg' => 0],
	        'dependent' => true,
	        'order' => ['id']
		],
	    'Orderexpensestran'=>[
	        'className' => 'Orderexpensestran',
	        'foreignKey' => 'ordertrans_id',
	        'dependent' => true
		],
	];

	public $belongsTo = [
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
				'carno'
			],
			'order' => ''
		],
	    'Productdetailmaster' => [
	        'className' => 'Productdetailmaster',
	        'foreignKey' => 'productdetailmasters_id',
	        'conditions' => ['Productdetailmaster.delflg' => 0],
	        'fields' => '',
	        'order' => ''
	    ],
		'Deliverymaster' => [
			'className' => 'Deliverymaster',
			'foreignKey' => 'deliverymasters_id',
			'conditions' => ['Deliverymaster.delflg' => 0],
			'fields' => [
				'deliveryname',
				'deliveryshortname'
			],
			'order' => ''
		],
		'Loadingmaster' => [
			'className' => 'Loadingmaster',
			'foreignKey' => 'loadingmasters_id',
			'conditions' => ['Loadingmaster.delflg' => 0],
			'fields' => [
				'loadingname',
				'loadingshortname'
			],
			'order' => ''
		],
	    'Truckkind' => [
	        'className' => 'Truckkind',
	        'foreignKey' => 'truckkinds_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ],
		'Driver1' => [
			'className' => 'User',
			'foreignKey' => 'driver1id',
			'conditions' => ['Driver1.zaisekiflg' => 0],
			'fields' => ['id','usernameK','useremail'],
			'order' => ''
		],
		'Driver2' => [
			'className' => 'User',
			'foreignKey' => 'driver2id',
			'conditions' => ['Driver2.zaisekiflg' => 0],
			'fields' => ['id','usernameK','useremail'],
			'order' => ''
		],
		'Productcontainer' => [
			'className' => 'Productcontainer',
			'foreignKey' => 'productcontainers_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	    'Workplace1' => [
	        'className' => 'Workplace',
	        'foreignKey' => 'workplaces_id1',
	        'conditions' => '',
	        'fields' => ['id','workplacename','workplaceshortname'],
	        'order' => ''
	    ],
	    'Workplace2' => [
	        'className' => 'Workplace',
	        'foreignKey' => 'workplaces_id2',
	        'conditions' => '',
	        'fields' => ['id','workplacename','workplaceshortname'],
	        'order' => ''
	    ],
		'Tantomaster' => [
	        'className' => 'Tantomaster',
	        'foreignKey' => 'tantomasters_id',
	        'conditions' => '',
	        'fields' => '',
	        'order' => ''
	    ],
	];

	public $hasAndBelongsToMany = [
		'Price'=>[
			'className' => 'Pricemaster',
			'with' => 'OrdertransPricemaster',
			'foreignKey' => 'ordertrans_id',
			'associationForeignKey' => 'pricemasters_id',
		],
	];

}
