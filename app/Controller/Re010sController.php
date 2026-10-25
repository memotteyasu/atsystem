<?php
App::uses('AppController', 'Controller');
/**
 * Re010 Controller
 */
class Re010sController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Receptionmessage');
    public $components = array('Search.Prg');

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);

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
	    $page='Re010s';

		$this->paginate = [
			'conditions' => [
			    'Receptionmessage.kaisyas_id'=>$this->Auth->user('kaisyas_id'),
			],
			'limit' => 10,
			'order' => [
				'Receptionmessage.receptiondate' => 'desc',
			],
			'recursive' => 0,
		];
		$this->set('receptionmessages', $this->paginate('Receptionmessage'));
	}

	public function add() {
	    if ($this->request->is(array('post', 'put'))) {
	        $this->request->data['Receptionmessage']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
	        if ($this->Receptionmessage->save($this->request->data)) {
	            $this->Flash->success(__('保存しました'));
	            return $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Flash->error(__('保存できません'));
	        }
	    }
	}

	public function edit($id = null) {
	    if ($this->request->is(array('post', 'put'))) {
	        $this->request->data['Receptionmessage']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
	        if ($this->Receptionmessage->save($this->request->data)) {
	            $this->Flash->success(__('保存しました'));
	            return $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Flash->error(__('保存できません'));
	        }
	    } else {
	        if($id){
	            $this->request->data = $this->Receptionmessage->findById($id);
	        }
	    }
	}


	public function delete($id = null) {
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{

		    if ($this->Receptionmessage->delete($id)){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
	}



}
