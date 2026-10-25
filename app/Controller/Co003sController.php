<?php
App::uses('AppController', 'Controller');
/**
 * Co003 Controller
 *
 * @copy Oi000
 */
class Co003sController extends AppController {
	public $layout = 'layout_host';
	public $uses = array('User','Kaisya','Branchmaster','Branchgroup','Departmentmaster','Ordertran','Shippermaster','Holiday','Carmaster','Processtran','Workdetailmaster','Kintaitran','Trailermaster','Workplace','Tantomaster','Ordertranimage','Pricemaster','OrdertranPricemaster','Orderexpensestran','Productcontainer');
	public $helpers = array('Html','Form','Session','Js' => ['Jquery'],'Format');
	public $components = ['SelectData'];
/**
 * Components
 *
 * @var array
 */
	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co003s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 23; //事務職（コンテナ）
				case 24; //事務職（全て）
				case 25; //事務職（化学品コンテナ）
				case 28; //事務職（化学品コンテナ）
				$this->set('user', $user);
					return true;
					break;
			}
		}
		return false;
	}
//workplaceに新規追加用
	public function ajax_add(){
		$this->autoRender = false;
		if($this->request->is('ajax')){
			$this->Workplace->create();
			$this->request->data['Workplace']['workplacename'] = $_POST['wpn'];
			$this->request->data['Workplace']['workplaceshortname'] = $_POST['wpsn'];
			$this->request->data['Workplace']['departmentmasters_id'] = $this->did;
			$this->request->data['Workplace']['kaisyas_id'] = $this->kaisyas_id;
			if($this->Workplace->save($this->request->data)){
				$lastid = $this->Workplace->getInsertID();
				$json_array[0] = $lastid;
				$json_array[1] = $_POST['wpsn'];
				return json_encode($json_array, JSON_UNESCAPED_UNICODE);
			}
		}
	}
//productcontainerに新規追加用
	public function ajax_add2(){
		$this->autoRender = false;
		if($this->request->is('ajax')){
			$this->Productcontainer->create();
			$this->request->data['Productcontainer']['pdconname'] = $_POST['pdc'];
			$this->request->data['Productcontainer']['productdetailmasters_id'] = 111; //コンテナカテゴリ
			if($this->Productcontainer->save($this->request->data)){
				$lastid = $this->Productcontainer->getInsertID();
				$json_array[0] = $lastid;
				$json_array[1] = $_POST['pdc'];
				return json_encode($json_array, JSON_UNESCAPED_UNICODE);
			}
		}
	}

	public function edit($id = null,$flg = null) {


		$post = $this->Ordertran->find('first',[
		    'conditions'=>[
		        'Ordertran.id'=>$id
		    ],
		    'contain'=>[
		        'Processtran'=>[
		            'order'=>[
		                'id',
		                'processstartdate',
		                'processstarttime',
		            ],
		            'fields'=>[
		                'Processtran.id',
		                'Processtran.workddetailcomment',
		                'Processtran.ordertrans_id',
		                'Processtran.processstartdate',
		                'Processtran.processstarttime',
		                'Processtran.pdriver1id',
		                'Processtran.pdriver2id',
		                'Processtran.workdetailmasters_id',
		                'Processtran.workplaces_id',
		                'Processtran.productdetailmastername',
		                'Processtran.container_no',
		                'Processtran.ref_no',
						'Processtran.productcontainers_id'
		            ],
		            'conditions'=>[
		                'Processtran.delflg'=>0
		            ]
		        ],
		        'Shippermaster',
		        'Carmaster',
		        'Driver1',
		        'Driver2',
		        'Workplace1',
		        'Workplace2',
		        'Processtran',
		    ]
		]);
		$bgoup = $this->Branchmaster->find('first',[
			'fields'=>[
				'branchgroups_id'
			],
			'conditions'=>[
				'Branchmaster.id'=>$post['Ordertran']['branchmasters_id']
			]
		]);
		$branchmasters = $this->Branchmaster->find('list',[
	        'fields'=>[
	            'Branchmaster.id'
	        ],
	        'conditions'=>[
	            'branchgroups_id'=>$bgoup['Branchmaster']['branchgroups_id']
	        ]
	    ]);

		// 更新者抽出
		$modifieduserid = $post['Ordertran']['modified_user'];
		$modified_username = $this->User->findById($modifieduserid);
		$this->set('modifiedusername',$modified_username['User']['usernameK']);

		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}
		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);
		$backpage = $this->SelectData->backpage_select2($backdata2,$this->page);

		// $did = 4;
		$did = $this->did;
		$taxflgs = [
			'0'=>'課税',
			'1'=>'免税',
		];
		$this->set('taxflgs',$taxflgs);

		$shippermasters = [$post['Ordertran']['shippermasters_id'] => $post['Shippermaster']['shippershortname']];
		$tantomasters = $this->Tantomaster->SelectTantoList($post['Ordertran']['shippermasters_id']);
		$productcontainers = $this->Productcontainer->SelectProductcontainerList2(111);

		/*
		$branchconditions = [
		    ['Carmaster.branchmasters_id' => $post['Ordertran']['branchmasters_id']],
		    ['Carmaster.departmentmasters_id' => $did]
		];
		*/
	    $branchconditions = [
	        ['Carmaster.branchmasters_id' => $branchmasters],
	        ['Carmaster.departmentmasters_id' => $did]
	    ];

		$carmasters = $this->Carmaster->find('list', [
		    'order' => [
	            'Carmaster.displayno' => 'asc'
		    ],
		    'fields' => [
		        'Carmaster.id',
		        'Carmaster.plateno'
		    ],
		    'conditions' => [
		        $branchconditions,
		        'Carmaster.scrapflg' => 0,
		    ],
		    'recursive' => 0,
		]);

		$workdetailmasters = $this->Workdetailmaster->SelectWorkdetailList($did);
		$workplaces = $this->Workplace->SelectWorkplaceList2($did);

		$this->set(compact('shippermasters','carmasters','users','workdetailmasters','workplaces','tantomasters','productcontainers'));

		if ($this->request->is(array('post', 'put'))) {
			$this->Ordertran->id = $id;
			$this->request->data['Ordertran']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
			$carmaster = $this->Carmaster->SelectCarList($this->request->data['Ordertran']['carmasters_id']);
			$this->request->data['Ordertran']['branchmasters_id'] = $carmaster['Carmaster']['branchmasters_id'];
			$this->request->data['Ordertran']['departmentmasters_id'] = $carmaster['Carmaster']['departmentmasters_id'];

			//変更時は承認を外す
			$this->request->data['Ordertran']['check1flg']=0;
			$this->request->data['Ordertran']['check1_user']=0;
			if ($this->Ordertran->saveAll($this->request->data)) {
				/*
				 * Processtranを読み直して、最初の日時と最後の日時を取得し、Ordertranにセット
				 * Processtranがないor1件のみしなかった場合に備えて、各種変数の初期値はOrdertranから取得しておく
				 */
				$i = 0;
				$w_productcontainer = $post['Ordertran']['productcontainers_id'];
				$orderstartdate = $post['Ordertran']['loadingdate'];
				$orderstarttime = $post['Ordertran']['loadingtime'];
				$orderenddate = $post['Ordertran']['deliverydate'];
				$orderendtime = $post['Ordertran']['deliverytime'];
				$workplaceid1 = $post['Ordertran']['workplaces_id1'];
				$workplaceid2 = $post['Ordertran']['workplaces_id2'];

				if ($this->request->data('Processtran')) {
					foreach ($this->request->data['Processtran'] as $processtran) {
						if ($processtran['workdetailmasters_id']) {
							if ($i==0) {
								// 1回目
								$orderstartdate = $processtran['processstartdate'];
								$orderstarttime = $processtran['processstarttime'];
								$workplaceid1 = $processtran['workplaces_id'];
							} else {
								// 1件でもprocesstranが合った場合
								$orderenddate = $processtran['processstartdate'];
								$orderendtime = $processtran['processstarttime'];
								$workplaceid2 = $processtran['workplaces_id'];
							}
						} else {
							unset($this->request->data['Processtran'][$i]);
						}
						$i = $i + 1;
					}
				}

				/*
				 * Processtranが一件のみだった場合、$orderstartdateは更新されるが$orderenddateは更新されずにstartdateよりも過去に設定されてしまう可能性がある
				 * なのでstartdateがenddateよりも未来だった場合、enddateの日付もstartdateと同じにしている
				 */
				if ($orderstartdate > $orderenddate) {
					$orderenddate = $orderstartdate;
				}

				$this->Ordertran->save([
					'id' => $id,
					'loadingdate' => $orderstartdate,
					'loadingtime' => $orderstarttime,
					'deliverydate' => $orderenddate,
					'deliverytime' => $orderendtime,
					'workplaces_id1' => $workplaceid1,
					'workplaces_id2' => $workplaceid2,
					'productcontainers_id' => $w_productcontainer,
				]);

				$this->Flash->success('オーダーを編集しました。');
				// $backpage = $this->Session->read('BackData2.Page');
				//20251203 $conditions = $this->SelectData->backpage_select($id,$flg,$backpage);
				$conditions = $this->SelectData->backpage_select($id,0,$backpage);
				return $this->redirect($conditions);
			} else {
				$this->Flash->error('オーダー編集に失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}


	public function index() {
		$did = $this->did;
		$page = $this->page;

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$branchgroups = $this->Branchgroup->BranchgroupList();

		$this->set(compact('loadingmasters','deliverymasters','shippermasters','branchgroups','containertypes','workplaces'));
		$cid = null;
		$shippermasters_id = null;
        $date = new DateTime();
		// 先にSessionがある場合初期値を設定
		if ($this->Session->check('BackData1')) {
			if ($this->Session->read('BackData1.Page') == $page) {
				if ($this->Session->check('BackData1.date')) {
					$date = $this->SelectData->date_select($this->Session->read('BackData1.date'));
				}
				if ($this->Session->check('BackData1.sid')) {
					if ($this->Session->read('BackData1.sid') == 0) {
						$shippermasters_id = null;
					} else {
						$shippermasters_id = $this->Session->read('BackData1.sid');
					}
				}
				if ($this->Session->check('BackData1.bid')) {
					$branchgroups_id = $this->Session->read('BackData1.bid');
				}
				$cid = $this->Session->read('BackData1.cid');
			} else {
				$this->Session->delete('BackData1');
			}
		}
		if ($this->request->is('post')) {
		    $date = new DateTime($this->request->data('selectym1'));
			$shippermasters_id = $this->request->data('shippermasters_id');
			$branchgroups_id = $this->request->data('branchgroups_id');
			$cid = $this->request->data('cid');

			if (isset($this->request->data['last1day'])) {
				$date->modify('-1 days');
			}
			if (isset($this->request->data['next1day'])) {
				$date->modify('+1 days');
			}
			$backdatas = [
				'date' => [
					'year' => $date->format('Y'),
					'month' => $date->format('m'),
					'day' => $date->format('d')
				],
				'sid' => $shippermasters_id,
				'bid' => $branchgroups_id,
				'Page' => $page,
				'cid' => $cid
			];
			$this->Session->write('BackData1', $backdatas);
			return $this->redirect(['action' => 'index']);
		}else{
		    if(!isset($branchgroups_id)){
		        $branchgroups_id = $this->branchgroups_id;
		    }
			//$cid = 0;
		}
		$carlist = $this->Carmaster->CarListGroupGas($branchgroups_id,$did);

		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date->modify('+6 day')->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','week','shippermasters_id','branchgroups_id','carlist','cid'));
		$selectData = $this->SelectData->data_select_conditionsCo003s($paramsDate,$paramsDate2,$did,$branchgroups_id,$cid);

		$carmasters = $selectData['carmasters'];
		$ordertrans = $selectData['ordertrans'];

		$notruncarmasters = $this->Carmaster->NotRunCar($paramsDate,$paramsDate2);
		$bglists = $this->Branchmaster->BranchList3($this->kaisyas_id,$branchgroups_id);
		$select_branchmaster_id = [];
		foreach($bglists as $bglist){
			$select_branchmaster_id[] = $bglist['Branchmaster']['id'];
		}
		$drivers = $this->Kintaitran->HolidayDriver_branchgroup($paramsDate,$paramsDate2,$select_branchmaster_id);

		$this->set(compact('notruncarmasters','carmasters','drivers','ordertrans'));
	}
	/*
	* add
	*/
	public function add($flg) {
		$this->Session->delete('Co005s');
	    if (!$this->params['named']) {
	        $cid = null;
	        $haidate = null;
	        $bgid = null;
	    } else {
	        $cid = $this->params['named']['cid'];
	        $haidate = $this->params['named']['date'];
	        $bgid = $this->params['named']['bgid'];
	    }
	    $backdata2 = $this->Session->read('BackData1');
	    $this->Session->write('BackData2', $backdata2);
	    $this->Session->write('BackData2.haidate',$haidate);

	    $backpage = $this->SelectData->backpage_select2($backdata2,$this->page);

	    $branchmasters = $this->Branchmaster->find('list',[
	        'fields'=>[
	            'id'
	        ],
	        'conditions'=>[
	            'branchgroups_id'=>$bgid
	        ]
	    ]);

	    $did = $this->did;
	    $taxflgs = [
	        '0'=>'課税',
	        '1'=>'免税',
	    ];
	    $this->set('taxflgs',$taxflgs);

	    $shippermasters = $this->Shippermaster->SelectShipperList($did);

	    if(isset($sid)){

	    }else{
	        $sid = 1;
	    }
	    $tantomasters = $this->Tantomaster->SelectTantoList($sid);
		$productcontainers = $this->Productcontainer->SelectProductcontainerList2(111);
	    $branchconditions = [
	        ['Carmaster.branchmasters_id' => $branchmasters],
	        ['Carmaster.departmentmasters_id' => $did]
	    ];
	    $carmasters = $this->Carmaster->find('list', [
	        'order' => [
	            'Carmaster.plateno' => 'asc'
	        ],
	        'fields' => [
	            'Carmaster.id',
	            'Carmaster.plateno'
	        ],
	        'conditions' => [
	            $branchconditions,
	            'Carmaster.scrapflg' => 0,
				'Carmaster.id' => $cid
	        ],
	        'recursive' => 0,
	    ]);

	    $workdetailmasters = $this->Workdetailmaster->SelectWorkdetailList($did);
	    $workplaces = $this->Workplace->SelectWorkplaceList2($did);

	    $this->set(compact('shippermasters','branchmasters','carmasters','users','workdetailmasters','workplaces','tantomasters','productcontainers'));

	    if ($this->request->is('post')) {
			//エラーチェック追加20240719 0は選択してない状態
			if($this->request->data['Ordertran']['tantomasters_id'] == 0){
				$this->Flash->error('担当者を選択してください');	
				return;
			}
	        $this->Ordertran->create();
	        $this->request->data['Ordertran']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
	        // コンテナ業務では商品区分を「コンテナ」に固定する。
	        $this->request->data['Ordertran']['productdetailmasters_id'] = 111;
	        // 数量入力を持たない画面のため、オーダー数量は1件として登録する。
	        $this->request->data['Ordertran']['amount'] = 1;
	        // 作業明細の保存後に実際の場所で更新するが、初回INSERTにも必須。
	        $this->request->data['Ordertran']['workplaces_id1'] = 0;
	        $this->request->data['Ordertran']['workplaces_id2'] = 0;
	        $carmaster = $this->Carmaster->SelectCarList($this->request->data['Ordertran']['carmasters_id']);
	        /* branchmasters_idはカレンダーで選択された営業所のものを事前セットしている。（車マスタのbranchidをセットに変更した） */
	        $this->request->data['Ordertran']['branchmasters_id'] = $carmaster['Carmaster']['branchmasters_id'];
	        $this->request->data['Ordertran']['departmentmasters_id'] = $carmaster['Carmaster']['departmentmasters_id'];
	        $this->request->data['Ordertran']['color'] = sprintf("#%06x",rand(0x000000, 0xFFFFFF));

	        if ($this->Ordertran->save($this->request->data['Ordertran'])) {
	            /* ここからProcesstran保存処理 */
	            $oid = $this->Ordertran->getLastInsertID();
	            $i = 0;
	            $w_productcontainer = 0;

	            // 最初にURLから取得しておくことで変数が存在しないというエラーを回避する
	            $date = new DateTime($this->params['named']['date']);
	            $orderstartdate = $date->format('Y-m-d');
	            $orderstarttime = '00:00';
	            $orderenddate = $date->format('Y-m-d');
	            $orderendtime = '23:59';
	            $workplaceid1 = null;
	            $workplaceid2 = null;
				$work_container_no = null;

	            foreach ($this->request->data['Processtran'] as $processtran) {
	                if ($processtran['workdetailmasters_id']) {
						//コンテナナンバーの退避
						if($processtran['container_no']){
							$work_container_no = $processtran['container_no'];
						}
	                    $this->request->data['Processtran'][$i]['ordertrans_id'] = $oid;
	                    if ($i == 0) {
	                        // 1回目
	                        $orderstartdate = $processtran['processstartdate'];
	                        $orderstarttime = $processtran['processstarttime'];
	                        $workplaceid1 = $processtran['workplaces_id'];
	                    } else {
	                        // 1件でもprocesstranが合った場合
	                        $orderenddate = $processtran['processstartdate'];
	                        $orderendtime = $processtran['processstarttime'];
	                        $workplaceid2 = $processtran['workplaces_id'];
	                    }
	                    if($processtran['productcontainers_id'] > 0){
	                        $w_productcontainer = $processtran['productcontainers_id'];
	                    }
	                } else {
	                    unset($this->request->data['Processtran'][$i]);
	                }
	                $i = $i + 1;
	            }
	            if ($this->request->data('Processtran')) {
	                // Processtranが存在したときのみProcesstranをsaveAll
	                $this->Processtran->create();
	                $this->Processtran->saveAll($this->request->data['Processtran']);
	            }
	            /*
	             * Processtranが存在した場合は、Processtranから最初と最後の日時を取得してOrdertranを上書き
	             * なかった場合はURLから取得した日時を最初と最後の日時として設定してOrdertranを上書き
	             */
	            $this->Ordertran->save([
	                'id' => $oid,
	                'loadingdate' => $orderstartdate,
	                'loadingtime' => $orderstarttime,
	                'deliverydate' => $orderenddate,
	                'deliverytime' => $orderendtime,
	                'workplaces_id1' => $workplaceid1,
	                'workplaces_id2' => $workplaceid2,
	                'productcontainers_id' => $w_productcontainer,
	            ]);

				//同じ日にちに同じコンテナナンバーが存在するかをチェック
				$checkno = $this->Processtran->find('first',[
					'conditions'=>[
						'NOT'=>[
							'Processtran.ordertrans_id' => $oid,
						],
						'Processtran.container_no' => $work_container_no,
						'Processtran.processstartdate' => $orderenddate
					],
					'recursive' => 0
				]);		


				if($checkno){
					//存在したらエラー
					$this->Flash->error('コンテナNo.がダブっています');	
				}else{
					$this->Flash->success('オーダーを保存しました。');
				}

	            $id = null;
	            $conditions = $this->SelectData->backpage_select($id,$flg,$backpage);
	            //return $this->redirect($conditions);
				return $this->redirect(['controller'=>'Co003s','action'=>'priceview',$oid]);
	        } else {
	            $this->Flash->error('オーダー登録に失敗しました。');
	        }
	    }
	}
	public function view($id = null) {
		//画像ファイルの登録または、価格の登録
	    if ($this->request->is('post')) {
			if ($this->request->data['Ordertranimage']['img']['error'] == 0) {
				$this->Ordertranimage->create();
				$this->request->data['Ordertranimage']['ordertrans_id']=$id;
				if ($this->Ordertranimage->saveAll($this->request->data)) {
					$this->Flash->success('画像をアップロードしました');
				} else {
					$this->Flash->error('画像のアップロードに失敗しました');
				}
			} else {
				// $this->Flash->error('画像が選択されていません');
				//画像がなくてもエラーにしない
			}
			if($this->request->data['Ordertranimage']['bikocomment']){
				//ordertranを更新する
				$this->Ordertran->save([
					'id' => $id,
					'bikocomment' => $this->request->data['Ordertranimage']['bikocomment']
				]);
				$this->Flash->success('登録しました');
			}
	    }

	    $ordertran = $this->Ordertran->find('first',[
	        'conditions'=>[
	            'Ordertran.id'=>$id
	        ],
	        'contain'=>[
	            'Processtran'=>[
	                'order'=>[
	                    'id',
	                    'processstartdate',
	                    'processstarttime',
	                ],
	                'fields'=>[
	                    'Processtran.id',
	                    'Processtran.workplaces_id',
	                    'Processtran.workddetailcomment',
	                    'Processtran.ordertrans_id',
	                    'Processtran.processstartdate',
	                    'Processtran.processstarttime',
	                    'Processtran.pdriver1id',
	                    'Processtran.pdriver2id',
	                    'Processtran.workdetailmasters_id',
	                    'Processtran.workplaces_id',
	                    'Processtran.productdetailmastername',
	                    'Processtran.container_no',
	                    'Processtran.ref_no',
						'Processtran.timenote',
						'Processtran.productcontainers_id'
	                ],
	                'conditions'=>[
	                    'Processtran.delflg'=>0
	                ]
	            ],
	            'Shippermaster',
	            'Carmaster'=>[
					'fields'=>[
						'Carmaster.plateno',
						'Carmaster.carno',
						'Carmaster.branchmasters_id'
					],
				],
	            'Driver1',
	            'Driver2',
	            'Workplace1',
	            'Workplace2',
	            'Processtran',
	            'Tantomaster',
	            'Ordertranimage'
	        ]
	    ]);

	    $this->set(compact('ordertran'));
		$pkey = array();
		foreach($ordertran['Processtran'] as $processtran){
			if($processtran['workplaces_id']){
				// keyの１番目が場所、２番めが作業内容、３番めが商品詳細（３つで１セット）
				$pkey[] = $processtran['workplaces_id'];
				$pkey[] = $processtran['workdetailmasters_id'];
				//20230608 商品は加味しない$pkey[] = $processtran['productcontainers_id'];
				$pkey[] = 0;
			}
		}
		/*
		* 現在登録されているordertrans_pricematerを読み込み、表示する
		*/
		$ordertranpricemasters = $this->OrdertranPricemaster->find('all',[
			'conditions'=>[
				'ordertrans_id'=>$id,
			]
		]);
		$this->set('ordertranpricemasters',$ordertranpricemasters);
		
		//荷主もキーにして読む
		$pricemasters2 = $this->Pricemaster->SelectPricemaster($pkey,$this->kaisyas_id);
		$this->set(compact('orderpriemasters','pricemasters','pricemasters2'));

	}

	public function printcomp($id = null){
		//$ordertran = $this->Ordertran->findById($id);

		$this->Ordertran->updateAll([
			'printcompflg' => 1
		], [
			'id' => $id
		]);
		$this->Flash->success(__('印刷済みにしました。'));
		return $this->redirect(['action' => 'view', $id]);


	}

	public function imagedelete($id = null, $oid = null) {
	    $post = $this->Ordertranimage->findById($id);
	    $kakutyoushi = [];
	    $kakutyoushi = explode('.',$post['Ordertranimage']['img_file_name']);
	    $this->Ordertranimage->id = $id;
	    if ($this->request->is('get')){
	        throw new MethodNotAllowedException();
	    }else{
//	        $fileName = './ordertranimages/original/'.$kakutyoushi[0].'.'.$kakutyoushi[1];
//	        $fileName2 = './ordertranimages/resize/'.$kakutyoushi[0].'.'.$kakutyoushi[1];
	        $fileName = './ordertranimages/original/'.$id.'.'.$kakutyoushi[1];
	        $fileName2 = './ordertranimages/resize/'.$id.'.'.$kakutyoushi[1];
			unlink($fileName);
	        unlink($fileName2);
	        if ($this->Ordertranimage->delete($id)) {
	            $this->Flash->success(__($fileName.'を削除しました'));
	        } else {
	            $this->Flash->error(__('削除に失敗しました'));
	        }
	    }
	    return $this->redirect(['action' => 'view', $oid]);
	}
	public function delete($id = null) {
	    if ($this->request->is('post')){
	        $this->request->data['Ordertran']['delflg'] = 1;
	        $this->Ordertran->id = $id;
	        $this->Ordertran->save([
				'id' => $id,
				'delflg' => 1
			]);
			$processtrans = $this->Processtran->find('all',[
				'conditions' => [
					'Processtran.ordertrans_id' => $id
				],
				'recursive' => 0,
			]);
			if ($processtrans) {
				foreach ($processtrans as $processtran) {
					$this->request->data['Processtran']['id'] = $processtran['Processtran']['id'];
					$this->request->data['Processtran']['delflg'] = 1;
					$this->Processtran->save($this->request->data);
				}
			}
			$this->Flash->success(__('削除しました'));
			return $this->redirect(['controller' => 'Co003s','action' => 'index']);
	    }else{
			//削除確認画面用
			$ordertran = $this->Ordertran->find('first',[
				'conditions'=>[
					'Ordertran.id'=>$id
				],
				'contain'=>[
					'Processtran'=>[
						'order'=>[
							'id',
							'processstartdate',
							'processstarttime',
						],
						'fields'=>[
							'Processtran.id',
							'Processtran.workplaces_id',
							'Processtran.workddetailcomment',
							'Processtran.ordertrans_id',
							'Processtran.processstartdate',
							'Processtran.processstarttime',
							'Processtran.pdriver1id',
							'Processtran.pdriver2id',
							'Processtran.workdetailmasters_id',
							'Processtran.workplaces_id',
							'Processtran.productdetailmastername',
							'Processtran.container_no',
							'Processtran.ref_no',
							'Processtran.timenote',
							'Processtran.productcontainers_id'
						],
						'conditions'=>[
							'Processtran.delflg'=>0
						]
					],
					'Shippermaster',
					'Carmaster'=>[
						'fields'=>[
							'Carmaster.plateno',
							'Carmaster.carno',
							'Carmaster.branchmasters_id'
						],
					],
					'Driver1',
					'Driver2',
					'Workplace1',
					'Workplace2',
					'Processtran',
					'Tantomaster',
					'Ordertranimage'
				]
			]);
	
			$this->set(compact('ordertran'));
			$pkey = array();
			foreach($ordertran['Processtran'] as $processtran){
				if($processtran['workplaces_id']){
					// keyの１番目が場所、２番めが作業内容、３番めが商品詳細（３つで１セット）
					$pkey[] = $processtran['workplaces_id'];
					$pkey[] = $processtran['workdetailmasters_id'];
					//20230608 商品は加味しない$pkey[] = $processtran['productcontainers_id'];
					$pkey[] = 0;
				}
			}
	
		}
	}
	public function ordercancel($id = null) {
		//20251218 price=0で更新を追加
	    if ($this->request->is('post')){
			//キャンセル扱いは３を設定
	        $this->request->data['Ordertran']['delflg'] = 3;
	        $this->Ordertran->id = $id;
	        $this->Ordertran->save([
				'id' => $id,
				'price' => 0,
				'delflg' => 3
			]);
			$processtrans = $this->Processtran->find('all',[
				'conditions' => [
					'Processtran.ordertrans_id' => $id
				],
				'recursive' => 0,
			]);
			if ($processtrans) {
				foreach ($processtrans as $processtran) {
					$this->request->data['Processtran']['id'] = $processtran['Processtran']['id'];
					$this->request->data['Processtran']['delflg'] = 3;
					$this->Processtran->save($this->request->data);
				}
			}
			$this->Flash->success(__('キャンセル扱いにしました'));
			return $this->redirect(['controller' => 'Co003s','action' => 'index']);
	    }else{
			//キャンセル確認画面用
			$ordertran = $this->Ordertran->find('first',[
				'conditions'=>[
					'Ordertran.id'=>$id
				],
				'contain'=>[
					'Processtran'=>[
						'order'=>[
							'id',
							'processstartdate',
							'processstarttime',
						],
						'fields'=>[
							'Processtran.id',
							'Processtran.workplaces_id',
							'Processtran.workddetailcomment',
							'Processtran.ordertrans_id',
							'Processtran.processstartdate',
							'Processtran.processstarttime',
							'Processtran.pdriver1id',
							'Processtran.pdriver2id',
							'Processtran.workdetailmasters_id',
							'Processtran.workplaces_id',
							'Processtran.productdetailmastername',
							'Processtran.container_no',
							'Processtran.ref_no',
							'Processtran.timenote',
							'Processtran.productcontainers_id'
						],
						'conditions'=>[
							'Processtran.delflg'=>0
						]
					],
					'Shippermaster',
					'Carmaster'=>[
						'fields'=>[
							'Carmaster.plateno',
							'Carmaster.carno',
							'Carmaster.branchmasters_id'
						],
					],
					'Driver1',
					'Driver2',
					'Workplace1',
					'Workplace2',
					'Processtran',
					'Tantomaster',
					'Ordertranimage'
				]
			]);
	
			$this->set(compact('ordertran'));
			$pkey = array();
			foreach($ordertran['Processtran'] as $processtran){
				if($processtran['workplaces_id']){
					// keyの１番目が場所、２番めが作業内容、３番めが商品詳細（３つで１セット）
					$pkey[] = $processtran['workplaces_id'];
					$pkey[] = $processtran['workdetailmasters_id'];
					//20230608 商品は加味しない$pkey[] = $processtran['productcontainers_id'];
					$pkey[] = 0;
				}
			}

		}
	}

	public function priceview($id = null,$pmasterid = null) {
	    $ordertran = $this->Ordertran->find('first',[
	        'conditions'=>[
	            'Ordertran.id'=>$id
	        ],
	        'contain'=>[
	            'Processtran'=>[
	                'order'=>[
	                    'id',
	                    'processstartdate',
	                    'processstarttime',
	                ],
	                'fields'=>[
	                    'Processtran.id',
	                    'Processtran.workplaces_id',
	                    'Processtran.workddetailcomment',
	                    'Processtran.ordertrans_id',
	                    'Processtran.processstartdate',
	                    'Processtran.processstarttime',
	                    'Processtran.pdriver1id',
	                    'Processtran.pdriver2id',
	                    'Processtran.workdetailmasters_id',
	                    'Processtran.workplaces_id',
	                    'Processtran.productdetailmastername',
	                    'Processtran.container_no',
	                    'Processtran.ref_no',
						'Processtran.timenote',
						'Processtran.productcontainers_id'
	                ],
	                'conditions'=>[
	                    'Processtran.delflg'=>0
	                ]
	            ],
	            'Shippermaster',
	            'Carmaster',
	            'Driver1',
	            'Driver2',
	            'Processtran',
	            'Tantomaster',
	            'Ordertranimage',
				'Productcontainer'
	        ],
	    ]);
	    $this->set(compact('ordertran'));
		$pricemasters = $this->Pricemaster->SelectPricelist($this->kaisyas_id,$this->did);
		$this->set('pricemasters',$pricemasters);
		$pkey = array();
		foreach($ordertran['Processtran'] as $processtran){
			if($processtran['workplaces_id']){
				// keyの１番目が場所、２番めが作業内容、３番めが商品詳細（３つで１セット）
				$pkey[] = $processtran['workplaces_id'];
				$pkey[] = $processtran['workdetailmasters_id'];
				//20230608 商品は加味しない$pkey[] = $processtran['productcontainers_id'];
				$pkey[] = 0;
			}
		}
		if ($this->request->is(array('post'))) {
			if(isset($this->request->data['price'])) {
				//価格マスタ登録のsubmitの場合
				$this->Pricemaster->create();
				$this->request->data['Pricemaster']['departmentmasters_id'] = $this->did;
				$this->request->data['Pricemaster']['kaisyas_id']=$this->kaisyas_id;
				$this->request->data['Pricemaster']['branchmasters_id']=$ordertran['Ordertran']['branchmasters_id'];
				$this->request->data['Pricemaster']['productcontainers_id'] = $ordertran['Ordertran']['productcontainers_id'];
				$textname = '';
				if(isset($pkey[0])){
					$this->request->data['Pricemaster']['key1'] = $pkey[0];
					//場所の検索
					$workplace = [];
					$workplace = $this->Workplace->find('first',[
						'conditions'=>[
							'Workplace.id'=>$pkey[0]
						],
						'fields'=>[
							'Workplace.workplaceshortname'
						],
						'recursive'=>0
					]);
					$textname = $textname . $workplace['Workplace']['workplaceshortname'];
				}else{
					$this->request->data['Pricemaster']['key1'] = 0;
				}
				if(isset($pkey[1])){
					$this->request->data['Pricemaster']['key2'] = $pkey[1];
					//作業の検索
					$workdetail = [];
					$workdetail = $this->Workdetailmaster->find('first',[
						'conditions'=>[
							'Workdetailmaster.id'=>$pkey[1]
						],
						'fields'=>[
							'Workdetailmaster.workname'
						],
						'recursive'=>0
					]);
					$textname = $textname . '(' . $workdetail['Workdetailmaster']['workname'] . ')';
				}else{
					$this->request->data['Pricemaster']['key2'] = 0;
				}
				if(isset($pkey[2])){
					$this->request->data['Pricemaster']['key3'] = $pkey[2];
					//商品
				}else{
					$this->request->data['Pricemaster']['key3'] = 0;
				}
				if(isset($pkey[3])){
					$this->request->data['Pricemaster']['key4'] = $pkey[3];
					//場所の検索
					$workplace = [];
					$workplace = $this->Workplace->find('first',[
						'conditions'=>[
							'Workplace.id'=>$pkey[3]
						],
						'fields'=>[
							'Workplace.workplaceshortname'
						],
						'recursive'=>0
					]);
					$textname = $textname . '-' . $workplace['Workplace']['workplaceshortname'];
				}else{
					$this->request->data['Pricemaster']['key4'] = 0;
				}
				if(isset($pkey[4])){
					$this->request->data['Pricemaster']['key5'] = $pkey[4];
					//作業の検索
					$workdetail = [];
					$workdetail = $this->Workdetailmaster->find('first',[
						'conditions'=>[
							'Workdetailmaster.id'=>$pkey[4]
						],
						'fields'=>[
							'Workdetailmaster.workname'
						],
						'recursive'=>0
					]);
					$textname = $textname . '(' . $workdetail['Workdetailmaster']['workname'] . ')';
				}else{
					$this->request->data['Pricemaster']['key5'] = 0;
				}
				if(isset($pkey[5])){
					$this->request->data['Pricemaster']['key6'] = $pkey[5];
					// 商品
				}else{
					$this->request->data['Pricemaster']['key6'] = 0;
				}
				if(isset($pkey[6])){
					$this->request->data['Pricemaster']['key7'] = $pkey[6];
					//場所の検索
					$workplace = [];
					$workplace = $this->Workplace->find('first',[
						'conditions'=>[
							'Workplace.id'=>$pkey[6]
						],
						'fields'=>[
							'Workplace.workplaceshortname'
						],
						'recursive'=>0
					]);
					$textname = $textname . '-' . $workplace['Workplace']['workplaceshortname'];
				}else{
					$this->request->data['Pricemaster']['key7'] = 0;
				}
				if(isset($pkey[7])){
					$this->request->data['Pricemaster']['key8'] = $pkey[7];
					//作業の検索
					$workdetail = [];
					$workdetail = $this->Workdetailmaster->find('first',[
						'conditions'=>[
							'Workdetailmaster.id'=>$pkey[7]
						],
						'fields'=>[
							'Workdetailmaster.workname'
						],
						'recursive'=>0
					]);
					$textname = $textname . '(' . $workdetail['Workdetailmaster']['workname'] . ')';
				}else{
					$this->request->data['Pricemaster']['key8'] = 0;
				}
				if(isset($pkey[8])){
					$this->request->data['Pricemaster']['key9'] = $pkey[8];
					//商品
				}else{
					$this->request->data['Pricemaster']['key9'] = 0;
				}
				if(isset($pkey[9])){
					$this->request->data['Pricemaster']['key10'] = $pkey[9];
					//場所の検索
					$workplace = [];
					$workplace = $this->Workplace->find('first',[
						'conditions'=>[
							'Workplace.id'=>$pkey[9]
						],
						'fields'=>[
							'Workplace.workplaceshortname'
						],
						'recursive'=>0
					]);
					$textname = $textname . '-' . $workplace['Workplace']['workplaceshortname'];
				}else{
					$this->request->data['Pricemaster']['key10'] = 0;
				}
				if(isset($pkey[10])){
					$this->request->data['Pricemaster']['key11'] = $pkey[10];
					//作業の検索
					$workdetail = [];
					$workdetail = $this->Workdetailmaster->find('first',[
						'conditions'=>[
							'Workdetailmaster.id'=>$pkey[10]
						],
						'fields'=>[
							'Workdetailmaster.workname'
						],
						'recursive'=>0
					]);
					$textname = $textname . '(' . $workdetail['Workdetailmaster']['workname'] . ')';
				}else{
					$this->request->data['Pricemaster']['key11'] = 0;
				}
				if(isset($pkey[11])){
					$this->request->data['Pricemaster']['key12'] = $pkey[11];
					//商品
				}else{
					$this->request->data['Pricemaster']['key12'] = 0;
				}
				if(isset($pkey[12])){
					$this->request->data['Pricemaster']['key13'] = $pkey[12];
					//場所の検索
					$workplace = [];
					$workplace = $this->Workplace->find('first',[
						'conditions'=>[
							'Workplace.id'=>$pkey[12]
						],
						'fields'=>[
							'Workplace.workplaceshortname'
						],
						'recursive'=>0
					]);
					$textname = $textname . '-' . $workplace['Workplace']['workplaceshortname'];
				}else{
					$this->request->data['Pricemaster']['key13'] = 0;
				}
				if(isset($pkey[13])){
					$this->request->data['Pricemaster']['key14'] = $pkey[13];
					//作業の検索
					$workdetail = [];
					$workdetail = $this->Workdetailmaster->find('first',[
						'conditions'=>[
							'Workdetailmaster.id'=>$pkey[13]
						],
						'fields'=>[
							'Workdetailmaster.workname'
						],
						'recursive'=>0
					]);
					$textname = $textname . '(' . $workdetail['Workdetailmaster']['workname'] . ')';
				}else{
					$this->request->data['Pricemaster']['key14'] = 0;
				}
				if(isset($pkey[14])){
					$this->request->data['Pricemaster']['key15'] = $pkey[14];
					//商品
				}else{
					$this->request->data['Pricemaster']['key15'] = 0;
				}
				if(isset($pkey[15])){
				    $this->request->data['Pricemaster']['key16'] = $pkey[15];
					//場所の検索
					$workplace = [];
					$workplace = $this->Workplace->find('first',[
						'conditions'=>[
							'Workplace.id'=>$pkey[15]
						],
						'fields'=>[
							'Workplace.workplaceshortname'
						],
						'recursive'=>0
					]);
					$textname = $textname . '-' . $workplace['Workplace']['workplaceshortname'];
				}else{
				    $this->request->data['Pricemaster']['key16'] = 0;
				}
				if(isset($pkey[16])){
				    $this->request->data['Pricemaster']['key17'] = $pkey[16];
					//作業の検索
					$workdetail = [];
					$workdetail = $this->Workdetailmaster->find('first',[
						'conditions'=>[
							'Workdetailmaster.id'=>$pkey[16]
						],
						'fields'=>[
							'Workdetailmaster.workname'
						],
						'recursive'=>0
					]);
					$textname = $textname . '(' . $workdetail['Workdetailmaster']['workname'] . ')';
				}else{
				    $this->request->data['Pricemaster']['key17'] = 0;
				}
				if(isset($pkey[17])){
				    $this->request->data['Pricemaster']['key18'] = $pkey[17];
					//商品
				}else{
				    $this->request->data['Pricemaster']['key18'] = 0;
				}
				$w_price = $this->request->data['Pricemaster']['price'];
				$this->request->data['Pricemaster']['pricename'] = $textname;
				$this->request->data['Pricemaster']['shippermasters_id'] = $ordertran['Ordertran']['shippermasters_id'];
				if ($this->Pricemaster->save($this->request->data)) {
					$pid = $this->Pricemaster->id;
					if (isset($pid)) {
						$opdata = $this->OrdertranPricemaster->create();
						$opdata['OrdertranPricemaster']['pricemasters_id']=$pid;
						$opdata['OrdertranPricemaster']['ordertrans_id']=$id;
						if($this->OrdertranPricemaster->save($opdata)){
							//ordertranの価格を更新
							$orderprice = $this->Ordertran->ExtractionPrice($id);
							$price = $orderprice['Ordertran']['price'] + $w_price;
							$data['Ordertran']['id'] = $id;
							$data['Ordertran']['price'] = $price;
							//20240729 productcontainers_idも登録
							$this->Ordertran->save($data);
							
							$this->Flash->success('価格マスタ及び請求内容を登録しました。');
							return $this->redirect(['action' => 'priceview', $id]);
						}else{
							$this->Flash->error('価格マスタ及び請求内容の登録に失敗しました。');
						}
						//ordertranのcorrectflg（実績確認）を更新
						$this->Ordertran->save([
							'id' => $ordertran['Ordertran']['id'],
							'correctflg' => 1,
							'price' => $w_price
						]);

					}
					return $this->redirect(['action' => 'priceview', $id]);
				} else {
					$this->Flash->error('価格マスタの登録に失敗しました。');
				}

			}elseif(isset($this->request->data['keihi'])){
				//経費の登録時
				$this->OrdertranPricemaster->create();
				$this->request->data['OrdertranPricemaster']['ordertrans_id'] = $id;
				if($this->OrdertranPricemaster->save($this->request->data)){
					//ordertranの価格を更新
					$pricemasterprice = $this->Pricemaster->ExtractionPrice($this->request->data['OrdertranPricemaster']['pricemasters_id']);
					$orderprice = $this->Ordertran->ExtractionPrice($id);
					$price = $orderprice['Ordertran']['price'] + $pricemasterprice['Pricemaster']['price'];
					$data['Ordertran']['id'] = $id;
					$data['Ordertran']['price'] = $price;
					$this->Ordertran->save($data);
					//ここまで
					$this->Flash->success('請求内容に諸費を追加しました');
					return $this->redirect(['action'=>'priceview',$id]);
				}else{
					$this->Flash->error('諸費追加に失敗しました');
				}
			}elseif(isset($this->request->data['shohi'])){
				//諸費の登録
				$this->Orderexpensestran->create();
				$this->request->data['Orderexpensestran']['ordertrans_id'] = $id;
				if($this->Orderexpensestran->save($this->request->data)){
					//ordertranの価格を更新
					$orderprice = $this->Ordertran->ExtractionPrice($id);
					$price = $orderprice['Ordertran']['price'] + $this->request->data['Orderexpensestran']['expensesprice'];
					$data['Ordertran']['id'] = $id;
					$data['Ordertran']['price'] = $price;
					$this->Ordertran->save($data);
					//ここまで
					$this->Flash->success('請求内容に諸費を追加しました');
					return $this->redirect(['action'=>'priceview',$id]);
				}else{
					$this->Flash->error('諸費追加に失敗しました');
				}
			}else{
				//ordertranpricemasterに登録
				$this->OrdertranPricemaster->create();
				$this->request->data['ordertrans_id'] = $id;
				$this->request->data['pricemasters_id'] = $pmasterid;
				$this->OrdertranPricemaster->save($this->request->data);
				//ordertranの金額も更新
				$pricemasterprice = $this->Pricemaster->ExtractionPrice($pmasterid);
				$orderprice = $this->Ordertran->ExtractionPrice($id);
				$price = $orderprice['Ordertran']['price'] + $pricemasterprice['Pricemaster']['price'];
				$data['Ordertran']['id'] = $id;
				$data['Ordertran']['price'] = $price;
				$this->Ordertran->save($data);
				$this->Flash->success('請求内容に追加しました');
				return $this->redirect(['action'=>'priceview',$id]);

			}
		}
		/*
		* 現在登録されているordertrans_pricematerを読み込み、表示する
		*/
		$ordertranpricemasters = $this->OrdertranPricemaster->find('all',[
			'conditions'=>[
				'ordertrans_id' => $id,
				'OrdertranPricemaster.delflg' => 0
			]
		]);
		$this->set('ordertranpricemasters',$ordertranpricemasters);
		// 諸費データを読み込み
		$orderexpensestrans = $this->Orderexpensestran->find('all',[
			'conditions'=>[
				'ordertrans_id' => $id
			],
			'recursive'=>-1
		]);
		$this->set('orderepensestrans',$orderexpensestrans);
		//荷主もキーにして読む
		$pricemasters2 = $this->Pricemaster->SelectPricemaster($pkey,$this->kaisyas_id,$ordertran['Ordertran']['shippermasters_id'],$ordertran['Ordertran']['productcontainers_id']);
		$this->set(compact('orderpriemasters','pricemasters2'));

	}
	public function pricedelete($oid = null,$id = null) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}
		//ordertranの価格を更新
		$opm = $this->OrdertranPricemaster->find('first',[
			'conditions'=>[
				'id' => $id
			],
			'recursive' => -1
		]);
		$pm = $this->Pricemaster->find('first',[
			'conditions'=>[
				'id' => $opm['OrdertranPricemaster']['pricemasters_id']
			],
			'recursive' => -1
		]);
		$orderprice = $this->Ordertran->ExtractionPrice($oid);
		$price = $orderprice['Ordertran']['price'] - $pm['Pricemaster']['price'];
		$data['Ordertran']['id'] = $oid;
		$data['Ordertran']['price'] = $price;
		if($this->Ordertran->save($data)){
			//保存成功
		}else{
			//失敗
			$this->Flash->error(__('ordertranの保存に失敗しました'));
		}

		if ($this->OrdertranPricemaster->delete($id)) {
			$this->Flash->success(__('削除しました'));
		} else {
			$this->Flash->error(__('失敗しました'));
		}
		return $this->redirect(['action' => 'priceview',$oid]);

	}

	public function expensesdelete($oid = null,$id = null) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}
		//ordertranの価格を更新
		$oet = $this->Orderexpensestran->find('first',[
			'conditions'=>[
				'id' => $id
			],
			'recursive' => -1
		]);
		$orderprice = $this->Ordertran->ExtractionPrice($oid);
		$price = $orderprice['Ordertran']['price'] - $oet['Orderexpensestran']['expensesprice'];
		$data['Ordertran']['id'] = $oid;
		$data['Ordertran']['price'] = $price;
		if($this->Ordertran->save($data)){
			//保存成功
		}else{
			//失敗
			$this->Flash->error(__('ordertranの保存に失敗しました'));
		}

		if ($this->Orderexpensestran->delete($id)) {
			$this->Flash->success(__('削除しました'));
		} else {
			$this->Flash->error(__('expensestranの削除に失敗しました'));
		}
		return $this->redirect(['action' => 'priceview',$oid]);

	}

}
