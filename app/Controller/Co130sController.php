<?php
App::uses('AppController', 'Controller');
/**
 * Co130 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Co130sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form','Number');
	public $uses = ['Ordertran','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster','Invoice','Invoicedetail','OrdertranPricemaster'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co130s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
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
	public function index() {
		// $did = 4;
		// $page = 'Co130s';
		$did = $this->did;
		$page = $this->page;

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$carmasters = $this->Carmaster->CarnameList($did);
		$branchmasters_id = $this->branchmasters_id;
		$branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
		$this->set(compact('shippermasters','carmasters','branchmasters'));

		$date = new DateTime();
		$date2 = new DateTime();
		$shippermasters_id = null;
		$productdetailmasters_id = null;
		$carmasters_id = null;

		if ($this->request->is('post')) {
			$date = $this->SelectData->date_select($this->data['selectym1']);
			$date2 = $this->SelectData->date_select($this->data['selectym2']);
			$shippermasters_id = $this->data['shippermasters_id'];
			$productdetailmasters_id = $this->data['productdetailmasters_id'];
			$carmasters_id = $this->data['carmasters_id'];
			$branchmasters_id = $this->request->data('branchmasters_id');
			$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')], 'date2' => ['year' => $date2->format('Y'), 'month' => $date2->format('m'), 'day' => $date2->format('d')], 'sid' => $shippermasters_id, 'pid' => $productdetailmasters_id, 'cid' => $carmasters_id, 'bid' => $branchmasters_id, 'Page' => $page];
			$this->Session->write('BackData1', $backdatas);
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$date2 = $this->SelectData->date_select($this->Session->read('BackData2.date2'));
				$shippermasters_id = $this->Session->read('BackData2.sid');
				$productdetailmasters_id = $this->Session->read('BackData2.pid');
				$carmasters_id = $this->Session->read('BackData2.cid');
				$branchmasters_id = $this->Session->read('BackData2.bid');
			}
			$this->Session->delete('BackData2');
		} else {
			$this->Session->delete('BackData1');
		}

		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date2->format('Y-m-d');
		$this->set(compact('paramsDate','paramsDate2','shippermasters_id','productdetailmasters_id','carmasters_id','branchmasters_id'));

		$selectData = $this->SelectData->data_select_conditions130s($paramsDate,$paramsDate2,$did,$shippermasters_id,$productdetailmasters_id,$carmasters_id,$branchmasters_id);
		$ordertrans = $selectData['ordertrans'];
		$productdetailmasters = $selectData['pdlist'];
		$this->set(compact('ordertrans','productdetailmasters'));
	}

	public function add($date = null, $date2 = null, $sid = null, $pid = null, $cid = null, $bid = null) {
		// $did = 4;
		$did = $this->did;
		$shippermasters_id = $sid;
		$productdetailmasters_id = $pid;
		$carmasters_id = $cid;
		$branchmasters_id = $bid;

		$tax = 0.1;

		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);

		$paramsDate = new DateTime($date);
		$paramsDate2 = new DateTime($date2);
		$this->set(compact('paramsDate','paramsDate2','tax'));

		$selectData = $this->SelectData->data_select_conditions130s2($paramsDate,$paramsDate2,$did,$shippermasters_id,$productdetailmasters_id,$carmasters_id,$branchmasters_id);
		$ordertrans = $selectData['ordertrans'];
		$orderlist = $selectData['orderlist'];
		$this->set(compact('ordertrans','orderlist'));

		if ($this->request->is('post')) {
			$i = 0;
			foreach ($this->request->data['Details'] as $details) {
				if ($details['itemname']) {
					$this->request->data['Invoicedetail'][$i]['itemname'] = $details['itemname'];
					$this->request->data['Invoicedetail'][$i]['quantity'] = $details['quantity'];
					$this->request->data['Invoicedetail'][$i]['unit'] = $details['unit'];
					//$this->request->data['Invoicedetail'][$i]['price'] = str_replace(',','',$details['price']);
					//$this->request->data['Invoicedetail'][$i]['payment'] = str_replace(',','',$details['payment']);
					$this->request->data['Invoicedetail'][$i]['price'] = $details['price'];
					$this->request->data['Invoicedetail'][$i]['payment'] = $details['payment'];
				}
				$i = $i + 1;
			}
			$j = $i;
			foreach ($this->request->data['Details2'] as $details2) {
				if ($details2['itemname']) {
					$this->request->data['Invoicedetail'][$j]['itemname'] = $details2['itemname'];
					$this->request->data['Invoicedetail'][$j]['quantity'] = $details2['quantity'];
					$this->request->data['Invoicedetail'][$j]['unit'] = $details2['unit'];
					$this->request->data['Invoicedetail'][$j]['price'] = $details2['price'];
					$this->request->data['Invoicedetail'][$j]['payment'] = $details2['payment'];
					$this->request->data['Invoicedetail'][$j]['tax_flg'] = 1;
				}
				$j = $j + 1;
			}
			$k = $j;
			if (isset($this->request->data['DetailsOrderPrice'])) {
				foreach ($this->request->data['DetailsOrderPrice'] as $details3) {
					if ($details3['itemname']) {
						$this->request->data['Invoicedetail'][$k]['itemname'] = $details3['itemname'];
						$this->request->data['Invoicedetail'][$k]['quantity'] = $details3['quantity'];
						$this->request->data['Invoicedetail'][$k]['unit'] = $details3['unit'];
						$this->request->data['Invoicedetail'][$k]['price'] = $details3['price'];
						$this->request->data['Invoicedetail'][$k]['payment'] = $details3['payment'];
					}
					$k = $k + 1;
				}
			}
			/*
			$hendouhi = $this->request->data['Invoice']['total'];
			$this->request->data['Invoicedetail'][$k]['itemname'] = '変動費';
			$this->request->data['Invoicedetail'][$k]['payment'] = $hendouhi;
			$this->request->data['Invoice']['mirror_flg'] = 1;
			$this->request->data['Invoicedetail'][$k]['mirror_flg'] = 1;
			*/
			/* Ordertran及びOrdertranPricemasterの請求書作成判定のための更新処理 */
			$l = $m = 0;
			foreach ($ordertrans as $ordertran) {
				$this->request->data['Ordertran'][$l]['id'] = $ordertran['Ordertran']['id'];
				$this->request->data['Ordertran'][$l]['invoice_flg'] = 1;
				foreach ($ordertran['Price'] as $price) {
					$this->request->data['OrdertranPricemaster'][$m]['id'] = $price['OrdertranPricemaster']['id'];
					//$this->request->data['OrdertranPricemaster'][$m]['invoice_flg'] = 1;
					$m = $m + 1;
				}
				$l = $l + 1;
			}

// 			debug($this->request->data);
			$this->Invoice->create();
			if ($this->Invoice->saveAll($this->request->data)) {
				$this->Ordertran->saveAll($this->request->data['Ordertran']);
				$this->OrdertranPricemaster->saveAll($this->request->data['OrdertranPricemaster']);
				$this->Flash->success(__('請求書を作成しました。'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('請求書作成に失敗しました。'));
			}
		}
	}

	public function index2() {
		$this->paginate = [
			'limit' => 10,
			'fields' => [
				'Invoice.id',
				'Invoice.honorific',
				'Invoice.billingdate',
				'Invoice.invoicenumber',
				'Invoice.subject',
				'Invoice.paymentdate',
				'Shippermaster.shippername',
			],
			'conditions' => [
				'Invoice.mirror_flg' => 1,
				'Invoice.delflg' => 0
			],
			'order' => [
				'Invoice.billingdate' => 'asc',
				'Invoice.paymentdate' => 'asc',
				'Invoice.invoicenumber' => 'asc',
			],
			'recursive' => 0
		];
		$this->set('invoices', $this->paginate('Invoice'));
	}

	public function view($id) {
		$invoice = $this->Invoice->find('first', [
			'fields' => [
				'Invoice.id',
				'Shippermaster.shippername',
				'Invoice.honorific',
				'Invoice.billingdate',
				'Invoice.invoicenumber',
				'Invoice.subject',
				'Invoice.paymentdate',
				'Invoice.subtotal',
				'Invoice.consumptiontax',
				'Invoice.total',
				'Invoice.billingnote',
				'Invoice.payee',
			],
			'conditions' => [
				'Invoice.id' => $id,
				'Invoice.delflg' => 0
			],
			'contain' => [
				'Shippermaster' => [
					'conditions' => ['Shippermaster.delflg' => 0]
				],
				'Invoicedetail' => [
					'fields' => [
						'Invoicedetail.itemname',
						'Invoicedetail.quantity',
						'Invoicedetail.unit',
						'Invoicedetail.price',
						'Invoicedetail.payment',
						'Invoicedetail.tax_flg',
					],
					'conditions' => [
						'Invoicedetail.delflg' => 0
					]
				]
			],
			'recursive' => 0
		]);
		$tax = 0.1;
		$this->set(compact('invoice','tax'));
	}

	public function pdf($id) {
		$this->set('invoices', $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'recursive' => 1,
		]));

		$this->set('invoices2', $this->Invoice->find('first', [
			'conditions' => [
				'AND' => [
				'Invoice.id' => $id,
				//'Invoicedetail.mirror_flg' => 1,
				]
			],
			'contain' => [
				'Invoicedetail' => [
					'conditions' => [
						'mirror_flg' => 1
					]
				]
			],
			'recursive' => 1,
		]));

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

	public function delete($id = null) {
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		} else {
			$invoicedetails = $this->Invoicedetail->find('all', [
				'conditions' => [
					'invoices_id' => $id
				],
				'recursive' => 0,
			]);
			$this->request->data['Invoice']['id'] = $id;
			$this->request->data['Invoice']['delflg'] = 1;
			$i = 0;
			foreach ($invoicedetails as $invoicedetail) {
				$this->request->data['Invoicedetail'][$i]['id'] = $invoicedetail['Invoicedetail']['id'];
				$this->request->data['Invoicedetail'][$i]['delflg'] = 1;
				$i = $i + 1;
			}
			if ($this->Invoice->saveAll($this->request->data)){
				$this->Flash->success(__('削除しました。'));
			}else{
				$this->Flash->error(__('削除に失敗しました。'));
			}
		}
		return $this->redirect(['action' => 'index2']);
	}
}
