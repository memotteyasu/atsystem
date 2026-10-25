<?php
App::uses('AppController', 'Controller');
/**
 * Oi000 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Oi000sController extends AppController {
	public $layout = 'layout_host';
	public $uses = array('User','Kaisya','Branchmaster','Departmentmaster','Ordertran','Shippermaster','Productdetailmaster','Holiday','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster','Kintaitran','Productcontainer','Trailermaster','Workplace','Tantomaster');
	public $helpers = array('Html','Form','Session','Js' => ['Jquery'],'Format');
	public $components = ['SelectData'];
	/**
	 * Components
	 *
	 * @var array
	 */
	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 1;
		$this->page = 'Oi000s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 20; //事務職（石油）
				case 23; //事務職（コンテナ）
				case 24; //事務職（全て）
				case 25; //事務職（化学品コンテナ）
				$this->set('user', $user);
				return true;
				break;
			}
		}
		return false;
		break;
	}

	/**
	 * index method
	 *
	 * @return void
	 */

	 // 2019-09-21 testとindexを入れ替え
	public function index() {
	    $did = $this->did;
	    $page = $this->page;
	    $branchmasters_id = $this->branchmasters_id;

	    $shippermasters = $this->Shippermaster->SelectShipperList($did);
	    $branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);

	    $this->set(compact('loadingmasters','deliverymasters','shippermasters','branchmasters','containertypes','workplaces'));

	    $shippermasters_id = null;
	    $productdetailmasters_id = null;
	    $date = new DateTime();
	    // 先にSessionがある場合初期値を設定
	    if ($this->Session->check('BackData2')) {
	        if ($this->Session->read('BackData2.Page') == $page) {
	            if ($this->Session->check('BackData2.date')) {
	                $date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
	            }
	            if ($this->Session->check('BackData2.sid')) {
	                if ($this->Session->read('BackData2.sid') == 0) {
	                    $shippermasters_id = null;
	                } else {
	                    $shippermasters_id = $this->Session->read('BackData2.sid');
	                }
	            }
	            if ($this->Session->check('BackData2.bid')) {
	                $branchmasters_id = $this->Session->read('BackData2.bid');
	            }
	        }
	        $this->Session->delete('BackData2');
	    } elseif ($this->Session->check('BackData1')) {
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
	                $branchmasters_id = $this->Session->read('BackData1.bid');
	            }
	        } else {
	            $this->Session->delete('BackData1');
	        }
	    }
	    if ($this->request->is('post')) {
	        $date = new DateTime($this->request->data('selectym1'));
	        $shippermasters_id = $this->request->data('shippermasters_id');
	        $branchmasters_id = $this->request->data('branchmasters_id');

	        if (isset($this->request->data['lastweek'])) {
	            $date->modify('-1 weeks');
	        }
	        if (isset($this->request->data['nextweek'])) {
	            $date->modify('+1 weeks');
	        }
	        $backdatas = [
	            'date' => [
	                'year' => $date->format('Y'),
	                'month' => $date->format('m'),
	                'day' => $date->format('d')
	            ],
	            'sid' => $shippermasters_id,
	            'bid' => $branchmasters_id,
	            'Page' => $page
	        ];
	        $this->Session->write('BackData1', $backdatas);
	        return $this->redirect(['action' => 'index']);
	    }

	    $paramsDate = $date->format('Y-m-d');
	    $paramsDate2 = $date->modify('+6 day')->format('Y-m-d');
	    $week = ['日','月','火','水','木','金','土'];
	    $this->set(compact('paramsDate','week','shippermasters_id','branchmasters_id'));
	    $selectData = $this->SelectData->data_select_conditionsCo000s($paramsDate,$paramsDate2,$did,$shippermasters_id,$branchmasters_id);
	    $truckkinds = $selectData['truckkinds'];
	    $ordertrans = $selectData['ordertrans'];
	    $carmasters = $this->Carmaster->NotRunCar($paramsDate,$paramsDate2);
	    $drivers = $this->Kintaitran->HolidayDriver($paramsDate,$paramsDate2);

	    $this->set(compact('truckkinds','carmasters','drivers','ordertrans'));
	}

	public function add($flg) {
		if (!$this->params['named']) {
			$sid = null;
			$pid = null;
		} else {
			$sid = $this->params['named']['sid'];
			$pid = $this->params['named']['pid'];
		}
		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);

		$backpage = $this->SelectData->backpage_select2($backdata2,$this->page);

		// $did = 1;
		$did = $this->did;

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$loadingmasters = $this->Loadingmaster->SelectLoadingList($did);
		$deliverymasters = $this->Deliverymaster->SelectDeliveryList($did);
		$productdetailmasters = $this->Productdetailmaster->pdnameList2($sid);
		$productcontainers = $this->Productcontainer->SelectProductcontainerList($pid);
		$truckkinds = $this->Truckkind->SelectTruckkindList($pid);
		$truckkind2s = $this->Truckkind->SelectTruckkindList2();

		// $users = $this->User->SelectUserDriversList($did);
		$workdetailmasters = $this->Workdetailmaster->SelectWorkdetailList($did);
		$workplaces = $this->Workplace->SelectWorkplaceList2($did);

		$this->set(compact('shippermasters','loadingmasters','deliverymasters','productdetailmasters','truckkinds','truckkind2s','productcontainers','users','workdetailmasters','workplaces'));

		if ($this->request->is('post')) {
			$this->Ordertran->create();
			$this->request->data['Ordertran']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
			$this->request->data['Ordertran']['carmasters_id'] = $truckkind2s[$this->request->data['Ordertran']['truckkinds_id']];
			//$this->request->data['Ordertran']['loadingdate'] = $this->request->data['Ordertran']['deliverydate'];
			//$this->request->data['Ordertran']['loadingtime'] = $this->request->data['Ordertran']['deliverytime'];
			$carmaster = $this->Carmaster->SelectCarList($this->request->data['Ordertran']['carmasters_id']);
			$this->request->data['Ordertran']['branchmasters_id'] = $carmaster['Carmaster']['branchmasters_id'];
			$this->request->data['Ordertran']['departmentmasters_id'] = $carmaster['Carmaster']['departmentmasters_id'];
			$this->request->data['Ordertran']['color'] = sprintf("#%06x",rand(0x000000, 0xFFFFFF));

			if ($this->Ordertran->save($this->request->data['Ordertran'])) {
				/* ここからProcesstran保存処理 */
				$oid = $this->Ordertran->getLastInsertID();
				$i = 0;
				// $w_productcontainer = 0;

				// 最初にURLから取得しておくことで変数が存在しないというエラーを回避する
				$date = new DateTime($this->params['named']['date']);
				$orderstartdate = $date->format('Y-m-d');
				$orderstarttime = '00:00';
				$orderenddate = $date->format('Y-m-d');
				$orderendtime = '23:59';
				$workplaceid1 = null;
				$workplaceid2 = null;

				foreach ($this->request->data['Processtran'] as $processtran) {
					if ($processtran['workdetailmasters_id']) {
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
				]);

				$this->Flash->success('オーダーを保存しました。');
				$id = null;
				// $backpage = $this->Session->read('BackData2.Page');
				$conditions = $this->SelectData->backpage_select($id,$flg,$backpage);
				return $this->redirect($conditions);
			} else {
				$this->Flash->error('オーダー登録に失敗しました。');
			}
		}
	}

	public function edit($id = null,$flg = null) {
		$post = $this->Ordertran->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}
		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);

		$backpage = $this->SelectData->backpage_select2($backdata2,$this->page);

		// $did = 1;
		$did = $this->did;

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$loadingmasters = $this->Loadingmaster->SelectLoadingList($did);
		$deliverymasters = $this->Deliverymaster->SelectDeliveryList($did);
		$productdetailmasters = $this->Productdetailmaster->pdnameList2($post['Ordertran']['shippermasters_id']);
		$productcontainers = $this->Productcontainer->SelectProductcontainerList($post['Ordertran']['productdetailmasters_id']);
		$truckkinds = $this->Truckkind->SelectTruckkindList($post['Ordertran']['productdetailmasters_id']);
		$truckkind2s = $this->Truckkind->SelectTruckkindList2();

		//$users = $this->User->SelectUserDriversList($did);
		$workdetailmasters = $this->Workdetailmaster->SelectWorkdetailList($did);
		$workplaces = $this->Workplace->SelectWorkplaceList2($did);

		$this->set(compact('shippermasters','loadingmasters','deliverymasters','productdetailmasters','truckkinds','truckkind2s','productcontainers','users','workdetailmasters','workplaces'));

		if ($this->request->is(array('post', 'put'))) {
			$this->Ordertran->id = $id;
			$this->request->data['Ordertran']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
			$this->request->data['Ordertran']['carmasters_id'] = $truckkind2s[$this->request->data['Ordertran']['truckkinds_id']];
			//$this->request->data['Ordertran']['loadingdate'] = $this->request->data['Ordertran']['deliverydate'];
			//$this->request->data['Ordertran']['loadingtime'] = $this->request->data['Ordertran']['deliverytime'];
			$carmaster = $this->Carmaster->SelectCarList($this->request->data['Ordertran']['carmasters_id']);
			$this->request->data['Ordertran']['branchmasters_id'] = $carmaster['Carmaster']['branchmasters_id'];
			$this->request->data['Ordertran']['departmentmasters_id'] = $carmaster['Carmaster']['departmentmasters_id'];

			if ($this->Ordertran->saveAll($this->request->data)) {
				/*
				 * Processtranを読み直して、最初の日時と最後の日時を取得し、Ordertranにセット
				 * Processtranがないor1件のみしかなかった場合に備えて、各種変数の初期値はOrdertranから取得しておく
				 */
				$i = 0;
				$w_productcontainer = 0;
				$orderstartdate = $post['Ordertran']['loadingdate'];
				$orderstarttime = $post['Ordertran']['loadingtime'];
				$orderenddate = $post['Ordertran']['deliverydate'];
				$orderendtime = $post['Ordertran']['deliverytime'];
				$workplaceid1 = $post['Ordertran']['workplaces_id1'];
				$workplaceid2 = $post['Ordertran']['workplaces_id2'];

				if ($this->request->data('Processtran')) {
					foreach ($this->request->data['Processtran'] as $processtran) {
						if ($processtran['workdetailmasters_id']) {
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
							if ($processtran['productcontainers_id']) {
								$w_productcontainer = $processtran['productcontainers_id'];
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
				$conditions = $this->SelectData->backpage_select($id,$flg,$backpage);
				return $this->redirect($conditions);
			} else {
				$this->Flash->error('オーダー編集に失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id = null) {
		$this->Ordertran->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Ordertran']['delflg'] = 1;
			$this->Ordertran->id = $id;
			if ($this->Ordertran->save($this->request->data,false,['delflg'])){
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
				return $this->redirect(['controller' => 'Oi000s','action' => 'index']);
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
	}

}
