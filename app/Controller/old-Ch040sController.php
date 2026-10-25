<?php
App::uses('AppController', 'Controller');
/**
 * Ch040s Controller
 * @copy Host010
 */
class Ch040sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form','Session','Js' => ['Jquery']);
	public $uses = array('User','Kaisya','Branchmaster','Departmentmaster','Kintaitran','Flgmaster');
	public $components = array('Search.Prg','RequestHandler');
	public $presetVars = array();

    public function beforeFilter(){
    	parent::beforeFilter();
        $this->set('auth',$this->Auth);

        // 検索対象のフィールド設定代入
        $this->presetVars = $this->User->presetVars;
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
		$flgmasters = $this->Flgmaster->find('list',[
			'fields' => ['id','flgname']
		]);
		$this->set(compact('flgmasters'));

		if ($this->request->is('post')) {
			$tyy1 = $this->data['selectym1']['year'];
			$tmm1 = $this->data['selectym1']['month'];
			$tdd1 = $this->data['selectym1']['day'];
			$branchmasters_id = $this->data['branchmasters_id'];
			$departmentmasters_id = $this->data['departmentmasters_id'];
		}else{
			$tyy1 = date('Y');
			$tmm1 = date('m');
			$tdd1 = date('d');
			$branchmasters_id = null;
			$departmentmasters_id = null;
		}

		$date = new DateTime($tyy1.'-'.$tmm1.'-'.$tdd1);
		$paramsDate = $date->modify('0 day')->format('Y-m-d');
		$paramsDate2 = $date->modify('+6 day')->format('Y-m-d');

		$week = ['日','月','火','水','木','金','土'];
		//$firstday = new DateTime('first day of '.$tyy1.'-'.$tmm1);
		//$lastday = new DateTime('last day of '.$tyy1.'-'.$tmm1);

		if ($branchmasters_id == null && $departmentmasters_id == null) {
			$this->set('users', $this->User->find('all', [
				'order' => [
					'User.id' => 'asc',
				],
				'fields' => [
					'User.id',
					'User.usernameK',
					'User.branchmasters_id',
					'User.departmentmasters_id',
				],
				'contain' => [
					'Kintaitran' => [
						'conditions' => [
							'AND' => [
								['Kintaitran.kintaidate >= ' => $paramsDate],
								['Kintaitran.kintaidate <= ' => $paramsDate2],
							]
						],
						'fields' => [
							'Kintaitran.id',
							'Kintaitran.kintaidate',
							'Kintaitran.flgmasters_id',
						],
					]
				],
				//'limit' => 10,
				//'recursive' => 0
			]));
		} elseif ($branchmasters_id == null) {
			$this->set('users', $this->User->SelectUserDepartment($paramsDate,$paramsDate2,$departmentmasters_id));
		} elseif ($departmentmasters_id == null) {
			$this->set('users', $this->User->SelectUserBranch($paramsDate,$paramsDate2,$branchmasters_id));
		} else {
			$this->set('users', $this->User->SelectUserAll($paramsDate,$paramsDate2,$branchmasters_id,$departmentmasters_id));
		}

		$branchmasters = $this->Branchmaster->find('list',[
			'fields' => ['id','branchname'],
			'conditions' => ['Branchmaster.kaisyas_id'=>$this->Auth->user('kaisyas_id')]
		]);
		$departmentmasters = $this->Departmentmaster->find('list',[
			'fields' => ['id','departmentname'],
			'conditions' => ['Departmentmaster.kaisyas_id'=>$this->Auth->user('kaisyas_id')]
		]);
		$this->set(compact('branchmasters','departmentmasters','paramsDate','week'));

	}

	public function pdf() {
		/*
		$this->set('users', $this->User->find('all', [
			'order' => ['User.id' => 'asc']
		]));
		*/
		$flgmasters = $this->Flgmaster->find('list',[
			'fields' => ['id','flgname']
		]);
		$firstday = new DateTime('first day of '.$this->params['named']['kyy'].'-'.$this->params['named']['kmm']);
		$lastday = new DateTime('last day of '.$this->params['named']['kyy'].'-'.$this->params['named']['kmm']);
		$this->set(compact('flgmasters','firstday','lastday'));

		$this->set('users', $this->User->find('all', [
			'order' => [
				'User.id' => 'asc',
			],
			'fields' => [
				'User.id',
				'User.usernameK',
			],
			'contain' => [
				'Kintaitran' => [
					'conditions' => [
						'AND' => [
							['Kintaitran.kintaidate >= ' => $firstday->format('Y-m-d')],
							['Kintaitran.kintaidate <= ' => $lastday->format('Y-m-d')],
						]
					],
					/*
					'fields' => [
						'Kintaitran.id',
						'Kintaitran.kintaidate',
						'Kintaitran.flgmasters_id',
					],
					*/
				]
			],
				//'recursive' => 0
		]));

		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';
	}

}
