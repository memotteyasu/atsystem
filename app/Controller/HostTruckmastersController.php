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
class HostTruckmastersController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Carmaster','Trailermaster','Branchmaster','Departmentmaster','Containertype');
    public $components = array('Search.Prg');
    public $presetVars = array();


    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);

    	// 検索対象のフィールド設定代入
    	$this->presetVars = $this->Carmaster->presetVars;
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
		$page = 'HostTruckmasters';
		$bid = $did = $cid = $scrapflg = null;

		// 検索条件設定
		$this->Prg->commonProcess();
		// 検索条件取得
		$conditions = $this->Carmaster->parseCriteria($this->passedArgs);

		$this->Session->write('BackData1', ['conditions' => $conditions,'Page' => $page]);

		if ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$conditions = $this->Session->read('BackData2.conditions');
				if (isset($conditions['Carmaster.branchmasters_id'])) {
					$bid = $conditions['Carmaster.branchmasters_id'];
				}
				if (isset($conditions['Carmaster.departmentmasters_id'])) {
					$did = $conditions['Carmaster.departmentmasters_id'];
				}
				if (isset($conditions['Carmaster.id'])) {
					$cid = $conditions['Carmaster.id'];
				}
				if (isset($conditions['Carmaster.scrapflg'])) {
					$scrapflg = $conditions['Carmaster.scrapflg'];
				}
			}
			$this->Session->delete('BackData2');
		} elseif ($conditions) {
			if (isset($conditions['Carmaster.branchmasters_id'])) {
				$bid = $conditions['Carmaster.branchmasters_id'];
			}
			if (isset($conditions['Carmaster.departmentmasters_id'])) {
				$did = $conditions['Carmaster.departmentmasters_id'];
			}
			if (isset($conditions['Carmaster.id'])) {
				$cid = $conditions['Carmaster.id'];
			}
			if (isset($conditions['Carmaster.scrapflg'])) {
				$scrapflg = $conditions['Carmaster.scrapflg'];
			}
		} else {
			$conditions = ['Carmaster.scrapflg' => 0];
		}

		$this->set(compact('bid','did','cid','scrapflg'));

		$this->paginate = [
			'fields' => [
				'Carmaster.id',
				'Carmaster.plateno',
				'Carmaster.displayno',
				'Carmaster.carno',
			    'Carmaster.innerno',
			    'Carmaster.carcheckdate',
			    'Carmaster.scrapflg',
			    'Carmaster.katasiki',
			    'Carmaster.syadaino',
			    'Carmaster.loadingaccount',
			    'Departmentmaster.departmentname',
				'Trailermaster.trailerkind',
			],
			'conditions' => [
			    'AND' => $conditions,
			    [
			        'Carmaster.delflg' => 0,
	                'Carmaster.kaisyas_id'=>$this->kaisyas_id
			    ]
			],
			'limit' => 30,
			'contain' => [
				'Departmentmaster',
				'Trailermaster',
				'Truckkind' => [
					'fields' => ['id','carmasters_id'],
					'conditions' => ['delflg' => 0]
				]
			],
			'order' => [
				'Carmaster.displayno' => 'asc'
			],
			'recursive' => 1,
		];
		$this->set('carmasters', $this->paginate('Carmaster'));

		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		// 車両候補も一覧で選択した現役/廃車区分に合わせる。
		$carlistScrapflg = ($scrapflg === null) ? 0 : $scrapflg;
		if (!$bid && !$did) {
			$carlist = $this->Carmaster->CarList($carlistScrapflg);
		} elseif ($bid && $did) {
			$carlist = $this->Carmaster->CarListW($bid, $did, $carlistScrapflg);
		} elseif ($bid) {
			$carlist = $this->Carmaster->CarListBranch($bid, $carlistScrapflg);
		} elseif ($did) {
			$carlist = $this->Carmaster->CarListDepartment($did, $carlistScrapflg);
		}
		$this->set(compact('branchmasters','departmentmasters','carlist'));
	}

	public function add() {
	    $this->edit();
	    $this->render('edit');
/*
		if ($this->request->is('post')) {
			$this->Carmaster->create();
			if ($this->Carmaster->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {

			}
		}
		$trailermasters = $this->Trailermaster->TrailerList();
		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('trailermasters','branchmasters','departmentmasters'));
*/
	}

	public function edit($id = null) {
		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);

		if ($this->request->is(array('post', 'put'))) {
			$this->Carmaster->id = $id;
			$this->request->data['Carmaster']['kaisyas_id'] = $this->kaisyas_id;
			$this->removeUnsetDateFields($this->request->data['Carmaster']);
			if ($this->Carmaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index'));
			}
			//$this->Flash->error('失敗しました。');
		}else{
		    if($id){
		        $this->request->data = $this->Carmaster->findById($id);
		        $this->blankZeroDateFields($this->request->data['Carmaster']);
		    }else{
		        // 新規登録時
		    }
		}

		$trailermasters = $this->Trailermaster->TrailerList();
		$containertypes = $this->Containertype->ContainertypeList();
		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('trailermasters','branchmasters','departmentmasters','containertypes'));
	}

	private function removeUnsetDateFields(&$data) {
		foreach (['entrydate', 'carcheckdate', 'notrundatestart', 'notrundateend'] as $field) {
			if (isset($data[$field]) && in_array($data[$field], ['', '0000-00-00', '0000-00-00 00:00:00'], true)) {
				unset($data[$field]);
			}
		}
	}

	private function blankZeroDateFields(&$data) {
		foreach (['entrydate', 'carcheckdate', 'notrundatestart', 'notrundateend'] as $field) {
			if (isset($data[$field]) && in_array($data[$field], ['0000-00-00', '0000-00-00 00:00:00'], true)) {
				$data[$field] = '';
			}
		}
	}

	public function delete($id = null) {
		$this->Carmaster->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Carmaster']['delflg'] = 1;
			$this->Carmaster->id = $id;
			if ($this->Carmaster->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(array('action' => 'index'));
	}


}
