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
class EmployeemastersController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('Employeemaster', 'Branchmaster');
	public $components = array('Search.Prg');
	public $presetVars = array();

    public function beforeFilter(){
    	parent::beforeFilter();
    	$this->set('auth',$this->Auth);

    	// 検索対象のフィールド設定代入
    	$this->presetVars = $this->Employeemaster->presetVars;
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
		// 検索条件設定
		$this->Prg->commonProcess();
		// 検索条件取得
		$conditions = $this->Employeemaster->parseCriteria($this->passedArgs);

		$this->paginate = [
				'conditions' => ['AND' => $conditions, ['Employeemaster.delflg' => 0]],
				'limit' => 10,
				'order' => [
						'Employeemaster.id' => 'asc'
				]
		];
		$this->set('employeemasters', $this->paginate('Employeemaster'));

		$branchmasters = $this->Branchmaster->find('list', [
				'fields' => ['id','branchname'],
		]);
		$this->set(compact('branchmasters'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Employeemaster->create();
			if ($this->Employeemaster->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {
				//$this->Flash->error('失敗しました');
			}
		}

		$branchmasters = $this->Branchmaster->find('list', [
				'fields' => ['id','branchname'],
		]);
		$this->set(compact('branchmasters'));
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Employeemaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Employeemaster->id = $id;
			if ($this->Employeemaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index'));
			}
			//$this->Flash->error('失敗しました。');
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}

		$branchmasters = $this->Branchmaster->find('list', [
				'fields' => ['id','branchname'],
		]);
		$this->set(compact('branchmasters'));
	}

	public function delete($id = null) {
		$this->Employeemaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Employeemaster']['delflg'] = 1;
			$this->Employeemaster->id = $id;
			if ($this->Employeemaster->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
	}


}
