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
class HostRegionmastersController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Areamaster','Departmentmaster');
    public $components = array('Search.Prg');
    public $presetVars = array();

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);

    	// 検索対象のフィールド設定代入
    	$this->presetVars = $this->Areamaster->presetVars;
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
		$conditions = $this->Areamaster->parseCriteria($this->passedArgs);

		$this->paginate = [
			'fields' => [
				'Areamaster.id',
				'Areamaster.areacode',
				'Areamaster.areaname',
			],
			'conditions' => ['AND' => $conditions, ['Areamaster.delflg' => 0]],
			'limit' => 10,
			'order' => [
				'Areamaster.areacode' => 'asc',
				'Areamaster.id' => 'asc'
			],
			'recursive' => 0,
		];
		$this->set('areamasters', $this->paginate('Areamaster'));

		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Areamaster->create();
			if ($this->Areamaster->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {

			}
		}
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters'));
	}

	public function edit($id = null) {
		if ($id == null) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Areamaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Areamaster->id = $id;
			if ($this->Areamaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index'));
			}
			//$this->Flash->error('失敗しました。');
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters'));
	}

	public function delete($id = null) {
		$this->Areamaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Areamaster']['delflg'] = 1;
			$this->Areamaster->id = $id;
			if ($this->Areamaster->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
		}


}
