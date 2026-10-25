<?php
App::uses('AppController', 'Controller');
/**
 * Tantomaster Controller
 */
class TantomastersController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('Tantomaster', 'Shippermaster');
	public $components = ['Search.Prg'];
	public $presetVars = [];

    public function beforeFilter(){
    	parent::beforeFilter();
    	// 検索対象のフィールド設定代入
    	$this->presetVars = $this->Tantomaster->presetVars;
    }

    public function isAuthorized($user){
    	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '29'){
    		$this->set('user',$user);
    		return true;
    	}
    	return false;
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
	    $conditions = $this->Tantomaster->parseCriteria($this->passedArgs);

	    $this->paginate = [
	        'fields'=>[
	            'Tantomaster.id',
	            'Tantomaster.tantoname',
	            'Tantomaster.tantotel',
	            'Shippermaster.shippershortname'
	        ],
	        'conditions' => [
	            'AND' => $conditions,
	            'Tantomaster.delflg'=>0,
	            'Tantomaster.tantoname NOT'=>'なし'
		    ],
	        'limit' => 10,
	        'order' => [
				'Tantomaster.shippermasters_id' => 'asc',
			]
		];
	    $this->set('tantomasters', $this->paginate('Tantomaster'));
	    $shippermasters = $this->Shippermaster->ShipperList($this->kaisyas_id);
	    $this->set('shippermasters',$shippermasters);
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Tantomaster->create();
			if ($this->Tantomaster->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {
				//$this->Flash->error('失敗しました');
			}
		}
		$shippermasters = $this->Shippermaster->ShipperList($this->kaisyas_id);
		$this->set('shippermasters',$shippermasters);
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Tantomaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Tantomaster->id = $id;
			if ($this->Tantomaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index'));
			}
			//$this->Flash->error('失敗しました。');
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
		$shippermasters = $this->Shippermaster->ShipperList($this->kaisyas_id);
		$this->set('shippermasters',$shippermasters);
	}

	public function delete($id = null) {

		$this->Tantomaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Tantomaster']['delflg'] = 1;
			$this->Tantomaster->id = $id;
			if ($this->Tantomaster->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
	}

}
