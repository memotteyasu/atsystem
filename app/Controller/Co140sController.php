<?php
App::uses('AppController', 'Controller');
/**
 * Ga140 Controller
 */
class Co140sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['Kaisya','Ordertran','Shippermaster','Carmaster','Truckkind','Processtran','Workdetailmaster','Branchmaster','Workplace','Invoice','Invoicedetail'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co140s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 22; //事務職（ガス）
				case 24; //事務職（全て）
				case 27; //事務職（ガス・石油）
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
	public function add() {
		$did = $this->did;
		$page = $this->page;
		$date = new DateTime();
		$date2 = new DateTime();
		$firstdate = $date->modify('first day of this months');
		$lastdate = $date2->modify('last day of this months');
		$branchmasters_id = null;

		$branchmasters = $this->Branchmaster->BranchList($this->kaisyas_id);
		$this->set(compact('branchmasters'));

		if ($this->request->is('post')) {
			if (isset($this->request->data['selectym1'])) {
				$date = $this->SelectData->date_select_month($this->data['selectym1']);
				$firstdate = $date[0];
				$lastdate = $date[1];
				$branchmasters_id = $this->data['branchmasters_id'];
				$backdatas = [
					'date' => ['year' => $firstdate->format('Y'), 'month' => $firstdate->format('m'), 'day' => $firstdate->format('d')],
					'date2' => ['year' => $lastdate->format('Y'), 'month' => $lastdate->format('m'), 'day' => $lastdate->format('d')],
					'bid' => $branchmasters_id,
					'Page' => $page
				];
				$this->Session->write('BackData1', $backdatas);
			} else {
				if ($this->request->data('Invoicedetail')) {
					if ($this->Session->check('BackData1')) {
						if ($this->Session->read('BackData1.Page') == $page) {
							$backdata2 = $this->Session->read('BackData1');
							$this->Session->write('BackData2', $backdata2);
						}
					}

					$this->Invoice->create();
					$firstdate = $this->SelectData->date_select($backdata2['date']);
					$lastdate = $this->SelectData->date_select($backdata2['date2']);
					$nextdate = $firstdate->modify('last day of next month');
					$this->request->data['Invoice']['honorific'] = '御中';
					$this->request->data['Invoice']['billingdate'] = $lastdate->format('Y-m-d');
					$this->request->data['Invoice']['invoicenumber'] = $lastdate->format('Ymd');
					$this->request->data['Invoice']['subject'] = $lastdate->format('Y年m月分');
					$this->request->data['Invoice']['paymentdate'] = $nextdate->format('Y-m-d');
					$subtotal = $consumptiontax = $tollfeetotal = $handtollfeetotal = 0;
					foreach ($this->request->data['Invoicedetail'] as $invoicedetail) {
						$subtotal = $subtotal + $invoicedetail['price'];
						// $tollfeetotal += $invoicedetail['tollfee'];
						// $handtollfeetotal += $invoicedetail['handtollfee'];
					}
					$consumptiontax = $subtotal * 0.10;
					$this->request->data['Invoice']['subtotal'] = $subtotal;
					$this->request->data['Invoice']['consumptiontax'] = $consumptiontax;
					$this->request->data['Invoice']['tollfeetotal'] = $tollfeetotal;
					$this->request->data['Invoice']['handtollfeetotal'] = $handtollfeetotal;
					$this->request->data['Invoice']['total'] = $subtotal + $consumptiontax + $tollfeetotal + $handtollfeetotal;
					$this->request->data['Invoice']['quantity'] = 1;
					$this->request->data['Invoice']['unit'] = '式';
					$this->request->data['Invoice']['departmentmasters_id'] = $this->did;
					$this->request->data['Invoice']['branchmasters_id']=$this->Session->read('BackData1.bid');
					// $this->request->data['Invoice']['shippermasters_id'] = 43;//出光
					$this->request->data['Invoice']['pagetype'] = $page;
					// debug($this->request->data);
					if ($this->Invoice->saveAll($this->request->data)) {
					 	$this->Flash->success(('請求登録しました。'));
					} else {
					 	$this->Flash->error(('請求登録に失敗しました。'));
					}
				}

				return $this->redirect(['action' => 'add']);
			}
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$firstdate = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$lastdate = $this->SelectData->date_select($this->Session->read('BackData2.date2'));
				$branchmasters_id = $this->Session->read('BackData2.bid');
			}
			$this->Session->delete('BackData2');
		} else {
			if ($this->Session->check('BackData1')) {
				if ($this->Session->read('BackData1.Page') == $page) {
					$firstdate = $this->SelectData->date_select($this->Session->read('BackData1.date'));
					$lastdate = $this->SelectData->date_select($this->Session->read('BackData1.date2'));
					$branchmasters_id = $this->Session->read('BackData1.bid');
				}
			}
			$this->Session->delete('BackData1');
		}

		$paramsDate = $firstdate->format('Y-m-d');
		$paramsDate2 = $lastdate->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','paramsDate2','week','branchmasters_id'));

		$truckkinds = $this->Truckkind->SelectTruckkindListOi140s($branchmasters_id,$did);
		$this->set(compact('truckkinds'));
	}

	public function index() {
		$did = $this->did;
		$page = $this->page;
		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$this->set(compact('shippermasters'));

		$date = new DateTime();
		$date->modify('first day of this months');
		$date2 = new DateTime();
		$date2->modify('last day of this months');
		$shippermasters_id = null;
		if ($this->request->is('post')) {
			list($date,$date2) = $this->SelectData->date_select_month($this->data['selectym1']);
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

		$selectData = $this->SelectData->data_select_conditions140s2($paramsDate,$paramsDate2,$did,$shippermasters_id);
		$invoices = $selectData['invoices'];
		$this->set(compact('invoices'));

	}
	public function edit($id = null) {
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
				return $this->redirect(['action' => 'index', 'controller' => 'Ga140s']);
			} else {
				$this->Flash->error(__('請求書編集に失敗しました。'));
			}
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
				// invoicedetailも更新
				/*
				 * 途中
				 */
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
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
}
