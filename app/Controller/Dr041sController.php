<?php
App::uses('AppController', 'Controller');

class Dr041sController extends AppController {
	public $layout = 'layout_drive';
	public $helpers = array('Html','Form');
	public $uses = array('Employeepoint','Employeegetpoint', 'User');

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);
    }

    public function isAuthorized($user){
    	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '30' && $user['rolemasters_id'] <= '39'){
    		$this->set('user',$user);
    		return true;
    	}
    	return false;
    	//break;
    }
/**
 * index method
 *
 * @return void
 */
	public function index() {
		//debug ($this->Auth->user());
		$users = $this->User->find('first',[
			'conditions' => ['User.id'=>$this->Auth->user('id')]
		]);
		$this->set(compact('users'));

		$employeegetpoints = $this->Employeegetpoint->find('all',[
				'conditions' => ['Employeegetpoint.users_id'=>$this->Auth->user('id')],
				'recursive' => 0,
				'order'=>[
					'getdate'=>'desc'

				]
		]);
		$employeegetpoint2s = $this->Employeegetpoint->find('all',[
				'conditions' => ['Employeegetpoint.sendeduserid'=>$this->Auth->user('id')],
				'recursive' => 0,
				'order'=>[
					'getdate'=>'desc'

				]
		]);

		$this->set(compact('employeegetpoints', 'employeegetpoint2s'));

	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Employeepoint->create();
			if ($this->Employeepoint->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {
				//$this->Flash->error('失敗しました');
			}
		}

		$users = $this->User->find('list', [
				'fields' => ['id','usernameK'],
		]);
		$this->set(compact('users'));
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Employeepoint->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Employeepoint->id = $id;
			if ($this->Employeepoint->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index'));
			}
			//$this->Flash->error('失敗しました。');
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}

		$users = $this->User->find('list', [
			'fields' => ['id','usernameK'],
		]);
		$this->set(compact('users'));
	}

	public function delete($id = null) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if ($this->Employeepoint->delete($id)) {
			$this->Flash->success(__('削除しました'));
		} else {
			$this->Flash->error(__('失敗しました'));
		}
		return $this->redirect(['action' => 'index']);


		/*
		$this->Branchmaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Branchmaster']['delflg'] = 1;
			$this->Branchmaster->id = $id;
			if ($this->Branchmaster->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
		*/
	}

}
