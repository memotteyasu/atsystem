<?php
App::uses('AppController', 'Controller');
/**
 * Productmasters Controller
 *
 * @property Productmaster $Productmaster
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class ProductmastersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session', 'Flash');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Productmaster->recursive = 0;
		$this->set('productmasters', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Productmaster->exists($id)) {
			throw new NotFoundException(__('Invalid productmaster'));
		}
		$options = array('conditions' => array('Productmaster.' . $this->Productmaster->primaryKey => $id));
		$this->set('productmaster', $this->Productmaster->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Productmaster->create();
			if ($this->Productmaster->save($this->request->data)) {
				$this->Flash->success(__('The productmaster has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The productmaster could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Productmaster->exists($id)) {
			throw new NotFoundException(__('Invalid productmaster'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Productmaster->save($this->request->data)) {
				$this->Flash->success(__('The productmaster has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The productmaster could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Productmaster.' . $this->Productmaster->primaryKey => $id));
			$this->request->data = $this->Productmaster->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Productmaster->id = $id;
		if (!$this->Productmaster->exists()) {
			throw new NotFoundException(__('Invalid productmaster'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Productmaster->delete()) {
			$this->Flash->success(__('The productmaster has been deleted.'));
		} else {
			$this->Flash->error(__('The productmaster could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
