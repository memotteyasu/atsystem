<?php
App::uses('AppController', 'Controller');

class EmployeepointsController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('Employeepoint', 'User');
	//public $components = array('Search.Prg');
	//public $presetVars = array();

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);

    	// 検索対象のフィールド設定代入
    	$this->presetVars = $this->Employeepoint->presetVars;
    }

    public function isAuthorized($user){
    	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '19'){
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
		/*
		// 検索条件設定
		$this->Prg->commonProcess();
		// 検索条件取得
		$conditions = $this->Employeepoint->parseCriteria($this->passedArgs);
		*/

		$this->paginate = [
			'fields' => [
				'Employeepoint.id',
				'User.usernameK',
				'Employeepoint.pointzan'
			],
			'limit' => 10,
			'recursive' => 0
		];
		$this->set('employeepoints', $this->paginate('Employeepoint'));

// 		$this->Employeepoint->recursive = 0;
// 		$this->set('employeepoints', $this->Paginator->paginate());

// 		$users = $this->User->find('list', [
// 			'fields' => ['id','usernameK'],
// 		]);
// 		$this->set(compact('users'));

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

	public function test(){
		$this->Employeepoint->updateAll(
			['pointzan' => 3000]
		);
	}

}
