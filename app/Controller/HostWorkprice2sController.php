<?php
App::uses('AppController', 'Controller');
/**
 * HostWorkprice2s Controller
 */
class HostWorkprice2sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = ['Html','Form'];
	public $uses = ['Workprice2','Departmentmaster','Branchmaster'];
	public $components = ['Search.Prg'];
	public $presetVars = [];

	public function beforeFilter(){
		parent::beforeFilter();
		// 検索対象のフィールド設定代入
		$this->presetVars = $this->Workprice2->presetVars;
		$this->page = 'HostWorkprices';
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
//		$conditions = $this->Workprice->parseCriteria($this->passedArgs);
        $conditions=[];
		if ($this->request->data) {
		    $conditions = $this->Workprice2->parseCriteria($this->passedArgs);
		    $this->Session->write('BackData1.conditions',$conditions);
		}else{
		    if($this->Session->read('BackData1.page')=='HostWorkprice2s'){
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
		}

		$this->paginate = [
			'fields' => [
				'Workprice2.id',
				'Workprice2.startkilo',
				'Workprice2.endkilo',
			    'Workprice2.zangyojikan',
			    'Workprice2.shinyajikan',
			],
			'conditions' => [
				'AND' => $conditions,
			    'Workprice2.delflg'=>0,
				'Workprice2.kaisyas_id' => $this->kaisyas_id
			],
			'limit' => 10,
			'order' => [
				'Workprice2.startkilo' => 'asc',
			],
			'recursive' => 0,
		];
		$this->set('workprice2s', $this->paginate('Workprice2'));

	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Workprice2->create();
			$this->request->data['Workprice2']['kaisyas_id'] = $this->kaisyas_id;
			if ($this->Workprice2->save($this->request->data)) {
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

		$post = $this->Workprice2->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid postaaa'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Workprice2->id = $id;
			if ($this->Workprice2->save($this->request->data)) {
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
		$this->Workprice2->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		} else {
			$this->request->data['Workprice2']['delflg'] = 1;
			if ($this->Workprice2->save($this->request->data)){
				$this->Flash->success(__('削除しました。'));
			} else {
				$this->Flash->error(__('削除に失敗しました。'));
			}
		}
		return $this->redirect(['action' => 'index']);
	}


}
