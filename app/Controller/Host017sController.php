<?php
App::uses('AppController', 'Controller');
/**
 * Host017 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Host017sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form','Csv','Format');
	public $uses = ['User','Kaisya','Departmentmaster','Branchmaster','Kousokutran'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->page = 'Host017s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
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
		$w_y = $date->format('Y');
		for($i=$w_y - 2;$i <= $w_y + 1;$i++){
			$yearlist[$i]=$i;
		}

	    $page = $this->page;
		$branchmasters_id = $this->branchmasters_id;
		$branchmasters = $this->SelectData->branch_select_conditions_kintai($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
		$departmentmasters_id = $this->Auth->user('departmentmasters_id');

		$departmentmasters = $this->Departmentmaster->SelectDepartmentList($this->kaisyas_id);
		$this->set(compact('branchmasters','departmentmasters','yearlist'));

		if ($this->request->is('post')) {
			$w_y = $this->request->data('selectym1');
			$date1 = $this->request->data('selectym1').'-01-01';
			$date2 = $this->request->data('selectym1').'-12-31';
			$branchmasters_id = $this->request->data('branchmasters_id');
			$departmentmasters_id = $this->request->data('departmentmasters_id');

			$backdatas = ['date' => ['year' => $this->request->data('selectym1'), 'month' => '01', 'day' => '01'],'branchmasters_id' => $branchmasters_id, 'departmentmasters_id' => $departmentmasters_id, 'Page' => $page];
			$this->Session->write('BackData1', $backdatas);
		}else{
			$date1 = $date->format('Y-m-d');
			$date2 = $date->format('Y-m-d');
		}
		$paramsDate = $date1;
		$paramsDate2 = $date2;
		$this->set(compact('paramsDate','paramsDate2','departmentmasters_id','branchmasters_id','w_y'));

		$users = $this->SelectData->data_select_conditionsHost017s($paramsDate,$paramsDate2,$branchmasters_id,$departmentmasters_id,$this->kaisyas_id);
		$this->set('users', $users);
		$this->set('selectym1',$w_y);
	}

  public function show($id = null) {
    // 現在の年月を基準に範囲を設定
    $currentYear = date('Y');
    $currentMonth = date('m');
    $minDate = ($currentYear - 2) . '-' . str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
    $maxDate = ($currentYear + 1) . '-' . str_pad($currentMonth, 2, '0', STR_PAD_LEFT);

    // GETパラメータから選択された開始年月と終了年月を取得
    $selectedStart = isset($this->request->query['selectym1'])
        ? $this->request->query['selectym1']
        : date('Y-m');
    $selectedEnd = isset($this->request->query['selectym2'])
        ? $this->request->query['selectym2']
        : (new DateTime($selectedStart . '-01'))->modify('+1 year')->format('Y-m');

    // 開始年月と終了年月の期間を計算
    $startDate = new DateTime($selectedStart . '-01');
    $endDate = new DateTime($selectedEnd . '-01');
    $interval = $startDate->diff($endDate);

    // 1年以上の場合は終了年月を制限
    if ($interval->y > 1 || ($interval->y == 1 && $interval->m > 0)) {
        $endDate = new DateTime($startDate->format('Y-m-d'));
        $endDate->modify('+1 year');
        $selectedEnd = $endDate->format('Y-m');
    }

    // min/max の制御
    $startMax = $endDate->format('Y-m');
    $endMin = $selectedStart ?: $minDate;

    // Viewにデータを渡す
    $this->set(compact('minDate', 'maxDate', 'selectedStart', 'selectedEnd', 'startMax', 'endMin'));

    $data = $this->User->find('first', [
      'conditions' => [
        'User.id' => $id
      ],
      'fields' => [
				'User.id',
				'User.usernameK',
				'User.syain_cd'
			],
      'contain' => [
        'Kousokutran'
      ]
    ]);

    if (!$data) {
      throw new NotFoundException(__('存在しないユーザーです。'));
    }
    // debug($user);
    $this->set(compact('data'));
  }

	public function summary(){
		//レイアウトを使わずに処理してindexへ
		$this->autoRender = false;
		debug($this->request->data);

		//return $this->redirect(['controller'=>'Host017s','action'=>'index']);

	}

	// 拘束時間一覧印刷
	public function pdfHost002_5y($date, $did, $bid) {
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

    $startMonth = new DateTime("$date-01-01");
    $endMonth = new DateTime(("$date-12-31"));
    $this->set(compact('startMonth', "endMonth"));

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
        'Kousokutran'
			],
		]);

		// $this->set(compact('users'));

		//usersの内容で、拘束時間テーブルに一旦保存する。
		// foreach ($users as $user) {
		// 	$roumu_goukei = '00:00';
		// 	$sou_hei_zan = '00:00';
		// 	$sou_holiday = '00:00';
		// 	$w_y = $firstday->format('Y');
		// 	if ($user['Kintaitran']) {
		// 		foreach ($user['Kintaitran'] as $kintaitran) {
		// 			$roumu_goukei = $this->Timediff->addvtime_25($roumu_goukei,$kintaitran['roumu_kousoku']);
		// 			//debug($kintaitran['kintaidate']."::".$roumu_goukei);
		// 			//20241126
		// 			if(date('w',strtotime($kintaitran['kintaidate']))==0){
		// 				//日曜日は残業時間から除く
		// 				$sou_holiday = $this->Timediff->addvtime_25($sou_holiday,$kintaitran['heijitsuzangyo_ori']);
		// 			}else{
		// 				$sou_hei_zan = $this->Timediff->addvtime_25($sou_hei_zan,$kintaitran['heijitsuzangyo_ori']);
		// 			}
		// 			//debug($sou_hei_zan);
		// 			//20240405 $roumu_goukei = $format->Addtime($roumu_goukei,$kintaitran['roumu_kousoku']);
		// 		}
		// 		//まずdelete
		// 		$delete_condition = [
		// 			'users_id' => $user['User']['id'],
		// 			'kousokudate' => $firstday->format('Y-m-d')
		// 		];
		// 		if($this->Kousokutran->deleteAll($delete_condition)){
		// 			//成功時は何もしない
		// 		}else{
		// 			//エラー時、無視する
		// 			//$this->Flash->error('Kousokutranの削除失敗');
		// 		}
		// 		//20231129 roumu_goukeiの値をkousokutransへ保存する。
		// 		$this->Kousokutran->create();
		// 		$this->request->data['Kousokutran']['kousokuyear'] = $w_y;
		// 		$this->request->data['Kousokutran']['kousokudate'] = $firstday->format('Y-m-d');
		// 		$this->request->data['Kousokutran']['kousokutime'] = $roumu_goukei;
		// 		$this->request->data['Kousokutran']['kousokuholiday'] = $sou_holiday;
		// 		$this->request->data['Kousokutran']['kousokuzangyotime'] = $sou_hei_zan;
		// 		$this->request->data['Kousokutran']['users_id'] = $user['User']['id'];
		// 		$this->request->data['Kousokutran']['kaisyas_id'] = $user['User']['kaisyas_id'];
		// 		$this->request->data['Kousokutran']['usernameK'] = $user['User']['usernameK'];
		// 		$this->Kousokutran->save($this->request->data);

		// 	}
		// 	//exit;
		// }

		$branchmasters = $this->Branchmaster->findById($bid);
		$departmentmasters = $this->Departmentmaster->findById($did);
		$this->set(compact('users','branchmasters','departmentmasters'));
    // debug($users);
	}

  // 個人拘束時間一覧印刷
	public function pdfHost002_5y2($id, $date1, $date2) {
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';

    // debug([$date1, $date2]);

		$week = ['日','月','火','水','木','金','土'];
		$paramsDate1 = new DateTime($date1);
		$paramsDate2 = new DateTime($date2);
		$paramsDate1->modify('first day of this months');
		$paramsDate2->modify('last day of this months');
		$this->set(compact('paramsDate1', 'paramsDate2', 'week'));

    // debug([$paramsDate1, $paramsDate2]);

		$this->User->Behaviors->load('EagerLoader.EagerLoader');

		$userData = $this->User->find('first', [
			'fields' => [
				'User.id',
				'User.usernameK',
				'User.syain_cd'
			],
			'conditions' => [
				'User.id' => $id
			],
			'contain' => [
				'Kintaitran' => [
					'conditions' => [
						'Kintaitran.kintaidate >=' => $paramsDate1->format('Y-m-d'),
						'Kintaitran.kintaidate <=' => $paramsDate2->format('Y-m-d'),
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
        'Kousokutran'
			],
		]);

    // debug($userData);

		$this->set(compact('userData'));
	}

}
