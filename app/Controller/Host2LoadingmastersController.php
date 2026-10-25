<?php
App::uses('AppController', 'Controller');
/**
 * HostTops Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Host2LoadingmastersController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('Loadingmaster', 'Loadingmasterimage', 'Departmentmaster', 'Branchmaster');
	public $components = array('Search.Prg');
	public $presetVars = array();

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);

    	// 検索対象のフィールド設定代入
    	$this->presetVars = $this->Loadingmaster->presetVars;
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
		$conditions = $this->Loadingmaster->parseCriteria($this->passedArgs);

		$this->paginate = [
			'fields' => [
				'Loadingmaster.id',
				'Loadingmaster.loadingcode',
				'Loadingmaster.loadingname',
				'Loadingmaster.loadingshortname',
			],
			'conditions' => ['AND' => $conditions, ['Loadingmaster.delflg' => 0]],
			'limit' => 10,
			'order' => [
				'Loadingmaster.loadingcode' => 'asc',
				'Loadingmaster.id' => 'asc'
			],
			'recursive' => 0,
		];
		$this->set('loadingmasters', $this->paginate('Loadingmaster'));

		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters', 'branchmasters'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Loadingmaster->create();
			if ($this->Loadingmaster->save($this->request->data)) {
				$this->Flash->success('積地を新規登録しました。');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('登録に失敗しました。');
			}
		}

		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters', 'branchmasters'));
	}

	public function edit($id = null) {
		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters', 'branchmasters'));

		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Loadingmaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		$this->set(compact('post'));

		if ($this->request->is(array('post', 'put'))) {
			$this->Loadingmaster->id = $id;
			if ($this->Loadingmaster->save($this->request->data)) {
				$this->Flash->success('積地の編集に成功しました。');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('編集に失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id = null) {
		$this->Loadingmaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Loadingmaster']['delflg'] = 1;
			$this->Loadingmaster->id = $id;
			if ($this->Loadingmaster->save($this->request->data, false, ['delflg'])){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function image($id = null) {
		$this->set('loadingimages', $this->Loadingmasterimage->find('all', [
			'conditions' => [
				'Loadingmasterimage.loadingmasters_id' => $id
			]
		]));
		$this->set('loadingname', $this->Loadingmaster->find('first', [
			'fields' => [
				'Loadingmaster.loadingname'
			],
			'conditions' => [
				'Loadingmaster.id' => $id
			]
		]));
	}

	public function imageedit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Loadingmasterimage->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Loadingmasterimage->id = $id;
			if ($this->Loadingmasterimage->save($this->request->data)) {
				$this->Flash->success('変更しました。');
				return $this->redirect(['action' => 'image', $post['Loadingmaster']['id']]);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function imagedelete($id = null, $did = null) {
		$post = $this->Loadingmasterimage->findById($id);
		$this->Loadingmasterimage->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$fileName = './loadingimages/original/'.$this->Loadingmasterimage->id.'_'.$post['Loadingmasterimage']['img_file_name'];
			$fileName2 = './loadingimages/resize/'.$this->Loadingmasterimage->id.'_'.$post['Loadingmasterimage']['img_file_name'];
			unlink($fileName);
			unlink($fileName2);
			if ($this->Loadingmasterimage->delete($id)) {
				$this->Flash->success(__($fileName.'を削除しました'));
			} else {
				$this->Flash->error(__('削除に失敗しました'));
			}
		}
		return $this->redirect(['action' => 'image', $did]);
	}

}
