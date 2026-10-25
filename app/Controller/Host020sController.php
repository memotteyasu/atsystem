<?php
App::uses('AppController', 'Controller');
/**
 * Host020s Controller
 *
 * @property Gasusetran $Gasusetran
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class Host020sController extends AppController {

/**
 * Components
 *
 * @var array
 */
    public $uses = ['Gasusetran','Departmentmaster','Branchmaster','Flgmaster','User','Kaisya'];
    public $layout = 'layout_host';
    public $helpers = array('Html','Form','Session','Js' => ['Jquery']);
    public $components = array('Search.Prg','RequestHandler');
    public $presetVars = array();

    public function beforeFilter(){
        parent::beforeFilter();

		// 検索対象のフィールド設定代入
		$this->presetVars = $this->Gasusetran->presetVars;
    }

    public function isAuthorized($user){
        if (isset($user['rolemasters_id'])) {
            switch($user['rolemasters_id']){
                case 10: //管理者
                case 11: //管理者
                case 12: //管理者
                case 20; //事務職（石油）
                case 21; //事務職（化学品）
                case 22; //事務職（ガス）
                case 23; //事務職（コンテナ）
                case 24; //事務職（全て）
                case 25; //事務職（化学品コンテナ）
                $this->set('user', $user);
                return true;
                break;
            }
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
		// debug($this->passedArgs);

		$date = new DateTime();
//		$departmentmasters_id = $this->Auth->user('departmentmasters_id');
		$branchmasters_id = $this->branchmasters_id;

		if (isset($this->passedArgs['chargedate'])) {
			$date = new DateTime($this->passedArgs['chargedate']['year'].'-'.$this->passedArgs['chargedate']['month']);
//			$departmentmasters_id = $this->passedArgs['departmentmasters_id'];
			$branchmasters_id = $this->passedArgs['branchmasters_id'];
			$this->passedArgs['chargedate'] = $date->format('Y-m-d');
			// 検索条件取得
			$conditions = $this->Gasusetran->parseCriteria($this->passedArgs);
		} else {
			//月初め
			$firstdate = new DateTime('first day of this month');
			//月終わり
			$lastdate  = new DateTime('last day of this month');
			$conditions = [
				['chargedate >=' => $firstdate->format('Y-m-d')],
				['chargedate <=' => $lastdate->format('Y-m-d')],
//				['departmentmasters_id' => $departmentmasters_id],
				['branchmasters_id' => $branchmasters_id]
			];
		}

		$paramsDate = $date->format('Y-m-d');
		$this->set(compact('paramsDate','departmentmasters_id','branchmasters_id'));

		//debug($this->passedArgs);
		//debug($conditions);

		$this->paginate = [
			'fields' => [
				'Gasusetran.id',
				'Gasusetran.chargedate',
				'User.usernameK',
				'Flgmaster.flgname',
				'Gasusetran.amount'
			],
			'conditions' => [
			    $conditions,
			    'Gasusetran.kaisyas_id'=>$this->kaisyas_id,
			],
			'limit' => 100,
			'order' => [
				'Gasusetran.users_id' => 'asc',
			    'Gasusetran.chargedate'
			]
		];
		$this->set('gasusetrans', $this->paginate('Gasusetran'));

		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));

		//$paramsDate = $date->format('Y-m-d');
		$this->set(compact('branchmasters','departmentmasters'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Gasusetran->exists($id)) {
			throw new NotFoundException(__('Invalid gasusetran'));
		}
		$options = array('conditions' => array('Gasusetran.' . $this->Gasusetran->primaryKey => $id));
		$this->set('gasusetran', $this->Gasusetran->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Gasusetran->create();
			$this->request->data['Gasusetran']['kaisyas_id'] = $this->kaisyas_id;
			if ($this->Gasusetran->save($this->request->data)) {
				$this->Flash->success(__('保存しました'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('保存できません'));
			}
		}
		$flgmasters = $this->Flgmaster->flgchk(3);
		$drivers = $this->User->find('list',[
		    'fields' => ['id','usernameK'],
		    'conditions'=>[
		        'User.kaisyas_id'=> $this->Auth->user('Kaisya.id'),
				'User.zaisekiflg' => 0
		    ],
		    'order'=>[
		        'User.departmentmasters_id'=>'asc',
		        'User.usernameKK'
		    ]
		]);

		$this->set(compact('flgmasters','drivers'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Gasusetran->exists($id)) {
			throw new NotFoundException(__('Invalid gasusetran'));
		}
		if ($this->request->is(array('post', 'put'))) {
		    $this->request->data['Gasusetran']['id'] = $id;
			if ($this->Gasusetran->save($this->request->data)) {
				$this->Flash->success(__('保存しました'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('保存できません'));
			}
		} else {
			$options = array('conditions' => array('Gasusetran.' . $this->Gasusetran->primaryKey => $id));
			$this->request->data = $this->Gasusetran->find('first', $options);
		}
		$flgmasters = $this->Flgmaster->flgchk(3);
		$drivers = $this->User->find('list',[
		    'fields' => ['id','usernameK'],
		    'conditions'=>[
		        'User.kaisyas_id'=> $this->Auth->user('Kaisya.id'),
		    ]
		]);

		$this->set(compact('flgmasters','drivers'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Gasusetran->id = $id;
		if (!$this->Gasusetran->exists()) {
			throw new NotFoundException(__('Invalid gasusetran'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Gasusetran->delete()) {
			$this->Flash->success(__('削除しました。'));
		} else {
			$this->Flash->error(__('削除できません'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function pdf($date,$bid) {
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';
/*
		if ($did == 0) {
			$dconditions = [];
		} else {
			$dconditions = ['User.departmentmasters_id' => $did];
		}
*/
		if ($bid == 0) {
			$bconditions = [];
		} else {
			$bconditions = ['User.branchmasters_id' => $bid];
		}

		$week = ['日','月','火','水','木','金','土'];
		$paramsDate = new DateTime($date);
		$paramsDate2 = new DateTime($date);
		$paramsDate3 = new DateTime($date);

		$firstday = $paramsDate2->modify('first day of this months');
		$lastday = $paramsDate3->modify('last day of this months');
		$this->set(compact('paramsDate','firstday','lastday','week'));

		// $this->Gasusetran->Behaviors->load('EagerLoader.EagerLoader');

		$gasusetrans = $this->Gasusetran->find('all', [
			'fields' => [
				'Gasusetran.id',
				'Gasusetran.chargedate',
				'Gasusetran.gaskind',
				'Gasusetran.amount',
				'User.id',
				'User.syain_cd',
				'User.usernameK'
			],
			'conditions' => [
				'Gasusetran.chargedate >=' => $firstday->format('Y-m-d'),
				'Gasusetran.chargedate <=' => $lastday->format('Y-m-d'),
//				$dconditions,
				$bconditions,
				'Gasusetran.kaisyas_id'=>$this->kaisyas_id,
			],
		    'order'=>[
		        'Gasusetran.users_id'=>'asc',
		        'Gasusetran.chargedate'
		    ],
			'recursive' => 0,
		]);

		$flgmasters = $this->Flgmaster->flgchk(3);
		$kaisya = $this->Kaisya->find('first', [
			'fields' => [
				'Kaisya.id',
				'Kaisya.companyname',
				'Kaisya.gasprice',
				'Kaisya.dieselprice',
				'Kaisya.oilprice',
			],
			'conditions' => [
				'Kaisya.id' => $this->kaisyas_id,
				'Kaisya.delflg' => 0,
			],
			'recursive' => -1,
		]);
		// debug($kaisya);

		$this->set(compact('gasusetrans','flgmasters','kaisya'));

		// $this->set(compact('users'));
		// $branchmasters = $this->Branchmaster->findById($bid);
		// $departmentmasters = $this->Departmentmaster->findById($did);
		// $this->set(compact('users','branchmasters','departmentmasters'));
	}
}
