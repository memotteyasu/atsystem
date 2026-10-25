<?php
App::uses('AppController', 'Controller');
/**
 * HostKobetsuteate Controller
 */
class HostKobetsuteatesController extends AppController {
	public $layout = 'layout_host';
	public $helpers = ['Html','Form'];
	public $uses = ['Kobetsuteatemaster'];

	public function beforeFilter(){
		parent::beforeFilter();
		// 検索対象のフィールド設定代入
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
	    if($this->Session->read('BackData1.page')=='HostKobetsuteates'){
	        $conditions = $this->Session->read('BackData1.conditions');
	    }else{
	        $this->Session->delete('BackData1');
	        $backdata1 = [
	            'page'=>$this->page,
	            'conditions'=>[
	            ]
	        ];
	        $this->Session->write('BackData1',$backdata1);
	        $conditions = $backdata1['conditions'];
	    }

		$this->paginate = [
			'fields' => [
				'Kobetsuteatemaster.id',
				'Kobetsuteatemaster.kobetsukilo',
				'Kobetsuteatemaster.teatename',
			],
			'conditions' => [
				'AND' => $conditions,
			    'Kobetsuteatemaster.delflg'=>0,
				'Kobetsuteatemaster.kaisyas_id' => $this->kaisyas_id
			],
			'limit' => 10,
			'order' => [
				'Kobetsuteatemaster.kobetsukilo' => 'asc',
			],
			'recursive' => 0,
		];
		$this->set('kobetsuteatemasters', $this->paginate('Kobetsuteatemaster'));

	}

	public function add() {
		if ($this->request->is('post')) {
		    $this->Kobetsuteatemaster->create();
			$this->request->data['Kobetsuteatemaster']['kaisyas_id'] = $this->kaisyas_id;
			if ($this->Kobetsuteatemaster->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Kobetsuteatemaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid postaaa'));
		}

		if ($this->request->is(array('post', 'put'))) {
		    $this->Kobetsuteatemaster->id = $id;
		    if ($this->Kobetsuteatemaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}

	}

	public function delete($id = null) {
	    $this->Kobetsuteatemaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		} else {
			$this->request->data['Kobetsuteatemaster']['delflg'] = 1;
			if ($this->Kobetsuteatemaster->save($this->request->data)){
				$this->Flash->success(__('削除しました。'));
			} else {
				$this->Flash->error(__('削除に失敗しました。'));
			}
		}
		return $this->redirect(['action' => 'index']);
	}


}
