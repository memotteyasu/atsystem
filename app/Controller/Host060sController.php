<?php
App::uses('AppController', 'Controller');
/**
 * Host060s Controller
 *
 * @property Kintaitran $Kintaitran
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class Host060sController extends AppController {
    public $layout = 'layout_host';
    public $helpers = array('Html','Form','Session','Js' => ['Jquery'],'Format');
    public $uses = array('User','Kaisya','Branchmaster','Departmentmaster','Kintaitran','Flgmaster','Holiday','Calendarmaster','Calendarusermaster');
    public $components = ['Search.Prg','RequestHandler','Timediff','SelectData'];

    public function beforeFilter(){
        parent::beforeFilter();
        $this->page = 'Host060s';
    }

    public function isAuthorized($user){
        if (isset($user['rolemasters_id'])) {
            switch($user['rolemasters_id']){
                case 10: //管理者
                case 11: //管理者
                case 12: //管理者
                case 20: //事務職（石油）
                case 21: //事務職（化学品）
                case 22: //事務職（ガス）
                case 23: //事務職（コンテナ）
                case 24: //事務職（全て）
				case 25: //事務職（化学品コンテナ）
                case 28: //事務職兼運転手
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
    public function index($did = null) {
	    $flglist = [
	        '1' => '当日合算　',
	    ];
	    $page = $this->page;
	    $branchmasters_id = $this->branchmasters_id;
	    $branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
	    $departmentmasters_id = $this->Auth->user('departmentmasters_id');

	    $date = new DateTime();

	    if ($this->Session->check('BackData2')) {
	        if ($this->Session->read('BackData2.Page') == $page) {
	            if ($this->Session->check('BackData2.date')) {
	                $date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
	            }
	            if ($this->Session->check('BackData2.bid')) {
	                $branchmasters_id = $this->Session->read('BackData2.bid');
	            }
	            if ($this->Session->check('BackData2.did')) {
	                if ($this->Session->read('BackData2.did') == 0) {
	                    $departmentmasters_id = null;
	                } else {
	                    $departmentmasters_id = $this->Session->read('BackData2.did');
	                }
	            }
	        }
	        $this->Session->delete('BackData2');
	    } elseif($this->Session->check('BackData1')){
	        if ($this->Session->read('BackData1.Page') == $page) {
	            if ($this->Session->check('BackData1.date')) {
	                $date = $this->SelectData->date_select($this->Session->read('BackData1.date'));
	            }
	            if ($this->Session->check('BackData1.bid')) {
	                $branchmasters_id = $this->Session->read('BackData1.bid');
	            }
	            if ($this->Session->check('BackData1.did')) {
	                if ($this->Session->read('BackData1.did') == 0) {
	                    $departmentmasters_id = null;
	                } else {
	                    $departmentmasters_id = $this->Session->read('BackData1.did');
	                }
	            }
	        }
	    }

	    $flgmasters = $this->Flgmaster->flgchk(1);
	    $this->set('flgmasters',$flgmasters);
	    if ($this->branchmasters_id == 1) {
	        // 本社所属の場合、部署の全てを表示
	        $branchmasters = $this->Branchmaster->BranchList($this->kaisyas_id);
	    } else {
	        // 本社以外の場合、自身の所属部署のみを表示
	        $branchmasters = $this->Branchmaster->find('list', [
	            'fields' => ['id','branchname'],
	            'conditions' => [
	                'kaisyas_id' => $this->kaisyas_id,
	                'id' => $this->branchmasters_id
	            ],
	            'order' => ['id' => 'asc']
	        ]);
	    }

	    $departmentmasters = $this->Departmentmaster->SelectDepartmentList($this->kaisyas_id);
	    $this->set(compact('branchmasters','departmentmasters'));

	    if ($this->request->is('post')) {
	        $date = new DateTime($this->request->data('selectym1'));
	        $branchmasters_id = $this->request->data('branchmasters_id');
	        $departmentmasters_id = $this->request->data('departmentmasters_id');

	        if (isset($this->request->data['lastweek'])) {
	            $date->modify('-1 weeks');
	        }
	        if (isset($this->request->data['nextweek'])) {
	            $date->modify('+1 weeks');
	        }
	        $backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')],'bid' => $branchmasters_id, 'did' => $departmentmasters_id, 'Page' => $page];
	        $this->Session->write('BackData1', $backdatas);
	        return $this->redirect(['action' => 'index']);
	    }
	    $paramsDate = $date->format('Y-m-d');
	    $paramsDate2 = $date->modify('+6 day')->format('Y-m-d');
	    $week = ['日','月','火','水','木','金','土'];
	    $this->set(compact('paramsDate','week','departmentmasters_id','branchmasters_id'));

	    $users = $this->SelectData->data_select_conditionsHost060s($paramsDate,$paramsDate2,$branchmasters_id,$departmentmasters_id,$this->kaisyas_id);
	    $this->set('users', $users);
	    $this->set('did',$did);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */



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

		// $this->Kintaitran->Behaviors->load('EagerLoader.EagerLoader');

		$Kintaitrans = $this->Kintaitran->find('all', [
			'fields' => [
				'Kintaitran.id',
				'Kintaitran.kintaidate',
				'User.id',
				'User.syain_cd',
				'User.usernameK'
			],
			'conditions' => [
				'Kintaitran.kintaidate >=' => $firstday->format('Y-m-d'),
				'Kintaitran.kintaidate <=' => $lastday->format('Y-m-d'),
				$bconditions,
				'Kintaitran.kaisyas_id'=>$this->kaisyas_id,
			],
		    'order'=>[
		        'Kintaitran.users_id'=>'asc',
		        'Kintaitran.kintaidate'
		    ],
			'recursive' => 0,
		]);

		$flgmasters = $this->Flgmaster->flgchk(3);

		$this->set(compact('Kintaitrans','flgmasters'));

	}

	public function pdfHost060_5($date,$did,$bid) {
	    // Content-Type
	    $this->RequestHandler->respondAs('application/pdf');

	    // レイアウトを使用しない
	    $this->layout = '';

	    if ($did == 0) {
	        $dconditions = [];
	    } else {
	        $dconditions = ['User.departmentmasters_id' => $did];
	    }

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

	    $this->User->Behaviors->load('EagerLoader.EagerLoader');

	    $users = $this->User->find('all', [
	        'order' => [
	            'User.id' => 'asc',
	        ],
	        'fields' => [
	            'User.id',
	            'User.usernameK',
	            'User.syain_cd'
	        ],
	        'conditions' => [
	            'User.zaisekiflg' => 0,
	            $dconditions,
	            $bconditions,
	        ],
	        'contain' => [
	            'Kintaitran' => [
	                'conditions' => [
	                    'Kintaitran.kintaidate >=' => $firstday->format('Y-m-d'),
	                    'Kintaitran.kintaidate <=' => $lastday->format('Y-m-d'),
	                ],
	                'Flgmaster' => [
	                    'fields' => [
	                        'id',
	                        'flgname'
	                    ]
	                ]
	            ],
	            'Kaisya' => [
	                'fields' => [
	                    'Kaisya.companyname'
	                ]
	            ],
	            'Departmentmaster' => [
	                'fields' => [
	                    'Departmentmaster.departmentname'
	                ],
	                'conditions' => [
	                    'Departmentmaster.delflg' => 0
	                ]
	            ],
	            'Branchmaster' => [
	                'fields' => [
	                    'Branchmaster.branchname'
	                ],
	            ],
	        ],
	    ]);

	    $this->set(compact('users'));
	    $branchmasters = $this->Branchmaster->findById($bid);
	    $departmentmasters = $this->Departmentmaster->findById($did);
	    $this->set(compact('users','branchmasters','departmentmasters'));
	}
}
