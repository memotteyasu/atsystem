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
class HostProductdetailmastersController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('Productdetailmaster','Shippermaster','Departmentmaster');
	public $components = array('Search.Prg');
	public $presetVars = array();

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);

    	// 検索対象のフィールド設定代入
    	$this->presetVars = $this->Productdetailmaster->presetVars;
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
		$conditions = $this->Productdetailmaster->parseCriteria($this->passedArgs);

		$this->paginate = [
			'fields' => [
				'Productdetailmaster.id',
				'Productdetailmaster.pdcode',
				'Productdetailmaster.pdname',
				'Productdetailmaster.pdshortname',
				'Productdetailmaster.teate1',
				'Productdetailmaster.teate2',
				'Productdetailmaster.teate3',
			    'Shippermaster.shippershortname'
			],
			'conditions' => [
			    'AND' => $conditions,
			    [
			        'Productdetailmaster.delflg' => 0,
			        'Productdetailmaster.kaisyas_id' => $this->kaisyas_id
			    ]
			],
			'limit' => 10,
			'order' => [
				'Productdetailmaster.pdcode' => 'asc',
				'Productdetailmaster.pdname' => 'asc'
			],
			'recursive' => 0,
		];
		$this->set('productdetailmasters', $this->paginate('Productdetailmaster'));

		$shippermasters = $this->Shippermaster->ShipperList($this->kaisyas_id);
		$this->set(compact('shippermasters'));

	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Productdetailmaster->create();
			$this->request->data['Productdetailmaster']['kaisyas_id'] = $this->kaisyas_id;
			if ($this->Productdetailmaster->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {

			}
		}
		$shippermasters = $this->Shippermaster->ShipperList($this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('shippermasters','departmentmasters'));
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Productdetailmaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Productdetailmaster->id = $id;
			if ($this->Productdetailmaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('失敗しました。');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}

		$shippermasters = $this->Shippermaster->ShipperList($this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('shippermasters','departmentmasters'));
	}

	public function delete($id = null) {
		$this->Productdetailmaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Productdetailmaster']['delflg'] = 1;
			$this->Productdetailmaster->id = $id;
			if ($this->Productdetailmaster->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
	}
}
