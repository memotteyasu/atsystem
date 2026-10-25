<?php
App::uses('AppController', 'Controller');
/**
 * Rolemasters Controller
 *
 * @property Rolemaster $Rolemaster
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class RolemastersController extends AppController {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Html', 'Form');

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Rolemaster->recursive = 0;
		$this->set('rolemasters', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Rolemaster->exists($id)) {
			throw new NotFoundException(__('Invalid rolemaster'));
		}
		$options = array('conditions' => array('Rolemaster.' . $this->Rolemaster->primaryKey => $id));
		$this->set('rolemaster', $this->Rolemaster->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Rolemaster->create();
			if ($this->Rolemaster->save($this->request->data)) {
				$this->Flash->success(__('The rolemaster has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The rolemaster could not be saved. Please, try again.'));
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
		if (!$this->Rolemaster->exists($id)) {
			throw new NotFoundException(__('Invalid rolemaster'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Rolemaster->save($this->request->data)) {
				$this->Flash->success(__('The rolemaster has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The rolemaster could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Rolemaster.' . $this->Rolemaster->primaryKey => $id));
			$this->request->data = $this->Rolemaster->find('first', $options);
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
		$this->Rolemaster->id = $id;
		if (!$this->Rolemaster->exists()) {
			throw new NotFoundException(__('Invalid rolemaster'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Rolemaster->delete()) {
			$this->Flash->success(__('The rolemaster has been deleted.'));
		} else {
			$this->Flash->error(__('The rolemaster could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
