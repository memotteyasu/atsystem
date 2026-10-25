<?php
App::uses('AppController', 'Controller');
/**
 * Oi080 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Oi080sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['User','Kaisya','Ordertran','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster','OrdertranPricemaster','Pricemaster'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 1;
		$this->page = 'Oi080s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 20; //事務職（石油）
				case 24; //事務職（全て）
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
	public function index() {
		// $did = 1;
		// $page = 'Oi080s';
		$did = $this->did;
		$page = $this->page;

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$branchmasters_id = $this->branchmasters_id;
		$branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
		$correctflgs = [0 => '未登録', 1 => '登録済'];
		$this->set(compact('shippermasters','correctflgs','branchmasters'));

		$date = new DateTime();
		$date2 = new DateTime();
		$shippermasters_id = null;
		$correctflg = null;

		if ($this->request->is('post')) {
			$date = $this->SelectData->date_select($this->data['selectym1']);
			$date2 = $this->SelectData->date_select($this->data['selectym2']);
			$shippermasters_id = $this->data['shippermasters_id'];
			$correctflg = $this->data['correctflg'];
			$branchmasters_id = $this->request->data('branchmasters_id');
			$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')], 'date2' => ['year' => $date2->format('Y'), 'month' => $date2->format('m'), 'day' => $date2->format('d')], 'sid' => $shippermasters_id, 'bid' => $branchmasters_id, 'cflg' => $correctflg, 'Page' => $page];
			$this->Session->write('BackData1', $backdatas);
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$date2 = $this->SelectData->date_select($this->Session->read('BackData2.date2'));
				$shippermasters_id = $this->Session->read('BackData2.sid');
				$branchmasters_id = $this->Session->read('BackData2.bid');
				$correctflg = $this->Session->read('BackData2.cflg');
			}
			$this->Session->delete('BackData2');
		} else {
			$this->Session->delete('BackData1');
		}

		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date2->format('Y-m-d');
		$this->set(compact('paramsDate','paramsDate2','shippermasters_id','correctflg','branchmasters_id'));

		$ordertrans = $this->SelectData->data_select_conditions080s($paramsDate,$paramsDate2,$did,$shippermasters_id,$correctflg,$branchmasters_id);
		$this->set('ordertrans',$ordertrans);
	}

	public function edit($id = null) {
		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);
		$kaisyasid=$this->Auth->user('kaisyas_id');
		// $did = 1;
		$did = $this->did;
		$ordertrans = $this->Ordertran->SelectOrdertran080s2($id);
		$processtrans = $this->Processtran->SelectProcesstran080s($id);
		// processtranのIDを配列に入れる
		$pkey = array();
		foreach($processtrans as $processtran){
			if($processtran['Processtran']['workplaces_id']){
				$pkey[] = $processtran['Processtran']['workplaces_id'];
			}
		}
		$orderpricemasters = $this->OrdertranPricemaster->SelectOrdertranPrice080s($id);
		$pricemasters = $this->Pricemaster->SelectPricelist($kaisyasid,$did);
		$pricemasters2 = $this->Pricemaster->SelectPricemaster($pkey,$kaisyasid);
		$this->set(compact('ordertrans','processtrans','orderpricemasters','pricemasters','pricemasters2'));

		if ($this->request->is(array('post', 'put'))) {
			if (isset($this->request->data['order'])) {
				$this->Ordertran->id = $id;
				$this->request->data['Ordertran']['correctdate'] = $this->request->data['Ordertran']['cdate'].' '.$this->request->data['Ordertran']['ctime'];
				if ($this->request->data['Ordertran']['correctdate']) {
					$this->request->data['Ordertran']['correctflg'] = 1;
				} else {
					$this->request->data['Ordertran']['correctflg'] = 0;
				}
				if ($this->Ordertran->save($this->request->data)) {
					$this->Flash->success('実績を登録しました。');
					return $this->redirect(['action' => 'edit', $id]);
				} else {
					$this->Flash->error('実績の登録に失敗しました。');
				}
			} elseif(isset($this->request->data['price'])) {
				// 化学品は数量の違いで単価が変わることがあるので、複数マスタ登録できるようにする
				/*
				 if($this->Pricemaster->Checkpricemaster($pkey,$kaisyasid)){
				 $this->Flash->error('既に価格マスタに登録されています');
				 return $this->redirect(['action' => 'edit', $id]);
				 }
				 */
				$this->Pricemaster->create();
				$this->request->data['Pricemaster']['departmentmasters_id'] = $did;
				$this->request->data['Pricemaster']['kaisyas_id']=$kaisyasid;
				if(isset($pkey[0])){
					$this->request->data['Pricemaster']['key1'] = $pkey[0];
				}else{
					$this->request->data['Pricemaster']['key1'] = 0;
				}
				if(isset($pkey[1])){
					$this->request->data['Pricemaster']['key2'] = $pkey[1];
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
				}else{
					$this->request->data['Pricemaster']['key4'] = 0;
				}
				if(isset($pkey[4])){
					$this->request->data['Pricemaster']['key5'] = $pkey[4];
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
				}else{
					$this->request->data['Pricemaster']['key7'] = 0;
				}
				if(isset($pkey[7])){
					$this->request->data['Pricemaster']['key8'] = $pkey[7];
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
				}else{
					$this->request->data['Pricemaster']['key10'] = 0;
				}
				$w_price = $this->request->data['Pricemaster']['price'];
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
							$this->Ordertran->save($data);
							//ここまで
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
				$post = $this->OrdertranPricemaster->findByPricemastersId($this->request->data['OrdertranPricemaster']['pricemasters_id']);
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
			}
		}
	}


	public function edit_back($id = null) {
		$post = $this->Ordertran->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);

		// $did = 1;
		$did = $this->did;

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$loadingmasters = $this->Loadingmaster->SelectLoadingList($did);
		$deliverymasters = $this->Deliverymaster->SelectDeliveryList($did);
		$productdetailmasters = $this->Productdetailmaster->pdnameList2($post['Ordertran']['shippermasters_id']);
		$truckkinds = $this->Truckkind->SelectTruckkindList($post['Ordertran']['productdetailmasters_id']);
		$truckkind2s = $this->Truckkind->SelectTruckkindList2();

		$this->set(compact('shippermasters','loadingmasters','deliverymasters','productdetailmasters','truckkinds','truckkind2s'));

		if ($this->request->is(array('post', 'put'))) {
			$this->Ordertran->id = $id;
			$this->request->data['Ordertran']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
			$this->request->data['Ordertran']['carmasters_id'] = $truckkind2s[$this->request->data['Ordertran']['truckkinds_id']];
			$this->request->data['Ordertran']['loadingdate'] = $this->request->data['Ordertran']['deliverydate'];
			$this->request->data['Ordertran']['loadingtime'] = $this->request->data['Ordertran']['deliverytime'];
			$this->request->data['Ordertran']['correctdate'] = $this->request->data['Ordertran']['cdate'].' '.$this->request->data['Ordertran']['ctime'];
			if ($this->request->data['Ordertran']['correctdate']) {
				$this->request->data['Ordertran']['correctflg'] = 1;
			} else {
				$this->request->data['Ordertran']['correctflg'] = 0;
			}
			if ($this->Ordertran->save($this->request->data)) {
				$this->Flash->success('実績を登録しました。');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('実績の登録に失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

}
