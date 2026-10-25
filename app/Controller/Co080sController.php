<?php
App::uses('AppController', 'Controller');
/**
 * Co080 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
//20240706 ordertranに請求する日付をセットするカラム(detailbilldate)を追加。
class Co080sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['User','Kaisya','Departmentmaster','Ordertran','Shippermaster','Productdetailmaster','Productcontainer','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Pricemaster','OrdertranPricemaster','Processtran','Workplace','Workdetailmaster','Branchmaster','Branchgroup','Orderexpensestran','Tantomaster','Ordertrankousokudai'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co080s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 23; //事務職（コンテナ）
				case 24; //事務職（全て）
				case 25; //事務職（化学品コンテナ）
				case 28; //事務職券運転手
    				$this->set('user', $user);
    				return true;
    				break;
			}
		}
		return false;
	}
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		// $did = 4;
		// $page = 'Co080s';
		$did = $this->did;
		$page = $this->page;
		$selectbid = [];

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$branchmasters_id = $this->branchmasters_id;

		$branchgroups = $this->Branchgroup->BranchgroupList();
		$this->set('branchgroups',$branchgroups);

		//$branchgroups = $this->Branchgroup->BranchgroupList();
		$confirmflgs = [0 => '未登録', 1 => '登録済'];
		$this->set(compact('shippermasters','billconfirmflgs','branchmasters'));
		$this->set('branchmasters_id',$branchmasters_id);

		if ($this->request->is('post')) {
			$date = new DateTime($this->request->data['selectym1']);
			$date2 = new DateTime($this->request->data['selectym2']);
			$shippermasters_id = $this->request->data['shippermasters_id'];
			$branchgroups_id = $this->request->data['branchgroups_id'];
			$cid = $this->request->data['cid'];
			$backdatas = [
				'date' => [
					'year' => $date->format('Y'),
					'month' => $date->format('m'),
					'day' => $date->format('d')
				],
				'date2' => [
					'year' => $date2->format('Y'),
					'month' => $date2->format('m'),
					'day' => $date2->format('d')
				],
				'sid' => $shippermasters_id,
				'bgid' => $branchgroups_id,
				'cid' => $cid,
			];
			$this->Session->write('BackData4',$backdatas);
		}else{
			//セッションの読み込み
			if ($this->Session->check('BackData4')){
				if ($this->Session->check('BackData4.date')) {
					$date = $this->SelectData->date_select($this->Session->read('BackData4.date'));
				}
				if ($this->Session->check('BackData4.date2')) {
					$date2 = $this->SelectData->date_select($this->Session->read('BackData4.date2'));
				}
				$shippermasters_id = $this->Session->read('BackData4.sid');
				$branchgroups_id = $this->Session->read('BackData4.bgid');
				$cid = $this->Session->read('BackData4.cid');
			}else{
				$date = new DateTime();
				$date2 = new DateTime();
				$shippermasters_id = null;
				$branchgroups_id = $this->branchgroups_id;
				$cid = null;
			}
		}
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$carlist = $this->Carmaster->CarListGroup($branchgroups_id);
		if ($cid && !array_key_exists($cid, $carlist)) {
			$cid = null;
		}
		$this->set('carlist',$carlist);
		$selectbranchgroups = $this->SelectData->branchgroup_select_conditions($branchgroups_id,$this->kaisyas_id,$this->rolemasters_id);
		$i = 0;
		foreach($selectbranchgroups as $key => $val){
			array_push($selectbid, $val['Branchmaster']['id']);
			$i = $i + 1;
		}
		$this->set('selectbid',$selectbid);

		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date2->format('Y-m-d');
		$this->set(compact('paramsDate','paramsDate2','shippermasters_id','branchgroups_id','cid'));
		//$branchmastersにbranchgroupに紐づくIDを配列で渡す

		$ordertrans = $this->SelectData->data_select_conditions080_index($paramsDate,$paramsDate2,$did,$shippermasters_id,$selectbid,$cid);
		$this->set('ordertrans',$ordertrans);
	}

	public function ajax_carlist() {
		$this->layout = false;
		$this->autoRender = true;

		$branchgroups_id = $this->branchgroups_id;
		if (!empty($this->request->query['branchgroups_id'])) {
			$branchgroups_id = $this->request->query['branchgroups_id'];
		} elseif (!empty($this->request->data['branchgroups_id'])) {
			$branchgroups_id = $this->request->data['branchgroups_id'];
		}

		$carlist = $this->Carmaster->CarListGroup($branchgroups_id);
		$this->set(compact('carlist'));
	}

	/*
	* edit
	*/
	public function edit($id = null) {
		$kaisyasid = $this->kaisyas_id;
		// $did = 4;
		$did = $this->did;
		$ordertrans = $this->Ordertran->SelectOrdertran080s2($id);

		$lastdate = New DateTime($ordertrans['Ordertran']['loadingdate']);
		$lastday = $lastdate->modify('last day of')->format('Y-m-d');
		$this->set('lastday',$lastday);

		$tanto = $this->Tantomaster->findById($ordertrans['Ordertran']['tantomasters_id']);

		$processtrans = $this->Processtran->SelectProcesstran080s($id);
		// processtranのIDを配列に入れる
		$pkey = array();
		foreach($processtrans as $processtran){
			if($processtran['Processtran']['workplaces_id']){
				// keyの１番目が場所、２番めが作業内容、３番めが商品詳細（３つで１セット）
				$pkey[] = $processtran['Processtran']['workplaces_id'];
				$pkey[] = $processtran['Processtran']['workdetailmasters_id'];
				//商品は別カラムで制御　$pkey[] = $processtran['Processtran']['productcontainers_id'];
				$pkey[] = 0;
			}
		}

		$orderpricemasters = $this->OrdertranPricemaster->SelectOrdertranPrice080s($id);
		$pricemasters = $this->Pricemaster->SelectPricelist($kaisyasid,$did);
		//20230925 $pricemasters2 = $this->Pricemaster->SelectPricemaster($pkey,$kaisyasid);
		$pricemasters2 = $this->Pricemaster->SelectPricemaster($pkey,$kaisyasid,$ordertrans['Ordertran']['shippermasters_id'],$ordertrans['Ordertran']['productcontainers_id']);

		$this->set(compact('ordertrans','processtrans','orderpricemasters','pricemasters','pricemasters2','tanto'));

		if ($this->request->is(array('post', 'put'))) {
			if (isset($this->request->data['order'])) {
				//出荷実績登録は外した
				$this->Ordertran->id = $id;
				$this->request->data['Ordertran']['billconfirmdate'] = $this->request->data['Ordertran']['cdate'].' '.$this->request->data['Ordertran']['ctime'];
				if ($this->request->data['Ordertran']['billconfirmdate']) {
					$this->request->data['Ordertran']['billconfirmflg'] = 1;
				} else {
					$this->request->data['Ordertran']['billconfirmflg'] = 0;
				}
				if ($this->Ordertran->save($this->request->data)) {
					$this->Flash->success('実績を登録しました。');
					return $this->redirect(['action' => 'edit', $id]);
				} else {
					$this->Flash->error('実績の登録に失敗しました。');
				}
			} elseif(isset($this->request->data['price'])) {
				// 存在チェック
				/* 一旦外す
				if($this->Pricemaster->Checkpricemaster($pkey,$kaisyasid)){
					$this->Flash->error('既に価格マスタに登録されています');
					return $this->redirect(['action' => 'edit', $id]);
				}
				*/
				$this->Pricemaster->create();
				$this->request->data['Pricemaster']['departmentmasters_id'] = $this->did;
				$this->request->data['Pricemaster']['kaisyas_id']=$this->kaisyas_id;
				$this->request->data['Pricemaster']['branchmasters_id']=$ordertrans['Carmaster']['branchmasters_id'];
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
				}else{
				    $this->request->data['Pricemaster']['key18'] = 0;
				}
				$w_price = $this->request->data['Pricemaster']['price'];
				$this->request->data['Pricemaster']['pricename'] = $textname;
				$this->request->data['Pricemaster']['shippermasters_id'] = $ordertrans['Ordertran']['shippermasters_id'];
				if ($this->Pricemaster->save($this->request->data)) {
					$pid = $this->Pricemaster->id;
					if (isset($pid)) {
						$opdata = $this->OrdertranPricemaster->create();
						$opdata['OrdertranPricemaster']['pricemasters_id']=$pid;
						$opdata['OrdertranPricemaster']['ordertrans_id']=$id;
						if($this->OrdertranPricemaster->save($opdata)){
							//ordertranの価格を更新
							$pricemasterprice = $this->Pricemaster->ExtractionPrice($pid);
							$orderprice = $this->Ordertran->ExtractionPrice($id);
							$price = $orderprice['Ordertran']['price'] + $pricemasterprice['Pricemaster']['price'];
							$data['Ordertran']['id'] = $id;
							$data['Ordertran']['price'] = $price;
							//ordertranのbillconfirmflg（実績確認）を更新
							$data['correctflg'] = 1;
							$this->Ordertran->save($data);
			
							$this->Flash->success('価格マスタ及び請求内容を登録しました。');
							return $this->redirect(['action' => 'edit', $id]);
						}else{
							$this->Flash->error('価格マスタ及び請求内容の登録に失敗しました。');
						}

						/*
						$this->Ordertran->id = $id;
						$this->request->data['Ordertran']['price'] = $price;
						$this->Ordertran->save($this->request->data);
						*/
					}
					return $this->redirect(['action' => 'edit', $id]);
				} else {
					$this->Flash->error('価格マスタの登録に失敗しました。');
				}
			}elseif(isset($this->request->data['orderprice'])){
			    // 重複チェック
				$post = $this->OrdertranPricemaster->find('first',[
				    'conditions'=>[
				        'OrdertranPricemaster.ordertrans_id'=>$id,
				        'OrdertranPricemaster.pricemasters_id'=>$this->request->data['OrdertranPricemaster']['pricemasters_id'],
				        'OrdertranPricemaster.delflg'=>0
				    ],
				]);
				if($post){
					// 同じキーのデータが存在する場合、登録しない。
					$this->Flash->error('既に価格は請求内容に登録されています');
				}else{
					$this->OrdertranPricemaster->create();
					if($this->OrdertranPricemaster->save($this->request->data)){
						//ordertranの価格を更新
						$pricemasterprice = $this->Pricemaster->ExtractionPrice($this->request->data['OrdertranPricemaster']['pricemasters_id']);
						$orderprice = $this->Ordertran->ExtractionPrice($id);
						$price = $orderprice['Ordertran']['price'] + $pricemasterprice['Pricemaster']['price'];
						$data['Ordertran']['id'] = $id;
						$data['Ordertran']['price'] = $price;
						$this->Ordertran->save($data);
						//ここまで
						$this->Flash->success('価格マスタから請求内容を登録しました。');
						return $this->redirect(['action' => 'edit', $id]);
					}else{
						$this->Flash->error('価格マスタから請求内容の登録に失敗しました。');
					}
				}
			}elseif(isset($this->request->data['keihi'])){
				$this->OrdertranPricemaster->create();
				$this->request->data['OrdertranPricemaster']['ordertrans_id'] = $id;
				if($this->OrdertranPricemaster->save($this->request->data)){
					//ordertranの価格を更新
					$pricemasterprice = $this->Pricemaster->ExtractionPrice($this->request->data['OrdertranPricemaster']['pricemasters_id']);
					$orderprice = $this->Ordertran->ExtractionPrice($id);
					//料金加算
					$price = $orderprice['Ordertran']['price'] + $pricemasterprice['Pricemaster']['price'];
					$data['Ordertran']['id'] = $id;
					$data['Ordertran']['price'] = $price;
					$this->Ordertran->save($data);
					//ここまで
					$this->Flash->success('請求内容に追加しました');
					return $this->redirect(['action'=>'edit',$id]);
				}else{
					$this->Flash->error('諸費追加に失敗しました');
				}
			}elseif(isset($this->request->data['orderpay'])){
				//ordertranのbillconfirmdate（請求確定）を更新
				//20240706 明細の請求日を保存(detailbilldate)
				//20250805 確定時にordertranのpriceを更新するー＞ordertrans_idからordertranpricemasterを読んで集計
				$totalprice = $this->OrdertranPricemaster->find('list',[
					'fields'=>[
						'Pricemaster.price'
					],
					'conditions'=>[
						'OrdertranPricemaster.ordertrans_id'=>$id,
						'OrdertranPricemaster.delflg'=>0
					],
					'contain'=>[
						'Pricemaster'
					],
					'recursive' => -1
				]);
				$totalprice_sum = 0;
				$totalprice_sum = array_sum($totalprice);
				//20250908 orderexpensestranも合計値に入れる
				$totalexpenses = $this->Orderexpensestran->find('list',[
					'fields'=>[
						'Orderexpensestran.expensesprice'
					],
					'conditions'=>[
						'Orderexpensestran.ordertrans_id' => $id
					],
					'recursive' => -1
				]);
				$totalexpenses_sum = 0;
				$totalexpenses_sum = array_sum($totalexpenses);
				$this->Ordertran->save([
					'id' => $id,
					'billconfirmdate' => $this->request->data['User']['billconfirmdate'],
					'detailbilldate' => $this->request->data['User']['detailbilldate'],
					'price' => $totalprice_sum + $totalexpenses_sum,
					'billconfirmflg' => 1
				]);

				$this->Flash->success('請求確定しました');
				return $this->redirect(['action'=>'edit',$id]);
			}elseif(isset($this->request->data['orderpayout'])){
				//ordertranのbillconfirmdate（請求確定）を消す
				$this->Ordertran->save([
					'id' => $id,
					'billconfirmdate' => '',
					'detailbilldate' => '',
					'billconfirmflg' => 0
				]);

				$this->Flash->success('請求を取り消しました');
				return $this->redirect(['action'=>'edit',$id]);
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
					return $this->redirect(['action'=>'edit',$id]);
				}else{
					$this->Flash->error('諸費追加に失敗しました');
				}
			}elseif(isset($this->request->data['kousokudaipay'])){
				//高速代登録
				$otk = $this->Ordertrankousokudai->findByOrdertransId($id);
				if($otk){
					$delsta = $this->Ordertrankousokudai->delete($otk['Ordertrankousokudai']['id']);
				}
				$this->Ordertrankousokudai->create();
				$this->request->data['Ordertrankousokudai']['ordertrans_id'] = $id;
				if($this->Ordertrankousokudai->save($this->request->data)){
					$this->Flash->success('請求内容に高速代を追加しました');
					return $this->redirect(['action'=>'edit',$id]);

				}

			}
		}
		// 諸費データを読み込み
		$orderexpensestrans = $this->Orderexpensestran->find('all',[
			'conditions'=>[
				'ordertrans_id' => $id
			],
			'recursive'=>-1
		]);
		$this->set('orderepensestrans',$orderexpensestrans);
		$ordertrankousokudais = $this->Ordertrankousokudai->findByOrdertransId($id);
		$this->set('ordertrankousokudais',$ordertrankousokudais);

	}

	public function delete($id = null, $oid = null, $pid = null) {
		$this->OrdertranPricemaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		} else {
			$this->request->data['OrdertranPricemaster']['delflg'] = 1;
			$data = $this->Pricemaster->ExtractionPrice($pid);
			$data2 = $this->Ordertran->ExtractionPrice($oid);
			$price = $data2['Ordertran']['price'] - $data['Pricemaster']['price'];
			if ($this->OrdertranPricemaster->save($this->request->data)){
				$this->Flash->success(__('請求内容を削除しました。'));
				if ($price >= 0) {
					$this->Ordertran->id = $oid;
					$this->request->data['Ordertran']['price'] = $price;
					$this->Ordertran->save($this->request->data);
				} else {
					$this->Ordertran->id = $oid;
					$this->request->data['Ordertran']['price'] = 0;
					$this->Ordertran->save($this->request->data);
				}
			} else {
				$this->Flash->error(__('請求内容の削除に失敗しました。'));
 			}
		}
		return $this->redirect(['action' => 'edit', $oid]);

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
		return $this->redirect(['action' => 'edit',$oid]);

	}

}
