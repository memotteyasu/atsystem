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
class HostProduct2mastersController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');


    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);
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
		/*
		$this->HostProductmaster->recursive = 0;
		$this->set('productmaseters', $this->Paginator->paginate());
		*/
	}

	public function add() {
		if($this->request->is('post')) {
			if($this->HostProductmaster->save($this->request->data)) {
				$this->Flash->set('OK');
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Flash->set('B');
			}
		}

	}

	public function edit($id = null) {
		/*
		$this->HostProductmaster->id = $id;

		if($this->request->is('post') || $this->request->is('put')){
			$this->HostProductmaster->save($this->request->data);
			$this->redirect(array('action'=>'index'));
		} else {
			$this->request->data = $this->HostProductmaster->read(null, $id);
		}
		*/

	}

	public function delete($id = null) {
		$this->HostProductmaster->id = $id;

		if($this->HostProductmaster->delete()){
			$this->redirect(array('action'=>'index'));
		}

	}


}
