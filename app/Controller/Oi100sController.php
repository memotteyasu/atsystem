<?php
App::uses('AppController', 'Controller');
/**
 * Oi100 Controller
 *
 */
class Oi100sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = ['Html','Form'];
	public $uses = ['User','Invoice','Invoicedetail','Kaisya','Shippermaster','Ordertran','Carmaster'];
	public $components = ['RequestHandler','SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 1;
		$this->page = 'Oi100s';
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
		// $page = 'Oi100s';
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

		$selectData = $this->SelectData->data_select_conditions100s($paramsDate,$paramsDate2,$did,$shippermasters_id,$this->bid);
		$invoices = $selectData['invoices'];
		$this->set(compact('invoices'));

	}

	public function pdf($id) {
		//$this->set('invoices', $this->Invoice->find('all'));
		$invoice = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'recursive' => 1,
		]);
		$this->set('invoices', $invoice);
		$this->set('detailBranches', $this->buildOi100DetailBranches($invoice));


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

	/**
	 * 請求明細PDF（鏡とは別出力）
	 */
	public function pdf_detail($id) {
		$invoice = $this->Invoice->find('first', [
			'conditions' => [
				'Invoice.id' => $id,
			],
			'recursive' => 1,
		]);
		$this->set('invoices', $invoice);

		// 石油部署の車両を、営業所ごとに集計して明細ページへ出力する。
		$branchLabels = [
			1 => '本社（朝日運輸）',
			21 => '大豊運輸',
			29 => '金川運輸',
		];
		$carmasters = $this->Carmaster->find('all', [
			'fields' => ['Carmaster.id', 'Carmaster.branchmasters_id', 'Carmaster.idemituplateno'],
			'conditions' => [
				'Carmaster.departmentmasters_id' => $this->did,
				'Carmaster.branchmasters_id' => array_keys($branchLabels),
				'Carmaster.scrapflg' => 0,
				'Carmaster.delflg' => 0,
			],
			'order' => ['Carmaster.idemituplateno' => 'asc'],
			'recursive' => -1,
		]);

		$carBranches = [];
		foreach ($carmasters as $carmaster) {
			$carId = $carmaster['Carmaster']['id'];
			$carBranches[$carId] = $carmaster['Carmaster'];
		}
		$detailBranches = [];
		foreach ($branchLabels as $branchId => $branchLabel) {
			$detailBranches[$branchId] = [
				'label' => $branchLabel,
				'commission_rate' => $branchId === 29 ? 0.045 : ($branchId === 21 ? 0.03 : null),
				'details' => [],
			];
		}
		foreach ($carBranches as $carId => $carmaster) {
			$branchId = (int)$carmaster['branchmasters_id'];
			$detailBranches[$branchId]['details'][$carId] = [
				'itemname' => $carmaster['idemituplateno'],
				'quantity' => 0,
				'unit' => '',
				'price' => 0,
				'payment' => 0,
				'tollfee' => 0,
				'handtollfee' => 0,
			];
		}
		foreach ($invoice['Invoicedetail'] as $detail) {
			$carId = (int)$detail['carmasters_id'];
			if (!isset($carBranches[$carId])) {
				continue;
			}

			$branchId = (int)$carBranches[$carId]['branchmasters_id'];
			$detailBranches[$branchId]['details'][$carId]['quantity'] += (float)$detail['quantity'];
			$detailBranches[$branchId]['details'][$carId]['price'] += (int)$detail['price'];
			$detailBranches[$branchId]['details'][$carId]['payment'] += (int)$detail['payment'];
			$detailBranches[$branchId]['details'][$carId]['tollfee'] += (int)$detail['tollfee'];
			$detailBranches[$branchId]['details'][$carId]['handtollfee'] += (int)$detail['handtollfee'];
		}
		foreach ($detailBranches as &$detailBranch) {
			// 金額未入力の車両は明細に印字しない。
			foreach ($detailBranch['details'] as $carId => $detail) {
				$displayAmount = !empty($detail['payment']) ? (int)$detail['payment'] : (int)$detail['price'];
				if ($displayAmount === 0) {
					unset($detailBranch['details'][$carId]);
				}
			}
			$detailBranch['details'] = array_values($detailBranch['details']);
		}
		unset($detailBranch);
		$this->set(compact('detailBranches'));

		$this->set('kaisyas', $this->Kaisya->find('first', [
			'conditions' => [
				'Kaisya.id' => $this->Auth->user('kaisyas_id')
			],
			'recursive' => 0,
		]));

		$this->RequestHandler->respondAs('application/pdf');
		$this->layout = '';
	}

	private function buildOi100DetailBranches($invoice) {
		$branchLabels = [
			1 => '本社（朝日運輸）',
			21 => '大豊運輸',
			29 => '金川運輸',
		];
		$carmasters = $this->Carmaster->find('all', [
			'fields' => ['Carmaster.id', 'Carmaster.branchmasters_id', 'Carmaster.idemituplateno'],
			'conditions' => [
				'Carmaster.departmentmasters_id' => $this->did,
				'Carmaster.branchmasters_id' => array_keys($branchLabels),
				'Carmaster.scrapflg' => 0,
				'Carmaster.delflg' => 0,
			],
			'order' => ['Carmaster.idemituplateno' => 'asc'],
			'recursive' => -1,
		]);

		$detailBranches = [];
		foreach ($branchLabels as $branchId => $branchLabel) {
			$detailBranches[$branchId] = [
				'label' => $branchLabel,
				'commission_rate' => $branchId === 29 ? 0.045 : ($branchId === 21 ? 0.03 : null),
				'details' => [],
			];
		}
		foreach ($carmasters as $carmaster) {
			$car = $carmaster['Carmaster'];
			$carId = (int)$car['id'];
			$branchId = (int)$car['branchmasters_id'];
			$detailBranches[$branchId]['details'][$carId] = [
				'itemname' => $car['idemituplateno'],
				'quantity' => 0,
				'unit' => '',
				'price' => 0,
				'payment' => 0,
				'tollfee' => 0,
				'handtollfee' => 0,
			];
		}
		foreach ($invoice['Invoicedetail'] as $detail) {
			$carId = (int)$detail['carmasters_id'];
			foreach ($detailBranches as $branchId => &$detailBranch) {
				if (!isset($detailBranch['details'][$carId])) {
					continue;
				}
				$detailBranch['details'][$carId]['quantity'] += (float)$detail['quantity'];
				$detailBranch['details'][$carId]['price'] += (int)$detail['price'];
				$detailBranch['details'][$carId]['payment'] += (int)$detail['payment'];
				$detailBranch['details'][$carId]['tollfee'] += (int)$detail['tollfee'];
				$detailBranch['details'][$carId]['handtollfee'] += (int)$detail['handtollfee'];
				break;
			}
			unset($detailBranch);
		}
		foreach ($detailBranches as &$detailBranch) {
			// 金額未入力の車両は明細に印字しない。
			foreach ($detailBranch['details'] as $carId => $detail) {
				$displayAmount = !empty($detail['payment']) ? (int)$detail['payment'] : (int)$detail['price'];
				if ($displayAmount === 0) {
					unset($detailBranch['details'][$carId]);
				}
			}
			$detailBranch['details'] = array_values($detailBranch['details']);
		}
		unset($detailBranch);

		return $detailBranches;
	}

	public function add() {
 		// $did = 1;
		// $page = 'Oi100s';
		$did = $this->did;
		$page = $this->page;
		// $this->set('shippermasters', $this->Shippermaster->SelectShipperList($did));
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
					$this->request->data['Invoicedetail'][$s]['tax'] = 1;
				}
				$s = $s + 1;
			}
			$this->Invoice->create();
			$this->request->data['Invoice']['pagetype'] = $page;
			$this->request->data['Invoice']['departmentmasters_id']=$did;
			if ($this->Invoice->saveAll($this->request->data)) {
				$this->Flash->success(__('請求書を作成しました。'));
				return $this->redirect(['action' => 'index', 'controller' => 'Oi100s']);
			} else {
				$this->Flash->error(__('請求書作成に失敗しました。'));
			}
		}
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
				return $this->redirect(['action' => 'index', 'controller' => 'Oi100s']);
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

}
