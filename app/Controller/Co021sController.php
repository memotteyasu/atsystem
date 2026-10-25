<?php
App::uses('AppController', 'Controller');
/**
 * Co020 Controller
 *
 * @copy Oi001s
 */
class Co021sController extends AppController {
	public $layout = 'layout_host';
	public $uses = array('User','Kaisya','Ordertran','Carmaster','Shippermaster','Truckkind','Productdetailmaster','Deliverymaster','Kintaitran','Branchmaster','Processtran');
    public $helpers = array('Html','Form');
/**
 * Components
 *
 * @var array
 */
    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);
    }

    public function isAuthorized($user){
        // 管理者(10-)事務職(20-)
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
		$did = 4;
		$deliverymasters = $this->Deliverymaster->find('list',[
			'fields' => ['id','deliveryname'],
			'conditions' => ['Deliverymaster.departmentmasters_id' => $did]
		]);
		$branchmasters = $this->Branchmaster->find('list',[
			'fields' => ['id','branchname'],
			'conditions' => ['Branchmaster.kaisyas_id' => $this->Auth->user('kaisyas_id')]
		]);
		$shippermasters = $this->Shippermaster->find('list', [
			'fields' => ['id','shippername'],
			'conditions' => ['Shippermaster.departmentmasters_id' => $did]
		]);
		$userlist = $this->User->find('list', [
			'fields' => ['id','usernameK'],
			'conditions' => [
			    'departmentmasters_id' => $did,
			    'zaisekiflg' => 0
			],
		]);
		$this->set(compact('branchmasters','deliverymasters','shippermasters', 'userlist'));

		if ($this->request->is('post')) {
			if (isset($this->request->data['selectym1'])) {
				$tyy1 = $this->data['selectym1']['year'];
				$tmm1 = $this->data['selectym1']['month'];
				$tdd1 = $this->data['selectym1']['day'];
				$branchmasters_id = $this->data['branchmasters_id'];
				return $this->redirect(['action' => 'index', $tyy1, $tmm1, $tdd1, $branchmasters_id]);
			}
			if (isset($this->request->data['Driver1'])) {
				$i = 0;
				$j = 0;
				foreach ($this->request->data['Driver'] as $ordertran) {
					$this->request->data['Ordertran'][$i]['id'] = $ordertran['id'];
					$this->request->data['Ordertran'][$i]['driver1id'] = $this->request->data['Driver1']['driver1id'];
					$processtrans = $this->Processtran->find('all', [
						'fields' => ['Processtran.id', 'Processtran.ordertrans_id', 'Processtran.users_id', 'Processtran.workdetailmasters_id'],
						'conditions' => [
							'Processtran.ordertrans_id' => $ordertran['id']
						],
						'recursive' => -1
					]);
					if ($processtrans) {
						foreach ($processtrans as $processtran) {
							$this->request->data['Processtran'][$j]['id'] = $processtran['Processtran']['id'];
							$this->request->data['Processtran'][$j]['users_id'] = $this->request->data['Driver1']['driver1id'];
							$j = $j + 1;
						}
					}
					$i = $i + 1;
				}
				if ($this->Ordertran->saveAll($this->request->data['Ordertran'])) {
					if (isset($this->request->data['Processtran'])) {
						$this->Processtran->saveAll($this->request->data['Processtran']);
						$this->Flash->success(('運転手及び工程作業者を変更しました'));
					} else {
						$this->Flash->success(('運転手を変更しました'));
					}
				}else{
					$this->Flash->error(('失敗しました'));
				}
				//debug($this->request->data);
				if (!$this->params['pass']) {
					$tyy1 = date('Y');
					$tmm1 = date('m');
					$tdd1 = date('d');
					$branchmasters_id = null;
				} else {
					$tyy1 = $this->params['pass'][0];
					$tmm1 = $this->params['pass'][1];
					$tdd1 = $this->params['pass'][2];
					if (isset($this->request->params['pass'][3])) {
						$branchmasters_id = $this->params['pass'][3];
					} else {
						$branchmasters_id = null;
					}
					return $this->redirect(['action' => 'index', $tyy1, $tmm1, $tdd1, $branchmasters_id]);
				}
			}
			if (isset($this->request->data['Driver2'])) {
				$i = 0;
				foreach ($this->request->data['Driver'] as $ordertran) {
					$this->request->data['Ordertran'][$i]['id'] = $ordertran['id'];
					$this->request->data['Ordertran'][$i]['driver2id'] = $this->request->data['Driver2']['driver2id'];
					$i = $i + 1;
				}
				if ($this->Ordertran->saveAll($this->request->data['Ordertran'])) {
					$this->Flash->success(('運転手を変更しました'));
				}else{
					$this->Flash->error(('失敗しました'));
				}
				if (!$this->params['pass']) {
					$tyy1 = date('Y');
					$tmm1 = date('m');
					$tdd1 = date('d');
					$branchmasters_id = null;
				} else {
					$tyy1 = $this->params['pass'][0];
					$tmm1 = $this->params['pass'][1];
					$tdd1 = $this->params['pass'][2];
					if (isset($this->request->params['pass'][3])) {
						$branchmasters_id = $this->params['pass'][3];
					} else {
						$branchmasters_id = null;
					}
					return $this->redirect(['action' => 'index', $tyy1, $tmm1, $tdd1, $branchmasters_id]);
				}
			}
		} else {
			if (!$this->params['pass']) {
				$tyy1 = date('Y');
				$tmm1 = date('m');
				$tdd1 = date('d');
				$branchmasters_id = null;
			} else {
				$tyy1 = $this->params['pass'][0];
				$tmm1 = $this->params['pass'][1];
				$tdd1 = $this->params['pass'][2];
				if (isset($this->request->params['pass'][3])) {
					$branchmasters_id = $this->params['pass'][3];
				} else {
					$branchmasters_id = null;
				}
			}
		}

		$firstday = new DateTime('first day of '.$tyy1.'-'.$tmm1);
		$lastday = new DateTime('last day of '.$tyy1.'-'.$tmm1);
		$paramsDate = $tyy1.'-'.$tmm1.'-'.$tdd1;
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','week'));

		if (!$branchmasters_id) {
			//$branchconditions = ['Ordertran.branchmasters_id' => null];
			$this->set('truckkinds', []);
		} else {
			//$branchconditions = ['Ordertran.branchmasters_id' => $branchmasters_id];
			$this->set('truckkinds', $this->Truckkind->find('all', [
				'order' => [
					'Truckkind.carmasters_id' => 'asc',
				],
				'fields' => [
					'Truckkind.id',
					'Truckkind.carmasters_id',
					'Truckkind.shippermasters_id',
					'Truckkind.productdetailmasters_id',
					'Carmaster.plateno',
					'Productdetailmaster.pdname',
				],
				'contain' => [
					'Carmaster',
					'Shippermaster',
					'Productdetailmaster',
					'Ordertran' => [
						'order' => [
							'Ordertran.deliverymasters_id' => 'asc'
						],
						'conditions' => [
							'AND' => [
								'Ordertran.deliverydate' => $paramsDate,
								'Ordertran.branchmasters_id' => $branchmasters_id,
								'Ordertran.departmentmasters_id' => 4,
								'Ordertran.delflg !=' => '1',
							]
						]
					]
				]
			]));
		}

		$this->set(compact('paramsDate','week'));
		$this->set('carmasters', $this->Carmaster->find('all', [
		    'conditions' => [
		        'AND' => [
		            ['Carmaster.notrundateend >=' => $paramsDate],
		            ['Carmaster.notrundateend <=' => $paramsDate],
		        ]
		    ],
		    'contain' => [
		        //'Trailermaster',
		        'Truckkind' => [
		            'conditions' => [
		                'OR' => [
		                    ['Truckkind.delflg' => ''],
		                    ['Truckkind.delflg' => 0]
		                ]
		            ]
		        ]
		    ],
		    'recursive' => 2
		]));

		$this->set('drivers', $this->Kintaitran->find('all', [
		    'conditions' => [
		        'AND' => [
		            ['Kintaitran.kintaidate >=' => $firstday->format('Y-m-d')],
		            ['Kintaitran.kintaidate <=' => $lastday->format('Y-m-d')],
		            ['Kintaitran.flgmasters_id' => 13],
		            ['User.rolemasters_id >=' => 30],
		            ['User.rolemasters_id <=' => 39],
		            ['User.zaisekiflg' => 0]
		        ]
		    ],
		    'fields' => [
		        'Kintaitran.kintaidate',
		        'Kintaitran.users_id',
		        'Kintaitran.flgmasters_id'
		    ],
		    'contain' => [
		        'User' => [
		            'fields' => 'usernameK',
		        ],
		        'Flgmaster' => [
		            'fields' => 'flgname'
		        ]
		    ]
		]));

	}

}
