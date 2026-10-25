<?php
App::uses('AppController', 'Controller');
/**
 * Co101 Controller
 *
 */
class Co101sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = ['Html','Form'];
	public $uses = ['User','Invoice','Invoicedetail','Kaisya','Shippermaster','Ordertran'];
	public $components = ['RequestHandler','SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co101s';
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
		break;
	}
	/**
 * index method
 *
 * @return void
 */
	public function index() {
		// $did = 4;
		// $page = 'Co100s';
		$did = $this->did;
		$page = $this->page;
		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$this->set(compact('shippermasters'));

		$date = new DateTime();
		$date2 = new DateTime();
		$shippermasters_id = null;
		if ($this->request->is('post')) {
			$date = $this->SelectData->date_select($this->data['selectym1']);
			$date2 = $this->SelectData->date_select($this->data['selectym2']);
			$shippermasters_id = $this->data['shippermasters_id'];
			$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')], 'date2' => ['year' => $date2->format('Y'), 'month' => $date2->format('m'), 'day' => $date2->format('d')], 'sid' => $shippermasters_id, 'Page' => $page];
			$this->Session->write('BackData1', $backdatas);
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$date2 = $this->SelectData->date_select($this->Session->read('BackData2.date2'));
				$shippermasters_id = $this->Session->read('BackData2.sid');
			}
			$this->Session->delete('BackData2');
		} else {
			$this->Session->delete('BackData1');
		}

		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date2->format('Y-m-d');
		$this->set(compact('paramsDate','paramsDate2','shippermasters_id'));

		$selectData = $this->SelectData->data_select_conditions100s($paramsDate,$paramsDate2,$did,$shippermasters_id);
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
		//$this->set('invoices', $this->Invoice->find('all'));
		$this->set('invoices', $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
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
				return $this->redirect(['action' => 'index', 'controller' => 'Co101s']);
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
//     				return $this->redirect(['action' => 'index', 'controller' => 'Co100s']);
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
		$this->Invoice->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Invoice']['delflg'] = 1;
			$this->Invoice->id = $id;
			if ($this->Invoice->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
	}

}
