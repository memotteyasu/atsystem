<?php
App::uses('AppController', 'Controller');

class EmployeegetpointsController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('Employeegetpoint', 'User');
	//public $components = array('Search.Prg');
	//public $presetVars = array();

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);

    	// 検索対象のフィールド設定代入
    	//$this->presetVars = $this->Employeegetpoint->presetVars;
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
		$conditions = $this->Employeegetpoint->parseCriteria($this->passedArgs);
		*/

		$this->paginate = [
			'fields' => [
				'Employeegetpoint.id',
				'User.usernameK',
				'Employeegetpoint.getdate',
				'SendedUser.usernameK',
				'Employeegetpoint.point',
				'Employeegetpoint.sendreason'
			],
			'limit' => 10,
			'order' => [
				'Employeegetpoint.id' => 'asc'
			],
			'recursive' => 0
		];
		$this->set('employeegetpoints', $this->paginate('Employeegetpoint'));
		//$this->set('employeegetpoints', $this->Employeegetpoint->find('all'));

// 		$this->Employeegetpoint->recursive = 0;
// 		$this->set('employeegetpoints', $this->Paginator->paginate());

// 		$users = $this->User->find('list', [
// 			'fields' => ['id','usernameK'],
// 		]);
// 		$sendedusers = $this->User->find('list', [
// 			'fields' => ['id','usernameK'],
// 		]);
// 		$this->set(compact('users','sendedusers'));

	}

	public function add() {

	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Employeegetpoint->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Employeegetpoint->id = $id;
			if ($this->Employeegetpoint->save($this->request->data)) {
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
		$sendedusers = $this->User->find('list', [
				'fields' => ['id','usernameK'],
		]);
		$this->set(compact('users','sendedusers'));
	}

	public function delete($id = null) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if ($this->Employeegetpoint->delete($id)) {
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
