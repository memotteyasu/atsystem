<?php
App::uses('AppController', 'Controller');

class ImagesController extends AppController {
	public $layout = 'layout_host';
    public $helpers = ['Html','Form','UploadPack.Upload'];
    public $uses = ['Image'];

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
		$this->set('images', $this->Image->find('all'));
	}

	public function add(){
		if ($this->request->is('post')) {
			$this->Image->create();
			if ($this->Image->save($this->request->data)) {
				$this->Flash->success('保存しました');
				echo debug($this->Image->getLastInsertId);
				echo debug($this->request->data);
				//return $this->redirect(array('action' => 'index'));
			}
		}
	}

	public function edti($id = null){
		$this->Image->id = $id;
		if (emptyempty($this->data)) {
			$this->data = $this->Image->read();
		} else {
			if ($this->Image->save($this->data['Image'])) {
				$this->redirect('/images');
			}
		}
	}
}
