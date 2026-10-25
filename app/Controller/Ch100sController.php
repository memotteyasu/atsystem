<?php
App::uses('AppController', 'Controller');
/**
 * Ch100 Controller
 *
 */
class Ch100sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = ['Html','Form'];
	public $uses = ['User','Invoice','Invoicedetail','Kaisya','Shippermaster','Ordertran','Branchgroup','Branchmaster'];
	public $components = ['RequestHandler','SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 2;
		$this->page = 'Ch100s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 23; //事務職（コンテナ）
				case 24; //事務職（全て）
				case 25; //事務職（化学品コンテナ）
				case 28; //事務職兼運転手
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
		// $page = 'Ch100s';
		$did = $this->did;
		$page = $this->page;
		$shippermasters = $this->Shippermaster->SelectShipperList($did);

		$branchmasters_id = $this->branchmasters_id;
		$branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
		$branchgroups = $this->Branchgroup->BranchgroupList();
		$this->set(compact('shippermasters','branchmasters','branchgroups'));

		$date = new DateTime();
		$date->modify('first day of this months');
		$date2 = new DateTime();
		$date2->modify('last day of this months');
		$shippermasters_id = null;
		if ($this->request->is('post')) {
			list($date,$date2) = $this->SelectData->date_select_month($this->request->data('selectym1'));
			$shippermasters_id = $this->request->data('shippermasters_id');
			$branchmasters_id = $this->request->data('branchmasters_id');
			$branchgroups_id = $this->request->data('branchgroups_id');
			$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')], 'date2' => ['year' => $date2->format('Y'), 'month' => $date2->format('m'), 'day' => $date2->format('d')], 'sid' => $shippermasters_id, 'bid' => $branchmasters_id, 'bgid' => $branchgroups_id, 'Page' => $page];
			$this->Session->write('BackData1', $backdatas);
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$date2 = $this->SelectData->date_select($this->Session->read('BackData2.date2'));
				$shippermasters_id = $this->Session->read('BackData2.sid');
				$branchmasters_id = $this->Session->read('BackData2.bid');
				$branchgroups_id = $this->Session->read('BackData2.bgid');
			}
			$this->Session->delete('BackData2');
		} else {
			$this->Session->delete('BackData1');
		}
		if(!isset($branchgroups_id)){
			$branchgroups_id = $this->branchgroups_id;
		}


		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date2->format('Y-m-d');
		$this->set(compact('paramsDate','paramsDate2','shippermasters_id','branchmasters_id','branchgroups_id'));
		//$branchmastersにbranchgroupに紐づくIDを配列で渡す
		$branchmasters = $this->Branchmaster->find('list',[
		'fields' => ['id'],
		'conditions' => [
			'kaisyas_id' => $this->kaisyas_id,
			'branchgroups_id' => $branchgroups_id,
		],
		'order' => ['id' => 'asc']
		]);

		$selectData = $this->SelectData->data_select_conditions100s($paramsDate,$paramsDate2,$did,$shippermasters_id,$branchmasters);
		$invoices = $selectData['invoices'];
		$this->set(compact('invoices'));
		/*
		$this->paginate = [
			'fields' => [
				'Invoice.id',
				'Invoice.honorific',
				'Invoice.billingdate',
				'Invoice.paymentdate',
				'Invoice.invoicenumber',
				'Invoice.subject',
				'Shippermaster.shippername'
			],
			//'limit' => 10,
			'conditions' => [
				'Invoice.mirror_flg' => 0,
				'Shippermaster.departmentmasters_id' => $did
			],
			'order' => [
				'Invoice.billingdate' => 'asc',
				'Invoice.invoicenumber' => 'asc',
				'Invoice.id' => 'asc',
			],
			'recursive' => 0
		];
		$this->set('invoices', $this->paginate('Invoice'));
*/
	}

	public function pdf($id) {
		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc',
						'Invoicedetail.id' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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
	public function pdf_a($id) {
		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc',
						'Invoicedetail.id' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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
	public function pdf_b($id) {
		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc',
						'Invoicedetail.id' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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
	public function pdf_c($id) {
		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc',
						'Invoicedetail.id' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

		$this->set('kaisyas', $this->Kaisya->find('first', [
			'conditions' => [
				'Kaisya.id' => $invoicedata['Invoice']['kaisyas_id']
			],
			'recursive' => 0,
		]));
		//サーチャージ
		$invoicedetails4 = $this->Invoicedetail->find('first',[
			'conditions'=>[
				'Invoicedetail.invoices_id' => $id,
				'Invoicedetail.tax_flg' => 4
			],
			'recursive' => -1
		]);
		$this->set('invoicedetails4',$invoicedetails4);
		//諸経費
		$invoicedetails5 = $this->Invoicedetail->find('first',[
			'conditions'=>[
				'Invoicedetail.invoices_id' => $id,
				'Invoicedetail.tax_flg' => 5
			],
			'recursive' => -1
		]);
		$this->set('invoicedetails5',$invoicedetails5);
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';
	}
	public function pdf_d($id) {
		
		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc',
						'Invoicedetail.id' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

		$this->set('kaisyas', $this->Kaisya->find('first', [
			'conditions' => [
				'Kaisya.id' => $this->Auth->user('kaisyas_id')
			],
			'recursive' => 0,
		]));
		//サーチャージ
		$invoicedetails4 = $this->Invoicedetail->find('first',[
			'conditions'=>[
				'Invoicedetail.invoices_id' => $id,
				'Invoicedetail.tax_flg' => 4
			],
			'recursive' => -1
		]);
		$this->set('invoicedetails4',$invoicedetails4);

		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';
	}
	public function pdf_e($id) {
		
		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc',
						'Invoicedetail.id' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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
	public function pdf_f($id) {
		
		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc',
						'Invoicedetail.id' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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

	//免税用
	public function pdf_men($id) {

		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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

	//横型
	//20240531修正 idをdescで追加してみた。
	public function pdf_width($id) {

		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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
	public function pdf_width_a($id) {

		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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
	public function pdf_width_b($id) {

		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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
	public function pdf_width_c($id) {

		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc',
						'Invoicedetail.id' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

		$this->set('kaisyas', $this->Kaisya->find('first', [
			'conditions' => [
				'Kaisya.id' => $this->Auth->user('kaisyas_id')
			],
			'recursive' => 0,
		]));
		//サーチャージ
		$invoicedetails4 = $this->Invoicedetail->find('first',[
			'conditions'=>[
				'Invoicedetail.invoices_id' => $id,
				'Invoicedetail.tax_flg' => 4
			],
			'recursive' => -1
		]);
		$this->set('invoicedetails4',$invoicedetails4);
		//諸経費
		$invoicedetails5 = $this->Invoicedetail->find('first',[
			'conditions'=>[
				'Invoicedetail.invoices_id' => $id,
				'Invoicedetail.tax_flg' => 5
			],
			'recursive' => -1
		]);
		$this->set('invoicedetails5',$invoicedetails5);
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';
	}
	public function pdf_width_d($id) {

		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

		$this->set('kaisyas', $this->Kaisya->find('first', [
			'conditions' => [
				'Kaisya.id' => $this->Auth->user('kaisyas_id')
			],
			'recursive' => 0,
		]));
		//サーチャージ
		$invoicedetails4 = $this->Invoicedetail->find('first',[
			'conditions'=>[
				'Invoicedetail.invoices_id' => $id,
				'Invoicedetail.tax_flg' => 4
			],
			'recursive' => -1
		]);
		$this->set('invoicedetails4',$invoicedetails4);
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';
	}
	public function pdf_width_e($id) {

		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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
	//免税用
	public function pdf_width_men($id) {

		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'contain' => [
				'Invoicedetail' => [
					'order' => [
						'Invoicedetail.detaildate' => 'asc'
					]
				],
				'Shippermaster',
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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

	public function pdffree($id) {
		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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
	public function pdffree3($id) {
		$invoicedata = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'recursive' => 1,
		]);
		$this->set('invoices',$invoicedata);

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

	public function pdf_back($id) {
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

	public function add() {
 		// $did = 4;
		// $page = 'Ch100s';
		$did = $this->did;
		$page = $this->page;
		// 		$this->set('shippermasters', $this->Shippermaster->SelectShipperList($did));
		$shippermasters = $this->Shippermaster->find('list',[
			'fields' => ['id','shippername'],
			'conditions'=>['departmentmasters_id' => $did],
			'order' => ['Shippermaster.shippercode' => 'asc']
		]);

		$this->set(compact('shippermasters'));

		if ($this->request->is('post')) {
			$i = 0;
			foreach ($this->request->data['Details'] as $details) {
				if ($details['payment']) {
					$this->request->data['Invoicedetail'][$i]['itemname'] = $details['itemname'];
					$this->request->data['Invoicedetail'][$i]['quantity'] = $details['quantity'];
					$this->request->data['Invoicedetail'][$i]['unit'] = $details['unit'];
					$this->request->data['Invoicedetail'][$i]['price'] = $details['price'];
					$this->request->data['Invoicedetail'][$i]['payment'] = $details['payment'];
				}
				$i = $i + 1;
			}
			$s = $i;
			foreach ($this->request->data['Details2'] as $details2) {
				if ($details2['payment']) {
					$this->request->data['Invoicedetail'][$s]['itemname'] = $details2['itemname'];
					$this->request->data['Invoicedetail'][$s]['quantity'] = $details2['quantity'];
					$this->request->data['Invoicedetail'][$s]['unit'] = $details2['unit'];
					$this->request->data['Invoicedetail'][$s]['price'] = $details2['price'];
					$this->request->data['Invoicedetail'][$s]['payment'] = $details2['payment'];
					$this->request->data['Invoicedetail'][$s]['tax_flg'] = 1;
				}
				$s = $s + 1;
			}
			$this->Invoice->create();
			$this->request->data['Invoice']['pagetype'] = $page;
			$this->request->data['Invoice']['departmentmasters_id']=$did;
			$this->request->data['Invoice']['branchmasters_id']=$this->branchmasters_id;
			
			if ($this->Invoice->saveAll($this->request->data)) {
				$this->Flash->success(__('請求書を作成しました。'));
				return $this->redirect(['action' => 'index', 'controller' => 'Ch100s']);
			} else {
				$this->Flash->error(__('請求書作成に失敗しました。'));
			}
		}
	}

	public function edit($id = null) {
		// $did=4;
		$did = $this->did;
		$shippermasters = $this->Shippermaster->find('list',[
			'fields' => ['id','shippername'],
			'conditions'=>['departmentmasters_id' => $did],
			'order' => ['Shippermaster.shippercode' => 'asc']
		]);

		$this->set(compact('shippermasters'));

		$post = $this->Invoice->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(['post', 'put'])) {
			$i = 0;
			foreach ($this->request->data['Details'] as $details) {
				if ($details['payment']) {
					$this->request->data['Invoicedetail'][$i]['id'] = $details['id'];
					$this->request->data['Invoicedetail'][$i]['itemname'] = $details['itemname'];
					$this->request->data['Invoicedetail'][$i]['quantity'] = $details['quantity'];
					$this->request->data['Invoicedetail'][$i]['unit'] = $details['unit'];
					$this->request->data['Invoicedetail'][$i]['price'] = $details['price'];
					$this->request->data['Invoicedetail'][$i]['payment'] = $details['payment'];
				}
				$i = $i + 1;
			}
			$j = $i;
			foreach ($this->request->data['Details2'] as $details2) {
				if ($details2['payment']) {
					$this->request->data['Invoicedetail'][$j]['id'] = $details2['id'];
					$this->request->data['Invoicedetail'][$j]['itemname'] = $details2['itemname'];
					$this->request->data['Invoicedetail'][$j]['quantity'] = $details2['quantity'];
					$this->request->data['Invoicedetail'][$j]['unit'] = $details2['unit'];
					$this->request->data['Invoicedetail'][$j]['price'] = $details2['price'];
					$this->request->data['Invoicedetail'][$j]['payment'] = $details2['payment'];
					$this->request->data['Invoicedetail'][$j]['tax_flg'] = 1;
				}
				$j = $j + 1;
			}
			$this->Invoice->id = $id;
			$this->request->data['Invoice']['departmentmasters_id']=$did;
			if ($this->Invoice->saveAll($this->request->data)) {
				$this->Flash->success(__('請求書を編集しました。'));
				return $this->redirect(['action' => 'index', 'controller' => 'Ch100s']);
			} else {
				$this->Flash->error(__('請求書編集に失敗しました。'));
			}
//     		if ($this->request->data('Invoice.mode') == 'confirm') {
//     			$i = 0;
//     			$subtotal = 0;
//     			$tax = 0.10;
//     			foreach ($this->request->data['Details'] as $details) {
//     				if ($details['itemname']) {
//     					$quantity = $details['quantity'];
//     					$price = $details['price'];
//     					$payment = $quantity * $price;
//     					$this->request->data['Details'][$i]['payment'] = $payment;
//     					$subtotal = $subtotal + $payment;
//     				}
//     				$i = $i + 1;
//     			}
//     			$this->request->data['Invoice']['subtotal'] = $subtotal;
//     			$consumptiontax = floor($subtotal * $tax);
//     			$this->request->data['Invoice']['consumptiontax'] = $consumptiontax;
//     			$this->request->data['Invoice']['total'] = $subtotal + $consumptiontax;
//     			$this->render('edit_confirm');
//     		} else {
//     			$i = 0;
//     			foreach ($this->request->data['Details'] as $details) {
//     				if ($details['itemname']) {
//     					$this->request->data['Invoicedetail'][$i]['id'] = $details['id'];
//     					$this->request->data['Invoicedetail'][$i]['itemname'] = $details['itemname'];
//     					$this->request->data['Invoicedetail'][$i]['quantity'] = $details['quantity'];
//     					$this->request->data['Invoicedetail'][$i]['unit'] = $details['unit'];
//     					$this->request->data['Invoicedetail'][$i]['price'] = $details['price'];
//     					$this->request->data['Invoicedetail'][$i]['payment'] = $details['payment'];
//     				}
//     				$i = $i + 1;
//     			}
//     			$this->Invoice->id = $id;
//     			if ($this->Invoice->saveAll($this->request->data)) {
//     				$this->Flash->success(__('請求書を編集しました。'));
//     				return $this->redirect(['action' => 'index', 'controller' => 'Ch100s']);
//     			} else {
//     				$this->Flash->error(__('請求書編集に失敗しました。'));
//     			}
//     		}
			//debug ($this->request->data);
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id = null) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if ($this->Invoice->delete($id)) {
			$this->Flash->success(__('削除しました'));
		} else {
			$this->Flash->error(__('失敗しました'));
		}
		return $this->redirect(['action' => 'index']);
	}

}
