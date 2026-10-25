<?php
App::uses('AppController', 'Controller');
/**
 * Ga200 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
//20240706 detailbilldateを追加
//20250430 諸費は免税ならサーチャージに含めない

 class Ga200sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['User','Kaisya','Ordertran','Shippermaster','Carmaster','Pricemaster','OrdertransPricemaster','Branchmaster','Branchgroup','Invoice','Invoicedetail','Productcontainer','Ordertrankousokudai','Orderexpensestran','Expensestran'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 7;
		$this->page = 'Ga200s';
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
		$did = $this->did;
		$page = $this->page;
		$tansanflg = [];

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$this->set(compact('shippermasters','branchmasters'));


		$date = new DateTime();
		$date2 = new DateTime();
		$shippermasters_id = null;
		$correctflg = null;
		$cid=0;

		if ($this->request->is('post')) {
			$date = new DateTime($this->request->data['selectym1']);
			$date2 = new DateTime($this->request->data['selectym2']);
			$shippermasters_id = $this->data['shippermasters_id'];
			$cid = !empty($this->request->data('c_id')) ? $this->request->data('c_id') : '0';
			$tansanflg = !empty($this->request->data('tansanflg')) ? $this->request->data('tansanflg') : '0';

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
				'tansanflg' => $tansanflg,
				'cid' => $cid,
			];
			$this->Session->write('BackData6',$backdatas);

			if($this->request->data('submit')=='この内容で請求書を作成'){
				// invoice 作成処理
				return $this->redirect(['controller'=>'Ga200s','action'=>'add',['sdate'=>$date,'edate'=>$date2]]);
			}
		}else{
			//セッションの読み込み
			if ($this->Session->check('BackData6')){
				if ($this->Session->check('BackData6.date')) {
					$date = $this->SelectData->date_select($this->Session->read('BackData6.date'));
				}
				if ($this->Session->check('BackData6.date2')) {
					$date2 = $this->SelectData->date_select($this->Session->read('BackData6.date2'));
				}
				$shippermasters_id = $this->Session->read('BackData6.sid');
				$tansanflg = $this->Session->read('BackData5.tansanflg');
				$cid = $this->Session->read('BackData6.cid');
			}else{
				$date = new DateTime();
				$date2 = new DateTime();
				$shippermasters_id = 0;
				$tansanflg = 0;
				$cid = 0;
			}

		}
		$carlist = $this->Carmaster->CarListDepartment($did);

		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date2->format('Y-m-d');
		$this->set(compact('paramsDate','paramsDate2','shippermasters_id','carlist','cid','tansanflg'));
		if($shippermasters_id){
			//値があれば
			$shipperdata = $this->Shippermaster->find('first',[
				'fields'=>[
					'billtype'
				],
				'conditions'=>[
					'Shippermaster.id'=>$shippermasters_id
				],
				'recursive'=>0
			]);
			$this->set('shipperdata',$shipperdata);
		}else{
			$this->set('shipperdata','');
		}
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$shippermasters_id,$cid,$tansanflg);
		$this->set('ordertrans',$ordertrans);
	}

	public function add() {
		// 指定された条件でordertranをselect
		// １件ずつ、ordertran_pricemasterをselectし印字
	   $did = $this->did;
	   $page = $this->page;
	   $tax_flg = 0; //20250304 税の区分をなくしデフォルトで０にする

	   $startdate = $this->Session->read('BackData6.date');
	   $enddate = $this->Session->read('BackData6.date2');
	   $paramsDate = $startdate['year'].'-'.$startdate['month'].'-'.$startdate['day'];
	   $paramsDate2 = $enddate['year'].'-'.$enddate['month'].'-'.$enddate['day'];
	   $sid = $this->Session->read('BackData6.sid');
	   $bgid = $this->Session->read('BackData6.bgid');
	   $cid = $this->Session->read('BackData6.cid');

	   if($sid != ''){
	   }else{
		$this->Flash->error(__('荷主を選択してください'));
		return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
	   }
		if(isset($bgid)){
			$branchgroups = $bgid;
		}else{
			$branchgroups = '';
		}
		if(isset($cid)){
		}else{
			$cid = '';
		}
		$shippermasters = $this->Shippermaster->find('first',[
			'fields'=>[
				'Shippermaster.shippername'
			],
			'conditions'=>[
				'Shippermaster.id'=>$sid
			],
			'recursive' => 0
		]);

	   $this->set('shippermasters',$shippermasters);
	   if ($this->request->is('post')) {

		if (!$this->request->data['Invoice']['subject']){
			$this->Flash->error(__('件名は必須です'));
			return $this->redirect(['action' => 'add', $paramsDate,$paramsDate2,$sid,$cid]);
		}
		//再度表示キーにてデータを読み込む
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$sid,$cid,$branchgroups,$tansanflg);
		//読み込んだデータを１件ずつ、invoicedeitalに登録
		$subtotal1 = $subtotal2 = $subtotal3 = $subtotal4 = $subtotal5 = 0;
		$s = 0;
		$v = 0;

		foreach($ordertrans as $ot){

			$otbmid = $ot['Ordertran']['branchmasters_id'];
			$tantomasterid = '';
			$tantomasterid = $ot['Ordertran']['tantomasters_id'];
			$tantoname = $ot['Tantomaster']['tantoname'];
			//品名、コンテナNo.REFNo.を取得。
			//processtranをforeachして入って入れば変数へ代入
			$pdconname = $hinmei = $conno = $refno = '';
			$pname = [];
			$z = 0;
			foreach($ot['Processtran'] as $otpt){
				$pname[$z] = $otpt['workdetailmasters_id'];
				if($otpt['productcontainers_id']){
					//productcontainersを読み込みセットする
					$pdcon = $this->Productcontainer->find('first',[
						'fields'=>[
							'Productcontainer.pdconname'
						],
						'conditions'=>[
							'Productcontainer.id' => $otpt['productcontainers_id']
						]
					]);
					$pdconname = $pdcon['Productcontainer']['pdconname'];
				}

				if($otpt['productdetailmastername']){
					$hinmei = $otpt['productdetailmastername'];
				}
				if($otpt['container_no']){
					$conno = $otpt['container_no'];
				}
				if($otpt['ref_no']){
					$refno = $otpt['ref_no'];
				}
				$z++;
			}
			if(($hinmei=='')and($pdconname=='')){
				//品名が入ってない場合
				if(in_array('5',$pname)){
					if(in_array('13',$pname)){
						$pdconname = '空コンテナ';
					}
				}
				if(in_array('11',$pname)){
					if(in_array('12',$pname)){
						$pdconname = '実入コンテナ';
					}
				}
			}
			//orertranのinvoice_flgを1で更新（請求書作成済み）
			
			if(!$this->Ordertran->updateAll(['Ordertran.invoice_flg'=>1],['Ordertran.id'=>$ot['Ordertran']['id']])){
				$this->Flash->error(__('オーダーの請求確定に失敗しました。'));
			}
			//ordertranのIDでordertran_pricemasterを読み込む
			$ops = $this->OrdertransPricemaster->find('all',[
				'fields' => [
					'OrdertransPricemaster.ordertrans_id', 
					'OrdertransPricemaster.pricemasters_id',
				],
				'conditions' => [
					'OrdertransPricemaster.ordertrans_id' => $ot['Ordertran']['id'],
					'OrdertransPricemaster.delflg' => 0
				],
				'order'=> [
					'OrdertransPricemaster.created'=>'asc'
				],
				'recursive' => 1
			]);
			$data_check = 0;
			//$v = 0;
			foreach($ops as $op){
				$data_check = 1;
				$pm = $this->Pricemaster->find('first',[
					'fields'=>[
						'Pricemaster.pricename',
						'Pricemaster.price',
						'Pricemaster.keihiflg'
					],
					'conditions'=>[
						'Pricemaster.id'=>$op['OrdertransPricemaster']['pricemasters_id']
					],
					'recursive'=>0
				]);
				if($pm['Pricemaster']['keihiflg']==1){
					//経費分
					$subtotal5 = $subtotal5 + $pm['Pricemaster']['price'];
				}
				//20240331 キャンセル金額が入っているものは別処理
				if($ot['Ordertran']['cancelprice'] > 0){
					$this->request->data['Invoicedetail'][$v]['itemname'] = $pm['Pricemaster']['pricename'];
					$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$v]['unit'] = '式';
					$this->request->data['Invoicedetail'][$v]['price'] = $ot['Ordertran']['cancelprice'];
					$this->request->data['Invoicedetail'][$v]['payment'] = $ot['Ordertran']['cancelprice'];
					$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
					$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
					//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
					$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
					$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
					$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
					$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
					$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
					$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
					$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
					$this->request->data['Invoicedetail'][$v]['delflg'] = 3;
					$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
				}else{
					$this->request->data['Invoicedetail'][$v]['itemname'] = $pm['Pricemaster']['pricename'];
					$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$v]['unit'] = '式';
					$this->request->data['Invoicedetail'][$v]['price'] = $pm['Pricemaster']['price'];
					$this->request->data['Invoicedetail'][$v]['payment'] = $pm['Pricemaster']['price'];
					$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
					$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
					//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
					$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
					$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
					$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
					$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
					$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
					$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
					$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
					$subtotal1 = $subtotal1 + $pm['Pricemaster']['price'];
				}

				
				$v++;
			}
			//20250228 add start
			$oexts = $this->Orderexpensestran->find('all',[
				'fields'=>[
					'Orderexpensestran.expensesname',
					'Orderexpensestran.expensesprice',
				],
				'conditions'=>[
					'Orderexpensestran.ordertrans_id'=>$ot['Ordertran']['id']
				],
				'order'=>[
					'Orderexpensestran.id'=>'asc'
				],
				'recursive'=>0
			]);
			foreach($oexts as $oext){
				$this->request->data['Invoicedetail'][$v]['itemname'] = $oext['Orderexpensestran']['expensesname'];
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $oext['Orderexpensestran']['expensesprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $oext['Orderexpensestran']['expensesprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				$subtotal1 = $subtotal1 + $oext['Orderexpensestran']['expensesprice'];
				$v++;
			}
			//20250228 add end

			if($data_check == 0){
				//ordertran_pricemasterが１件もなかった場合
				$this->request->data['Invoicedetail'][$v]['itemname'] = 'cancel';
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $ot['Ordertran']['cancelprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $ot['Ordertran']['cancelprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				$this->request->data['Invoicedetail'][$v]['delflg'] = 3; //3だったのを０にした->データがないなら３にすべき？
				$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
			}
		}

		//
		$s = $v;
		foreach ($this->request->data['Details2'] as $details2) {
			if ($details2['payment']) {
				$this->request->data['Invoicedetail'][$s]['itemname'] = $details2['itemname'];
				$this->request->data['Invoicedetail'][$s]['quantity'] = $details2['quantity'];
				$this->request->data['Invoicedetail'][$s]['unit'] = $details2['unit'];
				$this->request->data['Invoicedetail'][$s]['price'] = $details2['price'];
				$this->request->data['Invoicedetail'][$s]['payment'] = $details2['payment'];
				$this->request->data['Invoicedetail'][$s]['detaildate'] = $paramsDate2;
				$this->request->data['Invoicedetail'][$s]['tax_flg'] = 1; //免税品目
				$subtotal2 = $subtotal2 + $details2['payment'];
			}
			$s = $s + 1;
		}
		$s2 = $s;
		foreach ($this->request->data['Details3'] as $details3) {
			if ($details3['payment']) {
				$this->request->data['Invoicedetail'][$s2]['itemname'] = $details3['itemname'];
				//$this->request->data['Invoicedetail'][$s]['quantity'] = $details2['quantity'];
				//$this->request->data['Invoicedetail'][$s]['unit'] = $details2['unit'];
				//$this->request->data['Invoicedetail'][$s]['price'] = $details2['price'];
				$this->request->data['Invoicedetail'][$s2]['payment'] = $details3['payment'];
				$this->request->data['Invoicedetail'][$s2]['tax_flg'] = 2; //交通費（税込み入力）
				$this->request->data['Invoicedetail'][$s2]['detaildate'] = $paramsDate2;
				$subtotal3 = $subtotal3 + $details3['payment'];
			}
			$s2 = $s2 + 1;
		}
		$s3 = $s2;
		foreach ($this->request->data['Details4'] as $details4) {
			if ($details4['payment']) {
				$this->request->data['Invoicedetail'][$s3]['itemname'] = $details4['itemname'];
				//$this->request->data['Invoicedetail'][$s]['quantity'] = $details2['quantity'];
				//$this->request->data['Invoicedetail'][$s]['unit'] = $details2['unit'];
				//$this->request->data['Invoicedetail'][$s]['price'] = $details2['price'];
				$this->request->data['Invoicedetail'][$s3]['payment'] = $details4['payment'];
				$this->request->data['Invoicedetail'][$s3]['tax_flg'] = 3; //サーチャージ（税抜き入力）
				$this->request->data['Invoicedetail'][$s3]['detaildate'] = $paramsDate2;
				$subtotal4 = $subtotal4 + $details4['payment'];
			}
			$s2 = $s2 + 1;
		}

		$this->Invoice->create();
		$this->request->data['Invoice']['pagetype'] = $page;
		$this->request->data['Invoice']['delflg'] = 0;
		$this->request->data['Invoice']['tax_flg'] = 0; //20250304 課税免税を無視。課税の帳票か、非課税の帳票か（invoicedetailのtax_flgとは別物）
		$this->request->data['Invoice']['departmentmasters_id']=$did;
		$this->request->data['Invoice']['branchmasters_id']=$otbmid;
		//1:運賃、2:非課税明細、3:交通費、4:サーチャージ
		$this->request->data['Invoice']['subtotal'] = $subtotal1 + ceil($subtotal3 / 1.1) + $subtotal4;
		if($tax_flg==1){ //免税
			$this->request->data['Invoice']['consumptiontax'] = floor($subtotal3 * 0.1);
			$this->request->data['Invoice']['total'] = $this->request->data['Invoice']['subtotal'] + $subtotal2;
		}else{
			$this->request->data['Invoice']['consumptiontax'] = floor($this->request->data['Invoice']['subtotal'] * 0.1);
			$this->request->data['Invoice']['total'] = $this->request->data['Invoice']['subtotal'] + $subtotal2 + $this->request->data['Invoice']['consumptiontax'];
		}
		$this->request->data['Invoice']['subtotal2'] = $subtotal2;
		$this->request->data['Invoice']['subtotal3'] = $subtotal3;
		$this->request->data['Invoice']['subtotal4'] = $subtotal4;
		$this->request->data['Invoice']['subtotal5'] = $subtotal5;
		
		//debug($this->request->data['Invoice']);
		//debug($this->request->data['Invoicedetail']);
		if ($this->Invoice->saveAll($this->request->data)) {
			$this->Flash->success(__('請求書を作成しました。'));
			return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
		} else {
			$this->Flash->error(__('請求書作成に失敗しました。1'));
		}
	   }else{
		//ordertran_pricemasterの読み込み
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$sid,$cid,$branchgroups,$tansanflg);

		$this->set('ordertrans',$ordertrans);
	   }

	}

	public function add2() {
		// free請求書作成
		$did = $this->did;
		$page = $this->page;
		// 		$this->set('shippermasters', $this->Shippermaster->SelectShipperList($did));
		$shippermasters = $this->Shippermaster->find('list',[
			'fields' => ['id','shippername'],
			'conditions'=>['departmentmasters_id' => $did],
			'order' => ['Shippermaster.shippercode' => 'asc']
		]);

		$this->set(compact('shippermasters'));
		$kaisyas = $this->Kaisya->find('list', [
			'fields' => ['id', 'Kaisya.companyname'],
			'order' => ['Kaisya.id' => 'asc'],
			'recursive' => 0,
		]);
		$this->set(compact('kaisyas'));

		if ($this->request->is('post')) {
			$subtotal1 = $subtotal2 = $subtotal3 = $subtotal4 = $subtotal5 = 0;

			$i = 0;
			foreach ($this->request->data['Details'] as $details) {
				if ($details['payment']) {
					$this->request->data['Invoicedetail'][$i]['itemname'] = $details['itemname'];
					//$this->request->data['Invoicedetail'][$i]['quantity'] = $details['quantity'];
					//$this->request->data['Invoicedetail'][$i]['unit'] = $details['unit'];
					//$this->request->data['Invoicedetail'][$i]['price'] = $details['price'];
					$this->request->data['Invoicedetail'][$i]['payment'] = $details['payment'];
					$this->request->data['Invoicedetail'][$i]['detaildate'] = $this->request->data['Invoice']['paymentdate'];
					$subtotal1 = $subtotal1 + $details['payment'];
				}
				$i = $i + 1;
			}
			$s = $i;
			foreach ($this->request->data['Details2'] as $details2) {
				if ($details2['payment']) {
					$this->request->data['Invoicedetail'][$s]['itemname'] = $details2['itemname'];
					//$this->request->data['Invoicedetail'][$s]['quantity'] = $details2['quantity'];
					//$this->request->data['Invoicedetail'][$s]['unit'] = $details2['unit'];
					//$this->request->data['Invoicedetail'][$s]['price'] = $details2['price'];
					$this->request->data['Invoicedetail'][$s]['payment'] = $details2['payment'];
					$this->request->data['Invoicedetail'][$s]['tax_flg'] = 1; //免税品目
					$this->request->data['Invoicedetail'][$s]['detaildate'] = $this->request->data['Invoice']['paymentdate'];
					$subtotal2 = $subtotal2 + $details2['payment'];
				}
				$s = $s + 1;
			}
			$s2 = $s;
			foreach ($this->request->data['Details3'] as $details3) {
				if ($details3['payment']) {
					$this->request->data['Invoicedetail'][$s2]['itemname'] = $details3['itemname'];
					//$this->request->data['Invoicedetail'][$s]['quantity'] = $details2['quantity'];
					//$this->request->data['Invoicedetail'][$s]['unit'] = $details2['unit'];
					//$this->request->data['Invoicedetail'][$s]['price'] = $details2['price'];
					$this->request->data['Invoicedetail'][$s2]['payment'] = $details3['payment'];
					$this->request->data['Invoicedetail'][$s2]['tax_flg'] = 2; //非課税交通費
					$this->request->data['Invoicedetail'][$s2]['detaildate'] = $this->request->data['Invoice']['paymentdate'];
					$subtotal3 = $subtotal3 + $details3['payment'];
				}
				$s2 = $s2 + 1;
			}
			$s3 = $s2;
			foreach ($this->request->data['Details4'] as $details4) {
				if ($details4['payment']) {
					$this->request->data['Invoicedetail'][$s3]['itemname'] = $details4['itemname'];
					//$this->request->data['Invoicedetail'][$s]['quantity'] = $details2['quantity'];
					//$this->request->data['Invoicedetail'][$s]['unit'] = $details2['unit'];
					//$this->request->data['Invoicedetail'][$s]['price'] = $details2['price'];
					$this->request->data['Invoicedetail'][$s3]['payment'] = $details4['payment'];
					$this->request->data['Invoicedetail'][$s3]['tax_flg'] = 3; //サーチャージ（税抜き入力）
					$this->request->data['Invoicedetail'][$s3]['detaildate'] = $this->request->data['Invoice']['paymentdate'];
					$subtotal4 = $subtotal4 + $details4['payment'];
				}
				$s3 = $s3 + 1;
			}
	

			$this->Invoice->create();
			$page = 'Ga200sf';

			$this->request->data['Invoice']['pagetype'] = $page;
			$this->request->data['Invoice']['delflg'] = 0;
			$this->request->data['Invoice']['tax_flg'] = 0;
			$this->request->data['Invoice']['departmentmasters_id']=$did;
			$this->request->data['Invoice']['branchmasters_id']=$this->branchmasters_id;
			//1:運賃、2:非課税明細、3:交通費、4:サーチャージ
			$this->request->data['Invoice']['subtotal'] = $subtotal1 + ceil($subtotal3 / 1.1) + $subtotal4;
			$this->request->data['Invoice']['consumptiontax'] = floor($this->request->data['Invoice']['subtotal'] * 0.1);
			$this->request->data['Invoice']['total'] = $this->request->data['Invoice']['subtotal'] + $subtotal2 + $this->request->data['Invoice']['consumptiontax'];
			$this->request->data['Invoice']['subtotal2'] = $subtotal2;
			$this->request->data['Invoice']['subtotal3'] = $subtotal3;
			$this->request->data['Invoice']['subtotal4'] = $subtotal4;
			$this->request->data['Invoice']['subtotal5'] = $subtotal5;

	
			if ($this->Invoice->saveAll($this->request->data)) {
				$this->Flash->success(__('請求書2を作成しました。'));
				return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
			} else {
				$this->Flash->error(__('請求書2作成に失敗しました。2'));
			}
		}
	}

	public function add3() {
		// free請求書作成（計算無し）
		$did = $this->did;
		$page = $this->page;
		$shippermasters = $this->Shippermaster->find('list',[
			'fields' => ['id','shippername'],
			'conditions'=>['departmentmasters_id' => $did],
			'order' => ['Shippermaster.shippercode' => 'asc']
		]);

		$this->set(compact('shippermasters'));

		if ($this->request->is('post')) {
			$subtotal1 = $subtotal2 = $subtotal3 = $subtotal4 = $subtotal5 = 0;

			$i = 0;
			//課税対象
			foreach ($this->request->data['Details'] as $details) {
				if ($details['payment']) {
					$this->request->data['Invoicedetail'][$i]['itemname'] = $details['itemname'];
					$this->request->data['Invoicedetail'][$i]['payment'] = $details['payment'];
					$this->request->data['Invoicedetail'][$i]['detaildate'] = $this->request->data['Invoice']['paymentdate'];
					$subtotal1 = $subtotal1 + $details['payment'];
				}
				$i = $i + 1;
			}
			$s = $i;
			foreach ($this->request->data['Details2'] as $details2) {
				if ($details2['payment']) {
					$this->request->data['Invoicedetail'][$s]['itemname'] = $details2['itemname'];
					$this->request->data['Invoicedetail'][$s]['payment'] = $details2['payment'];
					$this->request->data['Invoicedetail'][$s]['tax_flg'] = 1; //免税品目
					$this->request->data['Invoicedetail'][$s]['detaildate'] = $this->request->data['Invoice']['paymentdate'];
					$subtotal2 = $subtotal2 + $details2['payment'];
				}
				$s = $s + 1;
			}
			$s2 = $s;
			foreach ($this->request->data['Details3'] as $details3) {
				if ($details3['payment']) {
					$this->request->data['Invoicedetail'][$s2]['itemname'] = $details3['itemname'];
					$this->request->data['Invoicedetail'][$s2]['payment'] = $details3['payment'];
					$this->request->data['Invoicedetail'][$s2]['tax_flg'] = 2; //交通費
					$this->request->data['Invoicedetail'][$s2]['detaildate'] = $this->request->data['Invoice']['paymentdate'];
					$subtotal3 = $subtotal3 + $details3['payment'];
				}
				$s2 = $s2 + 1;
			}
			$s3 = $s2;
			foreach ($this->request->data['Details4'] as $details4) {
				if ($details4['payment']) {
					$this->request->data['Invoicedetail'][$s3]['itemname'] = $details4['itemname'];
					$this->request->data['Invoicedetail'][$s3]['payment'] = $details4['payment'];
					$this->request->data['Invoicedetail'][$s3]['tax_flg'] = 3; //サーチャージ（税抜き入力）
					$this->request->data['Invoicedetail'][$s3]['detaildate'] = $this->request->data['Invoice']['paymentdate'];
					$subtotal4 = $subtotal4 + $details4['payment'];
				}
				$s3 = $s3 + 1;
			}
			$this->Invoice->create();
			$page = 'Ga200sf3';

			$this->request->data['Invoice']['pagetype'] = $page;
			$this->request->data['Invoice']['delflg'] = 0;
			$this->request->data['Invoice']['tax_flg'] = 0;
			$this->request->data['Invoice']['departmentmasters_id']=$did;
			$this->request->data['Invoice']['branchmasters_id']=$this->branchmasters_id;
			//1:運賃、2:非課税明細、3:交通費、4:サーチャージ　全部非課税
			$subtotal1 = $this->request->data['Invoice']['subtotal'];
			$this->request->data['Invoice']['subtotal'] = $subtotal1 + $subtotal2 + $subtotal3 + $subtotal4;
			$this->request->data['Invoice']['consumptiontax'] = $this->request->data['Invoice']['consumptiontax'] + $this->request->data['Invoice']['consumptiontax3'] + $this->request->data['Invoice']['consumptiontax4'];
			$this->request->data['Invoice']['total'] = $this->request->data['Invoice']['subtotal'] + $this->request->data['Invoice']['consumptiontax'];
			$this->request->data['Invoice']['subtotal2'] = $subtotal2;
			$this->request->data['Invoice']['subtotal3'] = $subtotal3;
			$this->request->data['Invoice']['subtotal4'] = $subtotal4;
			$this->request->data['Invoice']['subtotal5'] = $subtotal5;

	
			if ($this->Invoice->saveAll($this->request->data)) {
				$this->Flash->success(__('請求書3を作成しました。'));
				return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
			} else {
				$this->Flash->error(__('請求書3作成に失敗しました。2'));
			}
		}
	}
	/*
	* add_a
	*/
	public function add_a() {
		// 指定された条件でordertranをselect
		// １件ずつ、ordertran_pricemasterをselectし印字
	   $did = $this->did;
	   $page = $this->page;
	   $tax_flg = 0;

	   $startdate = $this->Session->read('BackData6.date');
	   $enddate = $this->Session->read('BackData6.date2');
	   $paramsDate = $startdate['year'].'-'.$startdate['month'].'-'.$startdate['day'];
	   $paramsDate2 = $enddate['year'].'-'.$enddate['month'].'-'.$enddate['day'];
	   $sid = $this->Session->read('BackData6.sid');
	   $bgid = $this->Session->read('BackData6.bgid');
	   $cid = $this->Session->read('BackData6.cid');

	   if($sid != ''){
	   }else{
		$this->Flash->error(__('荷主を選択してください'));
		return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
	   }
		if(isset($bgid)){
			$branchgroups = $bgid;
		}else{
			$branchgroups = '';
		}
		if(isset($cid)){
		}else{
			$cid = '';
		}
		$shippermasters = $this->Shippermaster->find('first',[
			'fields'=>[
				'Shippermaster.shippername'
			],
			'conditions'=>[
				'Shippermaster.id'=>$sid
			],
			'recursive' => 0
		]);

	   $this->set('shippermasters',$shippermasters);
	   if ($this->request->is('post')) {

		if (!$this->request->data['Invoice']['subject']){
			$this->Flash->error(__('件名は必須です'));
			return $this->redirect(['action' => 'add_a', $paramsDate,$paramsDate2,$sid,$cid]);
		}
		//再度表示キーにてデータを読み込む
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$sid,$cid,$branchgroups,$tansanflg);
		//読み込んだデータを１件ずつ、invoicedeitalに登録
		$subtotal1 = $subtotal1m = $subtotal3 = $subtotal5 = 0;
		$s = 0;
		$v = 0;

		foreach($ordertrans as $ot){

			$otbmid = $ot['Ordertran']['branchmasters_id'];
			$tantomasterid = '';
			$tantomasterid = $ot['Ordertran']['tantomasters_id'];
			$tantoname = $ot['Tantomaster']['tantoname'];
			//品名、コンテナNo.REFNo.を取得。
			//processtranをforeachして入って入れば変数へ代入
			$pdconname = $hinmei = $conno = $refno = '';
			$pname = [];
			$z = 0;
			foreach($ot['Processtran'] as $otpt){
				$pname[$z] = $otpt['workdetailmasters_id'];
				if($otpt['productcontainers_id']){
					//productcontainersを読み込みセットする
					$pdcon = $this->Productcontainer->find('first',[
						'fields'=>[
							'Productcontainer.pdconname'
						],
						'conditions'=>[
							'Productcontainer.id' => $otpt['productcontainers_id']
						]
					]);
					$pdconname = $pdcon['Productcontainer']['pdconname'];
				}

				if($otpt['productdetailmastername']){
					$hinmei = $otpt['productdetailmastername'];
				}
				if($otpt['container_no']){
					$conno = $otpt['container_no'];
				}
				if($otpt['ref_no']){
					$refno = $otpt['ref_no'];
				}
				$z++;
			}
			if(($hinmei=='')and($pdconname=='')){
				//品名が入ってない場合
				if(in_array('5',$pname)){
					if(in_array('13',$pname)){
						$pdconname = '空コンテナ';
					}
				}
				if(in_array('11',$pname)){
					if(in_array('12',$pname)){
						$pdconname = '実入コンテナ';
					}
				}
			}
			//orertranのinvoice_flgを1で更新（請求書作成済み）
			
			if(!$this->Ordertran->updateAll(['Ordertran.invoice_flg'=>1],['Ordertran.id'=>$ot['Ordertran']['id']])){
				$this->Flash->error(__('オーダーの請求確定に失敗しました。'));
			}
			//ordertranのIDでordertran_pricemasterを読み込む
			$ops = $this->OrdertransPricemaster->find('all',[
				'fields' => [
					'OrdertransPricemaster.ordertrans_id', 
					'OrdertransPricemaster.pricemasters_id',
				],
				'conditions' => [
					'OrdertransPricemaster.ordertrans_id' => $ot['Ordertran']['id'],
					'OrdertransPricemaster.delflg' => 0
				],
				'order'=> [
					'OrdertransPricemaster.created'=>'asc'
				],
				'recursive' => 1
			]);
			$data_check = 0;
			//$v = 0;
			foreach($ops as $op){
				$data_check = 1;
				$pm = $this->Pricemaster->find('first',[
					'fields'=>[
						'Pricemaster.pricename',
						'Pricemaster.price',
						'Pricemaster.keihiflg'
					],
					'conditions'=>[
						'Pricemaster.id'=>$op['OrdertransPricemaster']['pricemasters_id']
					],
					'recursive'=>0
				]);
				if($pm['Pricemaster']['keihiflg']==1){
					//経費分
					$subtotal5 = $subtotal5 + $pm['Pricemaster']['price'];
				}
				//20240331 キャンセル金額が入っているものは別処理
				if($ot['Ordertran']['cancelprice'] > 0){
					$this->request->data['Invoicedetail'][$v]['itemname'] = $pm['Pricemaster']['pricename'];
					$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$v]['unit'] = '式';
					$this->request->data['Invoicedetail'][$v]['price'] = $ot['Ordertran']['cancelprice'];
					$this->request->data['Invoicedetail'][$v]['payment'] = $ot['Ordertran']['cancelprice'];
					if($ot['Ordertran']['taxflg']==0){
						$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
						$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
					}else{
						if($pm['Pricemaster']['keihiflg']==1){
							$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
							$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
						}else{
							$this->request->data['Invoicedetail'][$v]['tax_flg'] = 1;
							$subtotal1m = $subtotal1m + $ot['Ordertran']['cancelprice'];
						}
					}
					$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
					$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
					$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
					$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
					$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
					$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
					$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
					$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
					$this->request->data['Invoicedetail'][$v]['delflg'] = 3;
				}else{
					$this->request->data['Invoicedetail'][$v]['itemname'] = $pm['Pricemaster']['pricename'];
					$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$v]['unit'] = '式';
					$this->request->data['Invoicedetail'][$v]['price'] = $pm['Pricemaster']['price'];
					$this->request->data['Invoicedetail'][$v]['payment'] = $pm['Pricemaster']['price'];
					if($ot['Ordertran']['taxflg']==0){
						$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
						$subtotal1 = $subtotal1 + $pm['Pricemaster']['price'];
					}else{
						if($pm['Pricemaster']['keihiflg']==1){
							$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
							$subtotal1 = $subtotal1 + $pm['Pricemaster']['price'];
						}else{
							$this->request->data['Invoicedetail'][$v]['tax_flg'] = 1;
							$subtotal1m = $subtotal1m + $pm['Pricemaster']['price'];
						}
					}
					$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
					$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
					$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
					$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
					$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
					$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
					$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
					$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				}
				$v++;
			}
			//20250228 add start
			$oexts = $this->Orderexpensestran->find('all',[
				'fields'=>[
					'Orderexpensestran.expensesname',
					'Orderexpensestran.expensesprice',
				],
				'conditions'=>[
					'Orderexpensestran.ordertrans_id'=>$ot['Ordertran']['id']
				],
				'order'=>[
					'Orderexpensestran.id'=>'asc'
				],
				'recursive'=>0
			]);
			foreach($oexts as $oext){
				$this->request->data['Invoicedetail'][$v]['itemname'] = $oext['Orderexpensestran']['expensesname'];
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $oext['Orderexpensestran']['expensesprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $oext['Orderexpensestran']['expensesprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				//経費はすべて課税対象
				$subtotal1 = $subtotal1 + $oext['Orderexpensestran']['expensesprice'];
				$v++;
			}
			//20250228 add end
			//20250305 高速代読み込み
			$otkds = $this->Ordertrankousokudai->find('all',[
				'fields'=>[
					'Ordertrankousokudai.kousokuprice'
				],
				'conditions'=>[
					'Ordertrankousokudai.ordertrans_id'=>$ot['Ordertran']['id']
				],
				'recursive'=>0
			]);
			foreach($otkds as $otkd){
				$this->request->data['Invoicedetail'][$v]['itemname'] = '高速代';
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $otkd['Ordertrankousokudai']['kousokuprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $otkd['Ordertrankousokudai']['kousokuprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				//高速代はすべて課税対象だが、消費税込みの金額なので合計は別にする？
				$subtotal3 = $subtotal3 + $otkd['Ordertrankousokudai']['kousokuprice'];
				$v++;
			}
			//20250305 add end

			if($data_check == 0){
				//ordertran_pricemasterが１件もなかった場合
				$this->request->data['Invoicedetail'][$v]['itemname'] = 'cancel';
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $ot['Ordertran']['cancelprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $ot['Ordertran']['cancelprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				$this->request->data['Invoicedetail'][$v]['delflg'] = 3; //3だったのを０にした->データがないなら３にすべき？
				if($ot['Ordertran']['taxflg']==0){
					$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
				}else{
					$subtotal1m = $subtotal1m + $ot['Ordertran']['cancelprice'];
				}
			}
		}

		//
		$this->Invoice->create();
		$this->request->data['Invoice']['pagetype'] = $page;
		$this->request->data['Invoice']['tax_flg'] = 0; //20250304 課税免税を無視。課税の帳票か、非課税の帳票か（invoicedetailのtax_flgとは別物）
		$this->request->data['Invoice']['departmentmasters_id']=$did;
		$this->request->data['Invoice']['branchmasters_id']=$otbmid;
		//1:運賃、2:非課税明細、3:交通費、4:サーチャージ
		$this->request->data['Invoice']['subtotal'] = $subtotal1;
		$this->request->data['Invoice']['subtotalm'] = $subtotal1m;
		$this->request->data['Invoice']['subtotal3'] = $subtotal3;
		$this->request->data['Invoice']['consumptiontax'] = floor($this->request->data['Invoice']['subtotal'] * 0.1);
		$this->request->data['Invoice']['total'] = $this->request->data['Invoice']['subtotal'] + $this->request->data['Invoice']['subtotal3'] + $this->request->data['Invoice']['subtotalm'] + $this->request->data['Invoice']['consumptiontax'];
		
		//debug($this->request->data['Invoice']);
		//debug($this->request->data['Invoicedetail']);
		if ($this->Invoice->saveAll($this->request->data)) {
			$this->Flash->success(__('請求書を作成しました。'));
			return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
		} else {
			$this->Flash->error(__('請求書作成に失敗しました。1'));
		}
	   }else{
		//ordertran_pricemasterの読み込み
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$sid,$cid,$branchgroups,$tansanflg);

		$this->set('ordertrans',$ordertrans);
	   }

	}
	/*
	* add_b ※タイプAに東郷した
	*/
	public function add_b() {
		// 指定された条件でordertranをselect
		// １件ずつ、ordertran_pricemasterをselectし印字
	   $did = $this->did;
	   $page = $this->page;
	   $tax_flg = 0;

	   $startdate = $this->Session->read('BackData6.date');
	   $enddate = $this->Session->read('BackData6.date2');
	   $paramsDate = $startdate['year'].'-'.$startdate['month'].'-'.$startdate['day'];
	   $paramsDate2 = $enddate['year'].'-'.$enddate['month'].'-'.$enddate['day'];
	   $sid = $this->Session->read('BackData6.sid');
	   $bgid = $this->Session->read('BackData6.bgid');
	   $cid = $this->Session->read('BackData6.cid');

	   if($sid != ''){
	   }else{
		$this->Flash->error(__('荷主を選択してください'));
		return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
	   }
		if(isset($bgid)){
			$branchgroups = $bgid;
		}else{
			$branchgroups = '';
		}
		if(isset($cid)){
		}else{
			$cid = '';
		}
		$shippermasters = $this->Shippermaster->find('first',[
			'fields'=>[
				'Shippermaster.shippername'
			],
			'conditions'=>[
				'Shippermaster.id'=>$sid
			],
			'recursive' => 0
		]);

	   $this->set('shippermasters',$shippermasters);
	   if ($this->request->is('post')) {

		if (!$this->request->data['Invoice']['subject']){
			$this->Flash->error(__('件名は必須です'));
			return $this->redirect(['action' => 'add_b', $paramsDate,$paramsDate2,$sid,$cid]);
		}
		//再度表示キーにてデータを読み込む
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$sid,$cid,$branchgroups,$tansanflg);
		//読み込んだデータを１件ずつ、invoicedeitalに登録
		$subtotal1 = $subtotal3 = $subtotal5 = 0;
		$s = 0;
		$v = 0;

		foreach($ordertrans as $ot){

			$otbmid = $ot['Ordertran']['branchmasters_id'];
			$tantomasterid = '';
			$tantomasterid = $ot['Ordertran']['tantomasters_id'];
			$tantoname = $ot['Tantomaster']['tantoname'];
			//品名、コンテナNo.REFNo.を取得。
			//processtranをforeachして入って入れば変数へ代入
			$pdconname = $hinmei = $conno = $refno = '';
			$pname = [];
			$z = 0;
			foreach($ot['Processtran'] as $otpt){
				$pname[$z] = $otpt['workdetailmasters_id'];
				if($otpt['productcontainers_id']){
					//productcontainersを読み込みセットする
					$pdcon = $this->Productcontainer->find('first',[
						'fields'=>[
							'Productcontainer.pdconname'
						],
						'conditions'=>[
							'Productcontainer.id' => $otpt['productcontainers_id']
						]
					]);
					$pdconname = $pdcon['Productcontainer']['pdconname'];
				}

				if($otpt['productdetailmastername']){
					$hinmei = $otpt['productdetailmastername'];
				}
				if($otpt['container_no']){
					$conno = $otpt['container_no'];
				}
				if($otpt['ref_no']){
					$refno = $otpt['ref_no'];
				}
				$z++;
			}
			if(($hinmei=='')and($pdconname=='')){
				//品名が入ってない場合
				if(in_array('5',$pname)){
					if(in_array('13',$pname)){
						$pdconname = '空コンテナ';
					}
				}
				if(in_array('11',$pname)){
					if(in_array('12',$pname)){
						$pdconname = '実入コンテナ';
					}
				}
			}
			//orertranのinvoice_flgを1で更新（請求書作成済み）
			
			if(!$this->Ordertran->updateAll(['Ordertran.invoice_flg'=>1],['Ordertran.id'=>$ot['Ordertran']['id']])){
				$this->Flash->error(__('オーダーの請求確定に失敗しました。'));
			}
			//ordertranのIDでordertran_pricemasterを読み込む
			$ops = $this->OrdertransPricemaster->find('all',[
				'fields' => [
					'OrdertransPricemaster.ordertrans_id', 
					'OrdertransPricemaster.pricemasters_id',
				],
				'conditions' => [
					'OrdertransPricemaster.ordertrans_id' => $ot['Ordertran']['id'],
					'OrdertransPricemaster.delflg' => 0
				],
				'order'=> [
					'OrdertransPricemaster.created'=>'asc'
				],
				'recursive' => 1
			]);
			$data_check = 0;
			//$v = 0;
			foreach($ops as $op){
				$data_check = 1;
				$pm = $this->Pricemaster->find('first',[
					'fields'=>[
						'Pricemaster.pricename',
						'Pricemaster.price',
						'Pricemaster.keihiflg'
					],
					'conditions'=>[
						'Pricemaster.id'=>$op['OrdertransPricemaster']['pricemasters_id']
					],
					'recursive'=>0
				]);
				if($pm['Pricemaster']['keihiflg']==1){
					//経費分
					$subtotal5 = $subtotal5 + $pm['Pricemaster']['price'];
				}
				//20240331 キャンセル金額が入っているものは別処理
				if($ot['Ordertran']['cancelprice'] > 0){
					$this->request->data['Invoicedetail'][$v]['itemname'] = $pm['Pricemaster']['pricename'];
					$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$v]['unit'] = '式';
					$this->request->data['Invoicedetail'][$v]['price'] = $ot['Ordertran']['cancelprice'];
					$this->request->data['Invoicedetail'][$v]['payment'] = $ot['Ordertran']['cancelprice'];
					$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
					$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
					//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
					$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
					$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
					$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
					$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
					$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
					$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
					$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
					$this->request->data['Invoicedetail'][$v]['delflg'] = 3;
					$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
				}else{
					//通常のデータ
					$this->request->data['Invoicedetail'][$v]['itemname'] = $pm['Pricemaster']['pricename'];
					$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$v]['unit'] = '式';
					$this->request->data['Invoicedetail'][$v]['price'] = $pm['Pricemaster']['price'];
					$this->request->data['Invoicedetail'][$v]['payment'] = $pm['Pricemaster']['price'];
					$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
					$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
					//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
					$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
					$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
					$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
					$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
					$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
					$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
					$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
					$subtotal1 = $subtotal1 + $pm['Pricemaster']['price'];
				}
				$v++;
			}

			//20250228 add start
			$oexts = $this->Orderexpensestran->find('all',[
				'fields'=>[
					'Orderexpensestran.expensesname',
					'Orderexpensestran.expensesprice',
				],
				'conditions'=>[
					'Orderexpensestran.ordertrans_id'=>$ot['Ordertran']['id']
				],
				'order'=>[
					'Orderexpensestran.id'=>'asc'
				],
				'recursive'=>0
			]);
			foreach($oexts as $oext){
				$this->request->data['Invoicedetail'][$v]['itemname'] = $oext['Orderexpensestran']['expensesname'];
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $oext['Orderexpensestran']['expensesprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $oext['Orderexpensestran']['expensesprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				$subtotal1 = $subtotal1 + $oext['Orderexpensestran']['expensesprice'];
				$v++;
			}
			//20250228 add end

			//20250305 高速代を読み込みinvoicedetailに保存
			$otks = $this->Ordertrankousokudai->findByOrdertransId($ot['Ordertran']['id']);
			if($otks){
				//データがあれば明細を印字
				$this->request->data['Invoicedetail'][$v]['itemname'] = '高速代';
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $otks['Ordertrankousokudai']['kousokuprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $otks['Ordertrankousokudai']['kousokuprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 1;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = '';
				$this->request->data['Invoicedetail'][$v]['refno'] = '';
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = '';
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = '';
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				$subtotal3 = $subtotal3 + $otks['Ordertrankousokudai']['kousokuprice'];
				$v++;
			}

			if($data_check == 0){
				//ordertran_pricemasterが１件もなかった場合
				$this->request->data['Invoicedetail'][$v]['itemname'] = 'cancel';
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $ot['Ordertran']['cancelprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $ot['Ordertran']['cancelprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				$this->request->data['Invoicedetail'][$v]['delflg'] = 3; //3だったのを０にした->データがないなら３にすべき？
				$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
			}
		}

		//
		$s = $v;
		$s2 = $s;

		$this->Invoice->create();
		$this->request->data['Invoice']['pagetype'] = $page;
		$this->request->data['Invoice']['tax_flg'] = 0; //20250304 課税免税を無視。課税の帳票か、非課税の帳票か（invoicedetailのtax_flgとは別物）
		$this->request->data['Invoice']['departmentmasters_id']=$did;
		$this->request->data['Invoice']['branchmasters_id']=$otbmid;
		//1:運賃
		$this->request->data['Invoice']['subtotal'] = $subtotal1;
		if($tax_flg==1){ //免税
			$this->request->data['Invoice']['consumptiontax'] = 0;
			$this->request->data['Invoice']['total'] = $this->request->data['Invoice']['subtotal'];
		}else{
			$this->request->data['Invoice']['consumptiontax'] = floor($this->request->data['Invoice']['subtotal'] * 0.1);
			$this->request->data['Invoice']['total'] = $this->request->data['Invoice']['subtotal'] + $this->request->data['Invoice']['consumptiontax'];
		}
		$this->request->data['Invoice']['subtotal3'] = $subtotal3;
		$this->request->data['Invoice']['subtotal5'] = $subtotal5;
		
		//debug($this->request->data['Invoice']);
		//debug($this->request->data['Invoicedetail']);
		if ($this->Invoice->saveAll($this->request->data)) {
			$this->Flash->success(__('請求書を作成しました。'));
			return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
		} else {
			$this->Flash->error(__('請求書作成に失敗しました。1'));
		}
	   }else{
		//ordertran_pricemasterの読み込み
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$sid,$cid,$branchgroups,$tansanflg);

		$this->set('ordertrans',$ordertrans);
	   }

	}
	/*
	* add_c サーチャージ
	*/
	public function add_c() {
		// 指定された条件でordertranをselect
		// １件ずつ、ordertran_pricemasterをselectし印字

	   $did = $this->did;
	   $page = $this->page;
	   $tax_flg = 0;

	   $startdate = $this->Session->read('BackData6.date');
	   $enddate = $this->Session->read('BackData6.date2');
	   $paramsDate = $startdate['year'].'-'.$startdate['month'].'-'.$startdate['day'];
	   $paramsDate2 = $enddate['year'].'-'.$enddate['month'].'-'.$enddate['day'];
	   $sid = $this->Session->read('BackData6.sid');
	   $cid = $this->Session->read('BackData6.cid');
	   $tansanflg = $this->Session->read('BackData6.tansanflg');
	   if($sid != ''){
	   }else{
		$this->Flash->error(__('荷主を選択してください'));
		return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
	   }
		$branchgroups = '';
		if(isset($cid)){
		}else{
			$cid = '';
		}
		$shippermasters = $this->Shippermaster->find('first',[
			'fields'=>[
				'Shippermaster.shippername',
				'Shippermaster.surcharge',
				'Shippermaster.expensestaxflg'
			],
			'conditions'=>[
				'Shippermaster.id'=>$sid
			],
			'recursive' => 0
		]);

	   $this->set('shippermasters',$shippermasters);
	   $kaisyas = $this->Kaisya->find('list', [
		'fields' => [
			'id','Kaisya.companyname'
		],
		'order' => [
			'Kaisya.id' => 'asc'
		],
		'recursive' => 0,
		]);
		$this->set('kaisyas',$kaisyas);
	   if ($this->request->is('post')) {

		if (!$this->request->data['Invoice']['subject']){
			$this->Flash->error(__('件名は必須です'));
			return $this->redirect(['action' => 'add_c', $paramsDate,$paramsDate2,$sid,$cid,$tansanflg]);
		}
		//再度表示キーにてデータを読み込む
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$sid,$cid,$tansanflg);
		$group1total = $group2total = $group3total = 0;
		if($tansanflg==0){
			//サマリー用（中部）
			$group1total = $this->SelectData->data_select_conditionsGa200_index_sum($paramsDate,$paramsDate2,$did,$sid,$cid,1,$tansanflg);
			//サマリー用（関東）
			$group2total = $this->SelectData->data_select_conditionsGa200_index_sum($paramsDate,$paramsDate2,$did,$sid,$cid,2,$tansanflg);
			//サマリー用（水島）
			$group3total = $this->SelectData->data_select_conditionsGa200_index_sum($paramsDate,$paramsDate2,$did,$sid,$cid,5,$tansanflg);
		}else{
			$group1total = $group2total = $group3total = 0;
		}
		//読み込んだデータを１件ずつ、invoicedeitalに登録
		
		$subtotal1 = $subtotal1m = $subtotal2 = $subtotal3 = $surchargetotal = $surchargetotalm = $subtotal5 = $subtotal6 = 0;
		$s = 0;
		$v = 0;

		//日コンガスは中部と関東の合計を明細に出力するために、group1total,groupt2totalを使って合計を退避する
		foreach($ordertrans as $ot){

			$otbmid = $ot['Ordertran']['branchmasters_id'];
			$tantomasterid = '';
			$tantomasterid = $ot['Ordertran']['tantomasters_id'];
			$tantoname = $ot['Tantomaster']['tantoname'];
			//品名、コンテナNo.REFNo.を取得。
			//processtranをforeachして入って入れば変数へ代入
			$pdconname = $hinmei = $conno = $refno = '';
			$pname = [];
			$z = 0;
			foreach($ot['Processtran'] as $otpt){
				$pname[$z] = $otpt['workdetailmasters_id'];
				if($otpt['productcontainers_id']){
					//productcontainersを読み込みセットする
					$pdcon = $this->Productcontainer->find('first',[
						'fields'=>[
							'Productcontainer.pdconname'
						],
						'conditions'=>[
							'Productcontainer.id' => $otpt['productcontainers_id']
						]
					]);
					$pdconname = $pdcon['Productcontainer']['pdconname'];
				}

				if($otpt['productdetailmastername']){
					$hinmei = $otpt['productdetailmastername'];
				}
				if($otpt['container_no']){
					$conno = $otpt['container_no'];
				}
				if($otpt['ref_no']){
					$refno = $otpt['ref_no'];
				}
				$z++;
			}
			if(($hinmei=='')and($pdconname=='')){
				//品名が入ってない場合
				if(in_array('5',$pname)){
					if(in_array('13',$pname)){
						$pdconname = '空コンテナ';
					}
				}
				if(in_array('11',$pname)){
					if(in_array('12',$pname)){
						$pdconname = '実入コンテナ';
					}
				}
			}
			//orertranのinvoice_flgを1で更新（請求書作成済み）
			
			if(!$this->Ordertran->updateAll(['Ordertran.invoice_flg'=>1],['Ordertran.id'=>$ot['Ordertran']['id']])){
				$this->Flash->error(__('オーダーの請求確定に失敗しました。'));
			}
			//ordertranのIDでordertran_pricemasterを読み込む
			$ops = $this->OrdertransPricemaster->find('all',[
				'fields' => [
					'OrdertransPricemaster.ordertrans_id', 
					'OrdertransPricemaster.pricemasters_id',
					'OrdertransPricemaster.keihitaxflg'
				],
				'conditions' => [
					'OrdertransPricemaster.ordertrans_id' => $ot['Ordertran']['id'],
					'OrdertransPricemaster.delflg' => 0
				],
				'order'=> [
					'OrdertransPricemaster.created'=>'asc'
				],
				'recursive' => 1
			]);
			$data_check = 0;
			//$v = 0;
			foreach($ops as $op){
				$data_check = 1;
				$pm = $this->Pricemaster->find('first',[
					'fields'=>[
						'Pricemaster.pricename',
						'Pricemaster.price',
						'Pricemaster.keihiflg'
					],
					'conditions'=>[
						'Pricemaster.id'=>$op['OrdertransPricemaster']['pricemasters_id']
					],
					'recursive'=>0
				]);
				if($pm['Pricemaster']['keihiflg']==1){
					//経費分
					$subtotal5 = $subtotal5 + $pm['Pricemaster']['price'];
				}
				//20240331 キャンセル金額が入っているものは別処理。キャンセル代はサーチャージに入れない
				if($ot['Ordertran']['cancelprice'] > 0){
					$this->request->data['Invoicedetail'][$v]['itemname'] = $pm['Pricemaster']['pricename'];
					$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$v]['unit'] = '式';
					$this->request->data['Invoicedetail'][$v]['price'] = $ot['Ordertran']['cancelprice'];
					$this->request->data['Invoicedetail'][$v]['payment'] = $ot['Ordertran']['cancelprice'];
					if($ot['Ordertran']['taxflg']==0){
						$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
						$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
					}else{
						//免税の場合
						if($pm['Pricemaster']['keihiflg']==1){
							if($shippermasters['Shippermaster']['expensestaxflg']==0){
								if($op['OrdertransPricemaster']['keihitaxflg']==1){
									$this->request->data['Invoicedetail'][$v]['tax_flg'] = 1;
									$subtotal1m = $subtotal1m + $ot['Ordertran']['cancelprice'];
								}else{
									$this->request->data['Invoicedetail'][$v]['tax_flg'] = 1;
									$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
								}
							}else{
								if($op['OrdertransPricemaster']['keihitaxflg']==1){
									$this->request->data['Invoicedetail'][$v]['tax_flg'] = 1;
									$subtotal1m = $subtotal1m + $ot['Ordertran']['cancelprice'];
								}else{
									$this->request->data['Invoicedetail'][$v]['tax_flg'] = 1;
									$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
								}
							}
						}else{
							$this->request->data['Invoicedetail'][$v]['tax_flg'] = 1;
							$subtotal1m = $subtotal1m + $ot['Ordertran']['cancelprice'];
						}
					}
					$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
					$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
					$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
					$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
					$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
					$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
					$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
					$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
					$this->request->data['Invoicedetail'][$v]['delflg'] = 3;
				}else{
					//通常のオーダーの場合
					$this->request->data['Invoicedetail'][$v]['itemname'] = $pm['Pricemaster']['pricename'];
					$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$v]['unit'] = '式';
					$this->request->data['Invoicedetail'][$v]['price'] = $pm['Pricemaster']['price'];
					$this->request->data['Invoicedetail'][$v]['payment'] = $pm['Pricemaster']['price'];
					if($ot['Ordertran']['taxflg']==0){
						//課税
						if($op['OrdertransPricemaster']['keihitaxflg']==1){
							$this->request->data['Invoicedetail'][$v]['tax_flg'] = 1;
							$subtotal1m = $subtotal1m + $pm['Pricemaster']['price'];
						}else{
							$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
							$subtotal1 = $subtotal1 + $pm['Pricemaster']['price'];
						}
						if($pm['Pricemaster']['keihiflg'] != 1){
							$surchargetotal = $surchargetotal + $pm['Pricemaster']['price'];
						}
					}else{
						//免税
						if($pm['Pricemaster']['keihiflg']==1){
							if($op['OrdertransPricemaster']['keihitaxflg']==1){
								$this->request->data['Invoicedetail'][$v]['tax_flg'] = 1;
								$subtotal1m = $subtotal1m + $pm['Pricemaster']['price'];
							}else{
								$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
								$subtotal1 = $subtotal1 + $pm['Pricemaster']['price'];
							}
						}else{
							$this->request->data['Invoicedetail'][$v]['tax_flg'] = 1;
							$subtotal1m = $subtotal1m + $pm['Pricemaster']['price'];
							//$surchargetotal = $surchargetotal + $pm['Pricemaster']['price'];
							$surchargetotalm = $surchargetotalm + $pm['Pricemaster']['price'];
						}
					}
					$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
					$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
					$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
					$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
					$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
					$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
					$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
					$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				}
				$v++;
			}
			//20250228 add start
			$oexts = $this->Orderexpensestran->find('all',[
				'fields'=>[
					'Orderexpensestran.expensesname',
					'Orderexpensestran.expensesprice',
				],
				'conditions'=>[
					'Orderexpensestran.ordertrans_id'=>$ot['Ordertran']['id']
				],
				'order'=>[
					'Orderexpensestran.id'=>'asc'
				],
				'recursive'=>0
			]);
			foreach($oexts as $oext){
				$this->request->data['Invoicedetail'][$v]['itemname'] = $oext['Orderexpensestran']['expensesname'];
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $oext['Orderexpensestran']['expensesprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $oext['Orderexpensestran']['expensesprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				//経費はすべて課税対象
				$subtotal1 = $subtotal1 + $oext['Orderexpensestran']['expensesprice'];
				$v++;
			}
			//20250228 add end
			//20250305 高速代読み込み
			$otkds = $this->Ordertrankousokudai->find('all',[
				'fields'=>[
					'Ordertrankousokudai.kousokuprice'
				],
				'conditions'=>[
					'Ordertrankousokudai.ordertrans_id'=>$ot['Ordertran']['id']
				],
				'recursive'=>0
			]);
			foreach($otkds as $otkd){
				$this->request->data['Invoicedetail'][$v]['itemname'] = '高速代';
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $otkd['Ordertrankousokudai']['kousokuprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $otkd['Ordertrankousokudai']['kousokuprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				//高速代はすべて課税対象だが、消費税込みの金額なので合計は別にする？
				$subtotal3 = $subtotal3 + $otkd['Ordertrankousokudai']['kousokuprice'];
				$v++;
			}
			//20250305 add end

			if($data_check == 0){
				//ordertran_pricemasterが１件もなかった場合
				$this->request->data['Invoicedetail'][$v]['itemname'] = 'cancel';
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $ot['Ordertran']['cancelprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $ot['Ordertran']['cancelprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				$this->request->data['Invoicedetail'][$v]['delflg'] = 3; //3だったのを０にした->データがないなら３にすべき？
				if($ot['Ordertran']['taxflg']==0){
					$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
				}else{
					$subtotal1m = $subtotal1m + $ot['Ordertran']['cancelprice'];
				}
			}
		}

		$expensestrans = $this->Expensestran->find('all',[
			'conditions'=>[
				'Expensestran.billconfirmdate >=' => $paramsDate,
				'Expensestran.billconfirmdate <=' => $paramsDate2,
				'Expensestran.shippermasters_id' => $sid,
				'Expensestran.departmentmasters_id' => $did,
			],
			'order'=>[
				'Expensestran.billconfirmdate' => 'asc'
			]
		]);
		foreach($expensestrans as $expt){
			$this->request->data['Invoicedetail'][$v]['itemname'] = $expt['Expensestran']['expensesname'];
			$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
			$this->request->data['Invoicedetail'][$v]['unit'] = '式';
			$this->request->data['Invoicedetail'][$v]['price'] = $expt['Expensestran']['expensesprice'];
			$this->request->data['Invoicedetail'][$v]['payment'] = $expt['Expensestran']['expensesprice'];
			$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
			$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
			$this->request->data['Invoicedetail'][$v]['detaildate'] = $expt['Expensestran']['billconfirmdate'];
			$this->request->data['Invoicedetail'][$v]['containerno'] = '';
			$this->request->data['Invoicedetail'][$v]['refno'] = '';
			$this->request->data['Invoicedetail'][$v]['productdetailname'] = '';
			$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = 0;
			$this->request->data['Invoicedetail'][$v]['carmasters_id'] = 0;
			$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = 0;
			$this->request->data['Invoicedetail'][$v]['tantoname'] = '';
			$subtotal6 = $subtotal6 +  $expt['Expensestran']['expensesprice'];
			$v++;
		}

		//20250401 会社を選択された情報にする
		$this->Invoice->create();
		$this->request->data['Invoice']['pagetype'] = $page;
		$this->request->data['Invoice']['delflg'] = 0;
		$this->request->data['Invoice']['tax_flg'] = 0; //20250304 課税免税を無視。課税の帳票か、非課税の帳票か（invoicedetailのtax_flgとは別物）
		$this->request->data['Invoice']['departmentmasters_id']=$did;
		$this->request->data['Invoice']['branchmasters_id']=$otbmid;
		//1:運賃、2:非課税明細、3:交通費、4:サーチャージ、６：別途諸経費
		$this->request->data['Invoice']['subtotal'] = $subtotal1;
		$this->request->data['Invoice']['subtotal2'] = $subtotal2;
		$this->request->data['Invoice']['subtotalm'] = $subtotal1m;
		$this->request->data['Invoice']['subtotal3'] = $subtotal3;
		$subtotal4 = $surchargetotal * $shippermasters['Shippermaster']['surcharge'] / 100;
		$subtotal4m = $surchargetotalm * $shippermasters['Shippermaster']['surcharge'] / 100;
		$surchargetotalm_tax = floor(($surchargetotalm * $shippermasters['Shippermaster']['surcharge'] / 100) * 0.1);
		//免税のサーチャージを消費税計算しない
		$this->request->data['Invoice']['subtotal4'] = $subtotal4;
		$this->request->data['Invoice']['subtotal4m'] = $subtotal4m;
		$this->request->data['Invoice']['subtotal5'] = $subtotal5;
		$this->request->data['Invoice']['subtotal6'] = $subtotal6;
		//20250430 $this->request->data['Invoice']['consumptiontax'] = floor(($this->request->data['Invoice']['subtotal'] + $this->request->data['Invoice']['subtotal4']) * 0.1);
		$this->request->data['Invoice']['consumptiontax'] = floor(($this->request->data['Invoice']['subtotal'] + $this->request->data['Invoice']['subtotal4'] + $this->request->data['Invoice']['subtotal6'] + $this->request->data['Invoice']['subtotal4m']) * 0.1) - $surchargetotalm_tax;
		$this->request->data['Invoice']['total'] = $this->request->data['Invoice']['subtotal'] + $this->request->data['Invoice']['subtotal3'] + $this->request->data['Invoice']['subtotal4'] + $this->request->data['Invoice']['subtotal6'] + $this->request->data['Invoice']['subtotal4m'] + $this->request->data['Invoice']['subtotalm'] + $this->request->data['Invoice']['consumptiontax'];
		//グループ合計を追加
		if(empty($group1total[0]['grouptotal_price'])){
			$this->request->data['Invoice']['group1total'] = 0;
		}else{
			$this->request->data['Invoice']['group1total'] = $group1total[0]['grouptotal_price'];
		}
		if(empty($group2total[0]['grouptotal_price'])){
			$this->request->data['Invoice']['group2total'] = 0;
		}else{
			$this->request->data['Invoice']['group2total'] = $group2total[0]['grouptotal_price'];
		}
		if(empty($group3total[0]['grouptotal_price'])){
			$this->request->data['Invoice']['group3total'] = 0;
		}else{
			$this->request->data['Invoice']['group3total'] = $group3total[0]['grouptotal_price'];
		}
		//debug($this->request->data['Invoice']);
		//debug($this->request->data['Invoicedetail']);
		if ($this->Invoice->saveAll($this->request->data)) {
			$this->Flash->success(__('請求書を作成しました。'));
			return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
		} else {
			$this->Flash->error(__('請求書作成に失敗しました。1'));
		}
	   }else{
		//ordertran_pricemasterの読み込み
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$sid,$cid,$tansanflg);
		$expensestrans = $this->Expensestran->find('all',[
			'conditions'=>[
				'Expensestran.billconfirmdate >=' => $paramsDate,
				'Expensestran.billconfirmdate <=' => $paramsDate2,
				'Expensestran.shippermasters_id' => $sid,
				'Expensestran.departmentmasters_id' => $did,
			],
			'order'=>[
				'Expensestran.billconfirmdate' => 'asc'
			]
		]);
		$options = [];
		foreach($expensestrans as $record){
			$options[$record['Expensestran']['id']] = '('.$record['Expensestran']['billconfirmdate'] . ') ' . $record['Expensestran']['expensesname'] . ' / ' . $record['Expensestran']['expensesprice'] . '円';
		}
		$this->set('ordertrans',$ordertrans);
		$this->set('expensestranOptions',$options);
	   }

	}

	/*
	* add_d
	*/
	public function add_d() {
		// 指定された条件でordertranをselect
		// １件ずつ、ordertran_pricemasterをselectし印字
	   $did = $this->did;
	   $page = $this->page;
	   $tax_flg = 0;

	   $startdate = $this->Session->read('BackData6.date');
	   $enddate = $this->Session->read('BackData6.date2');
	   $paramsDate = $startdate['year'].'-'.$startdate['month'].'-'.$startdate['day'];
	   $paramsDate2 = $enddate['year'].'-'.$enddate['month'].'-'.$enddate['day'];
	   $sid = $this->Session->read('BackData6.sid');
	   $bgid = $this->Session->read('BackData6.bgid');
	   $cid = $this->Session->read('BackData6.cid');

	   if($sid != ''){
	   }else{
		$this->Flash->error(__('荷主を選択してください'));
		return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
	   }
		if(isset($bgid)){
			$branchgroups = $bgid;
		}else{
			$branchgroups = '';
		}
		if(isset($cid)){
		}else{
			$cid = '';
		}
		$shippermasters = $this->Shippermaster->find('first',[
			'fields'=>[
				'Shippermaster.shippername',
				'Shippermaster.surcharge'
			],
			'conditions'=>[
				'Shippermaster.id'=>$sid
			],
			'recursive' => 0
		]);

	   $this->set('shippermasters',$shippermasters);
	   if ($this->request->is('post')) {

		if (!$this->request->data['Invoice']['subject']){
			$this->Flash->error(__('件名は必須です'));
			return $this->redirect(['action' => 'add_d', $paramsDate,$paramsDate2,$sid,$cid]);
		}
		//再度表示キーにてデータを読み込む
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$sid,$cid,$branchgroups,$tansanflg);
		//読み込んだデータを１件ずつ、invoicedeitalに登録
		$subtotal1 = $subtotal2 = $subtotal3 = $subtotal4 = $subtotal5 = 0;
		$s = 0;
		$v = 0;

		foreach($ordertrans as $ot){

			$otbmid = $ot['Ordertran']['branchmasters_id'];
			$tantomasterid = '';
			$tantomasterid = $ot['Ordertran']['tantomasters_id'];
			$tantoname = $ot['Tantomaster']['tantoname'];
			//品名、コンテナNo.REFNo.を取得。
			//processtranをforeachして入って入れば変数へ代入
			$pdconname = $hinmei = $conno = $refno = '';
			$pname = [];
			$z = 0;
			foreach($ot['Processtran'] as $otpt){
				$pname[$z] = $otpt['workdetailmasters_id'];
				if($otpt['productcontainers_id']){
					//productcontainersを読み込みセットする
					$pdcon = $this->Productcontainer->find('first',[
						'fields'=>[
							'Productcontainer.pdconname'
						],
						'conditions'=>[
							'Productcontainer.id' => $otpt['productcontainers_id']
						]
					]);
					$pdconname = $pdcon['Productcontainer']['pdconname'];
				}

				if($otpt['productdetailmastername']){
					$hinmei = $otpt['productdetailmastername'];
				}
				if($otpt['container_no']){
					$conno = $otpt['container_no'];
				}
				if($otpt['ref_no']){
					$refno = $otpt['ref_no'];
				}
				$z++;
			}
			if(($hinmei=='')and($pdconname=='')){
				//品名が入ってない場合
				if(in_array('5',$pname)){
					if(in_array('13',$pname)){
						$pdconname = '空コンテナ';
					}
				}
				if(in_array('11',$pname)){
					if(in_array('12',$pname)){
						$pdconname = '実入コンテナ';
					}
				}
			}
			//orertranのinvoice_flgを1で更新（請求書作成済み）
			
			if(!$this->Ordertran->updateAll(['Ordertran.invoice_flg'=>1],['Ordertran.id'=>$ot['Ordertran']['id']])){
				$this->Flash->error(__('オーダーの請求確定に失敗しました。'));
			}
			//ordertranのIDでordertran_pricemasterを読み込む
			$ops = $this->OrdertransPricemaster->find('all',[
				'fields' => [
					'OrdertransPricemaster.ordertrans_id', 
					'OrdertransPricemaster.pricemasters_id',
				],
				'conditions' => [
					'OrdertransPricemaster.ordertrans_id' => $ot['Ordertran']['id'],
					'OrdertransPricemaster.delflg' => 0
				],
				'order'=> [
					'OrdertransPricemaster.created'=>'asc'
				],
				'recursive' => 1
			]);
			$data_check = 0;
			//$v = 0;
			foreach($ops as $op){
				$data_check = 1;
				$pm = $this->Pricemaster->find('first',[
					'fields'=>[
						'Pricemaster.pricename',
						'Pricemaster.price',
						'Pricemaster.keihiflg'
					],
					'conditions'=>[
						'Pricemaster.id'=>$op['OrdertransPricemaster']['pricemasters_id']
					],
					'recursive'=>0
				]);
				if($pm['Pricemaster']['keihiflg']==1){
					//経費分
					$subtotal5 = $subtotal5 + $pm['Pricemaster']['price'];
				}
				//20240331 キャンセル金額が入っているものは別処理
				if($ot['Ordertran']['cancelprice'] > 0){
					$this->request->data['Invoicedetail'][$v]['itemname'] = $pm['Pricemaster']['pricename'];
					$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$v]['unit'] = '式';
					$this->request->data['Invoicedetail'][$v]['price'] = $ot['Ordertran']['cancelprice'];
					$this->request->data['Invoicedetail'][$v]['payment'] = $ot['Ordertran']['cancelprice'];
					$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
					$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
					//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
					$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
					$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
					$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
					$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
					$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
					$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
					$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
					$this->request->data['Invoicedetail'][$v]['delflg'] = 3;
					$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
				}else{
					$this->request->data['Invoicedetail'][$v]['itemname'] = $pm['Pricemaster']['pricename'];
					$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$v]['unit'] = '式';
					$this->request->data['Invoicedetail'][$v]['price'] = $pm['Pricemaster']['price'];
					$this->request->data['Invoicedetail'][$v]['payment'] = $pm['Pricemaster']['price'];
					$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
					$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
					//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
					$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
					$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
					$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
					$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
					$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
					$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
					$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
					$subtotal1 = $subtotal1 + $pm['Pricemaster']['price'];
				}

				
				$v++;
			}
			//20250228 add start
			$oexts = $this->Orderexpensestran->find('all',[
				'fields'=>[
					'Orderexpensestran.expensesname',
					'Orderexpensestran.expensesprice',
				],
				'conditions'=>[
					'Orderexpensestran.ordertrans_id'=>$ot['Ordertran']['id']
				],
				'order'=>[
					'Orderexpensestran.id'=>'asc'
				],
				'recursive'=>0
			]);
			foreach($oexts as $oext){
				$this->request->data['Invoicedetail'][$v]['itemname'] = $oext['Orderexpensestran']['expensesname'];
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $oext['Orderexpensestran']['expensesprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $oext['Orderexpensestran']['expensesprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				$subtotal1 = $subtotal1 + $oext['Orderexpensestran']['expensesprice'];
				$v++;
			}
			//20250228 add end

			if($data_check == 0){
				//ordertran_pricemasterが１件もなかった場合
				$this->request->data['Invoicedetail'][$v]['itemname'] = 'cancel';
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $ot['Ordertran']['cancelprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $ot['Ordertran']['cancelprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				$this->request->data['Invoicedetail'][$v]['delflg'] = 3; //3だったのを０にした->データがないなら３にすべき？
				$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
			}
		}

		//
		$s = $v;
		foreach ($this->request->data['Details2'] as $details2) {
			if ($details2['payment']) {
				$this->request->data['Invoicedetail'][$s]['itemname'] = $details2['itemname'];
				$this->request->data['Invoicedetail'][$s]['quantity'] = $details2['quantity'];
				$this->request->data['Invoicedetail'][$s]['unit'] = $details2['unit'];
				$this->request->data['Invoicedetail'][$s]['price'] = $details2['price'];
				$this->request->data['Invoicedetail'][$s]['payment'] = $details2['payment'];
				$this->request->data['Invoicedetail'][$s]['detaildate'] = $paramsDate2;
				$this->request->data['Invoicedetail'][$s]['tax_flg'] = 2; //免税品目
				$subtotal2 = $subtotal2 + $details2['payment'];
			}
			$s = $s + 1;
		}
		$s2 = $s;
		foreach ($this->request->data['Details3'] as $details3) {
			if ($details3['payment']) {
				$this->request->data['Invoicedetail'][$s2]['itemname'] = $details3['itemname'];
				//$this->request->data['Invoicedetail'][$s]['quantity'] = $details2['quantity'];
				//$this->request->data['Invoicedetail'][$s]['unit'] = $details2['unit'];
				//$this->request->data['Invoicedetail'][$s]['price'] = $details2['price'];
				$this->request->data['Invoicedetail'][$s2]['payment'] = $details3['payment'];
				$this->request->data['Invoicedetail'][$s2]['tax_flg'] = 3; //交通費（税込み入力）
				$this->request->data['Invoicedetail'][$s2]['detaildate'] = $paramsDate2;
				$subtotal3 = $subtotal3 + $details3['payment'];
			}
			$s2 = $s2 + 1;
		}
		$s3 = $s2;
		foreach ($this->request->data['Details4'] as $details4) {
			if ($details4['payment']) {
				$this->request->data['Invoicedetail'][$s3]['itemname'] = $details4['itemname'];
				//$this->request->data['Invoicedetail'][$s]['quantity'] = $details2['quantity'];
				//$this->request->data['Invoicedetail'][$s]['unit'] = $details2['unit'];
				//$this->request->data['Invoicedetail'][$s]['price'] = $details2['price'];
				$this->request->data['Invoicedetail'][$s3]['payment'] = $details4['payment'];
				$this->request->data['Invoicedetail'][$s3]['tax_flg'] = 4; //サーチャージ（税抜き入力）
				$this->request->data['Invoicedetail'][$s3]['detaildate'] = $paramsDate2;
				$subtotal4 = $subtotal4 + $details4['payment'];
			}
			$s2 = $s2 + 1;
		}

		$this->Invoice->create();
		$this->request->data['Invoice']['pagetype'] = $page;
		$this->request->data['Invoice']['tax_flg'] = 0; //課税の帳票か、非課税の帳票か（invoicedetailのtax_flgとは別物）
		$this->request->data['Invoice']['departmentmasters_id']=$did;
		$this->request->data['Invoice']['branchmasters_id']=$otbmid;
		//1:運賃、2:非課税明細、3:交通費、4:サーチャージ
		$this->request->data['Invoice']['subtotal'] = $subtotal1;
		if($tax_flg==1){ //免税
			$this->request->data['Invoice']['consumptiontax'] = floor($subtotal3 * 0.1);
			$this->request->data['Invoice']['total'] = $this->request->data['Invoice']['subtotal'] + $subtotal2;
		}else{
			$this->request->data['Invoice']['consumptiontax'] = floor($this->request->data['Invoice']['subtotal'] * 0.1);
			$this->request->data['Invoice']['total'] = $this->request->data['Invoice']['subtotal'] + $subtotal2 + $this->request->data['Invoice']['consumptiontax'];
		}
		$this->request->data['Invoice']['subtotal2'] = $subtotal2;
		$this->request->data['Invoice']['subtotal3'] = $subtotal3;
		$this->request->data['Invoice']['subtotal4'] = $subtotal4;
		$this->request->data['Invoice']['subtotal5'] = $subtotal5;
		
		//debug($this->request->data['Invoice']);
		//debug($this->request->data['Invoicedetail']);
		if ($this->Invoice->saveAll($this->request->data)) {
			$this->Flash->success(__('請求書を作成しました。'));
			return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
		} else {
			$this->Flash->error(__('請求書作成に失敗しました。1'));
		}
	   }else{
		//ordertran_pricemasterの読み込み
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$sid,$cid,$branchgroups,$tansanflg);

		$this->set('ordertrans',$ordertrans);
	   }

	}
	/*
	* add_e
	*/
	public function add_e() {
		// 指定された条件でordertranをselect
		// １件ずつ、ordertran_pricemasterをselectし印字
	   $did = $this->did;
	   $page = $this->page;
	   $tax_flg = 0;

	   $startdate = $this->Session->read('BackData6.date');
	   $enddate = $this->Session->read('BackData6.date2');
	   $paramsDate = $startdate['year'].'-'.$startdate['month'].'-'.$startdate['day'];
	   $paramsDate2 = $enddate['year'].'-'.$enddate['month'].'-'.$enddate['day'];
	   $sid = $this->Session->read('BackData6.sid');
	   $bgid = $this->Session->read('BackData6.bgid');
	   $cid = $this->Session->read('BackData6.cid');

	   if($sid != ''){
	   }else{
		$this->Flash->error(__('荷主を選択してください'));
		return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
	   }
		if(isset($bgid)){
			$branchgroups = $bgid;
		}else{
			$branchgroups = '';
		}
		if(isset($cid)){
		}else{
			$cid = '';
		}
		$shippermasters = $this->Shippermaster->find('first',[
			'fields'=>[
				'Shippermaster.shippername',
				'Shippermaster.surcharge'
			],
			'conditions'=>[
				'Shippermaster.id'=>$sid
			],
			'recursive' => 0
		]);

	   $this->set('shippermasters',$shippermasters);
	   if ($this->request->is('post')) {

		if (!$this->request->data['Invoice']['subject']){
			$this->Flash->error(__('件名は必須です'));
			return $this->redirect(['action' => 'add_d', $paramsDate,$paramsDate2,$sid,$cid]);
		}
		//再度表示キーにてデータを読み込む
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$sid,$cid,$branchgroups,$tansanflg);
		//読み込んだデータを１件ずつ、invoicedeitalに登録
		$subtotal1 = $subtotal2 = $subtotal3 = $subtotal4 = $subtotal5 = 0;
		$s = 0;
		$v = 0;

		foreach($ordertrans as $ot){

			$otbmid = $ot['Ordertran']['branchmasters_id'];
			$tantomasterid = '';
			$tantomasterid = $ot['Ordertran']['tantomasters_id'];
			$tantoname = $ot['Tantomaster']['tantoname'];
			//品名、コンテナNo.REFNo.を取得。
			//processtranをforeachして入って入れば変数へ代入
			$pdconname = $hinmei = $conno = $refno = '';
			$pname = [];
			$z = 0;
			foreach($ot['Processtran'] as $otpt){
				$pname[$z] = $otpt['workdetailmasters_id'];
				if($otpt['productcontainers_id']){
					//productcontainersを読み込みセットする
					$pdcon = $this->Productcontainer->find('first',[
						'fields'=>[
							'Productcontainer.pdconname'
						],
						'conditions'=>[
							'Productcontainer.id' => $otpt['productcontainers_id']
						]
					]);
					$pdconname = $pdcon['Productcontainer']['pdconname'];
				}

				if($otpt['productdetailmastername']){
					$hinmei = $otpt['productdetailmastername'];
				}
				if($otpt['container_no']){
					$conno = $otpt['container_no'];
				}
				if($otpt['ref_no']){
					$refno = $otpt['ref_no'];
				}
				$z++;
			}
			if(($hinmei=='')and($pdconname=='')){
				//品名が入ってない場合
				if(in_array('5',$pname)){
					if(in_array('13',$pname)){
						$pdconname = '空コンテナ';
					}
				}
				if(in_array('11',$pname)){
					if(in_array('12',$pname)){
						$pdconname = '実入コンテナ';
					}
				}
			}
			//orertranのinvoice_flgを1で更新（請求書作成済み）
			
			if(!$this->Ordertran->updateAll(['Ordertran.invoice_flg'=>1],['Ordertran.id'=>$ot['Ordertran']['id']])){
				$this->Flash->error(__('オーダーの請求確定に失敗しました。'));
			}
			//ordertranのIDでordertran_pricemasterを読み込む
			$ops = $this->OrdertransPricemaster->find('all',[
				'fields' => [
					'OrdertransPricemaster.ordertrans_id', 
					'OrdertransPricemaster.pricemasters_id',
				],
				'conditions' => [
					'OrdertransPricemaster.ordertrans_id' => $ot['Ordertran']['id'],
					'OrdertransPricemaster.delflg' => 0
				],
				'order'=> [
					'OrdertransPricemaster.created'=>'asc'
				],
				'recursive' => 1
			]);
			$data_check = 0;
			//$v = 0;
			foreach($ops as $op){
				$data_check = 1;
				$pm = $this->Pricemaster->find('first',[
					'fields'=>[
						'Pricemaster.pricename',
						'Pricemaster.price',
						'Pricemaster.keihiflg'
					],
					'conditions'=>[
						'Pricemaster.id'=>$op['OrdertransPricemaster']['pricemasters_id']
					],
					'recursive'=>0
				]);
				if($pm['Pricemaster']['keihiflg']==1){
					//経費分
					$subtotal5 = $subtotal5 + $pm['Pricemaster']['price'];
				}
				//20240331 キャンセル金額が入っているものは別処理
				if($ot['Ordertran']['cancelprice'] > 0){
					$this->request->data['Invoicedetail'][$v]['itemname'] = $pm['Pricemaster']['pricename'];
					$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$v]['unit'] = '式';
					$this->request->data['Invoicedetail'][$v]['price'] = $ot['Ordertran']['cancelprice'];
					$this->request->data['Invoicedetail'][$v]['payment'] = $ot['Ordertran']['cancelprice'];
					$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
					$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
					//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
					$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
					$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
					$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
					$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
					$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
					$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
					$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
					$this->request->data['Invoicedetail'][$v]['delflg'] = 3;
					$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
				}else{
					$this->request->data['Invoicedetail'][$v]['itemname'] = $pm['Pricemaster']['pricename'];
					$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$v]['unit'] = '式';
					$this->request->data['Invoicedetail'][$v]['price'] = $pm['Pricemaster']['price'];
					$this->request->data['Invoicedetail'][$v]['payment'] = $pm['Pricemaster']['price'];
					$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
					$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
					//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
					$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
					$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
					$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
					$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
					$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
					$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
					$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
					$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
					$subtotal1 = $subtotal1 + $pm['Pricemaster']['price'];
				}

				
				$v++;
			}
			//20250228 add start
			$oexts = $this->Orderexpensestran->find('all',[
				'fields'=>[
					'Orderexpensestran.expensesname',
					'Orderexpensestran.expensesprice',
				],
				'conditions'=>[
					'Orderexpensestran.ordertrans_id'=>$ot['Ordertran']['id']
				],
				'order'=>[
					'Orderexpensestran.id'=>'asc'
				],
				'recursive'=>0
			]);
			foreach($oexts as $oext){
				$this->request->data['Invoicedetail'][$v]['itemname'] = $oext['Orderexpensestran']['expensesname'];
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $oext['Orderexpensestran']['expensesprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $oext['Orderexpensestran']['expensesprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				$subtotal1 = $subtotal1 + $oext['Orderexpensestran']['expensesprice'];
				$v++;
			}
			//20250228 add end

			if($data_check == 0){
				//ordertran_pricemasterが１件もなかった場合
				$this->request->data['Invoicedetail'][$v]['itemname'] = 'cancel';
				$this->request->data['Invoicedetail'][$v]['quantity'] = 1;
				$this->request->data['Invoicedetail'][$v]['unit'] = '式';
				$this->request->data['Invoicedetail'][$v]['price'] = $ot['Ordertran']['cancelprice'];
				$this->request->data['Invoicedetail'][$v]['payment'] = $ot['Ordertran']['cancelprice'];
				$this->request->data['Invoicedetail'][$v]['tax_flg'] = 0;
				$this->request->data['Invoicedetail'][$v]['mirror_flg'] = 0;
				//20240706$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['loadingdate'];
				$this->request->data['Invoicedetail'][$v]['detaildate'] = $ot['Ordertran']['detailbilldate'];
				$this->request->data['Invoicedetail'][$v]['containerno'] = $conno;
				$this->request->data['Invoicedetail'][$v]['refno'] = $refno;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $hinmei;
				$this->request->data['Invoicedetail'][$v]['productdetailname'] = $pdconname;
				$this->request->data['Invoicedetail'][$v]['ordertrans_id'] = $ot['Ordertran']['id'];
				$this->request->data['Invoicedetail'][$v]['carmasters_id'] = $ot['Ordertran']['carmasters_id'];
				$this->request->data['Invoicedetail'][$v]['tantomasters_id'] = $tantomasterid;
				$this->request->data['Invoicedetail'][$v]['tantoname'] = $tantoname;
				$this->request->data['Invoicedetail'][$v]['delflg'] = 3; //3だったのを０にした->データがないなら３にすべき？
				$subtotal1 = $subtotal1 + $ot['Ordertran']['cancelprice'];
			}
		}

		//
		$s = $v;
		foreach ($this->request->data['Details2'] as $details2) {
			if ($details2['payment']) {
				$this->request->data['Invoicedetail'][$s]['itemname'] = $details2['itemname'];
				$this->request->data['Invoicedetail'][$s]['quantity'] = $details2['quantity'];
				$this->request->data['Invoicedetail'][$s]['unit'] = $details2['unit'];
				$this->request->data['Invoicedetail'][$s]['price'] = $details2['price'];
				$this->request->data['Invoicedetail'][$s]['payment'] = $details2['payment'];
				$this->request->data['Invoicedetail'][$s]['detaildate'] = $paramsDate2;
				$this->request->data['Invoicedetail'][$s]['tax_flg'] = 2; //免税品目
				$subtotal2 = $subtotal2 + $details2['payment'];
			}
			$s = $s + 1;
		}
		$s2 = $s;
		$s3 = $s2;

		$this->Invoice->create();
		$this->request->data['Invoice']['pagetype'] = $page;
		$this->request->data['Invoice']['tax_flg'] = 0; //課税の帳票か、非課税の帳票か（invoicedetailのtax_flgとは別物）
		$this->request->data['Invoice']['departmentmasters_id']=$did;
		$this->request->data['Invoice']['branchmasters_id']=$otbmid;
		//1:運賃、2:非課税明細、3:交通費、4:サーチャージ
		$this->request->data['Invoice']['subtotal'] = $subtotal1;
		if($tax_flg==1){ //免税
			$this->request->data['Invoice']['consumptiontax'] = floor($subtotal3 * 0.1);
			$this->request->data['Invoice']['total'] = $this->request->data['Invoice']['subtotal'] + $subtotal2;
		}else{
			$this->request->data['Invoice']['consumptiontax'] = floor($this->request->data['Invoice']['subtotal'] * 0.1);
			$this->request->data['Invoice']['total'] = $this->request->data['Invoice']['subtotal'] + $subtotal2 + $this->request->data['Invoice']['consumptiontax'];
		}
		$this->request->data['Invoice']['subtotal2'] = $subtotal2;
		$this->request->data['Invoice']['subtotal3'] = $subtotal3;
		$this->request->data['Invoice']['subtotal4'] = $subtotal4;
		$this->request->data['Invoice']['subtotal5'] = $subtotal5;
		
		//debug($this->request->data['Invoice']);
		//debug($this->request->data['Invoicedetail']);
		if ($this->Invoice->saveAll($this->request->data)) {
			$this->Flash->success(__('請求書を作成しました。'));
			return $this->redirect(['action' => 'index', 'controller' => 'Ga200s']);
		} else {
			$this->Flash->error(__('請求書作成に失敗しました。1'));
		}
	   }else{
		//ordertran_pricemasterの読み込み
		$ordertrans = $this->SelectData->data_select_conditionsGa200_index($paramsDate,$paramsDate2,$did,$sid,$cid,$branchgroups,$tansanflg);

		$this->set('ordertrans',$ordertrans);
	   }

	}
	/*
	* edit
	*/
	public function edit($id = null) {
		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);
		$kaisyasid = $this->kaisyas_id;
		// $did = 4;
		$did = $this->did;
		$ordertrans = $this->Ordertran->SelectOrdertran080s2($id);
		$processtrans = $this->Processtran->SelectProcesstran080s($id);
		// processtranのIDを配列に入れる
		$pkey = array();
		foreach($processtrans as $processtran){
			if($processtran['Processtran']['workplaces_id']){
				// keyの１番目が場所、２番めが作業内容、３番めが商品詳細（３つで１セット）
				$pkey[] = $processtran['Processtran']['workplaces_id'];
				$pkey[] = $processtran['Processtran']['workdetailmasters_id'];
				$pkey[] = $processtran['Processtran']['productcontainers_id'];
			}
		}

		$orderpricemasters = $this->OrdertranPricemaster->SelectOrdertranPrice080s($id);
		$pricemasters = $this->Pricemaster->SelectPricelist($kaisyasid,$did);
		$pricemasters2 = $this->Pricemaster->SelectPricemaster($pkey,$kaisyasid,null);
		$this->set(compact('ordertrans','processtrans','orderpricemasters','pricemasters','pricemasters2'));

		if ($this->request->is(array('post', 'put'))) {
			if (isset($this->request->data['order'])) {
				//出荷実績登録は外した
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
							//ordertranのcorrectflg（実績確認）を更新
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
				//ordertranのcorrectdate（請求確定）を更新
				$this->Ordertran->save([
					'id' => $id,
					'correctdate' => $this->request->data['User']['correctdate'],
					'correctflg' => 1
				]);

				$this->Flash->success('請求確定しました');
				return $this->redirect(['action'=>'edit',$id]);
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

	public function pdf() {
		$id = $_GET['id'];
		$paramsDate = $this->request->data['selectym1.year'].'-'.$this->request->data['selectym1.month'].'-'.$this->request->data['selectym1.day'];
		//ordertranをselect
		$ordertrans = $this->Ordertran->find('all',[
			'fields'=>[
				'Ordertran.carmasters_id',
				''
			],
			'conditions'=>[
				'Ordertran.deliverydate >=' => $paramsDate,
				'Ordertran.deliverydate <=' => $paramsDate2,
				'Ordertran.branchmasters_id' => $branchmasters,
				'Ordertran.departmentmasters_id' => $did,
				'Ordertran.carmasters_id' => $cid,
				'Ordertran.correctflg' => 1,
				'Ordertran.delflg' => 0,
			],
	        'contain' => [
	            'Shippermaster',
	            'Carmaster',
	        ],
	        'order' => [
				'Ordertran.deliverydate' => 'asc',
				'Carmaster.plateno' => 'asc',
			],
			'recursive' => 1
	
		]);



		$this->set('kaisyas', $this->Kaisya->find('first', [
			'conditions' => [
				'Kaisya.id' => $this->Auth->user('kaisyas_id')
			],
			'recursive' => 0,
		]));

		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';
	}



}
