<?php
App::uses('AppController', 'Controller');
/**
 * Host012s Controller
 *
 * copy Host010
 */
class Host012sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form','Session','Js' => ['Jquery']);
	public $uses = array('User','Kaisya','Branchmaster','Departmentmaster','Kintaitran','Flgmaster','Holiday','Employeeholiday');
	public $components = array('Search.Prg','RequestHandler','Timediff','SelectData');
	public $presetVars = array();

	public function beforeFilter(){
		parent::beforeFilter();
		$this->page = 'Host012s';

		// 検索対象のフィールド設定代入
		$this->presetVars = $this->User->presetVars;
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
				case 28; //事務職（運転手兼）
				$this->set('user', $user);
				return true;
				break;
			}
		}
		return false;
		break;
	}
	/*
 * index method
 */
	public function index() {
		$page = $this->page;

		$flgmasters = $this->Flgmaster->flgchk(1);
		$this->set(compact('flgmasters'));

		$branchmasters_id = $this->branchmasters_id;
		$departmentmasters_id = $this->Auth->user('departmentmasters_id');

		$date = new DateTime();
		$date->modify('first day of this months');
		$date2 = new DateTime();
		$date2->modify('last day of this months');

		// $tyy1 = date('Y');
		// $tmm1 = date('m');
		// $tdd1 = '01';
		// $tyy2 = date('Y') + 1;
		// $tmm2 = date('m');
		// $tdd2 = '31';

		if ($this->request->is('post')) {
			list($date,$date2) = $this->SelectData->date_select_month($this->request->data('selectym1'));
			// $tyy1 = $this->data['selectym1']['year'];
			// $tyy2 = $this->data['selectym1']['year'] + 1;
			$branchmasters_id = $this->request->data('branchmasters_id');
			$departmentmasters_id = $this->request->data('departmentmasters_id');
			// $backdatas = ['tyy1' => $tyy1, 'tyy2' => $tyy2, 'bid' => $branchmasters_id, 'did' => $departmentmasters_id, 'Page' => $page];
			$backdatas = [
				'date' => [
					'year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')
				],
				'date2' => [
					'year' => $date2->format('Y'), 'month' => $date2->format('m'), 'day' => $date2->format('d')
				],
				'bid' => $branchmasters_id,
				'did' => $departmentmasters_id,
				'Page' => $page
			];
			$this->Session->write('BackData1', $backdatas);
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				// $tyy1 = $this->Session->read('BackData2.tyy1');
				// $tyy2 = $this->Session->read('BackData2.tyy2');
				$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$date2 = $this->SelectData->date_select($this->Session->read('BackData2.date2'));
				$branchmasters_id = $this->Session->read('BackData2.bid');
				$departmentmasters_id = $this->Session->read('BackData2.did');
			}
			$this->Session->delete('BackData2');
		} else {
			$this->Session->delete('BackData1');
		}

		$this->set(compact('branchmasters_id','departmentmasters_id'));

		// $date1 = new DateTime($tyy1.'-'.$tmm1.'-'.$tdd1);
		// $paramsDate1 = $date1->format('Y-m-d');
		// $date2 = new DateTime($tyy2.'-'.$tmm2.'-'.$tdd2);
		// $paramsDate2 = $date2->format('Y-m-d');
		$paramsDate1 = $date->format('Y-m-d');
		$paramsDate2 = $date2->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];

		$users =  $this->SelectData->data_select_conditionsHost012s($paramsDate1,$paramsDate2,$branchmasters_id,$departmentmasters_id,$this->kaisyas_id,$this->branchmasters_id);

//		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
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
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->kaisyas_id);

		$employeeholidays = $this->Employeeholiday->find('all',[
			'fields'=>[
				'Employeeholiday.id',
				'Employeeholiday.applicationdate',
				'Employeeholiday.created',
				'Employeeholiday.users_id',
				'Employeeholiday.flgmasters_id',
				'User.usernameK',
				'Flgmaster.id',
				'Flgmaster.flgname',
				'Flgmaster2.flgname'
			],
			'conditions' => [
				'User.kaisyas_id' => $this->Auth->user('Kaisya.id'),
				'Employeeholiday.applicationdate >=' => $paramsDate1,
				'Employeeholiday.applicationdate <=' => $paramsDate2,
				'Employeeholiday.flgmasters_id <>' => 41//確定済み以外を表示
			],
			'order' => [
				'Employeeholiday.applicationdate' => 'asc'
			],
			'recursive' => 0,
		]);
		$this->set(compact('users','branchmasters','departmentmasters','paramsDate1','paramsDate2','week','employeeholidays'));
	}


/*
 * edit method
 */
	public function edit($id = null) {
		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);

		$employeeholidays = $this->Employeeholiday->findById($id);
		$this->set('employeeholidays', $employeeholidays);

		if ($this->request->is(['post', 'put'])) {
			// 確定処理
			if (isset($this->request->data['OK'])) {
				$this->Employeeholiday->id = $id;
				$this->request->data['Employeeholiday']['okuserid'] = $this->Auth->user('id');
				$this->request->data['Employeeholiday']['flgmasters_id'] = 41;
				if ($this->Employeeholiday->save($this->request->data)) {
					//有休の時だけ勤怠に登録
					if($employeeholidays['Employeeholiday']['flgmasters_id2']==13){
						$kintaitarans = $this->Kintaitran->find('first', [
							'fields' => [
								'id',
								'users_id',
								'flgmasters_id',
								'kintaidate'
							],
							'conditions' => [
								'AND' => [
									['Kintaitran.users_id' => $employeeholidays['Employeeholiday']['users_id']],
									['Kintaitran.flgmasters_id' => 13],
									['Kintaitran.kintaidate' => $employeeholidays['Employeeholiday']['applicationdate']]
								]
							]
						]);
						if (!$kintaitarans) {
							$this->Kintaitran->create();
							$this->request->data['Kintaitran']['users_id'] = $employeeholidays['Employeeholiday']['users_id'];
							$this->request->data['Kintaitran']['flgmasters_id'] = 13;
							$this->request->data['Kintaitran']['kintaidate'] = $employeeholidays['Employeeholiday']['applicationdate'];
							if ($this->Kintaitran->save($this->request->data)) {
								$this->request->data['User']['id'] = $employeeholidays['Employeeholiday']['users_id'];
								$holidayzan = $employeeholidays['User']['holidayzan'] - 1;
								$this->request->data['User']['holidayzan'] = $holidayzan;
								if ($this->User->save($this->request->data)) {
									$this->Flash->success('確定し、勤怠管理に登録しました。');
								}
							}
						} else {
							$this->Flash->success('確定しました。');
						}
					}else{
						$this->Flash->success('確定しました。');
					}
				} else {
					$this->Flash->error('確定できませんでした。');
				}
				return $this->redirect(['controller' => 'Host012s','action' => 'index']);
			} else {
				// 却下の処理
				$this->Employeeholiday->id = $id;
				$this->request->data['Employeeholiday']['okuserid'] = $this->Auth->user('id');
				$this->request->data['Employeeholiday']['flgmasters_id'] = 43;
				if ($this->Employeeholiday->save($this->request->data)) {
					// 勤怠データがあれば削除
					$kintaitrans = $this->Kintaitran->find('first', [
						'fields' => [
							'id',
							'users_id',
							'flgmasters_id',
							'kintaidate'
						],
						'conditions' => [
							'AND' => [
								['Kintaitran.users_id' => $employeeholidays['Employeeholiday']['users_id']],
								['Kintaitran.flgmasters_id' => 13],
								['Kintaitran.kintaidate' => $employeeholidays['Employeeholiday']['applicationdate']]
							]
						]
					]);
					if ($kintaitrans) {
						// データがあれば削除
						$this->Kintaitran->delete($kintaitrans['Kintaitran']['id']);
					}

					$this->Flash->success('却下しました。');
				} else {
					$this->Flash->error('確定できませんでした。');
				}
				return $this->redirect(['controller' => 'Host012s','action' => 'index']);
			}
		}
	}

	public function add(){
		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);

		if ($this->request->is('post')) {
			$this->Employeeholiday->create();
			$this->request->data['Employeeholiday']['users_id'] = $this->request->data['Employeeholiday']['users_id'];
			$this->request->data['Employeeholiday']['flgmasters_id'] = 42;
			if ($this->Employeeholiday->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(['controller'=>'Host012s','action'=>'index']);
			} else {
				//$this->Flash->error('失敗しました');
			}
		}

		$firstday = new DateTime('first day of '.date('Y').'-4');
		$lastday = new DateTime('last day of '.(date('Y')+1).'-3');

		$employeeholidays = $this->Employeeholiday->find('all',[
			//'fields' => 'User.usernameK',
			// 申請中だけを表示
			'conditions' => [
				['User.id' => $this->Auth->user('id')],
				['Employeeholiday.flgmasters_id'=>42],
			],
			'order' => [
				'applicationdate'=>'asc'
			],
			'recursive' => 0
		]);

		$kintaitrans = $this->Kintaitran->find('all', [
			'conditions' => [
				'AND' => [
					['Kintaitran.kintaidate >=' => $firstday->format('Y-m-d')],
					['Kintaitran.kintaidate <=' => $lastday->format('Y-m-d')],
					['Kintaitran.users_id' => $this->Auth->user('id')]
				]
			],
			'order' => [
				'Kintaitran.kintaidate'=>'asc'
			],
			'recursive' => 0
		]);
		$users =  $this->SelectData->data_select_conditionsHost012s2($this->kaisyas_id,$this->branchmasters_id);
		$this->set(compact('employeeholidays', 'kintaitrans', 'users'));

		$flgmasters = $this->Flgmaster->find('list',[
			'fields'=>[
				'id',
				'flgname'
			],
			'conditions'=>[
				'OR'=>[
					'flggroup'=>5,
					'Flgmaster.id'=>13
				]
			]
		]);
		$this->set('flgmasters',$flgmasters);
	}

}
