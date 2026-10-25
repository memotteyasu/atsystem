<?php
App::uses('AppController', 'Controller');
/**
 * Co111 Controller
 *
 */
class Co111sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = ['Html','Form'];
	public $uses = ['User','Invoice','Invoicedetail','Kaisya','Shippermaster','Ordertran'];
	public $components = ['RequestHandler'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co111s';
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
		$this->paginate = [
			//'limit' => 10,
			'conditions' => [
				'Invoice.mirror_flg' => 1
			],
			'order' => [
				'Invoice.billingdate' => 'asc',
				'Invoice.invoicenumber' => 'asc',
				'Invoice.id' => 'asc',

			],
			'recursive' => 0
		];
		$this->set('invoices', $this->paginate('Invoice'));
	}

	public function pdf($id) {
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

	public function pdf_meisai_back($id) {
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


	public function edit($id = null) {

		$post = $this->Invoice->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}
		if ($this->request->is(['post', 'put'])) {
			$i = 0;
			foreach ($this->request->data['Details'] as $details) {
				if ($details['itemname']) {
					$this->request->data['Invoicedetail'][$i]['id'] = $details['id'];
					$this->request->data['Invoicedetail'][$i]['itemname'] = $details['itemname'];
					$this->request->data['Invoicedetail'][$i]['price'] = $details['price'];
				}
				$i = $i + 1;
			}
			$j = $i;
			foreach ($this->request->data['Details2'] as $details2) {
				if ($details2['itemname']) {
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
			if ($this->Invoice->saveAll($this->request->data)) {
				$this->Flash->success(__('請求書を編集しました。'));
				return $this->redirect(['action' => 'index', 'controller' => 'Co111s']);
			} else {
				$this->Flash->error(__('請求書編集に失敗しました。'));
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id = null) {
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			if ($this->Invoice->delete($id,true)) {
				$this->Flash->success(__('請求書を削除しました。'));
			} else {
				$this->Flash->error(__('削除に失敗しました。'));
			}
		}
		return $this->redirect(['controller' => 'Co100s','action' => 'index']);
	}

}
