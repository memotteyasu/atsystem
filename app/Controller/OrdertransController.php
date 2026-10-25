<?php
App::uses('AppController', 'Controller');
/**
 * Ordertrans Controller
 *
 * @property Ordertran $Ordertran
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class OrdertransController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('Ordertran');
	public $components = array('Search.Prg');
	public $presetVars = array();

	public function beforeFilter(){
		parent::beforeFilter();
		$this->set('auth',$this->Auth);
	}

	public function isAuthorized($user){
		if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '19'){
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
		$this->Ordertran->recursive = 0;
		$this->set('ordertrans', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Ordertran->exists($id)) {
			throw new NotFoundException(__('Invalid ordertran'));
		}
		$options = array('conditions' => array('Ordertran.' . $this->Ordertran->primaryKey => $id));
		$this->set('ordertran', $this->Ordertran->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Ordertran->create();
			if ($this->Ordertran->save($this->request->data)) {
				$this->Flash->success(__('The ordertran has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The ordertran could not be saved. Please, try again.'));
			}
		}
		$shippermasters = $this->Ordertran->Shippermaster->find('list');
		$productdetailmasters = $this->Ordertran->Productdetailmaster->find('list');
		$loadingmastersHasDeliverymasters = $this->Ordertran->LoadingmastersHasDeliverymaster->find('list');
		$this->set(compact('shippermasters', 'productdetailmasters', 'loadingmastersHasDeliverymasters'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Ordertran->exists($id)) {
			throw new NotFoundException(__('Invalid ordertran'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Ordertran->save($this->request->data)) {
				$this->Flash->success(__('The ordertran has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The ordertran could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Ordertran.' . $this->Ordertran->primaryKey => $id));
			$this->request->data = $this->Ordertran->find('first', $options);
		}
		$shippermasters = $this->Ordertran->Shippermaster->find('list');
		$productdetailmasters = $this->Ordertran->Productdetailmaster->find('list');
		$loadingmastersHasDeliverymasters = $this->Ordertran->LoadingmastersHasDeliverymaster->find('list');
		$this->set(compact('shippermasters', 'productdetailmasters', 'loadingmastersHasDeliverymasters'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Ordertran->id = $id;
		if (!$this->Ordertran->exists()) {
			throw new NotFoundException(__('Invalid ordertran'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Ordertran->delete()) {
			$this->Flash->success(__('The ordertran has been deleted.'));
		} else {
			$this->Flash->error(__('The ordertran could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
