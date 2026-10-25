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
class DepartmentmastersController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('Departmentmaster', 'Kaisya','Branchmaster');

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
		$departmentmasters = $this->Departmentmaster->find('all',[
			'conditions' => [
				['Departmentmaster.kaisyas_id' => $this->Auth->user('kaisyas_id')]
			],
			'order' => [
				'Departmentmaster.branchmasters_id' => 'asc',
				'Departmentmaster.id' => 'asc'
			]
		]);
	    $this->set(compact('departmentmasters'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Departmentmaster->create();
			$this->request->data['Departmentmaster']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
			if ($this->Departmentmaster->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {
				//$this->Flash->error('失敗しました');
			}
		}

		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$this->set(compact('branchmasters'));
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Departmentmaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Departmentmaster->id = $id;
			if ($this->Departmentmaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index'));
			}
			//$this->Flash->error('失敗しました。');
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}

		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$this->set(compact('branchmasters'));
	}

	public function delete($id = null) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if ($this->Branchmaster->delete($id)) {
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
