<?php
App::uses('AppController', 'Controller');
/**
 * Co082 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Co082sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form','Format');
	public $uses = ['User','Kaisya','Departmentmaster','Shippermaster','Carmaster','Branchmaster','Orderexpensestran','Expensestran'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co082s';
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
		$expensestrans = $this->Expensestran->find('all',[
			'conditions' => [
				'Expensestran.departmentmasters_id' => $this->did,
			]
		]);
	    $this->set('expensestrans', $expensestrans);
	    $shippermasters = $this->Shippermaster->SelectShipperList($this->did);
	    $this->set('shippermasters',$shippermasters);		
	}
	public function add(){
		$did = $this->did;
		$page = $this->page;
		$bgid = $this->branchgroups_id;

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$this->set('shippermasters',$shippermasters);
		$carmasters = $this->Carmaster->CarListGroup($bgid);
		$this->set('carmasters',$carmasters);
		$today = date('Y-m-d');
		$this->set('today',$today);

		if ($this->request->is('post')) {
			$this->Expensestran->create();
			$this->request->data['Expensestran']['kaisyas_id'] = $this->kaisyas_id;
			$this->request->data['Expensestran']['branchmasters_id'] = $this->branchmasters_id;
			$this->request->data['Expensestran']['departmentmasters_id'] = $this->did;
			if ($this->Expensestran->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('失敗しました');
			}
		}

	}

	public function edit($id = null){
		$did = $this->did;
		$page = $this->page;
		$bgid = $this->branchgroups_id;
		$today = date('Y-m-d');
		$this->set('today',$today);
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Expensestran->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Expensestran->id = $id;
			if ($this->Expensestran->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index'));
			}
			//$this->Flash->error('失敗しました。');
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
		$shippermasters = $this->Shippermaster->SelectShipperList($this->did);
		$this->set('shippermasters',$shippermasters);
		$carmasters = $this->Carmaster->CarListGroup($bgid);
		$this->set('carmasters',$carmasters);

	}

	public function delete($id = null) {

		$this->Tantomaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->Expensestran->id = $id;
			if ($this->Expensestran->delete($id)){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
	}

}
