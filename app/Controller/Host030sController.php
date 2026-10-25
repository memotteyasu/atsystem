<?php
App::uses('AppController', 'Controller');
/**
 * Host030s Controller
 *
 */
class Host030sController extends AppController {

/**
 * Components
 *
 * @var array
 */
    public $uses = ['Accidenttran','Departmentmaster','Branchmaster','Flgmaster','User','Kaisya'];
    public $layout = 'layout_host';
    public $helpers = array('Html','Form','Session','Js' => ['Jquery']);
    public $components = array('Search.Prg','RequestHandler');
    public $presetVars = array();

    public function beforeFilter(){
        parent::beforeFilter();

		// 検索対象のフィールド設定代入
        $this->presetVars = $this->Accidenttran->presetVars;
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
    }


/**
 * index method
 *
 * @return void
 */
	public function index() {
	    $date = new DateTime();

	    // 検索条件設定
		$this->Prg->commonProcess();

		$treatmentflgs = [1=>'事故',2=>'非事故'];
        $this->set('treatmentflgs',$treatmentflgs);

        if (isset($this->passedArgs['selectym1'])) {
            $date = new DateTime($this->passedArgs['selectym1']['year'].'-'.$this->passedArgs['selectym1']['month']);
            $this->passedArgs['selectym1'] = $date->format('Y-m-d');
            // 検索条件取得
            $conditions = $this->Accidenttran->parseCriteria($this->passedArgs);
        } else {
            //月初め
            $firstdate = new DateTime('first day of this month');
            //月終わり
            $lastdate  = new DateTime('last day of this month');
            $conditions = [
                ['accidentdate >=' => $firstdate->format('Y-m-d')],
                ['accidentdate <=' => $lastdate->format('Y-m-d')],
            ];
        }

        $paramsDate = $date->format('Y-m-d');
        $this->set(compact('paramsDate'));

		$this->paginate = [
			'fields' => [
				'Accidenttran.id',
				'Accidenttran.accidentdate',
				'User.usernameK',
			    'Accidenttran.carno',
			    'Accidenttran.treatmentflg',
			    'Accidenttran.accidentplace',
			    'Accidenttran.treatmentdate',
			    'Accidenttran.salarynote'
			],
			'conditions' => [
			    $conditions,
			    'Accidenttran.kaisyas_id'=>$this->kaisyas_id,
			    'Accidenttran.delflg'=>0
			],
			'limit' => 100,
			'order' => [
				'Accidenttran.accidentdate' => 'asc'
			]
		];
		$this->set('accidenttrans', $this->paginate('Accidenttran'));

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
	    $date = new DateTime();
	    $paramsDate = $date->format('Y-m-d');

	    $treatmentflgs = [1=>'事故',2=>'非事故'];
	    $this->set('treatmentflgs',$treatmentflgs);

	    $reportflgs = [1=>'TEL',2=>'帰社後'];
	    $this->set('reportflgs',$reportflgs);


		if ($this->request->is('post')) {
		    $this->Accidenttran->create();
			$this->request->data['Accidenttran']['kaisyas_id'] = $this->kaisyas_id;
			if ($this->Accidenttran->save($this->request->data)) {
				$this->Flash->success(__('保存しました'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('保存できません'));
			}
		}
		$drivers = $this->User->find('list',[
		    'fields' => ['id','usernameK'],
		    'conditions'=>[
		        'User.kaisyas_id'=> $this->Auth->user('Kaisya.id'),
		        'User.zaisekiflg'=>0,
		        'User.rolemasters_id >='=>30,
		        'User.rolemasters_id <='=>39
		    ],
		    'order'=>'usernameKK'
		]);
		$this->set('drivers',$drivers);
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
	    $date = new DateTime();
	    $paramsDate = $date->format('Y-m-d');

	    $treatmentflgs = [1=>'事故',2=>'非事故'];
	    $this->set('treatmentflgs',$treatmentflgs);

	    $reportflgs = [1=>'TEL',2=>'帰社後'];
	    $this->set('reportflgs',$reportflgs);

	    if (!$this->Accidenttran->exists($id)) {
			throw new NotFoundException(__('Invalid gasusetran'));
		}
		if ($this->request->is(array('post', 'put'))) {
		    $this->request->data['Accidenttran']['id'] = $id;
		    if ($this->Accidenttran->save($this->request->data)) {
				$this->Flash->success(__('保存しました'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('保存できません'));
			}
		} else {
		    $options = array('conditions' => array('Accidenttran.' . $this->Accidenttran->primaryKey => $id));
		    $this->request->data = $this->Accidenttran->find('first', $options);
		}

		$drivers = $this->User->find('list',[
		    'fields' => ['id','usernameK'],
		    'conditions'=>[
		        'User.kaisyas_id'=> $this->Auth->user('Kaisya.id'),
		        'User.zaisekiflg'=>0,
		        'User.rolemasters_id >='=>30,
		        'User.rolemasters_id <='=>39
		    ],
		    'order'=>'usernameKK'
		]);
		$this->set('drivers',$drivers);
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
	    $this->Accidenttran->id = $id;
	    if (!$this->Accidenttran->exists()) {
			throw new NotFoundException(__('Invalid Accidenttran'));
		}
		$this->request->data['Accidenttran']['id'] = $id;
		$this->request->data['Accidenttran']['delflg']=1;
		if ($this->Accidenttran->save($this->request->data)) {
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

		$accidenttrans = $this->Accidenttran->find('all', [
			'fields' => [
				'Accidenttran.id',
				'Accidenttran.accidentdate',
			    'Accidenttran.carno',
			    'Accidenttran.reportflg',
			    'Accidenttran.accidentplace',
			    'User.usernameK'
			],
			'conditions' => [
				'Accidenttran.accidentdate >=' => $firstday->format('Y-m-d'),
				'Accidenttran.accidentdate <=' => $lastday->format('Y-m-d'),
				$bconditions,
				'Accidenttran.kaisyas_id'=>$this->kaisyas_id,
			],
			'recursive' => 0,
		]);

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

		$this->set(compact('accidenttrans','kaisya'));

	}
}
