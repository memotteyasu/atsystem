<?php
App::uses('AppController', 'Controller');
/**
 * Oi140 Controller
 */
class Oi140sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['Kaisya','Ordertran','Shippermaster','Carmaster','Truckkind','Processtran','Workdetailmaster','Branchmaster','Workplace','Invoice','Invoicedetail'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 1;
		$this->page = 'Oi140s';
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
	public function add() {
		return $this->redirect(['action' => 'index']);
	}

	public function index() {
		$did = $this->did;
		$page = $this->page;
		$fixedBranchmastersId = 1;
		$targetBranchmastersIds = [1, 21, 29];
		$branchLabels = [
			1 => '本社（朝日運輸）',
			21 => '大豊運輸',
			29 => '金川運輸',
		];
		$firstdate = new DateTime();
		$lastdate = new DateTime();
		$firstdate->modify('first day of this months');
		$lastdate->modify('last day of this months');
		$branchmasters_id = $fixedBranchmastersId;

		if ($this->request->is('post')) {
			if (isset($this->request->data['selectym1'])) {
				list($firstdate, $lastdate) = $this->SelectData->date_select_month($this->data['selectym1']);
				$backdatas = [
					'date' => ['year' => $firstdate->format('Y'), 'month' => $firstdate->format('m'), 'day' => $firstdate->format('d')],
					'date2' => ['year' => $lastdate->format('Y'), 'month' => $lastdate->format('m'), 'day' => $lastdate->format('d')],
					'bid' => $fixedBranchmastersId,
					'Page' => $page
				];
				$this->Session->write('BackData1', $backdatas);
			} elseif ($this->request->data('Invoicedetail')) {
				$backdata = $this->readOi140BackData($page, $firstdate, $lastdate);
				$backdata['bid'] = $fixedBranchmastersId;
				$this->Session->write('BackData2', $backdata);
				$this->saveOi140Invoice($backdata);
				return $this->redirect(['action' => 'index']);
			}
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$firstdate = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$lastdate = $this->SelectData->date_select($this->Session->read('BackData2.date2'));
			}
			$this->Session->delete('BackData2');
		} else {
			if ($this->Session->check('BackData1')) {
				if ($this->Session->read('BackData1.Page') == $page) {
					$firstdate = $this->SelectData->date_select($this->Session->read('BackData1.date'));
					$lastdate = $this->SelectData->date_select($this->Session->read('BackData1.date2'));
				}
			}
		}

		$paramsDate = $firstdate->format('Y-m-d');
		$paramsDate2 = $lastdate->format('Y-m-d');
		$this->set(compact('paramsDate','paramsDate2','branchmasters_id'));

		$carmasters = $this->Carmaster->Oi140CarList($targetBranchmastersIds);
		$registeredDetails = $this->Invoicedetail->find('all', [
			'fields' => [
				'Invoicedetail.carmasters_id',
				'Invoicedetail.price',
				'Invoicedetail.tollfee',
				'Invoicedetail.handtollfee',
			],
			'joins' => [[
				'table' => 'invoices',
				'alias' => 'Invoice',
				'type' => 'INNER',
				'conditions' => ['Invoice.id = Invoicedetail.invoices_id'],
			]],
			'conditions' => [
				'Invoice.billingdate >=' => $paramsDate,
				'Invoice.billingdate <=' => $paramsDate2,
				'Invoice.departmentmasters_id' => $did,
				'Invoice.branchmasters_id' => $branchmasters_id,
				'Invoice.pagetype' => $page,
				'Invoice.delflg' => 0,
			],
			'order' => ['Invoice.id' => 'asc', 'Invoicedetail.id' => 'asc'],
			'recursive' => -1,
		]);
		$registeredValues = [];
		foreach ($registeredDetails as $detail) {
			$registeredValues[$detail['Invoicedetail']['carmasters_id']] = $detail['Invoicedetail'];
		}
		$this->set(compact('carmasters', 'branchLabels'));
		$this->set(compact('registeredValues'));
	}
	public function edit($id = null) {
		// $did=1;
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
				return $this->redirect(['action' => 'index', 'controller' => 'Oi140s']);
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

	private function readOi140BackData($page, $defaultFirstdate, $defaultLastdate) {
		if ($this->Session->check('BackData1') && $this->Session->read('BackData1.Page') == $page) {
			return $this->Session->read('BackData1');
		}

		return [
			'date' => ['year' => $defaultFirstdate->format('Y'), 'month' => $defaultFirstdate->format('m'), 'day' => $defaultFirstdate->format('d')],
			'date2' => ['year' => $defaultLastdate->format('Y'), 'month' => $defaultLastdate->format('m'), 'day' => $defaultLastdate->format('d')],
			'bid' => 1,
			'Page' => $page,
		];
	}

	private function saveOi140Invoice($backdata) {
		if (empty($backdata['bid'])) {
			$this->Flash->error(('営業所を選択してください。'));
			return;
		}

		$firstdate = $this->SelectData->date_select($backdata['date']);
		$lastdate = $this->SelectData->date_select($backdata['date2']);
		$nextdate = clone $firstdate;
		$nextdate->modify('last day of next month');

		$details = [];
		$subtotal = 0;
		$tollfeetotal = 0;
		$handtollfeetotal = 0;

		foreach ($this->request->data['Invoicedetail'] as $invoicedetail) {
			$price = isset($invoicedetail['price']) && $invoicedetail['price'] !== '' ? (int)$invoicedetail['price'] : 0;
			$tollfee = isset($invoicedetail['tollfee']) && $invoicedetail['tollfee'] !== '' ? (int)$invoicedetail['tollfee'] : 0;
			$handtollfee = isset($invoicedetail['handtollfee']) && $invoicedetail['handtollfee'] !== '' ? (int)$invoicedetail['handtollfee'] : 0;

			if ($price === 0 && $tollfee === 0 && $handtollfee === 0) {
				continue;
			}

			$invoicedetail['price'] = $price;
			$invoicedetail['tollfee'] = $tollfee;
			$invoicedetail['handtollfee'] = $handtollfee;
			$invoicedetail['delflg'] = 0;
			$details[] = $invoicedetail;

			$subtotal += $price;
			$tollfeetotal += $tollfee;
			$handtollfeetotal += $handtollfee;
		}

		if (empty($details)) {
			$this->Flash->error(('登録する金額を入力してください。'));
			return;
		}

		$consumptiontax = (int)floor($subtotal * 0.10);
		$invoiceData = [
			'Invoice' => [
				'honorific' => '御中',
				'billingdate' => $lastdate->format('Y-m-d'),
				'invoicenumber' => $lastdate->format('Ymd'),
				'subject' => $lastdate->format('Y年m月分'),
				'paymentdate' => $nextdate->format('Y-m-d'),
				'subtotal' => $subtotal,
				'consumptiontax' => $consumptiontax,
				'tollfeetotal' => $tollfeetotal,
				'handtollfeetotal' => $handtollfeetotal,
				'total' => $subtotal + $consumptiontax + $tollfeetotal + $handtollfeetotal,
				'quantity' => 1,
				'unit' => '式',
				'departmentmasters_id' => $this->did,
				'branchmasters_id' => $backdata['bid'],
				'shippermasters_id' => 43,
				'pagetype' => $this->page,
				'delflg' => 0,
			],
			'Invoicedetail' => $details,
		];

		$dataSource = $this->Invoice->getDataSource();
		$dataSource->begin();
		$existingInvoiceConditions = [
			'Invoice.billingdate >=' => $firstdate->format('Y-m-d'),
			'Invoice.billingdate <=' => $lastdate->format('Y-m-d'),
			'Invoice.departmentmasters_id' => $this->did,
			'Invoice.branchmasters_id' => $backdata['bid'],
			'Invoice.pagetype' => $this->page,
			'Invoice.delflg' => 0,
		];
		$existingInvoiceIds = $this->Invoice->find('list', [
			'fields' => ['Invoice.id'],
			'conditions' => $existingInvoiceConditions,
			'recursive' => -1,
		]);
		$deleted = true;
		if (!empty($existingInvoiceIds)) {
			$deletedDetails = $this->Invoicedetail->deleteAll([
				'Invoicedetail.invoices_id' => array_values($existingInvoiceIds),
			], false);
			$deleted = $deletedDetails && $this->Invoice->deleteAll([
				'Invoice.id' => array_values($existingInvoiceIds),
			], false);
		}

		$this->Invoice->create();
		if ($deleted && $this->Invoice->saveAll($invoiceData)) {
			$dataSource->commit();
			$this->Flash->success(('請求登録しました。'));
		} else {
			$dataSource->rollback();
			$this->Flash->error(('請求登録に失敗しました。'));
		}
	}
}
