<?php
App::uses('AppController', 'Controller');
/**
 * Ch080 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Ch080sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['User','Kaisya','Departmentmaster','Ordertran','Shippermaster','Productdetailmaster','Productcontainer','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Pricemaster','OrdertranPricemaster','Processtran','Branchgroup'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 2;
		$this->page = 'Ch080s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 21; //事務職（化学品）
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
	public function index() {
		// $did = 2;
		// $page = 'Ch080s';
		$did = $this->did;
		$page = $this->page;
		$selectbid = [];
		$cid = '';

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$branchmasters_id = $this->branchmasters_id;

		$branchgroups = $this->Branchgroup->BranchgroupList();
		$this->set('branchgroups',$branchgroups);

		//$branchgroups = $this->Branchgroup->BranchgroupList();
		$confirmflgs = [0 => '未登録', 1 => '登録済'];
		$this->set(compact('shippermasters','billconfirmflgs','branchmasters'));
		$this->set('branchmasters_id',$branchmasters_id);

		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$carlist = $this->Carmaster->CarListGroup($this->branchgroups_id);
		$this->set('carlist',$carlist);

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
			$paramsDate = $date->format('Y-m-d');
			$paramsDate2 = $date2->format('Y-m-d');
			$this->Session->write('BackData4',$backdatas);

			$selectbranchgroups = $this->SelectData->branchgroup_select_conditions($branchgroups_id,$this->kaisyas_id,$this->rolemasters_id);
			$i = 0;
			foreach($selectbranchgroups as $key => $val){
				array_push($selectbid, $val['Branchmaster']['id']);
				$i = $i + 1;
			}
			$this->set('selectbid',$selectbid);

			$ordertrans = $this->SelectData->data_select_conditions080_index($paramsDate,$paramsDate2,$did,$shippermasters_id,$selectbid,$cid);
			$this->set('ordertrans',$ordertrans);

		}else{
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
			$paramsDate = $date->format('Y-m-d');
			$paramsDate2 = $date2->format('Y-m-d');
			$ordertrans = $this->SelectData->data_select_conditions080_index($paramsDate,$paramsDate2,$did,$shippermasters_id,$selectbid,$cid);
			$this->set('ordertrans',$ordertrans);
		}
		$selectbranchgroups = $this->SelectData->branchgroup_select_conditions($branchgroups_id,$this->kaisyas_id,$this->rolemasters_id);
		$i = 0;
		foreach($selectbranchgroups as $key => $val){
			array_push($selectbid, $val['Branchmaster']['id']);
			$i = $i + 1;
		}
		$this->set('selectbid',$selectbid);

		$this->set(compact('paramsDate','paramsDate2','shippermasters_id','branchgroups_id','cid'));
		//$branchmastersにbranchgroupに紐づくIDを配列で渡す
	}

	public function edit($id = null) {
		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);
		$kaisyasid = $this->kaisyas_id;

		// $did = 2;
		$did = $this->did;
		$ordertrans = $this->Ordertran->SelectOrdertran080s2($id);
		$processtrans = $this->Processtran->SelectProcesstran080s($id);
		$lastdate = New DateTime($ordertrans['Ordertran']['loadingdate']);
		$lastday = $lastdate->modify('last day of')->format('Y-m-d');
		$this->set('lastday',$lastday);
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
				$this->Ordertran->save([
					'id' => $id,
					'billconfirmdate' => $this->request->data['User']['billconfirmdate'],
					'detailbilldate' => $this->request->data['User']['detailbilldate'],
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

			}
		}
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

}
