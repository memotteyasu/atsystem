<?php
App::uses('AppController', 'Controller');
/**
 * Gyoumumessages Controller
 *
 * @property Gyoumumessage $Gyoumumessage
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class GyoumumessagesController extends AppController {

    public $helpers = array('Html','Form');

    public function beforeFilter(){
        parent::beforeFilter();
    }

    public function isAuthorized($user){
        if(($user['Rolemasters']['id'] >= '30')&&($user['Rolemasters']['id'] <= '39')){
            return true;
        }else{
            return false;
            break;
        }
    }

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Gyoumumessage->recursive = 0;
		$this->set('gyoumumessages', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Gyoumumessage->exists($id)) {
			throw new NotFoundException(__('Invalid gyoumumessage'));
		}
		$options = array('conditions' => array('Gyoumumessage.' . $this->Gyoumumessage->primaryKey => $id));
		$this->set('gyoumumessage', $this->Gyoumumessage->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Gyoumumessage->create();
			if ($this->Gyoumumessage->save($this->request->data)) {
				$this->Flash->success(__('The gyoumumessage has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The gyoumumessage could not be saved. Please, try again.'));
			}
		}
		$kaisyas = $this->Gyoumumessage->Kaisya->find('list');
		$departmentmasters = $this->Gyoumumessage->Departmentmaster->find('list');
		$this->set(compact('kaisyas', 'departmentmasters'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Gyoumumessage->exists($id)) {
			throw new NotFoundException(__('Invalid gyoumumessage'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Gyoumumessage->save($this->request->data)) {
				$this->Flash->success(__('The gyoumumessage has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The gyoumumessage could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Gyoumumessage.' . $this->Gyoumumessage->primaryKey => $id));
			$this->request->data = $this->Gyoumumessage->find('first', $options);
		}
		$kaisyas = $this->Gyoumumessage->Kaisya->find('list');
		$departmentmasters = $this->Gyoumumessage->Departmentmaster->find('list');
		$this->set(compact('kaisyas', 'departmentmasters'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Gyoumumessage->id = $id;
		if (!$this->Gyoumumessage->exists()) {
			throw new NotFoundException(__('Invalid gyoumumessage'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Gyoumumessage->delete()) {
			$this->Flash->success(__('The gyoumumessage has been deleted.'));
		} else {
			$this->Flash->error(__('The gyoumumessage could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
