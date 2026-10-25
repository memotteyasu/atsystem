<?php
App::uses('AppController', 'Controller');
/**
 * HostProcessmasters Controller
 *
 * @property HostProcessmasters $HostProcessmasters
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class HostProcessmastersController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');


    public function beforeFilter(){
    	parent::beforeFilter();
    	$this->set('auth',$this->Auth);
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
	}

	public function add() {
		if($this->request->is('post')) {
			if($this->HostProcessmaster->save($this->request->data)) {
				$this->Flash->set('OK');
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Flash->set('保存しました');
			}
		}

	}


	public function delete($id = null) {
		$this->HostProcessmaster->id = $id;

		if($this->HostProcessmaster->delete()){
			$this->redirect(array('action'=>'index'));
		}

	}


}
