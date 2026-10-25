<?php
App::uses('AppController', 'Controller');

class Dr020sController extends AppController {
	public $layout = 'layout_drive';
	public $helpers = array('Html','Form','Format');
	public $uses = ['User', 'Processtran', 'Ordertran', 'Workplace'];

	public function beforeFilter(){
		parent::beforeFilter();
		//$this->set('auth',$this->Auth);
	}

	public function isAuthorized($user){
		if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '30' && $user['rolemasters_id'] <= '39'){
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
	public function back_index($date){
		// $date = '2020-04-13'; // test用
		$week = ['日','月','火','水','木','金','土'];

		$newdate = new DateTime($date);

		$this->set('date',$newdate->format('Y-m-d'));
		$this->set('week',$week);

		// 自分が運転手を検索（確定済みデータ）
		$dr1confirm1 = $this->Processtran->find('all',[
			'fields'=>[
				'Processtran.id',
				'PDriver1.usernameK',
				'PDriver2.usernameK',
				'Processtran.processstartdate',
				'Processtran.processstarttime',
				'Processtran.workddetailcomment',
				'Workdetailmaster.workname',
				'Processtran.container_no',
				'Processtran.ref_no',
				'Processtran.ordertrans_id',
				'Workplace.workplaceshortname',
				'Workplace.googlemapurl',
				'Workplace.mapcomment',
				'Ordertran.carmasters_id',
				'Ordertran.workplaces_id1',
				'Ordertran.workplaces_id2',
				'Ordertran.correctdate',
				'Productcontainer.pdconname',
			],
			'conditions'=>[
				'Processtran.pdriver1id'=>$this->Auth->user('id'),
				'Processtran.processstartdate'=>$newdate->format('Y-m-d'),
				'Ordertran.confirmflg'=>1
			],
			'order'=>[
				'Processtran.id'
			],
			'recursive'=>1
		]);
		// 自分が助手を検索
		$dr2confirm1 = $this->Processtran->find('all',[
			'fields'=>[
				'Processtran.id',
				'PDriver1.usernameK',
				'PDriver2.usernameK',
				'Processtran.processstartdate',
				'Processtran.processstarttime',
				'Processtran.workddetailcomment',
				'Workdetailmaster.workname',
				'Processtran.container_no',
				'Processtran.ref_no',
				'Processtran.ordertrans_id',
				'Workplace.workplaceshortname',
				'Workplace.googlemapurl',
				'Workplace.mapcomment',
				'Ordertran.carmasters_id',
				'Ordertran.workplaces_id1',
				'Ordertran.workplaces_id2',
				'Ordertran.correctdate',
				'Productcontainer.pdconname',
			],
			'conditions'=>[
				'Processtran.pdriver2id'=>$this->Auth->user('id'),
				'Processtran.processstartdate'=>$newdate->format('Y-m-d'),
				'Ordertran.confirmflg'=>1
			],
			'order'=>[
				'Processtran.id'
			],
			'recursive'=>1
		]);
		$this->set(compact('dr1confirm1','dr2confirm1'));
	}
	public function index($date){
	    $week = ['日','月','火','水','木','金','土'];
	    $newdate = new DateTime($date);
	    $this->set('date',$newdate->format('Y-m-d'));
	    $this->set('week',$week);
	    $this->Ordertran->Behaviors->load('EagerLoader.EagerLoader');

	    //processtranから日付・自分が運転手のデータを取得
	    $dr1confirm1 = $this->Processtran->find('all',[
	        'fields'=>[
	            'Processtran.id',
	            'PDriver1.usernameK',
	            'PDriver2.usernameK',
	            'Processtran.processstartdate',
	            'Processtran.processstarttime',
	            'Processtran.workddetailcomment',
	            'Workdetailmaster.workname',
	            'Processtran.container_no',
	            'Processtran.ref_no',
	            'Processtran.ordertrans_id',
	            'Workplace.workplaceshortname',
	            'Workplace.googlemapurl',
	            'Workplace.mapcomment',
	            'Ordertran.carmasters_id',
	            'Ordertran.workplaces_id1',
	            'Ordertran.workplaces_id2',
	            'Ordertran.correctdate',
	            'Productcontainer.pdconname',
	        ],
	        'conditions'=>[
	            'Processtran.pdriver1id'=>$this->Auth->user('id'),
	            'Processtran.processstartdate'=>$newdate->format('Y-m-d'),
	            'Ordertran.confirmflg'=>1
	        ],
	        'order'=>[
	            'Processtran.processstartdate',
	            'Processtran.processstarttime',
	            'Processtran.id'
	        ],
	        'recursive'=>1
	    ]);
	}

	public function x_index($date) {
		$week = ['日','月','火','水','木','金','土'];

		$newdate = new DateTime($date);

		//$date = '2019-11-07';
		$this->set('date',$newdate->format('Y-m-d'));
		$this->set('week',$week);

		//動的にEagerLoaderを利用
		$this->Ordertran->Behaviors->load('EagerLoader.EagerLoader');

		//運転手も助手も条件以外は同じなので、fieldsとcontainを配列で設定
		$orderdfileds = [
			'Ordertran.id',
			'Ordertran.kaisen',
			'Ordertran.loadingnote',
			'Ordertran.deliverynote',
			'Ordertran.productnote',
			'Ordertran.driver1id',
			'Ordertran.driver2id',
			'Ordertran.dr1confirmdate',
			'Ordertran.dr2confirmdate',
		];

		$processfileds = [
			'Processtran.id',
			'Processtran.processstartdate',
			'Processtran.processstarttime',
			'Processtran.workddetailcomment',
			'Processtran.container_no',
			'Processtran.ref_no'
		];

		$ordercontain = [
			'Driver1',
			'Driver2',
			'Shippermaster',
			'Loadingmaster' => [
				'fields' => [
					'loadingname',
					'loadingshortname',
					'googlemapurl'
				],
				'Loadingmasterimage'
			],
			'Deliverymaster' => [
				'fields' => [
					'deliveryname',
					'deliveryshortname',
					'googlemapurl'
				],
				'Deliverymasterimage'
			],
			'Productdetailmaster',
			'Processtran' => [
				'fields' => $processfileds,
				'order' => [
					'processstartdate' => 'asc',
					'processstarttime' => 'asc',
					'id'=>'asc',
				],
//				'User',
				'PDriver1',
				'PDriver2',
				'Workdetailmaster',
				'Workplace',
				'Productcontainer',
			],
			'Workplace1',
			'Workplace2',
		];

		//運転手を抽出
		$ordertrans = $this->Ordertran->find('all', [
			'fields' => $orderdfileds,
			'conditions' => [
				'OR' => [
					'Ordertran.driver1id' => $this->Auth->user('id'),
					'Ordertran.driver2id' => $this->Auth->user('id')
				],
				'Ordertran.loadingdate' => $newdate->format('Y-m-d'),
				'Ordertran.delflg' => 0
			],
			'contain' => $ordercontain,
			'order' => [
				'Ordertran.kaisen' => 'asc',
				'Ordertran.loadingdate' => 'asc',
				'Ordertran.loadingtime' => 'asc'
			],
			'recursive' => 0
		]);

		$dr1condtions = [
			'Ordertran.driver1id' => $this->Auth->user('id'),
			'Ordertran.loadingdate' => $newdate->format('Y-m-d'),
			'Ordertran.delflg' => 0,
		];

		$dr2condtions = [
			'Ordertran.driver2id' => $this->Auth->user('id'),
			'Ordertran.loadingdate' => $newdate->format('Y-m-d'),
			'Ordertran.delflg' => 0,
		];

		$dr1confirm1 = $this->Ordertran->find('count', [
			'conditions' => [
				$dr1condtions,
			],
			'recursive' => -1
		]);

		$dr1confirm2 = $this->Ordertran->find('count', [
			'conditions' => [
				$dr1condtions,
				'Ordertran.dr1confirmflg' => 1,
			],
			'recursive' => -1
		]);

		$dr2confirm1 = $this->Ordertran->find('count', [
			'conditions' => [
				$dr2condtions,
			],
			'recursive' => -1
		]);

		$dr2confirm2 = $this->Ordertran->find('count', [
			'conditions' => [
				$dr2condtions,
				'Ordertran.dr2confirmflg' => 1,
			],
			'recursive' => -1
		]);

		$this->set(compact('ordertrans','dr1confirm1','dr1confirm2','dr2confirm1','dr2confirm2'));
	}


	/*
	 * 運転手が配車を確認した際、ordertranのdr1confirmflgを１にする
	 * 助手の場合は、dr2confirmflgを１にする
	 */
	public function confirm($date,$uid){
		// $date,$uidでordertranをセレクト。
		$ordertrans1 = $this->Ordertran->find('all',[
			'conditions' => [
				'loadingdate' => $date,
				'driver1id' => $uid,
			],
			'order' => [
				'loadingdate' => 'asc',
				'loadingtime' => 'asc'
			],
			'recursive' => -1
		]);
		$ordertrans2 = $this->Ordertran->find('all',[
			'conditions' => [
				'loadingdate'=>$date,
				'driver2id'=>$uid,
			],
			'order'=>[
				'loadingdate'=>'asc',
				'loadingtime'=>'asc'
			],
			'recursive' => -1
		]);
		if($ordertrans1){
			// 自分が運転手の情報を更新
			foreach($ordertrans1 as $ordertran1){
				$this->Ordertran->id = $ordertran1['Ordertran']['id'];
				$this->request->data['Ordertran']['dr1confirmflg'] = 1;
				$this->request->data['Ordertran']['dr1confirmdate'] = date('Y-m-d H:i:s');
				$this->Ordertran->save($this->request->data);
			}
		}
		if($ordertrans2){
			// 自分が助手の情報を更新
			foreach($ordertrans2 as $ordertran2){
				$this->Ordertran->id = $ordertran2['Ordertran']['id'];
				$this->request->data['Ordertran']['dr2confirmflg'] = 1;
				$this->request->data['Ordertran']['dr2confirmdate'] = date('Y-m-d H:i:s');
				$this->Ordertran->save($this->request->data);
			}
		}
		return $this->redirect(['controller' => 'Dr020s', 'action' => 'index',1]);
		//$this->redirect('/DrTops/index');
	}

	public function pdf($date) {
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';

		$week = ['日','月','火','水','木','金','土'];

		$newdate = new DateTime($date);

		$this->set('date',$newdate->format('Y-m-d'));
		$this->set('week',$week);

		// $this->Processtran->Behaviors->load('EagerLoader.EagerLoader');
		$drconfirm = $this->Processtran->find('all',[
			'fields' => [
				'Processtran.id',
				'PDriver1.usernameK',
				'PDriver2.usernameK',
				'Processtran.processstartdate',
				'Processtran.processstarttime',
				'Processtran.workddetailcomment',
				'Workdetailmaster.workname',
				'Processtran.container_no',
				// 'Processtran.ref_no',
				'Processtran.ordertrans_id',
				'Workplace.workplacename',
				'Workplace.workplaceshortname',
				'Workplace.googlemapurl',
				'Workplace.mapcomment',
				'Productcontainer.pdconname',
			],
			'conditions' => [
				'OR' => [
					'Processtran.pdriver1id'=>$this->Auth->user('id'),
					'Processtran.pdriver2id'=>$this->Auth->user('id'),
				],
				'Processtran.processstartdate'=>$newdate->format('Y-m-d'),
				'Ordertran.confirmflg'=>1
			],
			'contain' => [
				'PDriver1',
				'PDriver2',
				'Workdetailmaster',
				'Workplace',
				'Productcontainer',
				'Ordertran' => [
					'fields' => [
						'id',
						'amount',
						'carmasters_id',
						'shippermasters_id',
						'productdetailmasters_id',
					],
					'Carmaster' => [
						'fields' => [
							'id',
							'plateno'
						]
					],
					'Shippermaster' => [
						'fields' => [
							'id',
							'shippername'
						]
					],
					'Productdetailmaster' => [
						'fields' => [
							'id',
							'pdname'
						]
					]
				],
			],
			'order' => [
				'Processtran.id'
			],
			'recursive' => 0
		]);
		$this->set('drconfirm',$drconfirm);

	}

	public function pdf_work($id) {
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';

		$workplaces = $this->Workplace->find('first', [
			'conditions' => [
				'Workplace.id' => $id,
			]
		]);

		$this->set(compact('workplaces'));

		// debug($workplaces);

	}

}
