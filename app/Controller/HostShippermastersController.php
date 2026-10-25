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
class HostShippermastersController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Shippermaster','Departmentmaster');
    public $components = array('Search.Prg');
    public $presetVars = array();

    public function beforeFilter(){
        parent::beforeFilter();
        //$this->set('auth',$this->Auth);

        // 検索対象のフィールド設定代入
        $this->presetVars = $this->Shippermaster->presetVars;
    }

    public function isAuthorized($user){
        if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '29'){
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
		$conditions = $this->Shippermaster->parseCriteria($this->passedArgs);

		$this->paginate = [
			'fields' => [
				'Shippermaster.id',
				'Shippermaster.shippercode',
				'Shippermaster.shippername',
				'Shippermaster.shippershortname',
				'Shippermaster.billtype',
				'Shippermaster.surcharge',
				'Shippermaster.detailmix',
				'Shippermaster.expensestaxflg',
				'Shippermaster.displayno',
				'Departmentmaster.departmentname'
			],
			'conditions' => [
			    'AND' => $conditions,
			    [
			        'Shippermaster.delflg' => 0,
			        'Shippermaster.kaisyas_id' => $this->kaisyas_id
			    ]
			],
			'limit' => 10,
			'order' => [
				'Shippermaster.displayno' => 'asc',
				'Shippermaster.id' => 'asc'
			],
			'recursive' => 0,
		];
		$this->set('shippermasters', $this->paginate('Shippermaster'));

		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters'));
		$billtypelist = array('' => '','1' => 'A(消費税・高速）', '3' => 'C（サーチャージ）', '4' => 'D（非課税）', '5' => 'E（消費税・非課税）', '6' => 'F（消費税・高速代・非課税）');
		$mixlist = array('0' => '混在', '1' => '個別');
		$expenseslist = array('0' => '課税', '1' => '免税');

		$this->set('billtypelist',$billtypelist);
		$this->set('mixlist',$mixlist);
		$this->set('expenseslist',$expenseslist);

	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Shippermaster->create();
			$this->request->data['Shippermaster']['kaisyas_id'] = $this->kaisyas_id;
			if ($this->Shippermaster->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {
				//$this->Flash->error('失敗しました');
			}
		}
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters'));
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Shippermaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Shippermaster->id = $id;
			if ($this->Shippermaster->save($this->request->data)) {
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
		$this->Shippermaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Shippermaster']['delflg'] = 1;
			$this->Shippermaster->id = $id;
			if ($this->Shippermaster->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));

	}

}
