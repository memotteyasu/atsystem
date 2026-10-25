<?php
App::uses('AppController', 'Controller');
/**
 * Co081 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Co081sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form','Csv','Format');
	public $uses = ['User','Kaisya','Departmentmaster','Ordertran','Shippermaster','Productdetailmaster','Productcontainer','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Pricemaster','OrdertranPricemaster','Processtran','Workplace','Workdetailmaster','Branchmaster','Branchgroup','Orderexpensestran'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co081s';
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
		// $page = 'Co081s';
		$did = $this->did;
		$page = $this->page;
		$selectbid = [];

		$shippermasters = $this->Shippermaster->SelectShipperListCo081($did);
		$branchmasters_id = $this->branchmasters_id;

		$branchgroups = $this->Branchgroup->BranchgroupList();
		$this->set('branchgroups',$branchgroups);

		$this->set(compact('shippermasters'));
		$this->set('branchmasters_id',$branchmasters_id);

		if ($this->request->is('post')) {
			$branchgroups_id = $this->request->data['branchgroups_id'];
			$date = new DateTime($this->request->data['selectym1']);
			$date2 = new DateTime($this->request->data['selectym2']);
			$shippermasters_id = $this->request->data['shippermasters_id'];
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
			$this->Session->write('BackData5',$backdatas);
		}else{
			//セッションの読み込み
			if ($this->Session->check('BackData5')){
				if ($this->Session->check('BackData5.date')) {
					$date = $this->SelectData->date_select($this->Session->read('BackData5.date'));
				}
				if ($this->Session->check('BackData5.date2')) {
					$date2 = $this->SelectData->date_select($this->Session->read('BackData5.date2'));
				}
				$shippermasters_id = $this->Session->read('BackData5.sid');
				$branchgroups_id = $this->Session->read('BackData5.bgid');
				$cid = $this->Session->read('BackData5.cid');
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
		if($shippermasters_id){
			$ordertrans = $this->SelectData->data_select_conditions081_index($paramsDate,$paramsDate2,$did,$shippermasters_id,$selectbid,$cid);
		}else{
			$ordertrans = [];
		}
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

	public function csv() {
		$bgid = $this->request->data['User']['bgid'];
		$date = $this->request->data['User']['date'];
		$date2 = $this->request->data['User']['date2'];
		$sid = $this->request->data['User']['sid'];
		$cid = $this->request->data['User']['cid'];

		$selectbranchgroups = $this->SelectData->branchgroup_select_conditions($bgid,$this->kaisyas_id,$this->rolemasters_id);
		$i = 0;
		$selectbid = [];
		foreach($selectbranchgroups as $key => $val){
			array_push($selectbid, $val['Branchmaster']['id']);
			$i = $i + 1;
		}

		// レイアウトを使用しない
		$this->layout = '';
		$wdate = new DateTime($date);
		$wdate2 = new DateTime($date2);
		//sidで荷主を検索
		if($sid){
			$ninushi = $this->Shippermaster->find('first',[
				'fields'=>[
					'shippershortname'
				],
				'conditions'=>[
					'Shippermaster.id'=>$sid
				]
			]);
		}	

		$filename = $wdate->format('Ymd')."-".$wdate2->format('Ymd').$ninushi['Shippermaster']['shippershortname'];
/*
		$th = [
			'号車','開始日付','終了日付','担当者','納入先等','金額','コンテナ','REF','品名','コンテナ品名'
		];
*/
		$th = [
			'請求日','担当者','納入先等','コンテナ','REF','コンテナ品名','金額'
		];
		$ordertrans = $this->SelectData->data_select_conditions081_csv($date,$date2,$this->did,$sid,$selectbid,$cid);
		$this->set(compact('filename','th','paramsDate','paramsDate2','ordertrans'));
		$this->set('shippershortname',$ninushi['Shippermaster']['shippershortname']);
		if($cid){
			$plateno = $this->Carmaster->find('first',[
				'fields'=>[
					'Carmaster.plateno'
				],
				'conditions'=>[
					'Carmaster.id'=>$cid
				]
			]);
			$this->set('plateno',$plateno);
		}

	}

	public function ordercar(){
		//0:スタート日、１：エンド日、２：荷主、３：営業所グループ、４：車両
		//セッションの読み込み
		if ($this->Session->check('BackData5')){
			if ($this->Session->check('BackData5.date')) {
				$date = $this->SelectData->date_select($this->Session->read('BackData5.date'));
			}
			if ($this->Session->check('BackData5.date2')) {
				$date2 = $this->SelectData->date_select($this->Session->read('BackData5.date2'));
			}
			$sid = $this->Session->read('BackData5.sid');
			$bgid = $this->Session->read('BackData5.bgid');
			$cid = $this->Session->read('BackData5.cid');
		}else{
			$date = new DateTime();
			$date2 = new DateTime();
			$sid = null;
			$bgid = $this->branchgroups_id;
			$cid = null;
		}

		if($date > $date2){
			//開始日の方が大きい場合はエラー
			$this->Flash->error(('請求日を確認してください'));

		}

		$selectbranchgroups = $this->SelectData->branchgroup_select_conditions($bgid,$this->kaisyas_id,$this->rolemasters_id);
		$i = 0;
		$selectbid = [];
		foreach($selectbranchgroups as $key => $val){
			array_push($selectbid, $val['Branchmaster']['id']);
			$i = $i + 1;
		}


		//sidで荷主を検索
		if($sid){
			$ninushi = $this->Shippermaster->find('first',[
				'fields'=>[
					'shippershortname'
				],
				'conditions'=>[
					'Shippermaster.id'=>$sid
				],
				'recursive' => -1
			]);
		}
		//$carmasters = $this->Carmaster->Carnamelist(4);
		$ordertrans = $this->SelectData->data_select_conditions081_ordercar($date->format('Y-m-d'),$date2->format('Y-m-d'),$this->did,$sid,$selectbid,$cid);

		$this->set(compact('date','date2','ordertrans','selectbid','sid'));
		$this->set('shippershortname',$ninushi);

		$branchgroups = $this->Branchgroup->findById($bgid);
		$this->set('branchgroups',$branchgroups);
	}
}
