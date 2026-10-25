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
class HostTruckheadmastersController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Carheadmaster','Branchmaster','Departmentmaster');
    public $components = array('Search.Prg');
    public $presetVars = array();


    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);

    	// 検索対象のフィールド設定代入
    	$this->presetVars = $this->Carheadmaster->presetVars;
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
		$page = 'HostTruckheadmasters';
		$bid = $did = $cid = $scrapflg = null;

		// 検索条件設定
		$this->Prg->commonProcess();
		// 検索条件取得
		$conditions = $this->Carheadmaster->parseCriteria($this->passedArgs);

		$this->Session->write('BackData1', ['conditions' => $conditions,'Page' => $page]);

		if ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$conditions = $this->Session->read('BackData2.conditions');
				if (isset($conditions['Carheadmaster.branchmasters_id'])) {
					$bid = $conditions['Carheadmaster.branchmasters_id'];
				}
				if (isset($conditions['Carheadmaster.departmentmasters_id'])) {
					$did = $conditions['Carheadmaster.departmentmasters_id'];
				}
				if (isset($conditions['Carheadmaster.id'])) {
					$cid = $conditions['Carheadmaster.id'];
				}
				if (isset($conditions['Carheadmaster.scrapflg'])) {
					$scrapflg = $conditions['Carheadmaster.scrapflg'];
				}
			}
			$this->Session->delete('BackData2');
		} elseif ($conditions) {
			if (isset($conditions['Carheadmaster.branchmasters_id'])) {
				$bid = $conditions['Carheadmaster.branchmasters_id'];
			}
			if (isset($conditions['Carheadmaster.departmentmasters_id'])) {
				$did = $conditions['Carheadmaster.departmentmasters_id'];
			}
			if (isset($conditions['Carheadmaster.id'])) {
				$cid = $conditions['Carheadmaster.id'];
			}
			if (isset($conditions['Carheadmaster.scrapflg'])) {
				$scrapflg = $conditions['Carheadmaster.scrapflg'];
			}
		} else {
			$conditions = ['Carheadmaster.scrapflg' => 0];
		}

		$this->set(compact('bid','did','cid','scrapflg'));

		$this->paginate = [
			'fields' => [
				'Carheadmaster.id',
				'Carheadmaster.plateno',
				'Carheadmaster.carno',
			    'Carheadmaster.carcheckdate',
			    'Carheadmaster.scrapflg',
			    'Carheadmaster.katasiki',
			    'Carheadmaster.syadaino',
			    'Carheadmaster.loadingaccount',
			    'Departmentmaster.departmentname',
			],
			'conditions' => [
			    'AND' => $conditions,
			    [
			        'Carheadmaster.delflg' => 0,
			    ]
			],
			'limit' => 10,
			'contain' => [
				'Departmentmaster',
			],
			'order' => [
				'Carheadmaster.plateno' => 'asc'
			],
			'recursive' => 1,
		];
		$this->set('carheadmasters', $this->paginate('Carheadmaster'));

		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		if (!$bid && !$did) {
			$carlist = $this->Carheadmaster->CarList();
		} elseif ($bid && $did) {
			$carlist = $this->Carheadmaster->CarListW($bid,$did);
		} elseif ($bid) {
			$carlist = $this->Carheadmaster->CarListBranch($bid);
		} elseif ($did) {
			$carlist = $this->Carheadmaster->CarListDepartment($did);
		}
		$this->set(compact('branchmasters','departmentmasters','carlist'));
	}

	public function add() {
	    $this->edit();
	    $this->render('edit');
	}

	public function edit($id = null) {
		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);

		if ($this->request->is(array('post', 'put'))) {
			$this->Carheadmaster->id = $id;
			$this->request->data['Carheadmaster']['kaisyas_id'] = $this->kaisyas_id;
			if ($this->Carheadmaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index'));
			}
			//$this->Flash->error('失敗しました。');
		}else{
		    if($id){
		        $this->request->data = $this->Carheadmaster->findById($id);
		    }else{
		        // 新規登録時
		    }
		}

		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('branchmasters','departmentmasters'));
	}

	public function delete($id = null) {
		$this->Carheadmaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Carheadmaster']['delflg'] = 1;
			$this->Carheadmaster->id = $id;
			if ($this->Carheadmaster->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
	}


}
