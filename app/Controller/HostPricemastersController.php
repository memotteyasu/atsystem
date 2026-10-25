<?php
App::uses('AppController', 'Controller');
/**
 * HostTops Controller
 *
 * @property HostTop $HostTops
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class HostPricemastersController extends AppController {
	public $layout = 'layout_host';
	public $helpers = ['Html','Form'];
	public $uses = ['Pricemaster','Departmentmaster','Shippermaster','Deliverymaster','Loadingmaster','Productdetailmaster','Productcontainer'];
	public $components = ['Search.Prg'];
	public $presetVars = [];

	public function beforeFilter(){
		parent::beforeFilter();
		// 検索対象のフィールド設定代入
		$this->presetVars = $this->Pricemaster->presetVars;
	}

	public function isAuthorized($user){
		if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '29'){
			$this->set('user',$user);
			return true;
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
		// 検索条件設定
		$this->Prg->commonProcess();
		// 検索条件取得
		$conditions = $this->Pricemaster->parseCriteria($this->passedArgs);

		$this->paginate = [
			'fields' => [
// 				'id',
// 				'pricename',
// 				'price'
			],
			'conditions' => [
				'AND' => $conditions,
				'Pricemaster.delflg' => 0,
			    'Pricemaster.kaisyas_id'=>$this->kaisyas_id
			],
			'limit' => 10,
			'order' => [
				'Pricemaster.pricename' => 'asc',
				'Pricemaster.price' => 'asc'
			],
			'recursive' => 0,
		];
		$this->set('pricemasters', $this->paginate('Pricemaster'));

		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Pricemaster->create();
			$this->request->data['Pricemaster']['kaisyas_id']=$this->Auth->user('kaisyas_id');
			if ($this->Pricemaster->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('失敗しました。');
			}
		}
//		$shippermasters = $this->Shippermaster->ShipperList();
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
//		$loadingmasters = $this->Loadingmaster->SelectLoadingList2();
//		$deliverymasters = $this->Deliverymaster->SelectDeliveryList2();
		$productdetailmasters = $this->Productdetailmaster->SelectList();
//		$productcontainers = $this->Productcontainer->SelectProductcontainerList2();
		$this->set(compact('shippermasters','departmentmasters','loadingmasters','deliverymasters','productdetailmasters','productcontainers'));
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Pricemaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid postaaa'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Pricemaster->id = $id;
			if ($this->Pricemaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				//20240902 return $this->redirect(array('action' => 'index','page'=>$this->params->pass[1]));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}

		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$shippermasters = $this->Shippermaster->SelectShipperList($post['Pricemaster']['departmentmasters_id']);
		$loadingmasters = $this->Loadingmaster->SelectLoadingList($post['Pricemaster']['departmentmasters_id']);
		$deliverymasters = $this->Deliverymaster->SelectDeliveryList($post['Pricemaster']['departmentmasters_id']);
		$productdetailmasters = $this->Productdetailmaster->pdnameList2($post['Pricemaster']['shippermasters_id']);
		$productcontainers = $this->Productcontainer->SelectProductcontainerList($post['Pricemaster']['productdetailmasters_id']);
		$this->set(compact('shippermasters','departmentmasters','loadingmasters','deliverymasters','productdetailmasters','productcontainers'));
	}

	public function delete($id = null) {
		$this->Pricemaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		} else {
			$this->request->data['Pricemaster']['delflg'] = 1;
			if ($this->Pricemaster->save($this->request->data)){
				$this->Flash->success(__('削除しました。'));
			} else {
				$this->Flash->error(__('削除に失敗しました。'));
			}
		}
		return $this->redirect(['action' => 'index']);
	}


}
