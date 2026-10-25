<?php
App::uses('AppController', 'Controller');
/**
 * HostSummertimes Controller
 */
class HostSummertimesController extends AppController {
	public $layout = 'layout_host';
	public $helpers = ['Html','Form'];
	public $uses = ['Summertime','Departmentmaster','Branchmaster'];
	public $components = ['Search.Prg'];
	public $presetVars = [];

	public function beforeFilter(){
		parent::beforeFilter();
		// 検索対象のフィールド設定代入
		$this->presetVars = $this->Summertime->presetVars;
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
		// 検索条件設定
		$this->Prg->commonProcess();
		// 検索条件取得
		$conditions = $this->Summertime->parseCriteria($this->passedArgs);

		$this->paginate = [
			'conditions' => [
				'AND' => $conditions,
				'Summertime.delflg' => 0,
				'Summertime.kaisyas_id' => $this->kaisyas_id
			],
			'limit' => 10,
			'order' => [
				'Summertime.id' => 'asc',
				'Summertime.summerstratdate' => 'asc'
			],
			'recursive' => 0,
		];
		$this->set('summertimes', $this->paginate('Summertime'));

		$branchmasters = $this->Branchmaster->BranchList($this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->kaisyas_id);
		$this->set(compact('departmentmasters','branchmasters'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Summertime->create();
			$this->request->data['Summertime']['kaisyas_id'] = $this->kaisyas_id;
			if ($this->Summertime->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}
		$branchmasters = $this->Branchmaster->BranchList($this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->kaisyas_id);
		$this->set(compact('departmentmasters','branchmasters'));
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Summertime->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid postaaa'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Summertime->id = $id;
			if ($this->Summertime->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}

		$branchmasters = $this->Branchmaster->BranchList($this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->kaisyas_id);
		$this->set(compact('departmentmasters','branchmasters'));
	}

	public function delete($id = null) {
		$this->Summertime->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		} else {
			$this->request->data['Summertime']['delflg'] = 1;
			if ($this->Summertime->save($this->request->data)){
				$this->Flash->success(__('削除しました。'));
			} else {
				$this->Flash->error(__('削除に失敗しました。'));
			}
		}
		return $this->redirect(['action' => 'index']);
	}


}
