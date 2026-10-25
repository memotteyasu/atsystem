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
class BranchmastersController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('Branchmaster');
	public $components = array('Search.Prg');
	public $presetVars = array();

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
		$branchmasters = $this->Branchmaster->find('all', [
			'fields' => [
				'id','branchname'
			],
			'conditions' => [
				'kaisyas_id'=>$this->Auth->user('kaisyas_id')
			]
		]);
	    $this->set(compact('branchmasters'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Branchmaster->create();
			$this->request->data['Branchmaster']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
			if ($this->Branchmaster->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {
				//$this->Flash->error('失敗しました');
			}
		}
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Branchmaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Branchmaster->id = $id;
			if ($this->Branchmaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index'));
			}
			//$this->Flash->error('失敗しました。');
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
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

	}

}
